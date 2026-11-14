<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'security',
    'nav'         => '',
    'path'        => '/security',
    'title'       => 'Security and vulnerability disclosure | EPiTEK Solutions',
    'description' => 'How this website and the enquiry console are protected, what to do if you find a weakness, and how to report it safely.',
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
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Security</span>
      </nav>
      <span class="Eyebrow">Protecting the site and your enquiry</span>
      <h1>Security</h1>
      <p class="PageHero_Lead">What is in place on this site, what is deliberately not asked for, and the route to report a weakness if you find one.</p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Sec_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">Controls in place</span>
        <h2 id="Sec_T">How the site is protected</h2>

        <h3>Transport and headers</h3>
        <p>The site is served over HTTPS with HSTS once the certificate is live. Responses carry a strict Content Security Policy restricted to this domain, no framing, no MIME sniffing, a referrer policy and no directory listings. There are no third-party scripts or embeds to widen the attack surface.</p>

        <h3>Forms</h3>
        <p>Every form submission is checked against a per-session token to stop cross-site request forgery, validated on the server, rate-limited per address, and screened by a honeypot and a minimum completion time. Enquiry and application data is written with prepared statements only, so submitted text is never treated as SQL.</p>

        <h3>The console</h3>
        <p>Staff access lives at a private path that is excluded from search engines and caches and is not linked from the public site. Sessions use HttpOnly, SameSite cookies over HTTPS, the session id is rotated on sign-in, repeated sign-in failures are throttled, and roles are enforced on the server so a viewer account cannot change anything. Every sign-in, stage change, note, password change and export is written to an audit log. Passwords are stored as bcrypt hashes.</p>

        <h3>Data handling</h3>
        <p>Enquiry and application records are stored in a database that is not publicly readable, and database credentials are kept outside the web root. Access is limited to staff who need it. See the <a href="<?= e(url('/privacy')) ?>">privacy notice</a> for what is collected and how to have it removed.</p>

        <h3>What not to send</h3>
        <p>Do not send classified, controlled or operationally sensitive technical data through the web forms. Secure transfer arrangements and an NDA are agreed before any such exchange.</p>

        <h2 id="Report_T">Reporting a vulnerability</h2>
        <p>If you believe you have found a security weakness, send a short description through the <a href="<?= e(url('/contact')) ?>">contact form</a> with the subject line <strong>SECURITY</strong>, and include the page or feature affected, the steps to reproduce it and what you observed. Please do not include live credentials or personal data in the report.</p>
        <p>Please act in good faith: do not access, change or download data that is not yours, do not run disruptive tests against the live site, and give the team a reasonable opportunity to fix the issue before publishing anything about it. Reports are acknowledged within one working day and handled by the same regional team that runs the site.</p>

        <h2 id="Scope_T">Scope</h2>
        <p>In scope: this website and its enquiry, application and console functions. Out of scope: the hosting provider's platform, third-party networks, social engineering of staff, and physical security.</p>

        <p class="Note">This page describes the website only. Programme documentation, contracts and due-diligence exchanges are governed by the terms agreed for that engagement.</p>

        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/contact')) ?>">Report a concern <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="Btn Btn--ghost" href="<?= e(url('/privacy')) ?>">Privacy notice</a>
        </div>
      </div>

      <div class="Split_Media">
        <div class="AsideCard">
          <h2 class="AsideCard_T">At a glance</h2>
          <ul class="AsideList">
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> HTTPS, HSTS, strict CSP, no third-party scripts.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> CSRF tokens, server-side validation and rate limits on every form.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Private, audited console with server-enforced roles.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> bcrypt password hashes; credentials outside the web root.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Reports acknowledged within one working day.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php';
