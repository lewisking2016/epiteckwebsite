<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'cookies',
    'nav'         => '',
    'path'        => '/cookies',
    'title'       => 'Cookie notice | EPiTEK Solutions',
    'description' => 'Every cookie and local storage entry this website uses, why it exists, how long it lasts and how to remove it.',
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
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Cookie notice</span>
      </nav>
      <span class="Eyebrow">Cookies and local storage</span>
      <h1>Cookie notice</h1>
      <p class="PageHero_Lead">One session cookie, two local preferences, and nothing that follows you around the internet. This page lists every entry so you can check it against your own browser.</p>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Cookies_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">The full list</span>
        <h2 id="Cookies_T">What is stored on your device</h2>

        <div class="Table_Wrap">
          <table class="Table">
            <caption class="VisuallyHidden">Cookies and local storage entries set by this website</caption>
            <thead>
              <tr><th scope="col">Name</th><th scope="col">Type</th><th scope="col">Purpose</th><th scope="col">Lasts</th></tr>
            </thead>
            <tbody>
              <tr>
                <td><code>epx_session</code></td>
                <td>Cookie · strictly necessary</td>
                <td>Holds a security token so the enquiry, application and console forms cannot be submitted from another site, and keeps a signed-in console session.</td>
                <td>Until you close the browser</td>
              </tr>
              <tr>
                <td><code>epx-cookie-notice</code></td>
                <td>Local storage · preference</td>
                <td>Remembers that you dismissed the cookie notice so it does not reappear on every page.</td>
                <td>Until you clear site data</td>
              </tr>
            </tbody>
          </table>
        </div>

        <p class="Note">Local storage stays in your browser. Nothing in it is transmitted to this server.</p>

        <h2 id="No_T">What is not used</h2>
        <p>No analytics, advertising, social or affiliate cookies. No cross-site tracking pixels. No third-party scripts, embeds, fonts or images: fonts are served from this domain, so no request leaves the site when a page loads.</p>

        <h2 id="Why_T">Why there is no consent switch</h2>
        <p>The session cookie is required for a form you deliberately submit and for console sign-in, which places it in the strictly necessary category. Strictly necessary cookies do not require prior consent, and refusing them would only stop the forms from working. The notice is shown for transparency, not to gate the site.</p>

        <h2 id="Remove_T">Removing them</h2>
        <p>Every mainstream browser lets you see and delete cookies and site data for a single site, usually under Settings → Privacy → Cookies and site data. Deleting the session cookie signs you out of the console; deleting local storage brings the notice back once. Blocking cookies entirely will stop the enquiry and application forms from submitting.</p>

        <p class="Note">For what the forms collect and how it is handled, see the <a href="<?= e(url('/privacy')) ?>">privacy notice</a>.</p>

        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/contact')) ?>">Ask a question <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="Btn Btn--ghost" href="<?= e(url('/privacy')) ?>">Privacy notice</a>
        </div>
      </div>

      <div class="Split_Media">
        <div class="AsideCard">
          <h2 class="AsideCard_T">In short</h2>
          <ul class="AsideList">
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> One session cookie, set when you use a form.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Two browser preferences, never sent to the server.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> No analytics, advertising or third-party requests.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Nothing to opt into, because nothing tracks you.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php';
