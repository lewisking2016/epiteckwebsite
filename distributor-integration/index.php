<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'distributor-integration',
    'nav'         => 'distributor-integration',
    'path'        => '/distributor-integration',
    'title'       => 'Distributor & Systems Integrator Hub | EPiTEK Solutions',
    'description' => 'Regional distribution logistics, systems integration architecture and depot-level sustainment for defence technology across Kenya, Uganda, Tanzania, Rwanda and Ethiopia.',
    'heroImage'   => 'assets/img/c4isr.svg',
    'filterDomains' => true,
    'schema' => [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Regional distribution and systems integration',
        'provider' => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL . '/'],
        'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
        'serviceType' => 'Defence distribution, systems integration and depot sustainment',
        'description' => 'End-user monitoring, secure military transit, customs brokerage, sensor and C2 integration, and regional depot maintenance.',
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
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Distributor &amp; integrator</span>
      </nav>
      <span class="Eyebrow">Distribution · integration · sustainment</span>
      <h1>From cleared border to commissioned system</h1>
      <p class="PageHero_Lead">The unglamorous work that decides whether a capability is available on the day it is needed: end-user monitoring, secure transit, customs clearance, integration into sovereign infrastructure, and a depot that answers the phone.</p>
      <div class="PageHero_Act">
        <a class="Btn Btn--light" href="#integration">Integration architecture <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="Btn Btn--ghost-light" href="<?= e(url('/contact?topic=sla')) ?>">Initiate an SLA consultation</a>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Mandate_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body Reveal">
        <span class="Eyebrow">The mandate</span>
        <h2 id="Mandate_T">A regional integrator, accountable end to end</h2>
        <p>Importing a system is the simple part. Making it work inside an existing air force, navy or border service is where programmes fail: interfaces do not match, documentation is incomplete, calibration capability is missing, and the first failure waits weeks for a part.</p>
        <p>EPiTEK is structured to close those gaps in-region — holding the procurement relationship, the integration engineering and the sustainment obligation under one programme office.</p>
        <ul class="Split_List">
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> A named programme manager and technical lead per engagement.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Documentation set delivered in a controlled, audit-ready structure.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Escalation to the original equipment manufacturer when engineering limits are reached.</li>
        </ul>
      </div>
      <div class="Split_Media Reveal">
        <div class="MediaCard">
          <span class="MediaTag">PROGRAMME OFFICE · NAIROBI</span>
          <img src="<?= e(url('assets/img/depot.svg')) ?>" alt="Maintenance depot schematic with calibration bay, battery management and traceability callouts" width="860" height="640" loading="lazy" decoding="async">
          <div class="MediaCard_Cap"><span><b>Single point of accountability</b></span><span>EPX-HUB-01</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="Section Section--alt" aria-labelledby="Logistics_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Regional distribution logistics</span>
      <h2 id="Logistics_T">Four controls between the factory and the flight line</h2>
      <p class="Head_B">Each shipment runs through a documented chain so that the end user, the border authority and the receiving unit all hold the same record.</p>
    </div>
    <div class="Steps">
      <div class="Step Reveal">
        <span class="Step_N">STEP 01</span>
        <h3 class="Step_T">End-user monitoring</h3>
        <p class="Step_D">Eligibility, end-user statements and non-transfer undertakings verified before release of goods or technical data.</p>
      </div>
      <div class="Step Reveal">
        <span class="Step_N">STEP 02</span>
        <h3 class="Step_T">Secure military transit</h3>
        <p class="Step_D">Cleared carriers, handling protocols and chain-of-custody documentation for sensitive or controlled cargo.</p>
      </div>
      <div class="Step Reveal">
        <span class="Step_N">STEP 03</span>
        <h3 class="Step_T">Customs brokerage</h3>
        <p class="Step_D">Tariff classification, permits and duty handling coordinated with appointed clearing agents.</p>
      </div>
      <div class="Step Reveal">
        <span class="Step_N">STEP 04</span>
        <h3 class="Step_T">EAC clearance</h3>
        <p class="Step_D">Cross-border documentation aligned across East African Community procedures and receiving-authority acceptance.</p>
      </div>
    </div>
    <div class="Callout Callout--spaced">
      <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-shield"/></svg></span>
      <div>
        <h3 class="Callout_T">Export control is a gate, not a formality</h3>
        <p class="Callout_D">Dual-use and military items are screened against the applicable national and supplier-country regimes. Where a licence or authorisation is required, the programme timeline includes it explicitly rather than absorbing it silently.</p>
      </div>
    </div>
  </div>
</section>

<section class="Section" id="integration" aria-labelledby="Integration_T">
  <div class="Wrap">
    <div class="Head Head--split">
      <div>
        <span class="Eyebrow">System integration architecture</span>
        <h2 id="Integration_T">Integration into what you already operate</h2>
        <p class="Head_B">Imported hardware is only useful when it speaks to the sensors, radios and command posts already in service. Three integration methods carry most programmes.</p>
      </div>
      <div class="Head_A">
        <a class="Btn Btn--ghost" href="<?= e(url('/contact?topic=c4i')) ?>">Request integration review</a>
      </div>
    </div>

    <div class="Grid Grid--3">
      <article class="Card Reveal" data-domain-item data-domains="cyber aviation">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/c4isr.svg')) ?>" alt="Network diagram of radar, command, UAS and naval nodes" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Method 01</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">Sensors</span>
          <h3 class="Card_T">Radar and airspace nodes</h3>
          <p class="Card_D">Sensor integration with regional air traffic and military radar nodes, including track formatting, latency testing and fallback behaviour.</p>
          <a class="Card_L" href="<?= e(url('/contact?topic=c4i')) ?>">Discuss a sensor interface <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
      <article class="Card Reveal" data-domain-item data-domains="cyber air">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/uas-kargu.svg')) ?>" alt="UAS schematic with datalink and navigation annotations" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Method 02</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">Communications</span>
          <h3 class="Card_T">SDR and encrypted links</h3>
          <p class="Card_D">Software-defined radio configuration, key management and encrypted communication links, tested for degraded and contested conditions.</p>
          <a class="Card_L" href="<?= e(url('/contact?topic=c4i')) ?>">Discuss a link design <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
      <article class="Card Reveal" data-domain-item data-domains="cyber">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/avionics.svg')) ?>" alt="Command display with route, track and telemetry overlays" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Method 03</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">Command</span>
          <h3 class="Card_T">Command post telemetry</h3>
          <p class="Card_D">Real-time tactical observation feeds integrated into command posts, with recording, replay and operator training on the integrated picture.</p>
          <a class="Card_L" href="<?= e(url('/contact?topic=c4i')) ?>">Scope a command post <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
    </div>

    <div class="Callout Callout--spaced">
      <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-doc"/></svg></span>
      <div>
        <h3 class="Callout_T">Interface control documentation included</h3>
        <p class="Callout_D">Every integration delivers an interface control document, test evidence and a rollback procedure, so the capability can be maintained by more than the team that installed it.</p>
      </div>
    </div>
  </div>
</section>

<section class="Section Section--alt" aria-labelledby="Depot_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body Reveal">
        <span class="Eyebrow">Lifecycle sustainment</span>
        <h2 id="Depot_T">The Regional Maintenance Depot</h2>
        <p>Depot capability is the difference between a fleet and a procurement line item. The regional depot is designed around four functions that determine availability.</p>
        <ul class="Split_List">
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> <span><strong>Spare parts warehousing</strong> — controlled stock levels with consumption tracking and replenishment triggers.</span></li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> <span><strong>Battery management</strong> — cycle counting, storage conditions and safe disposal for tactical UAS batteries.</span></li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> <span><strong>Scheduled overhauls</strong> — mechanical inspection intervals and calibration schedules by platform.</span></li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> <span><strong>Mobile engineering teams</strong> — field deployment when the platform cannot travel to the depot.</span></li>
        </ul>
        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/services#support-desk')) ?>">Support tiers &amp; SLAs <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </div>
      <div class="Reveal">
        <div class="SupportCard">
          <div class="SupportCard_H"><span class="Dot" aria-hidden="true"></span> Depot readiness</div>
          <h3 class="SupportCard_T">Four capabilities, one repair loop.</h3>
          <div class="SLASteps">
            <div class="SLAStep"><span class="SLAStep_N">01</span><span class="SLAStep_T">Receive &amp; diagnose<small>Serialised intake and fault isolation</small></span><span class="SLAStep_V">Day 0</span></div>
            <div class="SLAStep"><span class="SLAStep_N">02</span><span class="SLAStep_T">Repair or replace<small>LRU swap, structural repair, calibration</small></span><span class="SLAStep_V">In depot</span></div>
            <div class="SLAStep"><span class="SLAStep_N">03</span><span class="SLAStep_T">Test &amp; release<small>Function test, documentation, return to service</small></span><span class="SLAStep_V">Recorded</span></div>
            <div class="SLAStep"><span class="SLAStep_N">04</span><span class="SLAStep_T">Feedback to OEM<small>Failure trend and configuration reporting</small></span><span class="SLAStep_V">Quarterly</span></div>
          </div>
          <p class="Note">Depot scope and turnaround commitments are defined per platform and stockholding agreement.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Quality_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Compliance, verification and quality</span>
      <h2 id="Quality_T">Quality control that survives an audit</h2>
      <p class="Head_B">Processes are mapped to ISO 9001 and AS9100 practice. Where a certificate is held, the certificate number is supplied. Where alignment is claimed, the evidence is the process record.</p>
    </div>
    <div class="Grid Grid--3">
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Quality</span>
          <h3 class="Card_T">Incoming and outgoing inspection</h3>
          <p class="Card_D">Acceptance checks on receipt, controlled storage, and a release inspection before any platform returns to service.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Traceability</span>
          <h3 class="Card_T">Serialised records</h3>
          <p class="Card_D">Component serials, firmware versions, calibration dates and technician sign-off held against each asset.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Avionics</span>
          <h3 class="Card_T">Export-control tracking</h3>
          <p class="Card_D">Controlled technical data access, handling rules and end-use tracking for military avionics and dual-use items.</p>
        </div>
      </article>
    </div>
    <div class="Callout Callout--spaced">
      <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-shield"/></svg></span>
      <div>
        <h3 class="Callout_T">Certification claims</h3>
        <p class="Callout_D">EPiTEK does not publish certification claims it cannot evidence. Certificates, authorisations and partner letters are made available to authorised procurement teams during due diligence.</p>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
