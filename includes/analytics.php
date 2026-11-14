<?php
declare(strict_types=1);

/**
 * Minimal traffic summary from the web server access log.
 * Configure the path in config/analytics.local.php: ['access_log' => '/home/user/logs/access.log'].
 * Nothing here is tracked in the browser; this only reads what Apache already writes.
 */

function analytics_log_path(): ?string
{
    $local = dirname(__DIR__) . '/config/analytics.local.php';
    if (is_file($local)) {
        $config = require $local;
        if (is_array($config) && !empty($config['access_log']) && is_readable((string) $config['access_log'])) {
            return (string) $config['access_log'];
        }
    }
    $env = getenv('EPX_ACCESS_LOG');
    if (is_string($env) && $env !== '' && is_readable($env)) {
        return $env;
    }
    return null;
}

/**
 * @return array{available:bool,path:?string,note:string,pageviews:int,notfound:int,mobile:int,top:array,referrers:array}
 */
function analytics_summary(int $maxBytes = 2097152): array
{
    $path = analytics_log_path();
    if ($path === null) {
        return [
            'available' => false,
            'path'      => null,
            'note'      => 'Access log not configured. Add config/analytics.local.php with the path to the Apache access log to see traffic here.',
            'pageviews' => 0,
            'notfound'  => 0,
            'mobile'    => 0,
            'top'       => [],
            'referrers' => [],
        ];
    }

    $handle = @fopen($path, 'rb');
    if (!$handle) {
        return [
            'available' => false, 'path' => $path, 'note' => 'Access log exists but could not be read.',
            'pageviews' => 0, 'notfound' => 0, 'mobile' => 0, 'top' => [], 'referrers' => [],
        ];
    }

    $size = (int) (filesize($path) ?: 0);
    if ($size > $maxBytes) {
        fseek($handle, $size - $maxBytes);
        fgets($handle); // discard the partial first line
    }

    $pages = [];
    $refs = [];
    $pageviews = 0;
    $notfound = 0;
    $mobile = 0;
    $requests = 0;

    $assets = '/\.(css|js|svg|png|jpe?g|webp|ico|woff2?|map|txt|json|xml)$/i';
    $self = SITE_DOMAIN;

    while (($line = fgets($handle)) !== false) {
        if (!preg_match('/^(\S+) \S+ \S+ \[[^\]]+\] "(\S+) ([^"]*?)(?: [^"]*)?" (\d{3}) /', $line, $m)) {
            continue;
        }
        $requests++;
        $method = strtoupper($m[2]);
        $target = $m[3];
        $status = (int) $m[4];
        if ($method !== 'GET') {
            continue;
        }
        $urlPath = parse_url($target, PHP_URL_PATH) ?: '/';
        if (preg_match($assets, $urlPath)) {
            continue;
        }
        if ($status === 404) {
            $notfound++;
            continue;
        }
        if ($status >= 400) {
            continue;
        }
        $pageviews++;
        $key = $urlPath === '/' ? '/' : rtrim($urlPath, '/');
        $pages[$key] = ($pages[$key] ?? 0) + 1;

        if (stripos($line, 'Mobi') !== false || stripos($line, 'Android') !== false || stripos($line, 'iPhone') !== false) {
            $mobile++;
        }

        if (preg_match('/"([^"]+)" "([^"]*)"\s*$/', $line, $r)) {
            $ref = $r[1];
            if ($ref !== '-' && $ref !== '' && stripos($ref, $self) === false) {
                $host = parse_url($ref, PHP_URL_HOST) ?: $ref;
                $refs[$host] = ($refs[$host] ?? 0) + 1;
            }
        }
    }
    fclose($handle);

    arsort($pages);
    arsort($refs);

    return [
        'available' => true,
        'path'      => $path,
        'note'      => 'Based on ' . number_format($requests) . ' requests in the current log file.',
        'pageviews' => $pageviews,
        'notfound'  => $notfound,
        'mobile'    => $pageviews > 0 ? (int) round($mobile / $pageviews * 100) : 0,
        'top'       => array_slice($pages, 0, 6, true),
        'referrers' => array_slice($refs, 0, 5, true),
    ];
}
