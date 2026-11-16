<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';
require_once dirname(__DIR__) . '/includes/visit_log.php';

$user = admin_require();
$returnTo = admin_url('visits.php');

if (is_post()) {
    if (!admin_can_write($user)) {
        admin_flash('err', 'Your role can view visits but not change them.');
    } elseif (!csrf_valid($_POST['csrf'] ?? null)) {
        admin_flash('err', 'Your session expired. Reload and try again.');
    } else {
        $marked = visit_mark_all_seen();
        if ($marked > 0) {
            audit('visits.seen', 'visits', 0, ['count' => $marked]);
        }
        admin_flash('ok', $marked === 0 ? 'Nothing new to clear.' : $marked . ' visit' . ($marked === 1 ? '' : 's') . ' marked as read.');
    }
    redirect($returnTo);
}

$user = admin_current() ?? $user;

$unseen = visit_unseen();
$last24 = visit_since('-24 hours');
$last7  = visit_since('-7 days');
$total  = visit_since('-10 years');
$top    = visit_top_paths(7, 6);

$recent = [];
$error = '';
try {
    $recent = db_all('SELECT path, referrer, ua, created_at, seen FROM visits ORDER BY id DESC LIMIT 80');
} catch (Throwable $e) {
    $error = 'The visits table does not exist yet. Open Console → Upgrade to create it.';
}

admin_head('Visits', 'visits', $user);
admin_page_head(
    'Visits',
    'Page views on the public site. New visits appear here and in the badge on this menu.',
    $unseen > 0
        ? '<form method="post" action="' . e($returnTo) . '"><input type="hidden" name="csrf" value="' . e(csrf_token()) . '"><button class="A_Btn A_Btn--sm A_Btn--primary" type="submit">Mark all read (' . (int) $unseen . ')</button></form>'
        : ''
);
?>

<?php if ($error !== ''): ?>
  <div class="A_Flash A_Flash--err"><?= e($error) ?></div>
<?php endif; ?>

<div class="A_Detail">
  <div class="A_Stack">
    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Traffic</h2></div>
      <div class="A_Detail_B">
        <dl class="A_DL A_DL--tight">
          <dt>Unread visits</dt><dd><b><?= number_format($unseen) ?></b></dd>
          <dt>Last 24 hours</dt><dd><?= number_format($last24) ?></dd>
          <dt>Last 7 days</dt><dd><?= number_format($last7) ?></dd>
          <dt>All time</dt><dd><?= number_format($total) ?></dd>
        </dl>
        <p class="A_Note">Bots, crawlers and monitor requests are not recorded. Addresses are stored as a one-way hash, never as plain IPs.</p>
      </div>
    </section>
  </div>

  <div class="A_Stack">
    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Most viewed pages (7 days)</h2></div>
      <div class="A_Detail_B">
        <?php if (!$top): ?>
          <p class="A_Note">No visits recorded yet.</p>
        <?php else: ?>
          <dl class="A_DL A_DL--tight">
            <?php foreach ($top as $row): ?>
              <dt class="mono"><?= e($row['path']) ?></dt><dd><?= number_format($row['hits']) ?></dd>
            <?php endforeach; ?>
          </dl>
        <?php endif; ?>
      </div>
    </section>
  </div>
</div>

<section class="A_Panel" style="margin-top:18px">
  <div class="A_Panel_H"><h2 class="A_Panel_T">Recent visits</h2></div>
  <div class="A_Detail_B">
    <?php if (!$recent): ?>
      <?= admin_empty('No visits yet', 'Once the site is being used, page views land here within seconds.') ?>
    <?php else: ?>
      <div class="A_Table_Wrap">
        <table class="A_Table">
          <thead>
            <tr>
              <th scope="col">When (UTC)</th>
              <th scope="col">Page</th>
              <th scope="col">Came from</th>
              <th scope="col">Browser</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent as $visit): ?>
              <tr>
                <td><?= e(human_date($visit['created_at'], 'j M Y, H:i')) ?></td>
                <td class="mono"><?= e((string) $visit['path']) ?></td>
                <td><?= e((string) ($visit['referrer'] !== '' ? $visit['referrer'] : 'direct')) ?></td>
                <td><?= e(mb_substr((string) $visit['ua'], 0, 48)) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php admin_foot(); ?>
