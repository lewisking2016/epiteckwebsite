<?php
declare(strict_types=1);

/** Import the JSONL enquiries written by the contact form into the dashboard database. */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/schema.php';
require_once dirname(__DIR__) . '/includes/pipeline.php';

run_schema();

$files = glob(dirname(__DIR__) . '/storage/leads/*.jsonl') ?: [];
$imported = 0;
$skipped = 0;
$failed = 0;

foreach ($files as $file) {
    $handle = fopen($file, 'rb');
    if (!$handle) {
        continue;
    }
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $row = json_decode($line, true);
        if (!is_array($row) || empty($row['reference'])) {
            $failed++;
            continue;
        }
        if (db_one('SELECT id FROM enquiries WHERE reference = ?', [(string) $row['reference']])) {
            $skipped++;
            continue;
        }

        $created = isset($row['received_at']) ? strtotime((string) $row['received_at']) : time();
        $interests = $row['interests'] ?? [];
        if (is_array($interests)) {
            $interests = implode(', ', array_map('strval', $interests));
        }

        db_exec(
            'INSERT INTO enquiries (reference, name, title, organisation, email, phone, country, interests, environment, timeline, briefing, message, status, source, ip_hash, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                (string) $row['reference'],
                (string) ($row['name'] ?? ''),
                (string) ($row['title'] ?? ''),
                (string) ($row['organisation'] ?? ''),
                (string) ($row['email'] ?? ''),
                (string) ($row['phone'] ?? ''),
                (string) ($row['country'] ?? ''),
                (string) $interests,
                (string) ($row['environment'] ?? ''),
                (string) ($row['timeline'] ?? ''),
                (string) ($row['demo'] ?? ''),
                (string) ($row['message'] ?? ''),
                'new',
                'contact-form',
                (string) ($row['ip_hash'] ?? ''),
                gmdate('Y-m-d H:i:s', $created ?: time()),
            ]
        );
        $imported++;
    }
    fclose($handle);
}

printf("Imported %d, skipped %d, unreadable %d, files %d\n", $imported, $skipped, $failed, count($files));
