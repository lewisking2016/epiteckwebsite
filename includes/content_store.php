<?php
declare(strict_types=1);

/**
 * Editorial overrides.
 *
 * Every editable string has a key. The built-in text stays in the code as the
 * default, so a page renders correctly with an empty table, a missing table or
 * an unreachable database; the console only stores what an editor changed.
 */

require_once __DIR__ . '/db.php';

/** @return array<string,string> key => value, loaded once per request */
function content_map(): array
{
    static $map = null;
    if ($map !== null) {
        return $map;
    }

    $map = [];
    try {
        foreach (db_all('SELECT ckey, cvalue FROM content') as $row) {
            $map[(string) $row['ckey']] = (string) $row['cvalue'];
        }
    } catch (Throwable $e) {
        // No database, no table, or no grant: fall back to the code defaults.
        $map = [];
    }

    return $map;
}

/** Effective value for a key: the edited text if present, otherwise the default. */
function c(string $key, string $default = ''): string
{
    $map = content_map();
    if (array_key_exists($key, $map) && trim($map[$key]) !== '') {
        return $map[$key];
    }
    return $default;
}

function content_has(string $key): bool
{
    return array_key_exists($key, content_map());
}

function content_set(string $key, string $value, ?int $userId = null): void
{
    $now = gmdate('Y-m-d H:i:s');
    $existing = db_one('SELECT ckey FROM content WHERE ckey = ?', [$key]);

    if ($existing) {
        db_exec(
            'UPDATE content SET cvalue = ?, updated_at = ?, updated_by = ? WHERE ckey = ?',
            [$value, $now, $userId, $key]
        );
        return;
    }

    db_id(
        'INSERT INTO content (ckey, cvalue, updated_at, updated_by) VALUES (?, ?, ?, ?)',
        [$key, $value, $now, $userId]
    );
}

function content_reset(string $key): void
{
    db_exec('DELETE FROM content WHERE ckey = ?', [$key]);
}

function content_edited_count(): int
{
    return count(content_map());
}
