<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'capabilities',
    'nav'         => 'capabilities',
    'path'        => '/capabilities',
    'title'       => 'Capabilities | EPiTEK Solutions',
    'description' => 'Four capability areas for East African aviation and air forces: unmanned systems support, aviation software and avionics, command and surveillance, depot and sustainment.',
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
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Capabilities</span>
      </nav>
      <span class="Eyebrow">Four areas, one team</span>
      <h1>What we support</h1>
      <p class="PageHero_Lead">Each area has its own page, because each one is a different conversation. Start with the one that matches your requirement.</p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Cap_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Capability areas</span>
      <h2 id="Cap_T">Pick the area you are working on</h2>
      <p class="Head_B">Engineering, training and sustainment run underneath all four. The difference is the platform and the operational question.</p>
    </div>

    <div class="Grid Grid--2">
      <?php foreach ($CAPABILITIES as $item): ?>
      <article class="Card">
        <div class="Card_Pic">
          <img src="<?= e(url($item['img'])) ?>" alt="<?= e($item['alt']) ?>" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag"><?= e($item['short']) ?></span>
        </div>
        <div class="Card_B">
          <span class="Card_K"><?= e($item['tagline']) ?></span>
          <h3 class="Card_T"><?= e($item['name']) ?></h3>
          <p class="Card_D"><?= e($item['summary']) ?></p>
          <a class="Card_L" href="<?= e(url($item['path'])) ?>">Read more <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
