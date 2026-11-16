<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';
require_once dirname(__DIR__) . '/includes/content_fields.php';

$user = admin_require();
$groups = content_field_groups();

$totalFields = 0;
$totalEdited = 0;
foreach ($groups as $group) {
    $totalFields += count($group['fields']);
    $totalEdited += content_group_edited($group);
}

admin_head('Pages', 'pages', $user);
admin_page_head(
    'Pages',
    'Every page on the public site, with the text you can edit. Pick a page to change its words.',
    '<a class="A_Btn A_Btn--sm" href="' . e(url('/')) . '" target="_blank" rel="noopener">View site</a>'
);
?>

<p class="A_Note" style="margin-bottom:14px">
  <b><?= number_format(count($groups)) ?></b> pages ·
  <b><?= number_format($totalFields) ?></b> editable fields ·
  <b><?= number_format($totalEdited) ?></b> edited from the original wording.
</p>

<section class="A_Panel">
  <div class="A_Panel_H"><h2 class="A_Panel_T">Content pages</h2></div>
  <div class="A_Detail_B">
    <div class="A_Table_Wrap">
      <table class="A_Table">
        <thead>
          <tr>
            <th scope="col">Page</th>
            <th scope="col">Web address</th>
            <th scope="col">Fields</th>
            <th scope="col">Edited</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($groups as $group): ?>
            <?php $edited = content_group_edited($group); ?>
            <tr>
              <td><b><?= e($group['label']) ?></b></td>
              <td class="mono"><?= e($group['path']) ?></td>
              <td><?= number_format(count($group['fields'])) ?></td>
              <td>
                <?php if ($edited > 0): ?>
                  <span class="Pill Pill--ok"><?= number_format($edited) ?></span>
                <?php else: ?>
                  <span class="Pill Pill--muted">0</span>
                <?php endif; ?>
              </td>
              <td>
                <a class="A_Btn A_Btn--sm" href="<?= e(admin_url('content.php?page=' . rawurlencode($group['id']))) ?>">Edit</a>
                <a class="A_Btn A_Btn--sm" href="<?= e(url($group['path'])) ?>" target="_blank" rel="noopener">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="A_Panel" style="margin-top:18px">
  <div class="A_Panel_H"><h2 class="A_Panel_T">How editing works</h2></div>
  <div class="A_Detail_B">
    <ul class="A_Security_List">
      <li><b>Nothing is overwritten.</b> The site keeps its built-in text; the database stores only what you change.</li>
      <li><b>Reset</b> on any field deletes the change and brings the original wording back.</li>
      <li><b>Live immediately</b> — no rebuild, no deploy, no cache to clear.</li>
      <li><b>Audited</b> — every save and reset is recorded in Activity against your account.</li>
      <li><b>Safe by default</b> — if the database is unavailable the page renders its built-in text instead of breaking.</li>
    </ul>
    <p class="A_Note">This screen edits words, not layout. New pages, services or sections still need a developer.</p>
  </div>
</section>

<?php admin_foot(); ?>
