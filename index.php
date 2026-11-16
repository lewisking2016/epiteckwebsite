<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$page = [
    'slug'        => 'home',
    'nav'         => 'home',
    'path'        => '/',
    'title'       => 'Aviation and air-force support in East Africa | EPiTEK Solutions',
    'description' => 'EPiTEK Solutions supports East African aviation and air forces: mission software and avionics integration, uncrewed systems, command integration, training and depot sustainment.',
    'heroImage'   => 'assets/img/hero-terrain.svg',
    'schema' => [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => SITE_URL . '/#organization',
                'name' => SITE_NAME,
                'url' => SITE_URL . '/',
                'logo' => SITE_URL . '/assets/img/epitek-logo.svg',
                'email' => SITE_EMAIL,
                'description' => 'Aviation and air-force engineering, integration, training and depot sustainment in East Africa.',
                'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
                'knowsAbout' => ['Aviation mission software', 'Avionics integration', 'Uncrewed aerial systems', 'Depot maintenance', 'Aviation training'],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'sales',
                    'email' => SITE_EMAIL,
                    'availableLanguage' => ['en'],
                    'areaServed' => ['KE', 'UG', 'TZ', 'RW', 'ET'],
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => SITE_URL . '/#website',
                'url' => SITE_URL . '/',
                'name' => SITE_NAME,
                'publisher' => ['@id' => SITE_URL . '/#organization'],
                'inLanguage' => 'en-GB',
            ],
        ],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="Hero" id="hero" aria-labelledby="Hero_T">
  <img class="Hero_Img" src="<?= e(url('assets/img/hero-terrain.svg')) ?>" alt="" width="1600" height="1100" fetchpriority="high" decoding="async">
  <span class="Hero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="Hero_In">
      <div class="Hero_Txt">
        <div class="Hero_Badge">
          <span class="Hero_Badge_Beacon" aria-hidden="true"></span>
          <span class="Hero_Badge_Text">AVIATION &amp; AIR-FORCE SUPPORT · EAST AFRICA</span>
        </div>

        <h1 class="Hero_H1" id="Hero_T">
          Aviation and air-force support, <span class="Hl">delivered in region</span>
        </h1>

        <p class="Hero_Lead">
          We keep East African fleets serviceable: mission software and avionics integration, uncrewed systems support, command integration, training and depot sustainment.
        </p>

        <div class="Hero_Act">
          <a class="Btn Btn--light Btn--lg Btn--go" href="<?= e(url('/capabilities')) ?>">
            Explore capabilities <span class="Btn_Ico" aria-hidden="true"><svg class="Ico Ico--sm"><use href="#i-arrow"/></svg></span>
          </a>
          <a class="Btn Btn--ghost-light Btn--lg" href="<?= e(url('/contact')) ?>">
            <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-shield"/></svg> Request a briefing
          </a>
        </div>

        <div class="Hero_Pills" aria-label="Capability summary">
          <span class="Hero_Pill">Mission software &amp; moving maps</span>
          <span class="Hero_Pill">Avionics integration</span>
          <span class="Hero_Pill">Uncrewed systems</span>
          <span class="Hero_Pill">Depot &amp; crew training</span>
        </div>
      </div>

      <div class="Hero_Viz">
        <div class="MediaCard">
          <span class="MediaTag">EAST AFRICA · EA-01</span>
          <img src="<?= e(url('assets/img/coverage-map.svg')) ?>" alt="Regional coverage map with hubs at Nairobi, Kampala, Dodoma, Kigali and Addis Ababa" width="900" height="760" decoding="async">
          <div class="MediaCard_Cap"><span><b>5 states</b> · coordinated from Nairobi</span><span>NOT TO SCALE</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="Section" id="capabilities" aria-labelledby="Domains_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Capabilities</span>
      <h2 id="Domains_T">Four areas. Four pages.</h2>
      <p class="Head_B">Each area is its own conversation, so each has its own page with the scope and the delivery model.</p>
    </div>

    <div class="Grid Grid--4">
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

<section class="Section Section--alt" aria-labelledby="Model_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">The operating model</span>
        <h2 id="Model_T">One programme office, from requirement to overhaul</h2>
        <p>An air force does not buy software, a sensor and a spare part in isolation. It buys availability — the aircraft serviceable on the day the tasking arrives, with the documentation to prove it.</p>
        <ul class="Split_List">
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Engineers and instructors based in the region, not flown in per project.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Integration into the fleet you already operate.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Acceptance tests, calibration records and training files retained by the receiving unit.</li>
        </ul>
        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/about')) ?>">About the company <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="Btn Btn--ghost" href="<?= e(url('/services')) ?>">Services</a>
        </div>
      </div>
      <div class="Split_Media">
        <div class="MediaCard">
          <span class="MediaTag">PROGRAMME OFFICE · NAIROBI</span>
          <img src="<?= e(url('assets/img/depot.svg')) ?>" alt="Maintenance depot schematic with calibration and traceability callouts" width="860" height="640" loading="lazy" decoding="async">
          <div class="MediaCard_Cap"><span><b>Single point of accountability</b></span><span>EAST AFRICA</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
