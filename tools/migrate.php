<?php
declare(strict_types=1);

/** Create or update the dashboard schema. Run: php tools/migrate.php */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/schema.php';

$config = db_config();
echo 'Driver: ' . ($config['driver'] ?? 'sqlite') . PHP_EOL;

if (($config['driver'] ?? 'sqlite') === 'mysql' && !isset($config['dsn'])) {
    echo 'Host: ' . ($config['host'] ?? '') . ' · Database: ' . ($config['name'] ?? '') . PHP_EOL;
} elseif (($config['driver'] ?? 'sqlite') === 'sqlite') {
    echo 'File: ' . ($config['path'] ?? '') . PHP_EOL;
}

try {
    $results = run_schema();
    echo 'Statements executed: ' . count($results) . PHP_EOL;

    foreach (['users', 'enquiries', 'applications', 'notes', 'activity'] as $table) {
        $count = db_value('SELECT COUNT(*) FROM ' . $table);
        printf("  %-13s %d rows\n", $table, $count);
    }
    echo 'Schema ready.' . PHP_EOL;
} catch (Throwable $e) {
    fwrite(STDERR, 'Migration failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
