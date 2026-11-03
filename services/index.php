<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'services',
    'nav'         => 'services',
    'path'        => '/services',
    'title'       => 'Training, Services & 24/7 Support | EPiTEK Solutions',
    'description' => 'Tactical UAS pilot certification, avionics maintenance, ICAO and EUROCONTROL compliance training, a 24/7 support desk and defence advisory across East Africa.',
    'heroImage'   => 'assets/img/depot.svg',
    'filterDomains' => true,
    'schema' => [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'ItemList',
                'name' => 'Training and support services',
                'itemListElement' => array_map(static function (array $svc, int $i): array {
                    return ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $svc['title']];
                }, $SERVICES, array_keys($SERVICES)),
            ],
            [
                '@type' => 'GovernmentService',
                'name' => 'Defence technical advisory',
                'serviceOperator' => ['@type' => 'Organization', 'name' => SITE_NAME],
                'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
                'description' => 'Needs analysis for ministries and civil authorities covering border security, anti-poaching and maritime domain awareness.',
            ],
        ],
    ],
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
      <span class="Eyebrow">Training, services &amp; support</span>
      <h1>Capability is a team, not a crate</h1>
      <p class="PageHero_Lead">Five services that keep a fleet, a sensor network or an airspace function operating: certification training, avionics maintenance, compliance modules, a staffed support desk and sovereign advisory.</p>
      <div class="PageHero_Act">
        <a class="Btn Btn--light" href="<?= e(url('/contact?topic=sla')) ?>">Initiate an SLA consultation <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="Btn Btn--ghost-light" href="#support-desk">Support tiers</a>
      </div>
      <div class="PageHero_Meta">
        <span><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Competency records retained</span>
        <span><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Training at depot or on site</span>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Services_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Service catalogue</span>
      <h2 id="Services_T">Five services, one support philosophy</h2>
      <p class="Head_B">Every service is scoped in writing, delivered by named personnel, and closed with a record the receiving organisation keeps. Filter using the domain bar above.</p>
    </div>
    <div class="SvcList">
      <?php foreach ($SERVICES as $i => $svc): ?>
        <article class="SvcRow Anchor Reveal" id="service-<?= e($svc['slug']) ?>" data-domain-item data-domains="<?= e(implode(' ', $svc['domains'])) ?>">
          <span class="SvcRow_N"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <div>
            <h3 class="SvcRow_T"><?= e($svc['title']) ?></h3>
            <p class="SvcRow_D"><?= e($svc['desc']) ?></p>
          </div>
          <a class="SvcRow_Ico" href="<?= e(url('/contact?topic=training')) ?>" aria-label="Enquire about <?= e($svc['title']) ?>">
            <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="FilterEmpty" data-filter-empty hidden>No service is published for this domain yet. <a href="<?= e(url('/contact')) ?>">Ask the team</a>.</p>
  </div>
</section>

<section class="Section Section--alt" id="support-desk" aria-labelledby="Support_T">
  <div class="Wrap">
    <div class="Head Head--split">
      <div>
        <span class="Eyebrow">24/7 technical support desk</span>
        <h2 id="Support_T">A response window you can plan around</h2>
        <p class="Head_B">Support is tiered by operational impact, not by how loudly a request is made. Each tier has a defined first response, escalation owner and reporting line.</p>
      </div>
      <div class="Head_A">
        <a class="Btn Btn--primary" href="<?= e(url('/contact?topic=sla')) ?>">Initiate an SLA consultation</a>
      </div>
    </div>

    <div class="Split">
      <div class="Reveal">
        <div class="Table_Scroll">
          <table class="Table">
            <caption class="Table_Note">Response targets are service objectives for scoped support agreements. Final terms are agreed per platform and deployment.</caption>
            <thead>
              <tr><th scope="col">Tier</th><th scope="col">Trigger</th><th scope="col">First response</th><th scope="col">Escalation</th></tr>
            </thead>
            <tbody>
              <tr>
                <td>Tier 1</td>
                <td>Critical mission platform failure; asset unserviceable</td>
                <td><span class="Pill Pill--ok">Under 2 hours target</span></td>
                <td>Duty engineer + programme manager</td>
              </tr>
              <tr>
                <td>Tier 2</td>
                <td>Degraded capability; redundancy or sensor fault</td>
                <td>Same working day</td>
                <td>Support engineer + OEM liaison</td>
              </tr>
              <tr>
                <td>Tier 3</td>
                <td>Routine maintenance, documentation, calibration</td>
                <td>Within 5 working days</td>
                <td>Depot planner</td>
              </tr>
              <tr>
                <td>Advisory</td>
                <td>Configuration, planning, obsolescence questions</td>
                <td>Next scheduled review</td>
                <td>Technical account lead</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="Reveal">
        <div class="SupportCard">
          <div class="SupportCard_H"><span class="Dot" aria-hidden="true"></span> What the desk actually does</div>
          <h3 class="SupportCard_T">Diagnose, dispatch, document.</h3>
          <div class="SLASteps">
            <div class="SLAStep"><span class="SLAStep_N">01</span><span class="SLAStep_T">Remote diagnosis<small>Configuration, logs, firmware and fault isolation</small></span><span class="SLAStep_V">First call</span></div>
            <div class="SLAStep"><span class="SLAStep_N">02</span><span class="SLAStep_T">Spares decision<small>Stock check, dispatch or repair routing</small></span><span class="SLAStep_V">Same day</span></div>
            <div class="SLAStep"><span class="SLAStep_N">03</span><span class="SLAStep_T">Field dispatch<small>Mobile engineering team when the asset cannot move</small></span><span class="SLAStep_V">Planned</span></div>
            <div class="SLAStep"><span class="SLAStep_N">04</span><span class="SLAStep_T">Monthly reporting<small>Failure trends, downtime and consumed spares</small></span><span class="SLAStep_V">Monthly</span></div>
          </div>
          <a class="Btn Btn--ghost" href="<?= e(url('/contact?topic=sla')) ?>">Scope a support agreement <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Training_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body Reveal">
        <span class="Eyebrow">Training pathway</span>
        <h2 id="Training_T">From fundamentals to instructor</h2>
        <p>Training is built as a progression, not a single course. Four role-based tracks take a cohort from standards awareness to instructor sign-off.</p>
        <div class="Ladder">
          <?php foreach ($TRAINING_PATHS as $path): ?>
            <div class="Ladder_Row">
              <span class="Ladder_Ph"><?= e($path['phase']) ?></span>
              <h3 class="Ladder_T"><?= e($path['title']) ?></h3>
              <p class="Ladder_D"><?= e($path['desc']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="Reveal">
        <div class="MediaCard">
          <span class="MediaTag">SIMULATION · LIVE · OJT</span>
          <img src="<?= e(url('assets/img/avionics.svg')) ?>" alt="Training display with a planned route, waypoints and aircraft state" width="860" height="640" loading="lazy" decoding="async">
          <div class="MediaCard_Cap"><span><b>Simulation first</b> · live where it is safe</span><span>TRAINING</span></div>
        </div>
        <div class="Callout Callout--tight">
          <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-doc"/></svg></span>
          <div>
            <h3 class="Callout_T">Evidence per trainee</h3>
            <p class="Callout_D">Each trainee receives an individual competency record covering practical tasks, assessment outcomes and instructor sign-off — the file an auditor will ask to see.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="Section Section--alt" aria-labelledby="Advisory_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Technical advisory</span>
      <h2 id="Advisory_T">Needs analysis before equipment selection</h2>
      <p class="Head_B">For ministries and civil authorities, the first deliverable is often a defensible requirement — not a platform. Advisory work is scoped to produce one.</p>
    </div>
    <div class="Grid Grid--3">
      <article class="Card Card--flat Reveal" data-domain-item data-domains="cyber">
        <div class="Card_B">
          <span class="Card_K">Border security</span>
          <h3 class="Card_T">Perimeter and corridor studies</h3>
          <p class="Card_D">Coverage modelling, sensor mix, communications constraints and response-time analysis for complex boundaries.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal" data-domain-item data-domains="cyber support">
        <div class="Card_B">
          <span class="Card_K">Environmental protection</span>
          <h3 class="Card_T">Anti-poaching surveillance</h3>
          <p class="Card_D">Detection concepts for protected areas, including UAS patrol patterns, sensor placement and ranger response integration.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal" data-domain-item data-domains="cyber">
        <div class="Card_B">
          <span class="Card_K">Maritime</span>
          <h3 class="Card_T">Maritime domain awareness</h3>
          <p class="Card_D">Coastal surveillance architecture, radar and AIS integration, and command-post workflows for shared maritime pictures.</p>
        </div>
      </article>
    </div>
    <div class="Callout Callout--spaced">
      <span class="Callout_Ico" aria-hidden="true"><svg class="Ico"><use href="#i-doc"/></svg></span>
      <div>
        <h3 class="Callout_T">Deliverable: a decision document</h3>
        <p class="Callout_D">Advisory engagements close with a written options appraisal, cost envelope and procurement risk register — usable in front of a board or a ministry committee.</p>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="SvcFaq_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Practical questions</span>
      <h2 id="SvcFaq_T">Before you request a quotation</h2>
    </div>
    <div class="Faq">
      <details class="Faq_Item" open>
        <summary>Can training be delivered in-country?</summary>
        <p>Yes. Training can be delivered at the regional depot, at the customer's facility, or in a mixed format with simulation-based practical work followed by on-site on-the-job training. Instructor travel and facility requirements are identified in the training plan.</p>
      </details>
      <details class="Faq_Item">
        <summary>Do you provide maintenance for systems you did not supply?</summary>
        <p>In many cases, yes, subject to technical assessment and access to the necessary documentation, special tools and spare parts. An initial inspection determines whether a maintenance agreement is realistic and what OEM support is needed.</p>
      </details>
      <details class="Faq_Item">
        <summary>How are support agreements priced?</summary>
        <p>Support is priced against the platform inventory, the required response tier, spares holding and reporting obligations. A consultation produces a scoped proposal with named deliverables rather than an open-ended retainer.</p>
      </details>
      <details class="Faq_Item">
        <summary>What happens with classified or controlled information?</summary>
        <p>Controlled technical data is handled under the applicable export-control and national security rules. Public forms and email are not appropriate channels for classified information; secure transfer arrangements are agreed before any sensitive exchange.</p>
      </details>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
