<?php
declare(strict_types=1);

/**
 * Small HTTP/view primitives shared by the console pages and the public forms.
 * Keeping them here means each page stays a thin controller.
 */

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function redirect(string $to, int $code = 303): void
{
    header('Location: ' . $to, true, $code);
    exit;
}

/** Reject anything that is not a POST with a helpful 405. */
function require_post(string $fallback = '/'): void
{
    if (is_post()) {
        return;
    }
    http_response_code(405);
    header('Allow: POST');
    header('Location: ' . $fallback, true, 303);
    exit;
}

/**
 * Split a page request into the three numbers every list view needs.
 *
 * @return array{page:int,pages:int,offset:int,per:int,total:int}
 */
function paginate(int $total, int $page, int $per = 25): array
{
    $per = max(1, $per);
    $pages = max(1, (int) ceil($total / $per));
    $page = min(max(1, $page), $pages);
    return ['page' => $page, 'pages' => $pages, 'offset' => ($page - 1) * $per, 'per' => $per, 'total' => $total];
}

/** Keep only the keys a view is allowed to see, in a stable order. */
function only(array $row, array $keys): array
{
    $out = [];
    foreach ($keys as $key) {
        $out[$key] = $row[$key] ?? '';
    }
    return $out;
}
