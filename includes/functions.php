<?php
declare(strict_types=1);

/** Shared helpers: escaping, URLs, CSRF, throttling and small view utilities. */

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = '/'): string
{
    if (preg_match('#^(https?:)?//#', $path)) {
        return $path;
    }
    return BASE_PATH . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $file = dirname(__DIR__) . '/' . ltrim($path, '/');
    $v = is_file($file) ? (string) filemtime($file) : '1';
    return url($path) . '?v=' . $v;
}

function canonical(string $path = '/'): string
{
    return SITE_URL . '/' . ltrim($path, '/');
}

function is_current(string $slug, string $current): string
{
    return $slug === $current ? ' aria-current="page"' : '';
}

function nav_active(string $slug, string $current): string
{
    return $slug === $current ? ' is-active' : '';
}

function body_id(string $slug): string
{
    return 'page-' . preg_replace('/[^a-z0-9-]/', '', $slug);
}

/** ---------------------------------------------------------------- security */

function session_start_secure(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => BASE_PATH !== '' ? BASE_PATH . '/' : '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('epx_session');
    session_start();
}

function csrf_token(): string
{
    session_start_secure();
    if (empty($_SESSION['csrf']) || !is_string($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valid(?string $token): bool
{
    session_start_secure();
    return is_string($token)
        && !empty($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $token);
}

function client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

/**
 * File-backed sliding-window throttle. Returns true when the action is allowed.
 * Kept deliberately small: one JSON file per hashed key.
 */
function rate_limit(string $bucket, int $limit = 4, int $window = 3600): bool
{
    $dir = dirname(__DIR__) . '/storage/ratelimit';
    if (!is_dir($dir)) {
        @mkdir($dir, 0750, true);
    }
    $file = $dir . '/' . hash('sha256', $bucket . '|' . client_ip()) . '.json';
    $now = time();
    $hits = [];
    if (is_file($file)) {
        $raw = @file_get_contents($file);
        $decoded = is_string($raw) ? json_decode($raw, true) : null;
        if (is_array($decoded)) {
            $hits = array_values(array_filter($decoded, static fn ($t) => is_int($t) && $t > $now - $window));
        }
    }
    if (count($hits) >= $limit) {
        return false;
    }
    $hits[] = $now;
    @file_put_contents($file, json_encode($hits), LOCK_EX);
    return true;
}

function security_headers(string $nonce): void
{
    if (headers_sent()) {
        return;
    }
    header_remove('X-Powered-By');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
    header('Cross-Origin-Opener-Policy: same-origin');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
    header('Content-Security-Policy: '
        . "default-src 'self'; "
        . "base-uri 'self'; "
        . "object-src 'none'; "
        . "frame-ancestors 'none'; "
        . "form-action 'self'; "
        . "img-src 'self' data:; "
        . "font-src 'self'; "
        . "style-src 'self' 'nonce-" . $nonce . "'; "
        . "script-src 'self' 'nonce-" . $nonce . "'; "
        . "manifest-src 'self'; "
        . "upgrade-insecure-requests");
}

function csp_nonce(): string
{
    static $nonce = null;
    if ($nonce === null) {
        $nonce = base64_encode(random_bytes(16));
    }
    return $nonce;
}

/** ---------------------------------------------------------------- messages */

function flash_set(string $key, string $message): void
{
    session_start_secure();
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    session_start_secure();
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return is_string($message) ? $message : null;
}

/** ---------------------------------------------------------------- content */

/**
 * Filter a list of records by the four operational domains.
 * Each record carries a "domains" array; records with none always show.
 */
function matches_domain(array $record, string $domain): bool
{
    if ($domain === 'all') {
        return true;
    }
    return in_array($domain, $record['domains'] ?? [], true);
}
