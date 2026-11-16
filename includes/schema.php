<?php
declare(strict_types=1);

/** Schema definitions used by tools/migrate.php. Kept driver-aware. */

function schema_tables(): array
{
    $mysql = !db_is_sqlite();

    $id = $mysql
        ? 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY'
        : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $tail = $mysql ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '';
    $now = $mysql ? 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP' : "TEXT NOT NULL DEFAULT (datetime('now'))";

    $statements = [];

    $statements[] = "CREATE TABLE IF NOT EXISTS users (
        id $id,
        username VARCHAR(64) NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        name VARCHAR(120) NOT NULL,
        role VARCHAR(20) NOT NULL DEFAULT 'owner',
        pass_hash VARCHAR(255) NOT NULL,
        created_at $now,
        last_login_at DATETIME NULL,
        pass_changed_at DATETIME NULL
    )$tail";

    $statements[] = "CREATE TABLE IF NOT EXISTS enquiries (
        id $id,
        reference VARCHAR(32) NOT NULL UNIQUE,
        name VARCHAR(120) NOT NULL,
        title VARCHAR(120) NOT NULL DEFAULT '',
        organisation VARCHAR(160) NOT NULL DEFAULT '',
        email VARCHAR(190) NOT NULL,
        phone VARCHAR(32) NOT NULL DEFAULT '',
        country VARCHAR(60) NOT NULL DEFAULT '',
        interests TEXT NULL,
        environment VARCHAR(80) NOT NULL DEFAULT '',
        timeline VARCHAR(60) NOT NULL DEFAULT '',
        briefing VARCHAR(80) NOT NULL DEFAULT '',
        message TEXT NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'new',
        owner_id INT NULL,
        source VARCHAR(40) NOT NULL DEFAULT 'website',
        ip_hash CHAR(64) NOT NULL DEFAULT '',
        created_at $now,
        updated_at DATETIME NULL
    )$tail";

    $statements[] = "CREATE TABLE IF NOT EXISTS applications (
        id $id,
        reference VARCHAR(32) NOT NULL UNIQUE,
        type VARCHAR(20) NOT NULL DEFAULT 'training',
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL,
        phone VARCHAR(32) NOT NULL DEFAULT '',
        organisation VARCHAR(160) NOT NULL DEFAULT '',
        role_title VARCHAR(120) NOT NULL DEFAULT '',
        country VARCHAR(60) NOT NULL DEFAULT '',
        programme VARCHAR(120) NOT NULL DEFAULT '',
        start_window VARCHAR(60) NOT NULL DEFAULT '',
        link_url VARCHAR(255) NOT NULL DEFAULT '',
        message TEXT NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'received',
        owner_id INT NULL,
        source VARCHAR(40) NOT NULL DEFAULT 'website',
        ip_hash CHAR(64) NOT NULL DEFAULT '',
        created_at $now,
        updated_at DATETIME NULL
    )$tail";

    $statements[] = "CREATE TABLE IF NOT EXISTS notes (
        id $id,
        subject_type VARCHAR(20) NOT NULL,
        subject_id INT NOT NULL,
        user_id INT NULL,
        body TEXT NOT NULL,
        created_at $now
    )$tail";

    $statements[] = "CREATE TABLE IF NOT EXISTS activity (
        id $id,
        user_id INT NULL,
        action VARCHAR(60) NOT NULL,
        subject_type VARCHAR(20) NOT NULL DEFAULT '',
        subject_id INT NOT NULL DEFAULT 0,
        meta TEXT NULL,
        created_at $now
    )$tail";

    // Editorial overrides. Pages fall back to their built-in text when a key is absent.
    $statements[] = "CREATE TABLE IF NOT EXISTS content (
        id $id,
        ckey VARCHAR(120) NOT NULL UNIQUE,
        cvalue TEXT NOT NULL,
        updated_at DATETIME NULL,
        updated_by INT NULL
    )$tail";

    // One row per public page view, used by the console's visit notifications.
    $statements[] = "CREATE TABLE IF NOT EXISTS visits (
        id $id,
        path VARCHAR(190) NOT NULL,
        referrer VARCHAR(190) NOT NULL DEFAULT '',
        ua VARCHAR(190) NOT NULL DEFAULT '',
        ip_hash CHAR(64) NOT NULL DEFAULT '',
        seen INTEGER NOT NULL DEFAULT 0,
        created_at $now
    )$tail";

    return $statements;
}

/** Indexes run after upgrade_schema(), so they can target added columns. */
function schema_indexes(): array
{
    return [
        'CREATE UNIQUE INDEX idx_users_username ON users (username)',
        'CREATE INDEX idx_enquiries_status ON enquiries (status)',
        'CREATE INDEX idx_enquiries_created ON enquiries (created_at)',
        'CREATE INDEX idx_applications_status ON applications (status)',
        'CREATE INDEX idx_applications_created ON applications (created_at)',
        'CREATE INDEX idx_notes_subject ON notes (subject_type, subject_id)',
        'CREATE INDEX idx_visits_created ON visits (created_at)',
        'CREATE INDEX idx_visits_seen ON visits (seen)',
    ];
}

function schema_statements(): array
{
    return array_merge(schema_tables(), schema_indexes());
}

/**
 * Columns added after the first release. Existing installs are brought forward
 * by upgrade_schema() instead of being rebuilt.
 *
 * @return array<string,array<string,string>> table => column => definition
 */
function schema_added_columns(): array
{
    $mysql = !db_is_sqlite();
    return [
        'users' => [
            'username'        => 'VARCHAR(64) NULL',
            'pass_changed_at' => 'DATETIME NULL',
        ],
    ];
}

/** Add any missing columns to an existing database. Returns the columns added. */
function upgrade_schema(): array
{
    $added = [];
    foreach (schema_added_columns() as $table => $columns) {
        $existing = [];
        if (db_is_sqlite()) {
            foreach (db_all('PRAGMA table_info(' . $table . ')') as $row) {
                $existing[] = strtolower((string) $row['name']);
            }
        } else {
            foreach (db_all('SHOW COLUMNS FROM ' . $table) as $row) {
                $existing[] = strtolower((string) $row['Field']);
            }
        }
        if (!$existing) {
            continue;
        }
        foreach ($columns as $column => $definition) {
            if (in_array(strtolower($column), $existing, true)) {
                continue;
            }
            db()->exec('ALTER TABLE ' . $table . ' ADD COLUMN ' . $column . ' ' . $definition);
            $added[] = $table . '.' . $column;
        }
    }
    return $added;
}

/** MySQL before 8.0.13 has no "CREATE INDEX IF NOT EXISTS"; run them tolerantly. */
function run_schema(): array
{
    $created = [];
    $run = static function (array $statements) use (&$created): void {
        foreach ($statements as $sql) {
            try {
                db()->exec($sql);
                $created[] = 'ok';
            } catch (Throwable $e) {
                $message = $e->getMessage();
                if (stripos($message, 'already exists') !== false || stripos($message, 'Duplicate key name') !== false) {
                    $created[] = 'exists';
                    continue;
                }
                throw $e;
            }
        }
    };

    $run(schema_tables());
    foreach (upgrade_schema() as $column) {
        $created[] = 'added ' . $column;
    }
    $run(schema_indexes());
    return $created;
}
