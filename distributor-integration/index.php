<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'distributor-integration',
    'nav'         => 'capabilities',
    'path'        => '/distributor-integration',
    'title'       => 'Logistics and integration | EPiTEK Solutions',
    'description' => 'How equipment reaches service in East Africa: end-user screening, transit, customs clearance and integration into existing sensors and command posts.',
    'heroImage'   => 'assets/img/c4isr.svg',
    'schema' => [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Regional logistics and systems integration',
        'provider' => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL . '/'],
        'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
        'url' => SITE_URL . '/distributor-integration',
    ],
];
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/c4isr.svg')) ?>" alt="" width="860" height="640" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Logistics &amp; integration</span>
      </nav>
      <span class="Eyebrow">Logistics &amp; integration</span>
      <h1>From clearance to commissioned system</h1>
      <p class="PageHero_Lead">Getting equipment into the country is the easy part. Making it work with the sensors, radios and command posts already in service is where programmes usually stall.</p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Steps_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">The chain</span>
      <h2 id="Steps_T">Four controls between order and handover</h2>
      <p class="Head_B">Each step produces a document. The end user, the border authority and the receiving unit all hold the same record.</p>
    </div>
    <div class="Steps">
      <div class="Step">
        <span class="Step_N">STEP 01</span>
        <h3 class="Step_T">End-user screening</h3>
        <p class="Step_D">Eligibility, end-user statements and non-transfer undertakings checked before goods or technical data are released.</p>
      </div>
      <div class="Step">
        <span class="Step_N">STEP 02</span>
        <h3 class="Step_T">Secure transit</h3>
        <p class="Step_D">Cleared carriers, handling protocols and chain-of-custody records for controlled cargo.</p>
      </div>
      <div class="Step">
        <span class="Step_N">STEP 03</span>
        <h3 class="Step_T">Customs clearance</h3>
        <p class="Step_D">Tariff classification, permits and duty handling with appointed clearing agents.</p>
      </div>
      <div class="Step">
        <span class="Step_N">STEP 04</span>
        <h3 class="Step_T">Acceptance</h3>
        <p class="Step_D">Function test against the agreed specification, then handover of the documentation set.</p>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Int_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Integration</span>
      <h2 id="Int_T">Integration into what you already run</h2>
      <p class="Head_B">Three interfaces carry most of the work. Each ends with an interface control document and a rollback procedure, so the system can be maintained by someone else.</p>
    </div>
    <div class="Grid Grid--3">
      <article class="Card Card--flat">
        <div class="Card_B">
          <span class="Card_K">Sensors</span>
          <h3 class="Card_T">Radar and airspace nodes</h3>
          <p class="Card_D">Track formatting, latency testing and defined fallback behaviour when a node drops out.</p>
        </div>
      </article>
      <article class="Card Card--flat">
        <div class="Card_B">
          <span class="Card_K">Communications</span>
          <h3 class="Card_T">Radio and data links</h3>
          <p class="Card_D">Link configuration, key management and testing under degraded conditions.</p>
        </div>
      </article>
      <article class="Card Card--flat">
        <div class="Card_B">
          <span class="Card_K">Command</span>
          <h3 class="Card_T">Command post displays</h3>
          <p class="Card_D">Live feeds into existing posts, with recording, replay and operator handover training.</p>
        </div>
      </article>
    </div>
    <div class="Callout Callout--spaced">
      <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-shield"/></svg></span>
      <div>
        <h3 class="Callout_T">Export control is a gate, not a formality</h3>
        <p class="Callout_D">Dual-use and military items are screened against the applicable national and supplier-country regimes. Where a licence is needed, it appears in the timeline instead of emerging at the border.</p>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
