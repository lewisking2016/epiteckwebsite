<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'about',
    'nav'         => 'about',
    'path'        => '/about',
    'title'       => 'About EPiTEK Solutions | East Africa',
    'description' => 'EPiTEK Solutions supports aviation and air-force fleets across East Africa through engineering, integration, training and depot sustainment.',
    'heroImage'   => 'assets/img/hero-terrain.svg',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/hero-terrain.svg')) ?>" alt="" width="1600" height="1100" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>About</span>
      </nav>
      <span class="Eyebrow"><?= e($COMPANY['eyebrow']) ?></span>
      <h1><?= e($COMPANY['title']) ?></h1>
      <p class="PageHero_Lead"><?= e($COMPANY['lead']) ?></p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="About_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">Why we exist</span>
        <h2 id="About_T">The gap is after delivery</h2>
        <?php foreach ($COMPANY['body'] as $paragraph): ?>
          <p><?= e($paragraph) ?></p>
        <?php endforeach; ?>
        <ul class="Split_List">
          <?php foreach ($COMPANY['list'] as $line): ?>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> <?= e($line) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="Split_Media">
        <div class="MediaCard">
          <span class="MediaTag">EAST AFRICA · REGIONAL OFFICE</span>
          <img src="<?= e(url('assets/img/coverage-map.svg')) ?>" alt="Map of the East African coverage area with regional hub markers" width="900" height="760" loading="lazy" decoding="async">
          <div class="MediaCard_Cap"><span><b>5 states</b> · coordinated from Nairobi</span><span>NOT TO SCALE</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="Section Section--alt" aria-labelledby="How_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">How we work</span>
      <h2 id="How_T">Four commitments</h2>
      <p class="Head_B">Simple rules that decide how an engagement is scoped, staffed and closed.</p>
    </div>
    <div class="WhyGrid">
      <?php foreach ($WHY as $why): ?>
        <article class="Why">
          <span class="Why_N"><?= e($why['n']) ?></span>
          <h3 class="Why_T"><?= e($why['title']) ?></h3>
          <p class="Why_D"><?= e($why['desc']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Cov_T">
  <div class="Wrap">
    <div class="Head Head--split">
      <div>
        <span class="Eyebrow">Regional footprint</span>
        <h2 id="Cov_T">Where we operate</h2>
        <p class="Head_B">Coordination, engineering, training and field support across five East African states, with one programme office accountable for delivery.</p>
      </div>
      <div class="Head_A">
        <a class="Btn Btn--ghost" href="<?= e(url('/distributor-integration')) ?>">Logistics &amp; clearance model</a>
      </div>
    </div>

    <div class="Countries">
      <?php foreach ($COUNTRIES as $country): ?>
        <article class="Country">
          <span class="Country_C" aria-hidden="true"><?= e($country['code']) ?></span>
          <h3 class="Country_N"><?= e($country['name']) ?></h3>
          <p class="Country_R"><?= e($country['role']) ?></p>
          <span class="Country_Note"><?= e($country['note']) ?></span>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="Stats Stats--spaced">
      <?php foreach ($METRICS as $metric): ?>
        <div class="Stat">
          <span class="Stat_V"><?= e($metric['value']) ?></span>
          <span class="Stat_L"><?= e($metric['label']) ?></span>
          <span class="Stat_D"><?= e($metric['detail']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
