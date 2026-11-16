<?php
declare(strict_types=1);

/**
 * Page-view log.
 *
 * One row per public page view, used by the console's "Visits" screen and the
 * unread badge. Deliberately minimal and privacy-preserving: no raw IP address,
 * no cookies, no third-party analytics, and the log never blocks a page.
 */

require_once __DIR__ . '/db.php';

function visit_is_bot(string $ua): bool
{
    if ($ua === '') {
        return true;
    }
    return (bool) preg_match('/bot|crawl|spider|slurp|preview|monitor|curl|wget|httpclient|headless|lighthouse/i', $ua);
}

/** Hash an address so visits can be grouped without storing personal data. */
function visit_ip_hash(): string
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    return $ip === '' ? '' : hash('sha256', $ip . '|' . (defined('SITE_DOMAIN') ? SITE_DOMAIN : 'epitek'));
}

/**
 * Record the current page view. Never throws: a locked or missing table must
 * not break a public page.
 */
function visit_record(): void
{
    if (PHP_SAPI === 'cli') {
        return;
    }
    if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'GET') {
        return;
    }

    $path = (string) (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');
    if ($path === '') {
        $path = '/';
    }
    if (str_starts_with($path, '/epiadmin') || preg_match('#\.(css|js|m?js|png|jpe?g|webp|gif|svg|ico|woff2?|json|xml|txt|map)$#i', $path)) {
        return;
    }

    $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
    if (visit_is_bot($ua)) {
        return;
    }

    $referrer = '';
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $host = (string) (parse_url((string) $_SERVER['HTTP_REFERER'], PHP_URL_HOST) ?? '');
        if ($host !== '' && $host !== (string) ($_SERVER['HTTP_HOST'] ?? '')) {
            $referrer = mb_substr($host, 0, 190);
        }
    }

    try {
        db_id(
            'INSERT INTO visits (path, referrer, ua, ip_hash, seen, created_at) VALUES (?, ?, ?, ?, 0, ?)',
            [mb_substr($path, 0, 190), $referrer, mb_substr($ua, 0, 190), visit_ip_hash(), gmdate('Y-m-d H:i:s')]
        );
    } catch (Throwable $e) {
        // Logging is best-effort by design.
    }
}

/** Visits not yet reviewed in the console. */
function visit_unseen(): int
{
    try {
        return db_value('SELECT COUNT(*) FROM visits WHERE seen = 0');
    } catch (Throwable $e) {
        return 0;
    }
}

function visit_mark_all_seen(): int
{
    try {
        return db_exec('UPDATE visits SET seen = 1 WHERE seen = 0');
    } catch (Throwable $e) {
        return 0;
    }
}

/** Visits since a point in time, e.g. '-24 hours'. */
function visit_since(string $when): int
{
    try {
        return db_value(
            'SELECT COUNT(*) FROM visits WHERE created_at >= ?',
            [gmdate('Y-m-d H:i:s', (int) strtotime($when))]
        );
    } catch (Throwable $e) {
        return 0;
    }
}

/** @return array<int,array{path:string,hits:int}> */
function visit_top_paths(int $days = 7, int $limit = 6): array
{
    try {
        $rows = db_all(
            'SELECT path, COUNT(*) AS hits FROM visits WHERE created_at >= ? GROUP BY path ORDER BY hits DESC LIMIT ' . (int) $limit,
            [gmdate('Y-m-d H:i:s', (int) strtotime('-' . $days . ' days'))]
        );
    } catch (Throwable $e) {
        return [];
    }

    $out = [];
    foreach ($rows as $row) {
        $out[] = ['path' => (string) $row['path'], 'hits' => (int) $row['hits']];
    }
    return $out;
}
