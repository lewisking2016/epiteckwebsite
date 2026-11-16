<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';
require_once dirname(__DIR__) . '/includes/schema.php';

$user = admin_require();
$canWrite = admin_can_write($user);
$returnTo = admin_url('upgrade.php');

if (is_post()) {
    if (!$canWrite) {
        admin_flash('err', 'Your role cannot change the database.');
    } elseif (!csrf_valid($_POST['csrf'] ?? null)) {
        admin_flash('err', 'Your session expired. Reload and try again.');
    } else {
        try {
            $result = run_schema();
            $ok = count(array_filter($result, static fn ($v) => $v === 'ok' || str_starts_with((string) $v, 'added')));
            audit('schema.upgrade', 'schema', 0, ['statements' => $ok]);
            admin_flash('ok', 'Schema checked. ' . $ok . ' statement' . ($ok === 1 ? '' : 's') . ' applied or already present.');
        } catch (Throwable $e) {
            error_log('epiadmin upgrade: ' . $e->getMessage());
            admin_flash('err', 'Schema upgrade failed: ' . $e->getMessage());
        }
    }
    redirect($returnTo);
}

$user = admin_current() ?? $user;

$expected = ['users', 'enquiries', 'applications', 'notes', 'activity', 'content', 'visits'];
$status = [];
foreach ($expected as $table) {
    try {
        $count = db_value('SELECT COUNT(*) FROM ' . $table);
        $status[] = ['table' => $table, 'ok' => true, 'rows' => $count];
    } catch (Throwable $e) {
        $status[] = ['table' => $table, 'ok' => false, 'rows' => 0];
    }
}
$missing = array_filter($status, static fn ($row) => !$row['ok']);

admin_head('Upgrade', 'upgrade', $user);
admin_page_head(
    'Database upgrade',
    'Creates any table this version of the site expects. Safe to run more than once — existing data is never touched.',
    ''
);
?>

<?php if ($missing): ?>
  <div class="A_Flash A_Flash--warn"><?= count($missing) ?> table(s) missing: <?= e(implode(', ', array_column($missing, 'table'))) ?></div>
<?php endif; ?>

<section class="A_Panel">
  <div class="A_Panel_H"><h2 class="A_Panel_T">Tables</h2></div>
  <div class="A_Detail_B">
    <div class="A_Table_Wrap">
      <table class="A_Table">
        <thead><tr><th scope="col">Table</th><th scope="col">Status</th><th scope="col">Rows</th></tr></thead>
        <tbody>
          <?php foreach ($status as $row): ?>
            <tr>
              <td class="mono"><?= e($row['table']) ?></td>
              <td><?= $row['ok'] ? '<span class="Pill Pill--ok">present</span>' : '<span class="Pill Pill--muted">missing</span>' ?></td>
              <td><?= $row['ok'] ? number_format($row['rows']) : '—' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($canWrite): ?>
      <form method="post" action="<?= e($returnTo) ?>" style="margin-top:16px">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <button class="A_Btn A_Btn--primary" type="submit">Check and create missing tables</button>
      </form>
      <p class="A_Note">Driver: <span class="mono"><?= e(db_is_sqlite() ? 'sqlite' : 'mysql') ?></span> · this page is owner-only in practice because it writes to the schema.</p>
    <?php else: ?>
      <p class="A_Note">Your role can inspect the schema but not change it.</p>
    <?php endif; ?>
  </div>
</section>

<?php admin_foot(); ?>
