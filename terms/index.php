<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'terms',
    'nav'         => '',
    'path'        => '/terms',
    'title'       => 'Website terms of use | EPiTEK Solutions',
    'description' => 'The terms that apply to using this website: what the content is, what it is not, acceptable use, trademarks, export control and liability.',
    'hideCta'     => true,
];

require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/hero-terrain.svg')) ?>" alt="" width="1600" height="1100" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Terms of use</span>
      </nav>
      <span class="Eyebrow">Using this website</span>
      <h1>Terms of use</h1>
      <p class="PageHero_Lead">This site describes what EPiTEK Solutions does and how to reach the team. It is information, not an offer, a quotation or a contract.</p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Terms_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">The essentials</span>
        <h2 id="Terms_T">Terms and conditions of use</h2>

        <h3>1. Acceptance</h3>
        <p>By using this website you accept these terms. If you do not accept them, please do not use the site. They apply to every visitor, whether you are browsing, submitting an enquiry, applying for training or signing in to the console.</p>

        <h3>2. Information only</h3>
        <p>Content here is general information about EPiTEK Solutions, the STM portfolio it supports and the services it delivers in East Africa. It is not technical advice, a specification, an invitation to tender or a binding offer. Programme scope, pricing, delivery dates and support levels are agreed only in a signed contract or purchase order.</p>

        <h3>3. Accuracy and change</h3>
        <p>Descriptions of programmes, markets and services are kept as current as is practical, but capability, availability and third-party portfolio details can change without notice. No warranty is given that every statement is complete or up to date at the moment you read it.</p>

        <h3>4. Acceptable use</h3>
        <p>You agree not to submit false details, to use the forms for unsolicited marketing, to attempt to access accounts or data that are not yours, to probe or disrupt the service, or to copy content for commercial use without written permission. Automated scraping of the console or the forms is not permitted.</p>

        <h3>5. Intellectual property</h3>
        <p>The EPiTEK name, logo and the design and copy of this site belong to EPiTEK Solutions. Product and company names belonging to STM or other partners remain the property of their owners and are used descriptively to identify the portfolio and the working relationship. No licence is granted by viewing this site.</p>

        <h3>6. Export control and eligibility</h3>
        <p>Some of the technology described is dual-use or defence-related. Nothing on this website is an offer to supply controlled items. Engagements are subject to national procurement law, end-user certification, sanctions screening and applicable export control regimes, and EPiTEK may decline an enquiry that does not satisfy those checks.</p>

        <h3>7. External links</h3>
        <p>Links to third-party sites are provided for context. EPiTEK does not control those sites and is not responsible for their content, availability or privacy practices.</p>

        <h3>8. Availability</h3>
        <p>The site is provided as-is. Access may be interrupted for maintenance, security work or reasons outside our control. There is no guarantee of uninterrupted availability.</p>

        <h3>9. Liability</h3>
        <p>To the extent permitted by law, EPiTEK Solutions is not liable for indirect or consequential loss arising from use of this website or reliance on its content. Nothing in these terms limits liability that cannot lawfully be limited.</p>

        <h3>10. Governing law</h3>
        <p>These terms are governed by the laws of Kenya, and the courts of Kenya have jurisdiction over any dispute connected with the website.</p>

        <h3>11. Changes</h3>
        <p>These terms may be updated as the site changes. The version published here is the one that applies.</p>

        <p class="Note">Questions about these terms can be sent through the <a href="<?= e(url('/contact')) ?>">contact form</a>. See also the <a href="<?= e(url('/privacy')) ?>">privacy notice</a> and the <a href="<?= e(url('/cookies')) ?>">cookie notice</a>.</p>

        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/contact')) ?>">Contact the team <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </div>

      <div class="Split_Media">
        <div class="AsideCard">
          <h2 class="AsideCard_T">At a glance</h2>
          <ul class="AsideList">
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Information, not an offer or quotation.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Scope and pricing only by signed agreement.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Supply subject to export control and eligibility checks.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Governed by Kenyan law.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php';
