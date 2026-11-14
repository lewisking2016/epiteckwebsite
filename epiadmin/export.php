<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once dirname(__DIR__) . '/includes/repository.php';

$user = admin_require();

$type = (string) ($_GET['type'] ?? 'enquiries');
if ($type !== 'applications') {
    $type = 'enquiries';
    $filter = (string) ($_GET['status'] ?? '');
    $filter = isset(ENQUIRY_STAGES[$filter]) ? $filter : '';
} else {
    $filter = (string) ($_GET['app_type'] ?? '');
    $filter = isset(APPLICATION_TYPES[$filter]) ? $filter : '';
}

$export = export_rows($type, $filter);
audit('export.' . $type, '', 0, ['rows' => count($export['rows'])]);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $export['filename'] . '"');
header('Cache-Control: no-store, private');
header('X-Content-Type-Options: nosniff');

$out = fopen('php://output', 'wb');
fwrite($out, "\xEF\xBB\xBF"); // BOM so Excel opens UTF-8 correctly
fputcsv($out, $export['headers']);

foreach ($export['rows'] as $row) {
    $line = [];
    foreach ($export['headers'] as $key) {
        $value = (string) ($row[$key] ?? '');
        // Neutralise spreadsheet formula injection in exported cells.
        if (preg_match('/^[=+\-@\t\r]/', $value) === 1) {
            $value = "'" . $value;
        }
        $line[] = $value;
    }
    fputcsv($out, $line);
}
fclose($out);
exit;
