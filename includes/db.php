<?php
declare(strict_types=1);

/**
 * Database access for the admin dashboard.
 *
 * Production: MySQL on cPanel, credentials in config/db.local.php (never committed).
 * Local development: SQLite fallback at storage/app.sqlite when no credentials exist.
 */

function db_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $local = dirname(__DIR__) . '/config/db.local.php';
    if (is_file($local)) {
        $loaded = require $local;
        if (is_array($loaded) && ($loaded['driver'] ?? '') !== '') {
            $config = $loaded;
            return $config;
        }
    }

    $envDsn = getenv('EPX_DB_DSN');
    if (is_string($envDsn) && $envDsn !== '') {
        $config = [
            'driver' => str_starts_with($envDsn, 'sqlite:') ? 'sqlite' : 'mysql',
            'dsn'    => $envDsn,
            'user'   => (string) getenv('EPX_DB_USER'),
            'pass'   => (string) getenv('EPX_DB_PASS'),
        ];
        return $config;
    }

    $config = ['driver' => 'sqlite', 'path' => dirname(__DIR__) . '/storage/app.sqlite'];
    return $config;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = db_config();
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    if (($config['driver'] ?? 'sqlite') === 'sqlite') {
        $path = $config['path'] ?? dirname(__DIR__) . '/storage/app.sqlite';
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        $pdo = new PDO('sqlite:' . $path, null, null, $options);
        $pdo->exec('PRAGMA journal_mode = WAL');
        $pdo->exec('PRAGMA foreign_keys = ON');
        return $pdo;
    }

    if (isset($config['dsn'])) {
        $dsn = (string) $config['dsn'];
    } else {
        $host = $config['host'] ?? 'localhost';
        $port = (int) ($config['port'] ?? 3306);
        $name = $config['name'] ?? '';
        $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $name . ';charset=utf8mb4';
    }

    $pdo = new PDO($dsn, (string) ($config['user'] ?? ''), (string) ($config['pass'] ?? ''), $options + [
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $pdo->exec("SET time_zone = '+00:00'");
    return $pdo;
}

/** @param array<string,mixed> $params */
function db_all(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/** @param array<string,mixed> $params */
function db_one(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
}

/** @param array<string,mixed> $params */
function db_exec(string $sql, array $params = []): int
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/** @param array<string,mixed> $params */
function db_id(string $sql, array $params = []): int
{
    db_exec($sql, $params);
    return (int) db()->lastInsertId();
}

function db_value(string $sql, array $params = []): int
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function db_is_sqlite(): bool
{
    return (db_config()['driver'] ?? 'sqlite') === 'sqlite';
}
