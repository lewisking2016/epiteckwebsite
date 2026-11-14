<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';

$user = admin_require();
$f = application_filters($_GET);
$result = applications_page($f);

$stageOptions = $f['type'] !== '' ? application_stages($f['type']) : APPLICATION_STAGES['training'];
$href = static fn (int $p): string => admin_url('applications.php?' . http_build_query(array_filter([
    'q' => $f['q'], 'type' => $f['type'], 'status' => $f['status'], 'page' => $p,
])));
$exportUrl = admin_url('export.php?type=applications' . ($f['type'] !== '' ? '&app_type=' . urlencode($f['type']) : ''));
$filtered = $f['q'] !== '' || $f['type'] !== '' || $f['status'] !== '';

admin_head('Applications', 'applications', $user);
admin_page_head(
    'Applications',
    number_format($result['total']) . ' matching ' . ($result['total'] === 1 ? 'application' : 'applications') . '. Training enrolments and organisation requests, newest first.',
    '<a class="A_Btn A_Btn--sm" href="' . e($exportUrl) . '">Export CSV</a>'
);
?>

<div class="A_Tabs" role="group" aria-label="Application type">
  <?php foreach (['' => 'All types'] + APPLICATION_TYPES as $key => $label): ?>
    <a class="A_Tab<?= $f['type'] === (string) $key ? ' is-on' : '' ?>" href="<?= e(admin_url('applications.php?' . http_build_query(array_filter(['type' => $key, 'status' => $f['status'], 'q' => $f['q']])))) ?>"<?= $f['type'] === (string) $key ? ' aria-current="true"' : '' ?>><?= e($label) ?></a>
  <?php endforeach; ?>
</div>

<form class="A_Filters" method="get" action="<?= e(admin_url('applications.php')) ?>">
  <label class="A_Filters_Search">
    <?= ai('search') ?>
    <input class="A_Input" type="search" name="q" value="<?= e($f['q']) ?>" placeholder="Name, email, organisation or programme" aria-label="Search applications">
  </label>
  <select class="A_Select" name="type" aria-label="Type">
    <option value="">All types</option>
    <?php foreach (APPLICATION_TYPES as $key => $label): ?>
      <option value="<?= e($key) ?>"<?= $f['type'] === $key ? ' selected' : '' ?>><?= e($label) ?></option>
    <?php endforeach; ?>
  </select>
  <select class="A_Select" name="status" aria-label="Stage">
    <option value="">All stages</option>
    <?php foreach ($stageOptions as $key => $label): ?>
      <option value="<?= e($key) ?>"<?= $f['status'] === $key ? ' selected' : '' ?>><?= e($label) ?></option>
    <?php endforeach; ?>
  </select>
  <button class="A_Btn A_Btn--primary" type="submit">Filter</button>
  <?php if ($filtered): ?>
    <a class="A_Btn A_Btn--ghost" href="<?= e(admin_url('applications.php')) ?>">Reset</a>
  <?php endif; ?>
</form>

<section class="A_Panel">
  <?php if (!$result['rows']): ?>
    <?php admin_empty(
        $filtered ? 'Nothing matches this filter' : 'No applications yet',
        $filtered ? 'Try a shorter search term or clear the filters.' : 'The public intake form lives at /apply.',
        $filtered
            ? '<a class="A_Btn A_Btn--sm" href="' . e(admin_url('applications.php')) . '">Clear filters</a>'
            : '<a class="A_Btn A_Btn--sm" href="' . e(url('/apply')) . '" target="_blank" rel="noopener">Open /apply</a>'
    ); ?>
  <?php else: ?>
    <div class="A_Table_Wrap">
      <table class="A_Table">
        <thead><tr><th>Reference</th><th>Applicant</th><th>Type</th><th>Programme / organisation</th><th>Stage</th><th class="num">Received</th></tr></thead>
        <tbody>
        <?php foreach ($result['rows'] as $row): ?>
          <tr>
            <td class="num"><a class="A_Row_A" href="<?= e(admin_url('application.php?id=' . (int) $row['id'])) ?>"><?= e((string) $row['reference']) ?></a></td>
            <td class="who">
              <b><?= e((string) $row['name']) ?></b>
              <span><?= e((string) $row['email']) ?></span>
            </td>
            <td><?= e(APPLICATION_TYPES[$row['type']] ?? (string) $row['type']) ?></td>
            <td><?= e(display_name($row['type'] === 'training' ? (string) $row['programme'] : (string) $row['organisation'])) ?></td>
            <td><?= stage_pill(application_stage((string) $row['type'], (string) $row['status']), enquiry_tone((string) $row['status'])) ?></td>
            <td class="num"><?= e(relative_date((string) $row['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?= pager_html($result['page'], $result['pages'], $href) ?>
  <?php endif; ?>
</section>

<?php admin_foot(); ?>
