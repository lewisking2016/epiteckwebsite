<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';

$user = admin_require();
$returnTo = admin_url('password.php');

if (is_post()) {
    $current = (string) ($_POST['current'] ?? '');
    $new = (string) ($_POST['new'] ?? '');
    $confirm = (string) ($_POST['confirm'] ?? '');

    if (!csrf_valid($_POST['csrf'] ?? null)) {
        admin_flash('err', 'Your session expired. Reload and try again.');
    } else {
        $account = db_one('SELECT pass_hash FROM users WHERE id = ?', [(int) $user['id']]);
        if (!$account || !password_verify($current, (string) $account['pass_hash'])) {
            admin_flash('err', 'The current password is not correct.');
        } elseif (strlen($new) < 10) {
            admin_flash('err', 'Use at least 10 characters for the new password.');
        } elseif ($new !== $confirm) {
            admin_flash('err', 'The two new passwords do not match.');
        } elseif ($new === $current) {
            admin_flash('err', 'Choose a password you have not used here before.');
        } else {
            admin_set_password((int) $user['id'], $new);
            admin_flash('ok', 'Password changed. Use it the next time you sign in.');
        }
    }
    redirect($returnTo);
}

$user = admin_current() ?? $user;

admin_head('Security', 'security', $user);
admin_page_head(
    'Security',
    'Change the password for your own account and review how the console is protected.',
    '<a class="A_Btn A_Btn--sm" href="' . e(admin_url('index.php')) . '">Overview</a>'
);
?>

<div class="A_Detail">
  <div class="A_Stack">
    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Change password</h2></div>
      <div class="A_Detail_B">
        <form class="A_Form" method="post" action="<?= e($returnTo) ?>">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <div class="A_Field">
            <label class="A_Label" for="current">Current password</label>
            <input class="A_Input" id="current" name="current" type="password" autocomplete="current-password" required>
          </div>
          <div class="A_Field">
            <label class="A_Label" for="new">New password</label>
            <input class="A_Input" id="new" name="new" type="password" autocomplete="new-password" minlength="10" required>
            <p class="A_Hint">At least 10 characters. A short phrase of three unrelated words is stronger than a scrambled word.</p>
          </div>
          <div class="A_Field">
            <label class="A_Label" for="confirm">Repeat new password</label>
            <input class="A_Input" id="confirm" name="confirm" type="password" autocomplete="new-password" minlength="10" required>
          </div>
          <div><button class="A_Btn A_Btn--primary" type="submit">Update password</button></div>
        </form>
      </div>
    </section>
  </div>

  <div class="A_Stack">
    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Account</h2></div>
      <div class="A_Detail_B">
        <dl class="A_DL A_DL--tight">
          <dt>Name</dt><dd><?= e((string) $user['name']) ?></dd>
          <dt>Username</dt><dd class="mono"><?= e(display_name((string) ($user['username'] ?? ''))) ?></dd>
          <dt>Email</dt><dd><?= e((string) $user['email']) ?></dd>
          <dt>Role</dt><dd><?= e(ROLES[$user['role']] ?? (string) $user['role']) ?></dd>
          <dt>Last sign-in</dt><dd><?= e(human_date($user['last_login_at'] ?? null, 'j M Y, H:i')) ?> UTC</dd>
          <dt>Password set</dt><dd><?= e(human_date($user['pass_changed_at'] ?? null, 'j M Y, H:i')) ?> UTC</dd>
        </dl>
      </div>
    </section>

    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">How this console is protected</h2></div>
      <div class="A_Detail_B">
        <ul class="A_Security_List">
          <li><b>Session cookies</b> are HttpOnly, SameSite=Lax and marked Secure over HTTPS. The session id changes on sign-in.</li>
          <li><b>Cross-site request forgery</b> is blocked on every write with a per-session token.</li>
          <li><b>Sign-in throttling</b> allows six failed attempts per quarter hour per address.</li>
          <li><b>Roles</b> are enforced on the server: viewer accounts cannot change stages, owners or notes.</li>
          <li><b>Passwords</b> are stored as bcrypt hashes; the plain text is never written to disk or logs.</li>
          <li><b>Audit trail</b> records sign-ins, stage changes, notes, password changes and exports.</li>
          <li><b>Data access</b> uses prepared statements only, and the console is excluded from search engines and caches.</li>
        </ul>
        <p class="A_Note">Site-wide policies: <a href="<?= e(url('/privacy')) ?>">Privacy</a> · <a href="<?= e(url('/cookies')) ?>">Cookies</a> · <a href="<?= e(url('/security')) ?>">Security</a> · <a href="<?= e(url('/terms')) ?>">Terms</a></p>
      </div>
    </section>
  </div>
</div>

<?php admin_foot(); ?>
