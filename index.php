<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';

$page = [
    'slug'        => 'home',
    'nav'         => 'home',
    'path'        => '/',
    'title'       => 'East Africa Defence & Aviation Technology Integrator | EPiTEK',
    'description' => 'EPiTEK Solutions integrates defence and aviation technology across East Africa: tactical UAS, mission software, C4ISR, training and depot sustainment.',
    'heroImage'   => 'assets/img/hero-terrain.svg',
    'filterDomains' => true,
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
                'description' => 'Regional distributor, systems integrator and lifecycle maintainer for defence, aerospace and mission technology in East Africa.',
                'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
                'knowsAbout' => ['Tactical unmanned aerial systems', 'Mission planning software', 'C4ISR integration', 'Aviation maintenance', 'Aviation standards compliance'],
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
            [
                '@type' => 'Service',
                'name' => 'Defence and aviation technology integration',
                'provider' => ['@id' => SITE_URL . '/#organization'],
                'serviceType' => 'Systems integration, procurement and lifecycle sustainment',
                'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
                'description' => 'Procurement, integration, training and depot-level maintenance for autonomous systems, aviation mission software and C4ISR infrastructure.',
            ],
        ],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<!-- ============================================================ hero -->
<section class="Hero" id="hero" aria-labelledby="Hero_T">
  <img class="Hero_Img" src="<?= e(url('assets/img/hero-terrain.svg')) ?>" alt="" width="1600" height="1100" fetchpriority="high" decoding="async">
  <span class="Hero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="Hero_In">
      <div class="Hero_Txt">
        <div class="Hero_Badge">
          <span class="Hero_Badge_Beacon" aria-hidden="true"></span>
          <span class="Hero_Badge_Text">REGIONAL DEFENCE &amp; AVIATION SYSTEMS INTEGRATOR</span>
          <span class="Hero_Badge_Code">STANAG · ICAO</span>
        </div>

        <h1 class="Hero_H1" id="Hero_T">
          Sovereign Defence &amp; Aviation Technology <span class="Hl">Engineered for East Africa</span>
        </h1>

        <p class="Hero_Lead">
          EPiTEK Solutions equips defence forces, civil aviation authorities, and security agencies across the East African Community with mission-critical tactical UAS, cockpit avionics, hardened C4ISR networks, and in-theatre depot sustainment.
        </p>

        <div class="Hero_Act">
          <a class="Btn Btn--light Btn--lg Btn--go" href="#domains">
            Explore Capabilities <span class="Btn_Ico" aria-hidden="true"><svg class="Ico Ico--sm"><use href="#i-arrow"/></svg></span>
          </a>
          <a class="Btn Btn--ghost-light Btn--lg" href="<?= e(url('/contact')) ?>">
            <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-shield"/></svg> Request Technical Demo
          </a>
        </div>

        <!-- Capability Matrix / Key Highlights -->
        <div class="Hero_StatsGrid">
          <div class="Hero_StatCard">
            <div class="Hero_StatNum">05</div>
            <div class="Hero_StatLbl">Sovereign Territories Linked</div>
            <div class="Hero_StatSub">KE · UG · TZ · RW · ET</div>
          </div>
          <div class="Hero_StatCard">
            <div class="Hero_StatNum">&lt;24h</div>
            <div class="Hero_StatLbl">In-Theatre Depot SLA</div>
            <div class="Hero_StatSub">Nairobi Support Hub</div>
          </div>
          <div class="Hero_StatCard">
            <div class="Hero_StatNum">100%</div>
            <div class="Hero_StatLbl">Interoperable Architectures</div>
            <div class="Hero_StatSub">NATO &amp; EUROCONTROL</div>
          </div>
        </div>

        <!-- Domain quick chips -->
        <div class="Hero_Pills" aria-label="Operational core capabilities">
          <span class="Hero_Pill">Tactical UAS &amp; Loitering Munitions</span>
          <span class="Hero_Pill">Mission Software &amp; Moving Maps</span>
          <span class="Hero_Pill">Sensor-to-Shooter C4ISR</span>
          <span class="Hero_Pill">Depot MRO Sustainment</span>
        </div>
      </div>

      <div class="Hero_Viz">
        <div class="Globe" id="hero-globe">
          <!-- Live Telemetry Status Header -->
          <div class="Globe_Header">
            <div class="Globe_Header_Left">
              <span class="Globe_LiveDot" aria-hidden="true"></span>
              <span class="Globe_LiveTitle">EAST AFRICA DEFENCE MESH</span>
              <span class="Globe_LiveCapCount">10 CAPITALS LINKED</span>
            </div>
            <div class="Globe_Header_Right">
              <span class="Globe_HudTag">TACTICAL C2 HUD</span>
            </div>
          </div>

          <!-- 3D Canvas Frame -->
          <div class="Globe_Frame" aria-label="Interactive 3D East Africa Defence and Aviation Globe">
            <img class="Globe_Poster" src="<?= e(url('assets/img/coverage-map.svg')) ?>" alt="Regional network coverage" width="900" height="760" decoding="async">
            
            <!-- Interactive Tactical HUD Tooltip -->
            <div class="Globe_Tip" hidden>
              <div class="Globe_Tip_Header">
                <span class="Globe_Tip_City">Nairobi</span>
                <span class="Globe_Tip_Country">Kenya</span>
              </div>
              <div class="Globe_Tip_Coord">01°17'S · 36°49'E</div>
              <div class="Globe_Tip_Role">Regional Operations Command HQ &amp; Avionics Depot</div>
              <div class="Globe_Tip_Status">
                <i class="Dot Dot--ok" aria-hidden="true"></i> Encrypted Telemetry · Low Latency
              </div>
            </div>
          </div>

          <!-- Quick Capital Targets Navigator -->
          <div class="Globe_Controls" aria-label="Focus strategic capital">
            <span class="Globe_Controls_Label">Target Hub:</span>
            <div class="Globe_Capitals">
              <button type="button" class="Globe_CapBtn is-active" data-cap="nairobi" title="Focus Nairobi HQ">Nairobi <span class="CapTag">HQ</span></button>
              <button type="button" class="Globe_CapBtn" data-cap="kampala" title="Focus Kampala">Kampala</button>
              <button type="button" class="Globe_CapBtn" data-cap="dodoma" title="Focus Dodoma">Dodoma</button>
              <button type="button" class="Globe_CapBtn" data-cap="daressalaam" title="Focus Dar es Salaam">Dar es Salaam</button>
              <button type="button" class="Globe_CapBtn" data-cap="kigali" title="Focus Kigali">Kigali</button>
              <button type="button" class="Globe_CapBtn" data-cap="addis" title="Focus Addis Ababa">Addis Ababa</button>
              <button type="button" class="Globe_CapBtn" data-cap="juba" title="Focus Juba">Juba</button>
              <button type="button" class="Globe_CapBtn" data-cap="mogadishu" title="Focus Mogadishu">Mogadishu</button>
            </div>
          </div>

          <!-- Globe Bottom Bar with Telemetry Readout -->
          <div class="Globe_Bar">
            <span class="Globe_Hint">
              <i class="Dot Dot--pulse" aria-hidden="true"></i> Drag to rotate · Select capital to focus
            </span>
            <span class="Globe_Coord" id="globe-telemetry">LAT 02.2°N · LNG 37.2°E</span>
          </div>
        </div>
      </div>
    </div>

    <div class="Hero_Chain">
      <article class="Chain">
        <div class="Chain_Pic"><img src="<?= e(url('assets/img/c4isr.svg')) ?>" alt="" width="860" height="640" loading="lazy" decoding="async"></div>
        <div class="Chain_B">
          <h2 class="Chain_T"><svg class="Ico" aria-hidden="true"><use href="#i-globe"/></svg> Domains</h2>
          <div class="Chain_Chips"><span>AIR</span><span>AVIATION</span><span>C4ISR</span><span>MRO</span></div>
          <div class="Chain_Chips"><span>Autonomous systems</span><span>Mission software</span></div>
        </div>
      </article>
      <article class="Chain">
        <div class="Chain_Pic"><img src="<?= e(url('assets/img/depot.svg')) ?>" alt="" width="860" height="640" loading="lazy" decoding="async"></div>
        <div class="Chain_B">
          <h2 class="Chain_T"><svg class="Ico" aria-hidden="true"><use href="#i-doc"/></svg> Standards</h2>
          <div class="Chain_Chips"><span>ICAO</span><span>EUROCONTROL</span><span>NATO</span></div>
          <div class="Chain_Chips"><span>ISO 9001-aligned</span><span>AS9100-aligned</span></div>
        </div>
      </article>
      <article class="Chain">
        <div class="Chain_Pic"><img src="<?= e(url('assets/img/uas-kargu.svg')) ?>" alt="" width="860" height="640" loading="lazy" decoding="async"></div>
        <div class="Chain_B">
          <h2 class="Chain_T"><svg class="Ico" aria-hidden="true"><use href="#i-shield"/></svg> Lifecycle</h2>
          <div class="Chain_Chips"><span>PROCURE</span><span>INTEGRATE</span><span>TRAIN</span><span>SUSTAIN</span></div>
          <div class="Chain_Chips"><span>One contract</span><span>One record</span></div>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- ============================================================ overview -->
<section class="Section" id="overview" aria-labelledby="Overview_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body Reveal">
        <span class="Eyebrow">Regional engineering backbone</span>
        <h2 id="Overview_T">Not a broker. An engineering presence in theatre.</h2>
        <p>East African procurement authorities need more than a catalogue and a freight forwarder. They need a partner who can clear a border, integrate a tactical data link, train the operator and keep the platform serviceable a decade after delivery.</p>
        <p>EPiTEK Solutions operates as that regional engineering layer: distribution logistics, system integration, certification-aware documentation and depot-level sustainment, delivered inside national procurement frameworks.</p>
        <ul class="Split_List">
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Factory-trained engineers and test capability positioned in the region.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Sensor, radio and command integration into existing sovereign infrastructure.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> One accountable contract from requirement definition to depot overhaul.</li>
        </ul>
        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/distributor-integration')) ?>">Open the distributor hub <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="Btn Btn--ghost" href="<?= e(url('/partnerships-stm')) ?>">Technology partnership</a>
        </div>
      </div>

      <div class="Split_Media Reveal">
        <div class="MediaCard">
          <span class="MediaTag">EAST AFRICA · EA-01</span>
          <img src="<?= e(url('assets/img/coverage-map.svg')) ?>" alt="Map of the East African coverage area with nodes for Addis Ababa, Nairobi, Kampala, Kigali and Dodoma" width="900" height="760" loading="lazy" decoding="async">
          <div class="MediaCard_Cap">
            <span><b>5 states</b> · coordinated from Nairobi</span>
            <span>NOT TO SCALE</span>
          </div>
        </div>
      </div>
    </div>

    <div class="Marks" aria-labelledby="Marks_T">
      <p class="Marks_L" id="Marks_T">Alignment and standards referenced in our engineering and training work</p>
      <div class="Marks_Row">
        <span class="Mark">ICAO<span>Airspace and interface standards</span></span>
        <span class="Mark">EUROCONTROL<span>ATM interoperability</span></span>
        <span class="Mark">NATO<span>Interoperable interfaces</span></span>
        <span class="Mark">ISO<span>9001-aligned processes</span></span>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================ domains -->
<section class="Section Section--alt" id="domains" aria-labelledby="Domains_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Operational domains</span>
      <h2 id="Domains_T">Four domains. One accountable integrator.</h2>
      <p class="Head_B">Filter the page by domain using the bar above. Each domain shares the same engineering, training and sustainment backbone, so capability does not arrive in disconnected contracts.</p>
    </div>

    <div class="Grid Grid--4">
      <article class="Card DomainCard Reveal" id="domain-air" data-domain-item data-domains="air">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/uas-kargu.svg')) ?>" alt="Schematic of a tactical quad-rotor UAS with payload and navigation annotations" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Domain 01</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">Air &amp; autonomous</span>
          <h3 class="Card_T">Tactical UAS and loitering systems</h3>
          <p class="Card_D">ISR and precision systems that keep working in GNSS-denied airspace, integrated with your command chain and supported in theatre.</p>
          <a class="Card_L" href="<?= e(url('/partnerships-stm#portfolio')) ?>">Explore the systems <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>

      <article class="Card DomainCard Reveal" id="domain-aviation" data-domain-item data-domains="aviation">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/avionics.svg')) ?>" alt="Mission planning display with moving map, route and telemetry" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Domain 02</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">Aviation software</span>
          <h3 class="Card_T">Mission planning &amp; situational awareness</h3>
          <p class="Card_D">Digital moving maps, route planning and cockpit feeds that modernise legacy fleets without replacing the airframe.</p>
          <a class="Card_L" href="<?= e(url('/partnerships-stm#portfolio')) ?>">Explore mission software <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>

      <article class="Card DomainCard Reveal" id="domain-cyber" data-domain-item data-domains="cyber">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/naval.svg')) ?>" alt="Naval platform schematic with coastal surveillance and radar coverage" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Domain 03</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">C4ISR &amp; cyber</span>
          <h3 class="Card_T">Command, control &amp; maritime awareness</h3>
          <p class="Card_D">Sensor-to-shooter integration, coastal surveillance and hardened mission networks, including naval platform integration.</p>
          <a class="Card_L" href="<?= e(url('/distributor-integration#integration')) ?>">See integration methods <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>

      <article class="Card DomainCard Reveal" id="domain-support" data-domain-item data-domains="support">
        <div class="Card_Pic">
          <img src="<?= e(url('assets/img/depot.svg')) ?>" alt="Depot maintenance bench with calibration, battery management and serial traceability callouts" width="860" height="640" loading="lazy" decoding="async">
          <span class="Card_Tag">Domain 04</span>
        </div>
        <div class="Card_B">
          <span class="Card_K">Integrated support</span>
          <h3 class="Card_T">Depot maintenance &amp; sustainment</h3>
          <p class="Card_D">Regional depot MRO, spares warehousing, battery management and mobile engineering teams that shorten the repair loop.</p>
          <a class="Card_L" href="<?= e(url('/services#support-desk')) ?>">SLA and support tiers <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
    </div>
    <p class="FilterEmpty" data-filter-empty hidden>No capability is published for this domain yet. <a href="<?= e(url('/contact')) ?>">Ask the team</a>.</p>
  </div>
</section>

<!-- ============================================================ portfolio -->
<section class="Section" id="portfolio" aria-labelledby="Portfolio_T">
  <div class="Wrap">
    <div class="Head Head--split">
      <div>
        <span class="Eyebrow">Technology portfolio</span>
        <h2 id="Portfolio_T">Systems we integrate, train on and sustain</h2>
        <p class="Head_B">A focused portfolio across autonomous systems, mission software, naval command and depot support. Specification sheets are released after eligibility screening.</p>
      </div>
      <div class="Head_A">
        <a class="Btn Btn--ghost" href="<?= e(url('/partnerships-stm')) ?>">Full portfolio table <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
      </div>
    </div>

    <div class="Grid Grid--3">
      <?php foreach ($PORTFOLIO as $item): ?>
        <article class="Card Reveal" data-domain-item data-domains="<?= e(implode(' ', $item['domains'])) ?>">
          <div class="Card_Pic">
            <img src="<?= e(url($item['img'])) ?>" alt="<?= e($item['alt']) ?>" width="860" height="640" loading="lazy" decoding="async">
            <span class="Card_Tag"><?= e($item['tag']) ?></span>
          </div>
          <div class="Card_B">
            <span class="Card_K"><?= e($item['role']) ?></span>
            <h3 class="Card_T"><?= e($item['name']) ?></h3>
            <p class="Card_D"><?= e($item['desc']) ?></p>
            <a class="Card_L" href="<?= e(url('/contact?topic=' . $item['slug'])) ?>"><?= e($item['cta']) ?> <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="Table_Note">Platform availability, configuration and release of technical data are subject to end-user screening and export-control authorisation. Portfolio marks belong to their respective owners.</p>
  </div>
</section>

<!-- ============================================================ standards band -->
<section class="Band Section" id="standards" aria-labelledby="Standards_T">
  <div class="Wrap">
    <div class="Band_Grid">
      <div class="Band_Aside Reveal">
        <span class="Eyebrow">Standards &amp; compliance</span>
        <h2 id="Standards_T">Standards are the baseline. Evidence is the deliverable.</h2>
        <p>“Aligned” is not “certified”. We state the difference, keep the paperwork, and hand over the evidence file your audit team will ask for.</p>
        <div class="Stats Stats--dark">
          <div class="Stat"><span class="Stat_V">05</span><span class="Stat_L">Territories</span><span class="Stat_D">EAC coverage</span></div>
          <div class="Stat"><span class="Stat_V">&lt;2h</span><span class="Stat_L">Tier 1 target</span><span class="Stat_D">Service objective</span></div>
          <div class="Stat"><span class="Stat_V">24/7</span><span class="Stat_L">Support desk</span><span class="Stat_D">Duty engineer</span></div>
          <div class="Stat"><span class="Stat_V">04</span><span class="Stat_L">Domains</span><span class="Stat_D">One integrator</span></div>
        </div>
        <a class="Btn Btn--ghost-light" href="<?= e(url('/contact#compliance')) ?>">Read the compliance approach <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
      </div>

      <div class="Reveal">
        <div class="Table_Scroll">
          <table class="Table Table--dark">
            <caption class="Table_Note">Frameworks referenced in engineering, training and quality documentation. Certification status is stated per engagement.</caption>
            <thead>
              <tr><th scope="col">Framework</th><th scope="col">Scope</th><th scope="col">How it applies</th><th scope="col">Status</th></tr>
            </thead>
            <tbody>
              <?php foreach ($STANDARDS as $row): ?>
                <tr>
                  <td><?= e($row['standard']) ?></td>
                  <td><?= e($row['scope']) ?></td>
                  <td><?= e($row['apply']) ?></td>
                  <td><span class="Pill<?= $row['status'] === 'Aligned' ? ' Pill--ok' : '' ?>"><?= e($row['status']) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p class="Table_Note">Certificate numbers and authorisation evidence are supplied during due diligence, not published as marketing claims.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================ training + support -->
<section class="Section" id="sustainment" aria-labelledby="Sustain_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body Reveal">
        <span class="Eyebrow">Training &amp; support</span>
        <h2 id="Sustain_T">Training that produces a record, not just a certificate.</h2>
        <p>Every training path is mapped to a competency file: who was trained, on which configuration, by whom, and what evidence proves it. That file is what sustains a fleet after the instructors leave.</p>
        <div class="Ladder">
          <?php foreach ($TRAINING_PATHS as $path): ?>
            <div class="Ladder_Row">
              <span class="Ladder_Ph"><?= e($path['phase']) ?></span>
              <h3 class="Ladder_T"><?= e($path['title']) ?></h3>
              <p class="Ladder_D"><?= e($path['desc']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/services')) ?>">All training &amp; services <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </div>

      <div class="Reveal">
        <div class="SupportCard">
          <div class="SupportCard_H"><span class="Dot" aria-hidden="true"></span> Support desk · live capability</div>
          <h3 class="SupportCard_T">Defined response windows, not a generic inbox.</h3>
          <p>Critical mission platform failures enter a named escalation path with a duty engineer and a spares decision inside the first response window.</p>
          <div class="SLASteps">
            <div class="SLAStep">
              <span class="SLAStep_N">T1</span>
              <span class="SLAStep_T">Critical — platform grounded<small>Aircraft or mission system unserviceable</small></span>
              <span class="SLAStep_V">&lt; 2 h target</span>
            </div>
            <div class="SLAStep">
              <span class="SLAStep_N">T2</span>
              <span class="SLAStep_T">Degraded — mission limited<small>Reduced redundancy or sensor fault</small></span>
              <span class="SLAStep_V">Same day</span>
            </div>
            <div class="SLAStep">
              <span class="SLAStep_N">T3</span>
              <span class="SLAStep_T">Routine — planned maintenance<small>Documentation, spares, calibration</small></span>
              <span class="SLAStep_V">5 working days</span>
            </div>
          </div>
          <a class="Btn Btn--ghost" href="<?= e(url('/contact?topic=sla')) ?>">Initiate an SLA consultation <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <p class="Note">Response windows are service objectives, not guaranteed outcomes. Final SLA terms are agreed per platform and deployment.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================ coverage -->
<section class="Section Section--alt" id="coverage" aria-labelledby="Coverage_T">
  <div class="Wrap">
    <div class="Head Head--split">
      <div>
        <span class="Eyebrow">Regional footprint</span>
        <h2 id="Coverage_T">Present in the territories we serve</h2>
        <p class="Head_B">Coordination, engineering, training and field support distributed across five East African states, with a single programme office accountable for delivery.</p>
      </div>
      <div class="Head_A">
        <a class="Btn Btn--ghost" href="<?= e(url('/distributor-integration')) ?>">Logistics &amp; clearance model</a>
      </div>
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

<!-- ============================================================ briefs -->
<section class="Section" id="briefs" aria-labelledby="Briefs_T">
  <div class="Wrap">
    <div class="Head Head--split">
      <div>
        <span class="Eyebrow">Technical briefs</span>
        <h2 id="Briefs_T">Short reads for procurement and engineering teams</h2>
        <p class="Head_B">Written to be useful in a requirements meeting: what to ask, what evidence to request, and where the technical risk usually sits.</p>
      </div>
      <div class="Head_A">
        <a class="Btn Btn--ghost" href="<?= e(url('/contact')) ?>">Request a briefing</a>
      </div>
    </div>
    <div class="Briefs">
      <?php foreach ($BRIEFS as $brief): ?>
        <article class="Brief Reveal">
          <span class="Brief_Tag"><?= e($brief['topic']) ?></span>
          <h3 class="Brief_T"><?= e($brief['title']) ?></h3>
          <p class="Brief_D"><?= e($brief['desc']) ?></p>
          <a class="Brief_L" href="<?= e(url('/contact?topic=brief')) ?>"><?= e($brief['read']) ?> <svg class="Ico" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================ why -->
<section class="Section Section--alt" id="why" aria-labelledby="Why_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Why EPiTEK</span>
      <h2 id="Why_T">The difference is what happens after delivery</h2>
      <p class="Head_B">Procurement closes a contract. Sustainment keeps a capability alive. Our structure is built for the second part.</p>
    </div>
    <div class="WhyGrid">
      <?php foreach ($WHY as $why): ?>
        <article class="Why Reveal">
          <span class="Why_N"><?= e($why['n']) ?></span>
          <h3 class="Why_T"><?= e($why['title']) ?></h3>
          <p class="Why_D"><?= e($why['desc']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================ faq -->
<section class="Section" id="faq" aria-labelledby="Faq_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Direct answers</span>
      <h2 id="Faq_T">Questions procurement teams ask first</h2>
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

<?php require __DIR__ . '/includes/footer.php'; ?>
