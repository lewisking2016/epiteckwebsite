<?php /** Global footer + mobile action bar. */ ?>
</main>

<?php if (empty($page['hideCta'])): ?>
<section class="PreCta" aria-labelledby="PreCta_T">
  <div class="Wrap PreCta_In">
    <div class="PreCta_Txt">
      <div class="PreCta_Badge">
        <span class="PreCta_Dot" aria-hidden="true"></span>
        <span class="PreCta_Badge_Txt">AVIATION &amp; AIR-FORCE SUPPORT · EAST AFRICA</span>
      </div>
      <h2 id="PreCta_T" class="PreCta_H2">Bring the operational requirement. We bring the regional engineering.</h2>
      <p class="PreCta_Lead">Mission software, avionics integration, crew training and depot support for air forces and civil aviation operators in East Africa. Enquiries are handled by the regional team in Nairobi.</p>
      <div class="PreCta_Meta">
        <span><svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-shield"/></svg> Eligibility-screened procurement</span>
        <span><svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-check"/></svg> Response target: one working day</span>
      </div>
    </div>
    <div class="PreCta_Act">
      <a class="Btn Btn--light Btn--lg" href="<?= e(url('/contact')) ?>">
        Request a briefing <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg>
      </a>
      <?php if (SITE_EMAIL !== ''): ?>
      <a class="Btn Btn--ghost-light Btn--lg" href="mailto:<?= e(SITE_EMAIL) ?>">
        <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-mail"/></svg> <?= e(SITE_EMAIL) ?>
      </a>
      <?php endif; ?>
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
        <p class="Foot_Desc">Regional support arm for STM in East Africa: aviation mission software, avionics integration, crew training and depot sustainment.</p>

        <div class="Foot_Badges" aria-label="Standards referenced">
          <span>ICAO</span>
          <span>EUROCONTROL</span>
          <span>NATO-INTEROPERABLE</span>
          <span>ISO 9001-ALIGNED</span>
          <span>STM PORTFOLIO</span>
        </div>
      </div>

      <nav class="Foot_Col" aria-label="Capabilities">
        <h3>Operational domains</h3>
        <a href="<?= e(url('/#domain-air')) ?>">Air force &amp; unmanned systems</a>
        <a href="<?= e(url('/#domain-aviation')) ?>">Aviation software &amp; avionics</a>
        <a href="<?= e(url('/#domain-cyber')) ?>">Command &amp; surveillance</a>
        <a href="<?= e(url('/#domain-support')) ?>">Depot &amp; sustainment</a>
        <a href="<?= e(url('/#portfolio')) ?>">Technology portfolio</a>
      </nav>

      <nav class="Foot_Col" aria-label="Company">
        <h3>Programmes &amp; hubs</h3>
        <a href="<?= e(url('/distributor-integration')) ?>">Distributor hub</a>
        <a href="<?= e(url('/partnerships-stm')) ?>">Partnership</a>
        <a href="<?= e(url('/services')) ?>">Training &amp; services</a>
        <a href="<?= e(url('/services#support-desk')) ?>">Support desk</a>
        <a href="<?= e(url('/contact')) ?>">Contact</a>
      </nav>

      <div class="Foot_Col Foot_Contact">
        <h3>Contact</h3>
        <?php if (SITE_EMAIL !== ''): ?>
        <a class="Foot_EmailLink" href="mailto:<?= e(SITE_EMAIL) ?>">
          <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-mail"/></svg>
          <span><?= e(SITE_EMAIL) ?></span>
        </a>
        <?php endif; ?>
        <p class="Foot_Address">
          <strong>Regional Headquarters</strong><br>
          Nairobi, Kenya <span class="Muted">(address shared during onboarding)</span>
        </p>
        <p class="Foot_Territories">
          <strong>Operating Territories:</strong><br>
          Kenya · Uganda · Tanzania · Rwanda · Ethiopia
        </p>
        <?php if (SITE_LINKEDIN !== ''): ?>
        <a class="Foot_Soc" href="<?= e(SITE_LINKEDIN) ?>" rel="noopener" target="_blank">
          <svg class="Ico" aria-hidden="true"><use href="#i-linkedin"/></svg> LinkedIn
        </a>
        <?php endif; ?>
      </div>
    </div>

    <details class="Foot_Notice">
      <summary>
        <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-shield"/></svg>
        Procurement &amp; Dual-Use Export Compliance Notice
      </summary>
      <div class="Foot_Notice_B">
        <p>EPiTEK Solutions engages with air forces, civil aviation authorities and government buyers under national procurement statutes, End-User Certification (EUC) requirements, and relevant international dual-use export control regimes.</p>
        <p>Technical data sheets, tactical flight specifications, telemetry protocols, and live system demonstrations are released solely to verified institutional personnel following identity and eligibility validation. Statements of alliance or standard alignment denote operational compatibility and process compliance.</p>
        <p>All institutional correspondence and RFPs submitted through this portal are treated as privileged and confidential under standard defence non-disclosure terms.</p>
      </div>
    </details>

    <div class="Foot_Bot">
      <p class="Foot_Copy">© <?= date('Y') ?> <?= e(SITE_LEGAL) ?> · Nairobi, Kenya · <?= e(SITE_DOMAIN) ?></p>
      <div class="Foot_Links">
        <a href="<?= e(url('/privacy')) ?>">Privacy notice</a>
        <a href="<?= e(url('/contact#compliance')) ?>">Compliance</a>
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
  <a class="MBar_A MBar_A--alt" href="<?= SITE_EMAIL !== '' ? 'mailto:' . e(SITE_EMAIL) : e(url('/contact')) ?>">
    <svg class="Ico" aria-hidden="true"><use href="#i-mail"/></svg>
    <span><?= SITE_EMAIL !== '' ? 'Email us' : 'Enquire' ?></span>
  </a>
</div>

<script src="<?= e(asset('assets/js/main.js')) ?>" defer></script>
</body>
</html>
