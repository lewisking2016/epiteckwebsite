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
        <p class="Foot_Desc">Aviation and air-force engineering, integration, training and depot sustainment across East Africa.</p>

        <div class="Foot_Badges" aria-label="Standards referenced">
          <span>ICAO</span>
          <span>EUROCONTROL</span>
          <span>NATO-INTEROPERABLE</span>
          <span>ISO 9001-ALIGNED</span>
        </div>
      </div>

      <nav class="Foot_Col" aria-label="Capabilities">
        <h3>Capabilities</h3>
        <?php foreach ($CAPABILITIES as $cap): ?>
        <a href="<?= e(url($cap['path'])) ?>"><?= e($cap['name']) ?></a>
        <?php endforeach; ?>
        <a href="<?= e(url('/capabilities')) ?>">All capabilities</a>
      </nav>

      <nav class="Foot_Col" aria-label="Services">
        <h3>Services</h3>
        <a href="<?= e(url('/services')) ?>">All services</a>
        <a href="<?= e(url('/services/support-desk')) ?>">Support desk</a>
        <a href="<?= e(url('/training')) ?>">Training pathway</a>
        <a href="<?= e(url('/standards')) ?>">Standards &amp; compliance</a>
        <a href="<?= e(url('/about')) ?>">About EPiTEK</a>
        <a href="<?= e(url('/distributor-integration')) ?>">Logistics hub</a>
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
        <p>Work is delivered under national procurement rules, End-User Certification requirements and applicable dual-use export controls. Technical data is released to verified institutional recipients only. <a href="<?= e(url('/standards')) ?>">Standards &amp; compliance</a>.</p>
      </div>
    </details>

    <div class="Foot_Bot">
      <p class="Foot_Copy">© <?= date('Y') ?> <?= e(SITE_LEGAL) ?> · Nairobi, Kenya · <?= e(SITE_DOMAIN) ?></p>
      <div class="Foot_Links">
        <a href="<?= e(url('/privacy')) ?>">Privacy notice</a>
        <a href="<?= e(url('/cookies')) ?>">Cookies</a>
        <a href="<?= e(url('/terms')) ?>">Terms of use</a>
        <a href="<?= e(url('/security')) ?>">Security</a>
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

<?php require_once __DIR__ . '/bot.php'; epi_render(); ?>

<aside class="Cookie" data-cookie hidden aria-label="Cookie notice">
  <p class="Cookie_T">One cookie, and only for the forms</p>
  <p class="Cookie_B">This site uses a single session cookie so enquiry and application forms cannot be submitted from another site. No analytics, advertising or third-party tracking.</p>
  <div class="Cookie_Row">
    <button class="Cookie_Btn" type="button" data-cookie-ok>Understood</button>
    <a class="Cookie_A" href="<?= e(url('/cookies')) ?>">Cookie notice</a>
  </div>
</aside>

<script src="<?= e(asset('assets/js/main.js')) ?>" defer></script>
<script src="<?= e(asset('assets/js/bot.js')) ?>" defer></script>
</body>
</html>
