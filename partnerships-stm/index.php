<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'partnerships-stm',
    'nav'         => 'partnerships-stm',
    'path'        => '/partnerships-stm',
    'title'       => 'STM Partnership & Representation in East Africa | EPiTEK',
    'description' => 'EPiTEK Solutions represents STM defence technology across East Africa: tactical UAS, FocusFlite mission software, naval systems and C4ISR, with regional training and lifecycle support.',
    'heroImage'   => 'assets/img/uas-kargu.svg',
    'filterDomains' => true,
    'schema' => [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'STM technology portfolio represented by EPiTEK in East Africa',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'KARGU tactical loitering munition'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'TOGAN reconnaissance UAS'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => 'BOYGA precision payload UAS'],
            ['@type' => 'ListItem', 'position' => 4, 'name' => 'ALPAGU-B fixed-wing loitering munition'],
            ['@type' => 'ListItem', 'position' => 5, 'name' => 'FocusFlite mission planning software'],
            ['@type' => 'ListItem', 'position' => 6, 'name' => 'Naval and C4ISR integration'],
        ],
    ],
];

require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/uas-kargu.svg')) ?>" alt="" width="860" height="640" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Partnership</span>
      </nav>
      <span class="Eyebrow">Partnership &amp; representation</span>
      <h1>STM technology, delivered with East African engineering</h1>
      <p class="PageHero_Lead">EPiTEK is the regional integration and technical support vehicle for the STM portfolio across East Africa: autonomous systems, mission software, naval engineering and command integration.</p>
      <div class="PageHero_Act">
        <a class="Btn Btn--light" href="<?= e(url('/contact?topic=uas')) ?>">Request a tactical data sheet <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="Btn Btn--ghost-light" href="#portfolio">See the portfolio</a>
      </div>
      <div class="PageHero_Meta">
        <span><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Documentation on request</span>
        <span><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Export-control screened</span>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Mandate_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body Reveal">
        <span class="Eyebrow">Representation mandate</span>
        <h2 id="Mandate_T">A technical relationship, not a reseller listing</h2>
        <p>The value of a regional partnership is not access to a catalogue. It is the ability to escalate a configuration question to the manufacturer's engineering team, adapt a payload for local atmospheric conditions, and keep a trained instructor cadre in the region.</p>
        <p>EPiTEK's role is to carry that technical relationship into East African programmes: requirement translation, demonstration planning, integration engineering, operator training and lifecycle support.</p>
        <ul class="Split_List">
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Manufacturer escalation path for engineering and configuration questions.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Local adaptation work for East African operating environments.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Regional instructor cadre and training records.</li>
        </ul>
      </div>
      <div class="Reveal">
        <div class="Callout">
          <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-doc"/></svg></span>
          <div>
            <h3 class="Callout_T">Authorisation statement</h3>
            <p class="Callout_D">Statements of authorised representation, distributor status or territory are supported by written documentation. Procurement teams should request the current authorisation letter and confirm the exact legal entity, territory and product scope before contract award. This page is not itself a legal authorisation instrument.</p>
          </div>
        </div>
        <div class="Callout Callout--tight">
          <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-shield"/></svg></span>
          <div>
            <h3 class="Callout_T">Publication review</h3>
            <p class="Callout_D">Publicly available capability descriptions exclude controlled technical data. Offensive-capability content is reviewed against export-control and licensing rules before publication or release.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="Section Section--alt" id="portfolio" aria-labelledby="Portfolio_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Technology portfolio</span>
      <h2 id="Portfolio_T">Platforms and software in the STM portfolio</h2>
      <p class="Head_B">Grouped by operational domain. Data sheets state release conditions; detailed specifications are shared after eligibility screening.</p>
    </div>
    <div class="Table_Scroll Reveal">
      <table class="Table">
        <caption class="Table_Note">Applications describe operational roles, not confirmed configurations for a specific national programme.</caption>
        <thead>
          <tr><th scope="col">Domain</th><th scope="col">Systems &amp; platforms</th><th scope="col">Primary operational applications</th></tr>
        </thead>
        <tbody>
          <tr>
            <td>Tactical autonomous systems</td>
            <td>KARGU · ALPAGU-B · TOGAN · BOYGA</td>
            <td>Loitering strike munitions, GNSS-denied reconnaissance, precision mortar payload delivery, asymmetric perimeter surveillance</td>
          </tr>
          <tr>
            <td>Aviation &amp; mission software</td>
            <td>FocusFlite · C4ISR</td>
            <td>Digital moving map, situational awareness, mission route planning, cockpit situational feeds</td>
          </tr>
          <tr>
            <td>Command, cyber &amp; naval</td>
            <td>Naval platform engineering · coastal surveillance</td>
            <td>Coastal surveillance, corvette mission-system integration, sovereign decision-support, hardened infrastructure cyber protection</td>
          </tr>
          <tr>
            <td>Integrated support</td>
            <td>Regional maintenance depot · training academy</td>
            <td>Depot MRO, spares, battery management, field engineering, operator and maintainer training</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="Grid Grid--2 Grid--spaced">
      <article class="Card Reveal" data-domain-item data-domains="aviation">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/avionics.svg')) ?>" alt="Mission planning display showing a moving map, active route and aircraft telemetry" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Aviation software</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">FocusFlite</span>
          <h3 class="Card_T">Modernise the cockpit, keep the airframe</h3>
          <p class="Card_D">A modular mission-software layer for rotary and fixed-wing platforms: digital moving map, route planning, cockpit situational awareness and recorded mission data. It lowers the capital cost of fleet modernisation by deferring airframe replacement.</p>
          <a class="Card_L" href="<?= e(url('/contact?topic=focusflite')) ?>">Book a software architecture briefing <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
      <article class="Card Reveal" data-domain-item data-domains="air">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/uas-kargu.svg')) ?>" alt="Tactical quad-rotor UAS schematic with EO/IR payload and GNSS-denied navigation annotations" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Autonomous systems</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">KARGU · TOGAN · BOYGA · ALPAGU-B</span>
          <h3 class="Card_T">Autonomy that holds when navigation fails</h3>
          <p class="Card_D">Onboard computer vision and edge processing keep orientation, support target acquisition and return the platform to base when satellite navigation is jammed or degraded — relevant to border patrol and mountainous terrain.</p>
          <a class="Card_L" href="<?= e(url('/contact?topic=uas')) ?>">Request the UAS capability brief <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Joint_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Joint operational execution</span>
      <h2 id="Joint_T">Where the regional team adds engineering value</h2>
      <p class="Head_B">Manufacturer capability is the starting point. Local execution is what turns it into an operational outcome.</p>
    </div>
    <div class="Grid Grid--3">
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Demonstrations</span>
          <h3 class="Card_T">Live firing and field demonstrations</h3>
          <p class="Card_D">Planning, range coordination, safety case and observer documentation for in-country demonstrations, delivered with the manufacturer's technical team.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Localisation</span>
          <h3 class="Card_T">Payload and environment adaptation</h3>
          <p class="Card_D">Configuration work for East African atmospheric profiles, altitude, dust and temperature, validated before handover rather than after.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">People</span>
          <h3 class="Card_T">Sovereign operator training</h3>
          <p class="Card_D">Competency-based training for operators, maintainers and instructors, with records retained by the receiving authority.</p>
        </div>
      </article>
    </div>
    <div class="Callout Callout--spaced">
      <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-shield"/></svg></span>
      <div>
        <h3 class="Callout_T">Responsible publication</h3>
        <p class="Callout_D">This page intentionally omits performance figures, seeker specifications and employment detail. Those are released only to eligible recipients under the applicable export-control framework.</p>
      </div>
    </div>
  </div>
</section>

<section class="Section Section--alt" aria-labelledby="Scope_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Territory &amp; scope</span>
      <h2 id="Scope_T">East African coverage</h2>
      <p class="Head_B">Regional coordination from Nairobi, with engineering and training reach across the five states below. Territory and product scope are confirmed in the authorisation documentation for each programme.</p>
    </div>
    <div class="Countries">
      <?php foreach ($COUNTRIES as $country): ?>
        <article class="Country Reveal">
          <span class="Country_C" aria-hidden="true"><?= e($country['code']) ?></span>
          <h3 class="Country_N"><?= e($country['name']) ?></h3>
          <p class="Country_R"><?= e($country['role']) ?></p>
          <span class="Country_Note"><?= e($country['note']) ?></span>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
