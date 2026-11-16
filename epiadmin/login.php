<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';

admin_no_cache();

if (admin_current()) {
    redirect(admin_url('index.php'));
}

$error = '';
$login = '';

if (is_post()) {
    $login = trim((string) ($_POST['login'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $error = 'Your session expired. Reload the page and try again.';
    } else {
        try {
            if (admin_login($login, $password)) {
                redirect(admin_url('index.php'));
            }

            // Only failed attempts count toward the throttle, so normal sign-ins are never blocked.
            $error = rate_limit('admin-login', 6, 900)
                ? 'Those details do not match an account.'
                : 'Too many failed attempts. Wait fifteen minutes and try again.';
        } catch (Throwable $e) {
            error_log('epiadmin sign-in: ' . $e->getMessage());
            $error = admin_db_message($e);
        }
    }

    // A database problem is a server fault, not a bad password.
    http_response_code(str_contains($error, 'database') || str_contains($error, 'tables are missing') ? 503 : 401);
}
?><!DOCTYPE html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<meta name="color-scheme" content="light">
<title>Sign in · EPiTEK Console</title>
<link rel="icon" href="<?= e(asset('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="stylesheet" href="<?= e(asset('assets/css/fonts.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
</head>
<body class="A A_Auth_Body">
<main class="A_Auth">
  <section class="A_Auth_Panel" aria-hidden="true">
    <img class="A_Auth_Logo" src="<?= e(asset('assets/img/epitek-logo-white.svg')) ?>" alt="" width="168" height="27">
    <p class="A_Auth_Eyebrow">Regional operations · East Africa</p>
    <h2 class="A_Auth_T">The console behind the<br>aviation support desk.</h2>
    <ul class="A_Auth_List">
      <li>Enquiries from the public contact form</li>
      <li>Training enrolments and organisation requests</li>
      <li>Stage changes and internal notes, fully audited</li>
    </ul>
    <p class="A_Auth_Meta">Authorised staff only. All access is logged.</p>
  </section>

  <section class="A_Auth_Form">
    <form class="A_Auth_Card" method="post" action="<?= e(admin_url('login.php')) ?>" novalidate>
      <img class="A_Auth_CardLogo" src="<?= e(asset('assets/img/epitek-logo.svg')) ?>" alt="EPiTEK Solutions" width="160" height="26">
      <div>
        <h1 class="A_Auth_H1">Console sign-in</h1>
        <p class="A_Auth_Sub">Use the username issued to you, or your account email.</p>
      </div>

      <?php if ($error !== ''): ?>
        <div class="A_Flash A_Flash--err" role="alert"><?= e($error) ?></div>
      <?php endif; ?>

      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <div class="A_Field">
        <label class="A_Label" for="login">Username or email</label>
        <input class="A_Input" id="login" name="login" type="text" autocomplete="username" autocapitalize="none" spellcheck="false" required value="<?= e($login) ?>">
      </div>
      <div class="A_Field">
        <label class="A_Label" for="password">Password</label>
        <input class="A_Input" id="password" name="password" type="password" autocomplete="current-password" required>
      </div>
      <button class="A_Btn A_Btn--primary A_Btn--block" type="submit">Sign in</button>
      <p class="A_Auth_Note">Sessions are cookie-based, expire when you close the browser and are rate-limited after repeated failures.</p>
      <a class="A_Auth_Back" href="<?= e(url('/')) ?>">← Back to epitexsolution.africa</a>
    </form>
  </section>
</main>
</body>
</html>
