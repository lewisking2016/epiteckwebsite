<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once dirname(__DIR__) . '/includes/analytics.php';
require_once __DIR__ . '/_layout.php';

$user = admin_require();
$d = dashboard_data();
$traffic = analytics_summary();

$peak = 1;
foreach ($d['series'] as $day) {
    $peak = max($peak, $day['enquiries'], $day['applications']);
}

admin_head('Overview', 'overview', $user);
admin_page_head(
    'Overview',
    'What arrived, what is open and what needs a decision. Times are UTC.',
    '<a class="A_Btn A_Btn--sm" href="' . e(admin_url('enquiries.php')) . '">Enquiries</a>'
    . '<a class="A_Btn A_Btn--sm A_Btn--primary" href="' . e(admin_url('applications.php')) . '">Applications ' . ai('arrow') . '</a>'
);
?>

<div class="A_Metrics">
  <article class="A_Metric">
    <span class="A_Metric_L">Enquiries · 30 days</span>
    <span class="A_Metric_V"><?= number_format($d['enquiries_30']) ?></span>
    <span class="A_Metric_D"><?= number_format($d['enquiries_7']) ?> this week · <?= number_format($d['enquiries_today']) ?> today</span>
  </article>
  <article class="A_Metric">
    <span class="A_Metric_L">Open enquiries</span>
    <span class="A_Metric_V"><?= number_format($d['enquiries_open']) ?></span>
    <span class="A_Metric_D"><?= number_format($d['enquiries_total']) ?> received in total</span>
  </article>
  <article class="A_Metric">
    <span class="A_Metric_L">Applications · 30 days</span>
    <span class="A_Metric_V"><?= number_format($d['applications_30']) ?></span>
    <span class="A_Metric_D"><?= number_format($d['applications_total']) ?> in the pipeline overall</span>
  </article>
  <article class="A_Metric A_Metric--accent">
    <span class="A_Metric_L">Still to decide</span>
    <span class="A_Metric_V"><?= number_format($d['applications_open']) ?></span>
    <span class="A_Metric_D"><?= number_format($d['enrolled']) ?> enrolled to date</span>
  </article>
</div>

<div class="A_Grid A_Grid--main">
  <div class="A_Stack">
    <section class="A_Panel" aria-labelledby="Intake_T">
      <div class="A_Panel_H">
        <div>
          <h2 class="A_Panel_T" id="Intake_T">Intake · last 14 days</h2>
          <p class="A_Panel_Sub">Enquiries and applications received each day</p>
        </div>
        <div class="A_Legend">
          <span class="A_Legend_I A_Legend_I--enq">Enquiries</span>
          <span class="A_Legend_I A_Legend_I--app">Applications</span>
        </div>
      </div>
      <div class="A_Chart" role="img" aria-label="Daily enquiries and applications received over the last fourteen days">
        <?php foreach ($d['series'] as $day):
            $h1 = (int) round(($day['enquiries'] / $peak) * 100);
            $h2 = (int) round(($day['applications'] / $peak) * 100); ?>
          <div class="A_Chart_Col" title="<?= e($day['label']) ?>: <?= (int) $day['enquiries'] ?> enquiries, <?= (int) $day['applications'] ?> applications">
            <span class="A_Chart_Bar A_Chart_Bar--enq" style="height: <?= $day['enquiries'] ? max(6, $h1) : 0 ?>%"></span>
            <span class="A_Chart_Bar A_Chart_Bar--app" style="height: <?= $day['applications'] ? max(6, $h2) : 0 ?>%"></span>
            <span class="A_Chart_X"><?= e(substr($day['label'], 0, 2)) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="A_Panel" aria-labelledby="Enq_T">
      <div class="A_Panel_H">
        <h2 class="A_Panel_T" id="Enq_T">Latest enquiries</h2>
        <a class="A_Btn A_Btn--sm A_Btn--ghost" href="<?= e(admin_url('enquiries.php')) ?>">All enquiries</a>
      </div>
      <?php if (!$d['enquiries_recent']): ?>
        <?php admin_empty('No enquiries yet', 'Contact-form submissions appear here the moment they arrive.', '<a class="A_Btn A_Btn--sm" href="' . e(url('/contact')) . '" target="_blank" rel="noopener">Open contact form</a>'); ?>
      <?php else: ?>
        <div class="A_Table_Wrap">
          <table class="A_Table">
            <thead><tr><th>Who</th><th>Interest</th><th>Status</th><th class="num">Received</th></tr></thead>
            <tbody>
            <?php foreach ($d['enquiries_recent'] as $row): ?>
              <tr>
                <td class="who">
                  <a class="A_Row_A" href="<?= e(admin_url('enquiry.php?id=' . (int) $row['id'])) ?>"><?= e((string) $row['name']) ?></a>
                  <span><?= e(display_name((string) $row['organisation'])) ?></span>
                </td>
                <td><?= e(display_name((string) $row['interests'])) ?></td>
                <td><?= stage_pill(enquiry_stage((string) $row['status']), enquiry_tone((string) $row['status'])) ?></td>
                <td class="num"><?= e(relative_date((string) $row['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

    <section class="A_Panel" aria-labelledby="App_T">
      <div class="A_Panel_H">
        <h2 class="A_Panel_T" id="App_T">Latest applications</h2>
        <a class="A_Btn A_Btn--sm A_Btn--ghost" href="<?= e(admin_url('applications.php')) ?>">All applications</a>
      </div>
      <?php if (!$d['applications_recent']): ?>
        <?php admin_empty('No applications yet', 'The public intake form is at /apply.', '<a class="A_Btn A_Btn--sm" href="' . e(url('/apply')) . '" target="_blank" rel="noopener">Open /apply</a>'); ?>
      <?php else: ?>
        <div class="A_Table_Wrap">
          <table class="A_Table">
            <thead><tr><th>Applicant</th><th>Type</th><th>Stage</th><th class="num">Received</th></tr></thead>
            <tbody>
            <?php foreach ($d['applications_recent'] as $row): ?>
              <tr>
                <td class="who">
                  <a class="A_Row_A" href="<?= e(admin_url('application.php?id=' . (int) $row['id'])) ?>"><?= e((string) $row['name']) ?></a>
                  <span><?= e(display_name($row['programme'] !== '' ? (string) $row['programme'] : (string) $row['organisation'])) ?></span>
                </td>
                <td><?= e(APPLICATION_TYPES[$row['type']] ?? (string) $row['type']) ?></td>
                <td><?= stage_pill(application_stage((string) $row['type'], (string) $row['status']), enquiry_tone((string) $row['status'])) ?></td>
                <td class="num"><?= e(relative_date((string) $row['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>
  </div>

  <div class="A_Stack">
    <section class="A_Panel" aria-labelledby="Attn_T">
      <div class="A_Panel_H">
        <h2 class="A_Panel_T" id="Attn_T">Needs attention</h2>
        <span class="A_Note">Open 3+ days</span>
      </div>
      <?php if (!$d['attention']): ?>
        <p class="A_Panel_Empty">Nothing is waiting. Every open record has been touched in the last three days.</p>
      <?php else: ?>
        <ul class="A_List">
          <?php foreach ($d['attention'] as $item): ?>
            <li class="A_List_Item">
              <a href="<?= e(admin_url(($item['kind'] === 'application' ? 'application.php' : 'enquiry.php') . '?id=' . (int) $item['id'])) ?>">
                <b><?= e((string) $item['name']) ?></b>
                <span><?= e($item['kind'] === 'application' ? 'Application' : 'Enquiry') ?> · <?= e(relative_date((string) $item['created_at'])) ?></span>
              </a>
              <?= stage_pill(
                  $item['kind'] === 'application'
                      ? application_stage((string) $item['type'], (string) $item['status'])
                      : enquiry_stage((string) $item['status']),
                  enquiry_tone((string) $item['status'])
              ) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>

    <section class="A_Panel" aria-labelledby="Pipe_T">
      <div class="A_Panel_H"><h2 class="A_Panel_T" id="Pipe_T">Pipeline</h2></div>
      <div class="A_Panel_B">
        <p class="A_Metric_L">Enquiries</p>
        <div class="A_Segments">
          <?php foreach (ENQUIRY_STAGES as $key => $label):
              $n = $d['enquiries_by_stage'][$key] ?? 0; ?>
            <a class="A_Seg" href="<?= e(admin_url('enquiries.php?status=' . urlencode($key))) ?>">
              <span class="A_Seg_N"><?= number_format($n) ?></span>
              <span class="A_Seg_L"><?= e($label) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
        <?php foreach (APPLICATION_TYPES as $type => $typeLabel): ?>
          <p class="A_Metric_L"><?= e($typeLabel) ?></p>
          <div class="A_Segments">
            <?php foreach (application_stages($type) as $key => $label):
                $n = $d['applications_by_stage'][$type][$key] ?? 0; ?>
              <span class="A_Seg A_Seg--static">
                <span class="A_Seg_N"><?= number_format($n) ?></span>
                <span class="A_Seg_L"><?= e($label) ?></span>
              </span>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="A_Panel" aria-labelledby="Traffic_T">
      <div class="A_Panel_H">
        <h2 class="A_Panel_T" id="Traffic_T">Traffic</h2>
        <span class="A_Note"><?= e(human_date(gmdate('Y-m-d H:i:s'))) ?></span>
      </div>
      <div class="A_Panel_B">
        <?php if (!$traffic['available']): ?>
          <p class="A_Note"><?= e($traffic['note']) ?></p>
        <?php else: ?>
          <div class="A_Stat_Row">
            <div><span class="A_Metric_L">Page views</span><b><?= number_format($traffic['pageviews']) ?></b></div>
            <div><span class="A_Metric_L">Not found</span><b><?= number_format($traffic['notfound']) ?></b></div>
            <div><span class="A_Metric_L">Mobile</span><b><?= $traffic['mobile'] ?>%</b></div>
          </div>
          <?php if ($traffic['top']): ?>
            <table class="A_Table A_Table--tight">
              <thead><tr><th>Page</th><th class="num">Views</th></tr></thead>
              <tbody>
              <?php foreach ($traffic['top'] as $page => $views): ?>
                <tr><td><?= e((string) $page) ?></td><td class="num"><?= number_format((int) $views) ?></td></tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
          <?php if ($traffic['referrers']): ?>
            <p class="A_Note">Referrers: <?= e(implode(' · ', array_keys($traffic['referrers']))) ?></p>
          <?php endif; ?>
          <p class="A_Note"><?= e($traffic['note']) ?></p>
        <?php endif; ?>
      </div>
    </section>

    <section class="A_Panel" aria-labelledby="Act_T">
      <div class="A_Panel_H">
        <h2 class="A_Panel_T" id="Act_T">Activity</h2>
        <a class="A_Btn A_Btn--sm A_Btn--ghost" href="<?= e(admin_url('activity.php')) ?>">Log</a>
      </div>
      <?php if (!$d['activity']): ?>
        <p class="A_Panel_Empty">No recorded activity yet.</p>
      <?php else: ?>
        <ul class="A_Timeline">
          <?php foreach ($d['activity'] as $row): ?>
            <li>
              <span class="A_Timeline_Dot" aria-hidden="true"></span>
              <span class="A_Timeline_B">
                <b><?= e(str_replace('.', ' · ', (string) $row['action'])) ?></b>
                <span><?= e(display_name($row['user_name'] ?? '')) ?> · <?= e(relative_date((string) $row['created_at'])) ?></span>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </div>
</div>

<?php admin_foot(); ?>
