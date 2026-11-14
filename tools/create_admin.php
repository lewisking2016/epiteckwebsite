<?php
declare(strict_types=1);

/**
 * Create, update or reset a console user.
 *
 * Usage:
 *   php tools/create_admin.php you@example.com "Your Name" [owner|editor|viewer] [--username=name] [--password=secret]
 *
 * The password is read from stdin when --password is omitted. Omit --password on
 * shared hosting so the secret never lands in shell history.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI only\n");
}

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/schema.php';

$args = [];
$flags = [];
foreach (array_slice($argv, 1) as $arg) {
    $arg = (string) $arg;
    if (preg_match('/^--([a-z-]+)=(.*)$/', $arg, $m)) {
        $flags[$m[1]] = $m[2];
    } elseif (str_starts_with($arg, '--')) {
        $flags[substr($arg, 2)] = true;
    } else {
        $args[] = $arg;
    }
}

$email = strtolower(trim((string) ($args[0] ?? '')));
$name  = trim((string) ($args[1] ?? ''));
$role  = strtolower(trim((string) ($args[2] ?? 'owner')));
$username = strtolower(trim((string) ($flags['username'] ?? '')));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "A valid email address is required.\nUsage: php tools/create_admin.php you@example.com \"Your Name\" [owner|editor|viewer] [--username=name] [--password=secret]\n");
    exit(1);
}
if ($name === '') {
    $name = ucfirst(strtok($email, '@') ?: 'Console user');
}
if (!in_array($role, ['owner', 'editor', 'viewer'], true)) {
    fwrite(STDERR, "Role must be owner, editor or viewer.\n");
    exit(1);
}
if ($username !== '' && !preg_match('/^[a-z0-9][a-z0-9._-]{2,63}$/', $username)) {
    fwrite(STDERR, "Username must be 3-64 characters of lowercase letters, digits, dot, dash or underscore.\n");
    exit(1);
}

$password = (string) ($flags['password'] ?? '');
if ($password === '') {
    fwrite(STDOUT, 'Password for ' . ($username !== '' ? $username : $email) . ': ');
    $password = trim((string) fgets(STDIN));
}
if (strlen($password) < 10) {
    fwrite(STDERR, "Use at least 10 characters.\n");
    exit(1);
}

run_schema();

$hash = password_hash($password, PASSWORD_DEFAULT);
$now = gmdate('Y-m-d H:i:s');
$existing = db_one('SELECT id FROM users WHERE email = ?', [$email]);

if ($username !== '') {
    $clash = db_one('SELECT id FROM users WHERE username = ?', [$username]);
    if ($clash && (!$existing || (int) $clash['id'] !== (int) $existing['id'])) {
        fwrite(STDERR, "Username '" . $username . "' is already taken.\n");
        exit(1);
    }
}

if ($existing) {
    $id = (int) $existing['id'];
    db_exec(
        'UPDATE users SET username = ?, name = ?, role = ?, pass_hash = ?, pass_changed_at = NULL WHERE id = ?',
        [$username !== '' ? $username : null, $name, $role, $hash, $id]
    );
    echo 'Updated user #' . $id . ' (' . ($username !== '' ? $username . ' / ' : '') . $email . ', ' . $role . ").\n";
} else {
    $id = db_id(
        'INSERT INTO users (username, email, name, role, pass_hash, created_at, pass_changed_at) VALUES (?, ?, ?, ?, ?, ?, NULL)',
        [$username !== '' ? $username : null, $email, $name, $role, $hash, $now]
    );
    echo 'Created user #' . $id . ' (' . ($username !== '' ? $username . ' / ' : '') . $email . ', ' . $role . ").\n";
}

echo "Sign in at /epiadmin/ and set your own password from the Security page.\n";
