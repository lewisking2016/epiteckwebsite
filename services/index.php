<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'services',
    'nav'         => 'services',
    'path'        => '/services',
    'title'       => 'Services | EPiTEK Solutions',
    'description' => 'Avionics maintenance, compliance training, UAS operator certification, a tiered support desk and technical advisory across East Africa.',
    'heroImage'   => 'assets/img/depot.svg',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/depot.svg')) ?>" alt="" width="860" height="640" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Services</span>
      </nav>
      <span class="Eyebrow">Services</span>
      <h1>Five services that keep a fleet flying</h1>
      <p class="PageHero_Lead">Maintenance, training, support and advisory work, each scoped in writing and closed with a record the receiving unit keeps.</p>
      <div class="PageHero_Act">
        <a class="Btn Btn--light" href="<?= e(url('/contact')) ?>">Discuss a requirement <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="Btn Btn--ghost-light" href="<?= e(url('/training')) ?>">Training pathway</a>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Svc_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Service catalogue</span>
      <h2 id="Svc_T">Choose a service</h2>
      <p class="Head_B">Every service has its own page with the scope, what is included and how it is delivered.</p>
    </div>

    <div class="SvcList">
      <?php foreach ($SERVICES as $i => $svc): ?>
        <article class="SvcRow">
          <span class="SvcRow_N"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <div>
            <h3 class="SvcRow_T"><a href="<?= e(url($svc['path'])) ?>"><?= e($svc['title']) ?></a></h3>
            <p class="SvcRow_D"><?= e($svc['summary']) ?></p>
          </div>
          <a class="SvcRow_Ico" href="<?= e(url($svc['path'])) ?>" aria-label="Read about <?= e($svc['title']) ?>">
            <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg>
          </a>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="Callout Callout--spaced">
      <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-shield"/></svg></span>
      <div>
        <h3 class="Callout_T">Response windows are service objectives</h3>
        <p class="Callout_D">A Tier 1 target of under two hours is what we plan to, not a guaranteed outcome. Final terms are agreed per platform and written into the support agreement.</p>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Faq_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Direct answers</span>
      <h2 id="Faq_T">Before you request a quotation</h2>
    </div>
    <div class="Faq">
      <?php foreach ($FAQ as $i => $faq): ?>
        <details class="Faq_Item"<?= $i === 0 ? ' open' : '' ?>>
          <summary><?= e($faq['q']) ?></summary>
          <p><?= e($faq['a']) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
