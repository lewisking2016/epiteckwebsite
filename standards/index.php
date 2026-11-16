<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'standards',
    'nav'         => 'standards',
    'path'        => '/standards',
    'title'       => 'Standards and compliance | EPiTEK Solutions',
    'description' => 'How ICAO, EUROCONTROL, NATO STANAG, ISO 9001 and AS9100 apply to EPiTEK engineering and training work, and what evidence is supplied on request.',
    'heroImage'   => 'assets/img/c4isr.svg',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/c4isr.svg')) ?>" alt="" width="860" height="640" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Standards</span>
      </nav>
      <span class="Eyebrow">Standards &amp; compliance</span>
      <h1>Standards are the baseline. Evidence is the deliverable.</h1>
      <p class="PageHero_Lead">“Aligned” is not “certified”. We keep the distinction visible and hand over the evidence file an audit team will ask for.</p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Table_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Frameworks</span>
      <h2 id="Table_T">What we reference, and how it applies</h2>
      <p class="Head_B">Status describes how far the framework is applied in our own work. Certification is stated per engagement, not asserted site-wide.</p>
    </div>

    <div class="Table_Scroll">
      <table class="Table">
        <caption class="Table_Note">Frameworks referenced in engineering, training and quality documentation.</caption>
        <thead>
          <tr><th scope="col">Framework</th><th scope="col">Scope</th><th scope="col">How it applies</th><th scope="col">Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($STANDARDS as $row): ?>
            <tr>
              <td><?= e($row['standard']) ?></td>
              <td><?= e($row['scope']) ?></td>
              <td><?= e($row['apply']) ?></td>
              <td><span class="Pill<?= $row['status'] === 'Aligned' ? ' Pill--ok' : '' ?>"><?= e($row['status']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <p class="Table_Note">Certificate numbers and authorisation evidence are supplied during due diligence rather than published as marketing claims.</p>
  </div>
</section>

<section class="Section Section--alt" aria-labelledby="Exp_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">Export control</span>
        <h2 id="Exp_T">A gate, not a formality</h2>
        <p>Dual-use and military items are screened against the applicable national and supplier-country regimes. Where a licence or authorisation is required, the programme timeline includes it explicitly instead of absorbing it silently.</p>
        <ul class="Split_List">
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> End-user statements and non-transfer undertakings verified before release.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Technical data released only to screened institutional recipients.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Authorisation tracked through the programme, not assumed at the border.</li>
        </ul>
        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/contact')) ?>">Request compliance evidence <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </div>
      <div class="Split_Media">
        <div class="MediaCard">
          <span class="MediaTag">COMPLIANCE FILE</span>
          <img src="<?= e(url('assets/img/depot.svg')) ?>" alt="Documentation set with traceability callouts" width="860" height="640" loading="lazy" decoding="async">
          <div class="MediaCard_Cap"><span>Evidence on request</span><span>DUE DILIGENCE</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
