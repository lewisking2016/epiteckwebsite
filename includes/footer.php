<?php /** Global footer + mobile action bar. */ ?>
</main>

<?php if (empty($page['hideCta'])): ?>
<section class="PreCta" aria-labelledby="PreCta_T">
  <div class="Wrap PreCta_In">
    <div class="PreCta_Txt">
      <div class="PreCta_Badge">
        <span class="PreCta_Dot" aria-hidden="true"></span>
        <span class="PreCta_Badge_Txt">IN-THEATRE DEFENCE &amp; AVIATION READINESS</span>
      </div>
      <h2 id="PreCta_T" class="PreCta_H2">Bring the operational requirement. We deploy the sovereign engineering.</h2>
      <p class="PreCta_Lead">Direct engineering consultations, technical data packages, flight demonstrations, and depot SLA agreements. Enquiries are handled directly by our Nairobi regional technical team.</p>
      <div class="PreCta_Meta">
        <span><svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-shield"/></svg> Eligibility-screened defence procurement</span>
        <span><svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-check"/></svg> &lt;24-hour response target</span>
      </div>
    </div>
    <div class="PreCta_Act">
      <a class="Btn Btn--light Btn--lg" href="<?= e(url('/contact')) ?>">
        Request a Technical Demo <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg>
      </a>
      <a class="Btn Btn--ghost-light Btn--lg" href="mailto:<?= e(SITE_EMAIL) ?>">
        <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-mail"/></svg> <?= e(SITE_EMAIL) ?>
      </a>
      <p class="PreCta_Note">Dual-use export compliance · End-User Certificates (EUC) supported · NDA on request.</p>
    </div>
  </div>
</section>
<?php endif; ?>

<footer class="Foot" id="footer">
  <div class="Wrap">
    <div class="Foot_Top">
      <div class="Foot_Brand">
        <img src="<?= e(asset('assets/img/epitek-logo-white.svg')) ?>" alt="<?= e(SITE_NAME) ?>" width="186" height="30" loading="lazy" decoding="async">
        <p class="Foot_Desc">Regional distributor, systems integrator, and lifecycle maintainer for defence, aerospace, and mission technology across East Africa.</p>
        
        <div class="Foot_LiveStatus">
          <span class="Foot_LiveDot" aria-hidden="true"></span>
          <span class="Foot_LiveTxt">Nairobi Operations Command &amp; Depot Active</span>
        </div>

        <div class="Foot_Badges" aria-label="Standards referenced">
          <span>ICAO</span>
          <span>EUROCONTROL</span>
          <span>NATO-INTEROPERABLE</span>
          <span>ISO 9001-ALIGNED</span>
          <span>STANAG COMPLIANT</span>
        </div>
      </div>

      <nav class="Foot_Col" aria-label="Capabilities">
        <h3>Operational Domains</h3>
        <a href="<?= e(url('/#domain-air')) ?>">Air &amp; Autonomous Systems</a>
        <a href="<?= e(url('/#domain-aviation')) ?>">Aviation Software &amp; Maps</a>
        <a href="<?= e(url('/#domain-cyber')) ?>">Cyber &amp; C4ISR Networks</a>
        <a href="<?= e(url('/#domain-support')) ?>">Depot Maintenance &amp; MRO</a>
        <a href="<?= e(url('/#portfolio')) ?>">Technology Portfolio</a>
      </nav>

      <nav class="Foot_Col" aria-label="Company">
        <h3>Programs &amp; Hubs</h3>
        <a href="<?= e(url('/distributor-integration')) ?>">Distributor Hub</a>
        <a href="<?= e(url('/partnerships-stm')) ?>">OEM &amp; Sovereign Partnerships</a>
        <a href="<?= e(url('/services')) ?>">Training &amp; Flight Simulation</a>
        <a href="<?= e(url('/services#support-desk')) ?>">24/7 Mission Support Desk</a>
        <a href="<?= e(url('/contact')) ?>">Procurement &amp; Contact</a>
      </nav>

      <div class="Foot_Col Foot_Contact">
        <h3>Regional Command</h3>
        <a class="Foot_EmailLink" href="mailto:<?= e(SITE_EMAIL) ?>">
          <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-mail"/></svg>
          <span><?= e(SITE_EMAIL) ?></span>
        </a>
        <p class="Foot_Address">
          <strong>Regional Headquarters</strong><br>
          Nairobi, Kenya <span class="Muted">(Facility address on security clearance)</span>
        </p>
        <p class="Foot_Territories">
          <strong>Operating Territories:</strong><br>
          Kenya · Uganda · Tanzania · Rwanda · Ethiopia · South Sudan
        </p>
        <a class="Foot_Soc" href="<?= e(SITE_LINKEDIN) ?>" rel="noopener" target="_blank">
          <svg class="Ico" aria-hidden="true"><use href="#i-linkedin"/></svg> Official LinkedIn Channel
        </a>
      </div>
    </div>

    <details class="Foot_Notice">
      <summary>
        <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-shield"/></svg>
        Procurement &amp; Dual-Use Export Compliance Notice
      </summary>
      <div class="Foot_Notice_B">
        <p>EPiTEK Solutions engages with sovereign governments, military branches, and civil aviation buyers in strict compliance with national procurement statutes, sovereign End-User Certification (EUC) requirements, and relevant international dual-use export control regimes.</p>
        <p>Technical data sheets, tactical flight specifications, telemetry protocols, and live system demonstrations are released solely to verified institutional personnel following identity and eligibility validation. Statements of alliance or standard alignment denote operational compatibility and process compliance.</p>
        <p>All institutional correspondence and RFPs submitted through this portal are treated as privileged and confidential under standard defence non-disclosure terms.</p>
      </div>
    </details>

    <div class="Foot_Bot">
      <p class="Foot_Copy">© <?= date('Y') ?> <?= e(SITE_LEGAL) ?>. All rights reserved. · Sovereign Defence &amp; Aerospace Integrator · <?= e(SITE_DOMAIN) ?></p>
      <div class="Foot_Links">
        <a href="<?= e(url('/contact#compliance')) ?>">Compliance &amp; Ethics</a>
        <a href="<?= e(url('sitemap.xml')) ?>">Sitemap</a>
        <a href="#top" class="Foot_Top_Link" aria-label="Scroll back to top">Back to top ↑</a>
      </div>
    </div>
  </div>
</footer>

<div class="MBar" aria-label="Quick contact">
  <a class="MBar_A" href="<?= e(url('/contact')) ?>">
    <svg class="Ico" aria-hidden="true"><use href="#i-doc"/></svg>
    <span>Request demo</span>
  </a>
  <a class="MBar_A MBar_A--alt" href="mailto:<?= e(SITE_EMAIL) ?>">
    <svg class="Ico" aria-hidden="true"><use href="#i-mail"/></svg>
    <span>Email us</span>
  </a>
</div>

<script src="<?= e(asset('assets/js/main.js')) ?>" defer></script>
</body>
</html>
