<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
http_response_code(404);

$page = [
    'slug'        => 'not-found',
    'nav'         => '',
    'path'        => '/404',
    'title'       => 'Page not found | EPiTEK Solutions',
    'description' => 'The requested page could not be found. Use the site navigation or contact the EPiTEK regional team.',
    'hideCta'     => true,
];
require __DIR__ . '/includes/header.php';
?>
<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/hero-terrain.svg')) ?>" alt="" width="1600" height="1100" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Not found</span>
      </nav>
      <span class="Eyebrow">404 · Sector not found</span>
      <h1>This route is not on the map</h1>
      <p class="PageHero_Lead">The page may have moved. Use the links below, or ask the regional team directly — we answer technical questions faster than a search engine.</p>
      <div class="PageHero_Act">
        <a class="Btn Btn--light" href="<?= e(url('/')) ?>">Return home <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="Btn Btn--ghost-light" href="<?= e(url('/contact')) ?>">Contact the team</a>
      </div>
    </div>
  </div>
</section>
<section class="Section">
  <div class="Wrap">
    <div class="Grid Grid--4">
      <a class="Card Card--flat" href="<?= e(url('/#domains')) ?>"><div class="Card_B"><span class="Card_K">Capabilities</span><h2 class="Card_T">Operational domains</h2></div></a>
      <a class="Card Card--flat" href="<?= e(url('/distributor-integration')) ?>"><div class="Card_B"><span class="Card_K">Company</span><h2 class="Card_T">Distributor hub</h2></div></a>
      <a class="Card Card--flat" href="<?= e(url('/services')) ?>"><div class="Card_B"><span class="Card_K">Support</span><h2 class="Card_T">Training &amp; services</h2></div></a>
      <a class="Card Card--flat" href="<?= e(url('/contact')) ?>"><div class="Card_B"><span class="Card_K">Enquiry</span><h2 class="Card_T">Contact</h2></div></a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
