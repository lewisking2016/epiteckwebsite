<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'privacy',
    'nav'         => '',
    'path'        => '/privacy',
    'title'       => 'Privacy notice | EPiTEK Solutions',
    'description' => 'How EPiTEK Solutions handles the information submitted through this website, what is stored, why it is stored and how to request its removal.',
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
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Privacy notice</span>
      </nav>
      <span class="Eyebrow">Website information handling</span>
      <h1>Privacy notice</h1>
      <p class="PageHero_Lead">What this website collects, where it is kept, and how to have it removed. Written plainly, because it is a statement of practice rather than a legal brochure.</p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Privacy_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">What is collected</span>
        <h2 id="Privacy_T">Only what an enquiry requires</h2>
        <p>The enquiry form asks for your name, official title, organisation, official email address, telephone number, operating country, technology interest, operating environment, project timeline and a description of the requirement. Submitting the form is the only way this website collects personal information.</p>
        <p>Technical information about your visit — pages requested, timestamps, browser type and a shortened network address — is written to the web server's standard access log by the hosting platform. It is used for security and to diagnose faults.</p>

        <h2 id="Cookies_T">Cookies</h2>
        <p>This site does not use analytics, advertising or third-party tracking cookies, and loads no external fonts, scripts or images. A single session cookie is set when you open the enquiry form; it holds a security token and expires when you close your browser.</p>

        <h2 id="Use_T">How it is used</h2>
        <p>Enquiry details are used to answer the enquiry, to carry out the eligibility and end-use checks described on the contact page, and to keep a record of the correspondence. They are not sold, rented or shared for marketing purposes.</p>

        <h2 id="Retention_T">Retention and access</h2>
        <p>Enquiries are kept for as long as needed to answer them and to meet contractual, export-control or legal record-keeping obligations, then deleted. You can ask what is held about you, ask for a correction, or ask for the record to be deleted, by writing through the contact page and quoting your reference number.</p>

        <h2 id="Security_T">Security</h2>
        <p>The form is served over HTTPS, protected against cross-site request forgery and rate-limited. Do not send classified, controlled or sensitive technical data through the form; secure transfer arrangements are agreed separately before any such exchange.</p>

        <p class="Note">This notice describes the website only. Programme documentation, contracts and due-diligence exchanges are governed by the terms agreed for that engagement.</p>

        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/contact')) ?>">Contact the team <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        </div>
      </div>

      <div class="Split_Media">
        <div class="AsideCard">
          <h2 class="AsideCard_T">At a glance</h2>
          <ul class="AsideList">
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> No analytics, advertising or tracking cookies.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> No third-party scripts, fonts or embeds.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> One session cookie, set only for the enquiry form.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Enquiry data used to answer the enquiry and meet record-keeping duties.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Access, correction and deletion on request.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php';
