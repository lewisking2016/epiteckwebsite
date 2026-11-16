<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/detail.php';

$item = detail_item($SERVICES, 'uas-training');
if ($item === null) {
    http_response_code(404);
    require dirname(__DIR__, 2) . '/404.php';
    exit;
}
detail_render($item, 'services', 'Services', '/services');
