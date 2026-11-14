<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';

$user = admin_require();
$f = enquiry_filters($_GET);
$result = enquiries_page($f);
$countries = enquiry_countries();

$href = static fn (int $p): string => admin_url('enquiries.php?' . http_build_query(array_filter([
    'q' => $f['q'], 'status' => $f['status'], 'country' => $f['country'], 'page' => $p,
])));
$exportUrl = admin_url('export.php?type=enquiries' . ($f['status'] !== '' ? '&status=' . urlencode($f['status']) : ''));
$filtered = $f['q'] !== '' || $f['status'] !== '' || $f['country'] !== '';

admin_head('Enquiries', 'enquiries', $user);
admin_page_head(
    'Enquiries',
    number_format($result['total']) . ' matching ' . ($result['total'] === 1 ? 'enquiry' : 'enquiries') . '. Contact-form submissions, newest first.',
    '<a class="A_Btn A_Btn--sm" href="' . e($exportUrl) . '">Export CSV</a>'
);
?>

<form class="A_Filters" method="get" action="<?= e(admin_url('enquiries.php')) ?>">
  <label class="A_Filters_Search">
    <?= ai('search') ?>
    <input class="A_Input" type="search" name="q" value="<?= e($f['q']) ?>" placeholder="Name, organisation, email or reference" aria-label="Search enquiries">
  </label>
  <select class="A_Select" name="status" aria-label="Status">
    <option value="">All statuses</option>
    <?php foreach (ENQUIRY_STAGES as $key => $label): ?>
      <option value="<?= e($key) ?>"<?= $f['status'] === $key ? ' selected' : '' ?>><?= e($label) ?></option>
    <?php endforeach; ?>
  </select>
  <select class="A_Select" name="country" aria-label="Country">
    <option value="">All countries</option>
    <?php foreach ($countries as $row): ?>
      <option value="<?= e((string) $row['country']) ?>"<?= $f['country'] === $row['country'] ? ' selected' : '' ?>><?= e((string) $row['country']) ?> (<?= (int) $row['n'] ?>)</option>
    <?php endforeach; ?>
  </select>
  <button class="A_Btn A_Btn--primary" type="submit">Filter</button>
  <?php if ($filtered): ?>
    <a class="A_Btn A_Btn--ghost" href="<?= e(admin_url('enquiries.php')) ?>">Reset</a>
  <?php endif; ?>
</form>

<section class="A_Panel">
  <?php if (!$result['rows']): ?>
    <?php admin_empty(
        $filtered ? 'Nothing matches this filter' : 'No enquiries yet',
        $filtered ? 'Try a shorter search term or clear the filters.' : 'Contact-form submissions land here automatically.',
        $filtered ? '<a class="A_Btn A_Btn--sm" href="' . e(admin_url('enquiries.php')) . '">Clear filters</a>' : ''
    ); ?>
  <?php else: ?>
    <div class="A_Table_Wrap">
      <table class="A_Table">
        <thead>
          <tr><th>Reference</th><th>Who</th><th>Country</th><th>Interest</th><th>Status</th><th class="num">Received</th></tr>
        </thead>
        <tbody>
        <?php foreach ($result['rows'] as $row): ?>
          <tr>
            <td class="num"><a class="A_Row_A" href="<?= e(admin_url('enquiry.php?id=' . (int) $row['id'])) ?>"><?= e((string) $row['reference']) ?></a></td>
            <td class="who">
              <b><?= e((string) $row['name']) ?></b>
              <span><?= e(display_name((string) $row['organisation'])) ?></span>
            </td>
            <td><?= e(display_name((string) $row['country'])) ?></td>
            <td><?= e(display_name((string) $row['interests'])) ?></td>
            <td><?= stage_pill(enquiry_stage((string) $row['status']), enquiry_tone((string) $row['status'])) ?></td>
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
