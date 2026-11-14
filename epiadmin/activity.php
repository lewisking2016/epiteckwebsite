<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';

$user = admin_require();
$page = max(1, (int) ($_GET['page'] ?? 1));
$result = activity_page($page);
$href = static fn (int $p): string => admin_url('activity.php?page=' . $p);

admin_head('Activity', 'activity', $user);
admin_page_head(
    'Activity',
    'Sign-ins, stage changes, notes, password changes and exports. ' . number_format($result['total']) . ' records, newest first.'
);
?>

<section class="A_Panel">
  <?php if (!$result['rows']): ?>
    <?php admin_empty('No activity yet', 'Every sign-in, stage change and export is recorded here.'); ?>
  <?php else: ?>
    <div class="A_Table_Wrap">
      <table class="A_Table A_Table--log">
        <thead><tr><th class="num">When</th><th>Action</th><th>Record</th><th>Who</th></tr></thead>
        <tbody>
        <?php foreach ($result['rows'] as $row): ?>
          <tr>
            <td class="num"><?= e(human_date((string) $row['created_at'], 'j M Y, H:i')) ?></td>
            <td><b><?= e(str_replace('.', ' · ', (string) $row['action'])) ?></b></td>
            <td class="num">
              <?php if ($row['subject_type'] !== ''): ?>
                <?= e((string) $row['subject_type']) ?> #<?= (int) $row['subject_id'] ?>
                <?php if (!empty($row['meta'])): ?><span class="A_Meta"><?= e((string) $row['meta']) ?></span><?php endif; ?>
              <?php else: ?>—<?php endif; ?>
            </td>
            <td><?= e(display_name($row['user_name'] ?? '')) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?= pager_html($result['page'], $result['pages'], $href) ?>
  <?php endif; ?>
</section>

<?php admin_foot(); ?>
