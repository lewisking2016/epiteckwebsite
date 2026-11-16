<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/pipeline.php';
require_once __DIR__ . '/http.php';

/**
 * Console authentication, roles, audit trail and view helpers.
 * Path: the console lives at /epiadmin/ (ADMIN_PATH), never /admin.
 */

const ADMIN_PATH = '/epiadmin/';

function admin_url(string $path = ''): string
{
    return url(ADMIN_PATH . ltrim($path, '/'));
}

function admin_no_cache(): void
{
    if (headers_sent()) {
        return;
    }
    header_remove('X-Powered-By');
    header('X-Robots-Tag: noindex, nofollow, noarchive');
    header('Cache-Control: no-store, private, max-age=0');
    header('Pragma: no-cache');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: no-referrer');
    header('Cross-Origin-Opener-Policy: same-origin');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
    // The console needs inline style attributes for the intake bars, so styles are
    // relaxed for this path only. Scripts stay restricted and there is no remote origin.
    header("Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; "
        . "frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self'; "
        . "style-src 'self' 'unsafe-inline'; script-src 'self'; manifest-src 'self'");
}

/** ---------------------------------------------------------------- session */

function admin_current(): ?array
{
    session_start_secure();
    $id = $_SESSION['admin_id'] ?? null;
    if (!is_int($id) && !ctype_digit((string) $id)) {
        return null;
    }
    $user = db_one(
        'SELECT id, username, email, name, role, created_at, last_login_at, pass_changed_at FROM users WHERE id = ?',
        [(int) $id]
    );
    if (!$user) {
        unset($_SESSION['admin_id']);
        return null;
    }
    return $user;
}

function admin_require(): array
{
    $user = admin_current();
    if (!$user) {
        redirect(admin_url('login.php'));
    }
    admin_no_cache();
    return $user;
}

/** Read-only roles may view but not change anything. */
function admin_can_write(?array $user): bool
{
    return $user !== null && ($user['role'] ?? '') !== 'viewer';
}

/** True while the account still uses the password it was seeded with. */
function admin_uses_setup_password(?array $user): bool
{
    return $user !== null && empty($user['pass_changed_at']);
}

/** ---------------------------------------------------------------- auth */

/** Accepts the console username or the account email. */
function admin_find_user(string $login): ?array
{
    $login = strtolower(trim($login));
    if ($login === '') {
        return null;
    }
    $user = db_one('SELECT * FROM users WHERE username = ?', [$login]);
    if (!$user) {
        $user = db_one('SELECT * FROM users WHERE email = ?', [$login]);
    }
    return $user ?: null;
}

function admin_login(string $login, string $password): ?array
{
    if (trim($login) === '' || $password === '') {
        return null;
    }
    $user = admin_find_user($login);
    if (!$user || !password_verify($password, (string) $user['pass_hash'])) {
        return null;
    }

    if (password_needs_rehash((string) $user['pass_hash'], PASSWORD_DEFAULT)) {
        db_exec('UPDATE users SET pass_hash = ? WHERE id = ?', [
            password_hash($password, PASSWORD_DEFAULT),
            (int) $user['id'],
        ]);
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $user['id'];
    db_exec('UPDATE users SET last_login_at = ? WHERE id = ?', [gmdate('Y-m-d H:i:s'), (int) $user['id']]);
    audit('login', 'user', (int) $user['id']);

    unset($user['pass_hash']);
    return $user;
}

/**
 * Turn a database failure during sign-in into an operator-readable message.
 * An unconfigured or unmigrated database used to surface as a blank HTTP 500.
 */
function admin_db_message(Throwable $e): string
{
    $message = $e->getMessage();

    if (stripos($message, 'no such table') !== false || stripos($message, "doesn't exist") !== false) {
        return 'The console database exists but its tables are missing. Upload setup_config.php to the site root, open it with ?key=epitek-setup-2026, then delete it.';
    }

    if (
        stripos($message, 'Access denied') !== false
        || stripos($message, 'Unknown database') !== false
        || stripos($message, 'Connection refused') !== false
        || stripos($message, 'could not find driver') !== false
        || stripos($message, 'SQLSTATE[HY000] [2002]') !== false
        || stripos($message, 'unable to open database file') !== false
    ) {
        return 'The console cannot reach its database. Check the credentials in config/db.local.php, then reload this page.';
    }

    return 'The console could not sign you in because of a database error. The details are in the PHP error log.';
}

function admin_logout(): void
{
    session_start_secure();
    $id = $_SESSION['admin_id'] ?? null;
    unset($_SESSION['admin_id']);
    session_regenerate_id(true);
    if ($id !== null) {
        audit('logout', 'user', (int) $id);
    }
}

function admin_set_password(int $id, string $password): void
{
    db_exec(
        'UPDATE users SET pass_hash = ?, pass_changed_at = ? WHERE id = ?',
        [password_hash($password, PASSWORD_DEFAULT), gmdate('Y-m-d H:i:s'), $id]
    );
    audit('user.password', 'user', $id);
}

/** ---------------------------------------------------------------- audit */

function audit(string $action, string $subjectType = '', int $subjectId = 0, array $meta = []): void
{
    $userId = isset($_SESSION['admin_id']) ? (int) $_SESSION['admin_id'] : null;
    db_exec(
        'INSERT INTO activity (user_id, action, subject_type, subject_id, meta, created_at) VALUES (?, ?, ?, ?, ?, ?)',
        [$userId, $action, $subjectType, $subjectId, $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null, gmdate('Y-m-d H:i:s')]
    );
}

/** @return array<int,array<string,mixed>> */
function admin_users(): array
{
    return db_all('SELECT id, username, email, name, role, created_at, last_login_at FROM users ORDER BY id');
}

/** ---------------------------------------------------------------- flash */

function admin_flash(string $tone, string $message): void
{
    session_start_secure();
    $_SESSION['admin_flash'][] = ['tone' => $tone, 'message' => $message];
}

/** @return array<int,array{tone:string,message:string}> */
function admin_flash_take(): array
{
    session_start_secure();
    $items = $_SESSION['admin_flash'] ?? [];
    unset($_SESSION['admin_flash']);
    return is_array($items) ? $items : [];
}

/** ---------------------------------------------------------------- views */

function display_name(?string $value): string
{
    $value = trim((string) $value);
    return $value === '' ? '—' : $value;
}
