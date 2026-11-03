<?php
declare(strict_types=1);

if (!isset($page) || !is_array($page)) {
    $page = [
        'slug'        => 'home',
        'title'       => SITE_NAME . ' — ' . SITE_TAGLINE,
        'description' => 'Regional defence, aerospace and mission-technology integration across East Africa.',
        'path'        => '/',
    ];
}
$nonce = csp_nonce();
security_headers($nonce);
$navSlug = $page['nav'] ?? $page['slug'];
$filterDomains = !empty($page['filterDomains']);
$shareImage = url('assets/img/og-image.png');
?>
<!DOCTYPE html>
<html lang="en-GB" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($page['title']) ?></title>
<meta name="description" content="<?= e($page['description']) ?>">
<link rel="canonical" href="<?= e(canonical($page['path'] ?? '/')) ?>">
<meta name="theme-color" content="#12294A">
<meta name="color-scheme" content="light">
<meta name="format-detection" content="telephone=no">

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($page['title']) ?>">
<meta property="og:description" content="<?= e($page['description']) ?>">
<meta property="og:url" content="<?= e(canonical($page['path'] ?? '/')) ?>">
<meta property="og:image" content="<?= e($shareImage) ?>">
<meta property="og:locale" content="en_GB">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($page['title']) ?>">
<meta name="twitter:description" content="<?= e($page['description']) ?>">
<meta name="twitter:image" content="<?= e($shareImage) ?>">

<link rel="icon" href="<?= e(asset('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?= e(asset('assets/img/favicon.svg')) ?>">
<link rel="sitemap" type="application/xml" href="<?= e(url('sitemap.xml')) ?>">
<link rel="preload" href="<?= e(url('assets/fonts/bricolage-grotesque-650.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= e(url('assets/fonts/source-sans-3-400.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<?php if (!empty($page['heroImage'])): ?>
<link rel="preload" href="<?= e(url($page['heroImage'])) ?>" as="image" type="image/svg+xml">
<?php endif; ?>
<link rel="stylesheet" href="<?= e(asset('assets/css/fonts.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('assets/css/main.css')) ?>">
<script nonce="<?= e($nonce) ?>">document.documentElement.classList.replace('no-js','js');</script>
<?php if (!empty($page['schema'])): ?>
<script type="application/ld+json" nonce="<?= e($nonce) ?>"><?= json_encode($page['schema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<?php endif; ?>
</head>
<body id="<?= e(body_id($page['slug'])) ?>" class="Page <?= e($page['bodyClass'] ?? '') ?>">
<a class="Skip" href="#main">Skip to content</a>

<svg class="Sprite" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
  <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M4 12h15M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
  <symbol id="i-chev" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></symbol>
  <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h10" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></symbol>
  <symbol id="i-close" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></symbol>
  <symbol id="i-mail" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3.6 7l8.4 6 8.4-6"/></g></symbol>
  <symbol id="i-phone" viewBox="0 0 24 24"><path d="M6.2 3.5h3l1.4 4.2-2 1.4a12 12 0 006.3 6.3l1.4-2 4.2 1.4v3a2 2 0 01-2.2 2A17.3 17.3 0 014.2 5.7a2 2 0 012-2.2z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></symbol>
  <symbol id="i-linkedin" viewBox="0 0 24 24"><path d="M4.6 8.8h3.1V20H4.6zM6.15 4a1.8 1.8 0 110 3.6 1.8 1.8 0 010-3.6zM10.4 8.8h3v1.55c.5-.9 1.7-1.75 3.3-1.75 2.9 0 3.9 1.8 3.9 4.6V20h-3.1v-5.6c0-1.5-.55-2.4-1.85-2.4-1.1 0-1.75.75-2.05 1.5-.1.25-.1.6-.1.95V20h-3.1z" fill="currentColor"/></symbol>
  <symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 3l7 2.8v5.5c0 4.3-2.9 7.7-7 9.7-4.1-2-7-5.4-7-9.7V5.8z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M8.6 12.2l2.3 2.3 4.5-4.7" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></symbol>
  <symbol id="i-pin" viewBox="0 0 24 24"><path d="M12 21s7-6.1 7-11a7 7 0 10-14 0c0 4.9 7 11 7 11z" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="10" r="2.5" fill="none" stroke="currentColor" stroke-width="1.7"/></symbol>
  <symbol id="i-globe" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 2.4 3.9 5.4 3.9 9s-1.3 6.6-3.9 9c-2.6-2.4-3.9-5.4-3.9-9S9.4 5.4 12 3z"/></g></symbol>
  <symbol id="i-doc" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4M9 12h6M9 16h6"/></g></symbol>
  <symbol id="i-check" viewBox="0 0 24 24"><path d="M4 12.5l5 5L20 6.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>

<header class="Top" id="top">
  <div class="Wrap Top_Bar">
    <a class="Logo" href="<?= e(url('/')) ?>" aria-label="<?= e(SITE_NAME) ?> — home">
      <img src="<?= e(asset('assets/img/epitek-logo.svg')) ?>" alt="<?= e(SITE_NAME) ?>" width="186" height="30" decoding="async">
    </a>

    <nav class="Nav" id="Nav_Main" aria-label="Main">
      <ul class="Nav_Items">
        <li class="Nav_Item"><a class="Nav_A<?= nav_active('home', $navSlug) ?>" href="<?= e(url('/')) ?>"<?= is_current('home', $navSlug) ?>>Home</a></li>
        <li class="Nav_Item Nav_Item--has">
          <button class="Nav_A Nav_Btn" type="button" aria-expanded="false" aria-controls="Nav_Cap">
            Capabilities <svg class="Ico Ico--sm Nav_Car" aria-hidden="true"><use href="#i-chev"/></svg>
          </button>
          <div class="Nav_Panel" id="Nav_Cap" hidden>
            <p class="Nav_Ph">Operational domains</p>
            <a class="Nav_Sub" href="<?= e(url('/#domain-air')) ?>"><strong>Air &amp; autonomous systems</strong><span>Tactical UAS, loitering munitions, ISR</span></a>
            <a class="Nav_Sub" href="<?= e(url('/#domain-aviation')) ?>"><strong>Aviation software</strong><span>Mission planning, moving maps, C2</span></a>
            <a class="Nav_Sub" href="<?= e(url('/#domain-cyber')) ?>"><strong>Cyber &amp; C4ISR</strong><span>Sensor-to-shooter, coastal surveillance</span></a>
            <a class="Nav_Sub" href="<?= e(url('/#domain-support')) ?>"><strong>Sustainment</strong><span>Depot MRO, spares, field teams</span></a>
            <a class="Nav_All" href="<?= e(url('/#portfolio')) ?>">View the technology portfolio <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          </div>
        </li>
        <li class="Nav_Item"><a class="Nav_A<?= nav_active('distributor-integration', $navSlug) ?>" href="<?= e(url('/distributor-integration')) ?>"<?= is_current('distributor-integration', $navSlug) ?>>Distributor hub</a></li>
        <li class="Nav_Item"><a class="Nav_A<?= nav_active('partnerships-stm', $navSlug) ?>" href="<?= e(url('/partnerships-stm')) ?>"<?= is_current('partnerships-stm', $navSlug) ?>>Partnership</a></li>
        <li class="Nav_Item"><a class="Nav_A<?= nav_active('services', $navSlug) ?>" href="<?= e(url('/services')) ?>"<?= is_current('services', $navSlug) ?>>Services</a></li>
      </ul>
    </nav>

    <div class="Top_Actions">
      <a class="Top_Soc" href="<?= e(SITE_LINKEDIN) ?>" rel="noopener" target="_blank" aria-label="EPiTEK Solutions on LinkedIn">
        <svg class="Ico" aria-hidden="true"><use href="#i-linkedin"/></svg>
      </a>
      <a class="Btn Btn--primary Btn--sm Top_Cta" href="<?= e(url('/contact')) ?>">Contact</a>
      <button class="Burger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="Drawer" data-menu-open>
        <svg class="Ico" aria-hidden="true"><use href="#i-menu"/></svg>
      </button>
    </div>
  </div>

  <?php include __DIR__ . '/domain-bar.php'; ?>
</header>

<div class="Drawer" id="Drawer" hidden>
  <div class="Drawer_Hd">
    <span class="Drawer_T">Menu</span>
    <button class="Drawer_X" type="button" aria-label="Close menu" data-menu-close>
      <svg class="Ico" aria-hidden="true"><use href="#i-close"/></svg>
    </button>
  </div>
  <nav class="Drawer_Nav" aria-label="Mobile">
    <a href="<?= e(url('/')) ?>">Home <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
    <a href="<?= e(url('/distributor-integration')) ?>">Distributor &amp; integrator hub <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
    <a href="<?= e(url('/partnerships-stm')) ?>">Partnership &amp; representation <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
    <a href="<?= e(url('/services')) ?>">Training, services &amp; support <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
    <a href="<?= e(url('/contact')) ?>">Contact &amp; demo request <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
  </nav>
  <div class="Drawer_Domains">
    <p class="Drawer_L">Operational domains</p>
    <?php foreach ($DOMAINS as $key => $domain): ?>
      <a href="<?= e(url('/#domain-' . $key)) ?>"><?= e($domain['label']) ?></a>
    <?php endforeach; ?>
  </div>
  <div class="Drawer_Meta">
    <a href="mailto:<?= e(SITE_EMAIL) ?>"><svg class="Ico" aria-hidden="true"><use href="#i-mail"/></svg><?= e(SITE_EMAIL) ?></a>
    <p><svg class="Ico" aria-hidden="true"><use href="#i-pin"/></svg>Regional headquarters · Nairobi, Kenya</p>
    <a class="Btn Btn--primary" href="<?= e(url('/contact')) ?>">Request a technical demo <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
  </div>
</div>
<div class="Scrim" data-scrim hidden></div>

<main id="main">
