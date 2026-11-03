<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
session_start_secure();

/* --------------------------------------------------------------- intake */
$errors = [];
$old = [];
$successRef = null;
$mailSent = false;

$interestOptions = [
    'loitering'   => 'Tactical loitering munitions',
    'recon'       => 'Reconnaissance UAS',
    'focusflite'  => 'FocusFlite avionics software',
    'c4i'         => 'C4I infrastructure',
    'maintenance' => 'Custom maintenance contracts',
];
$countryOptions = ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia', 'Other'];
$envOptions = ['Border / land', 'Maritime / coastal', 'Airbase / aviation', 'Urban / critical infrastructure', 'Training academy', 'Other'];
$timelineOptions = ['Immediate (under 3 months)', '3–6 months', '6–12 months', 'Beyond 12 months', 'Not yet defined'];
$demoOptions = ['virtual' => 'Virtual architecture review', 'in-country' => 'In-country field demonstration'];
$topicMap = [
    'uas' => 'loitering', 'kargu' => 'loitering', 'alpagub' => 'loitering',
    'togan' => 'recon', 'boyga' => 'recon',
    'focusflite' => 'focusflite', 'naval' => 'c4i', 'c4i' => 'c4i',
    'sla' => 'maintenance', 'training' => 'maintenance', 'brief' => 'c4i',
];

$prefTopic = isset($_GET['topic']) ? (string) $_GET['topic'] : '';
$prefInterest = $topicMap[$prefTopic] ?? '';

if (!empty($_SESSION['contact_ref'])) {
    $successRef = (string) $_SESSION['contact_ref'];
    $mailSent = !empty($_SESSION['contact_mailed']);
    unset($_SESSION['contact_ref'], $_SESSION['contact_mailed']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = [
        'name'         => trim((string) ($_POST['name'] ?? '')),
        'title'        => trim((string) ($_POST['title'] ?? '')),
        'organisation' => trim((string) ($_POST['organisation'] ?? '')),
        'email'        => trim((string) ($_POST['email'] ?? '')),
        'phone'        => trim((string) ($_POST['phone'] ?? '')),
        'country'      => trim((string) ($_POST['country'] ?? '')),
        'environment'  => trim((string) ($_POST['environment'] ?? '')),
        'timeline'     => trim((string) ($_POST['timeline'] ?? '')),
        'demo'         => trim((string) ($_POST['demo'] ?? '')),
        'message'      => trim((string) ($_POST['message'] ?? '')),
        'consent'      => isset($_POST['consent']) ? '1' : '',
        'interests'    => (array) ($_POST['interests'] ?? []),
        'company_website' => (string) ($_POST['company_website'] ?? ''),
    ];
    $formTs = (int) ($_POST['form_ts'] ?? 0);
    $old = $raw;

    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $errors['form'] = 'Your session expired. Please review the form and submit again.';
    }
    if ($raw['company_website'] !== '') {
        $errors['form'] = 'The request could not be processed.';
    }
    if ($formTs <= 0 || (time() - $formTs) < 3) {
        $errors['form'] = 'Please take a moment to review the form, then submit again.';
    }
    if ($formTs > 0 && (time() - $formTs) > 21600) {
        $errors['form'] = 'This form session has expired. Please reload the page and try again.';
    }
    if (!rate_limit('contact-form', 5, 3600)) {
        $errors['form'] = 'Too many requests from this connection. Please email ' . SITE_EMAIL . ' directly.';
    }

    $nameLen = mb_strlen($raw['name']);
    if ($nameLen < 2 || $nameLen > 120) {
        $errors['name'] = 'Enter your full name (2–120 characters).';
    }
    if (mb_strlen($raw['title']) > 120) {
        $errors['title'] = 'Keep the official title under 120 characters.';
    }
    $orgLen = mb_strlen($raw['organisation']);
    if ($orgLen < 2 || $orgLen > 160) {
        $errors['organisation'] = 'Enter the verified organisation name.';
    }
    if (!filter_var($raw['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($raw['email']) > 190) {
        $errors['email'] = 'Enter a valid official email address.';
    }
    if ($raw['phone'] !== '' && !preg_match('/^[0-9+()\-.\s]{6,32}$/', $raw['phone'])) {
        $errors['phone'] = 'Enter a valid telephone number.';
    }
    if (!in_array($raw['country'], $countryOptions, true)) {
        $errors['country'] = 'Select the operating country.';
    }
    if ($raw['environment'] !== '' && !in_array($raw['environment'], $envOptions, true)) {
        $errors['environment'] = 'Select a valid operating environment.';
    }
    if ($raw['timeline'] !== '' && !in_array($raw['timeline'], $timelineOptions, true)) {
        $errors['timeline'] = 'Select a valid project timeline.';
    }
    if (!in_array($raw['demo'], array_keys($demoOptions), true)) {
        $errors['demo'] = 'Choose a briefing format.';
    }
    $msgLen = mb_strlen($raw['message']);
    if ($msgLen < 20 || $msgLen > 4000) {
        $errors['message'] = 'Summarise the requirement in 20–4000 characters.';
    }
    $old['interests'] = array_values(array_filter((array) $raw['interests'], static fn ($v) => is_string($v) && array_key_exists($v, $interestOptions)));
    if (!$old['interests']) {
        $errors['interests'] = 'Select at least one technology interest.';
    }
    if ($raw['consent'] !== '1') {
        $errors['consent'] = 'Confirm that the enquiry may be processed.';
    }
    if (preg_match('/[\r\n]/', $raw['name'] . $raw['email'] . $raw['organisation'] . $raw['title'])) {
        $errors['form'] = 'Invalid characters submitted.';
    }

    if (!$errors) {
        $reference = 'EPX-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $record = [
            'reference' => $reference,
            'received_at' => gmdate('c'),
            'name' => $raw['name'],
            'title' => $raw['title'],
            'organisation' => $raw['organisation'],
            'email' => $raw['email'],
            'phone' => $raw['phone'],
            'country' => $raw['country'],
            'interests' => array_map(static fn ($k) => $interestOptions[$k], $old['interests']),
            'environment' => $raw['environment'],
            'timeline' => $raw['timeline'],
            'demo' => $demoOptions[$raw['demo']],
            'message' => $raw['message'],
            'ip_hash' => hash('sha256', client_ip() . '|' . SITE_EMAIL),
        ];

        $dir = dirname(__DIR__) . '/storage/leads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        $line = json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
        @file_put_contents($dir . '/leads-' . date('Ym') . '.jsonl', $line, FILE_APPEND | LOCK_EX);

        $mailed = false;
        if (MAIL_ENABLED) {
            $subject = '[' . $reference . '] Technical demo request — ' . preg_replace('/[^\p{L}\p{N} .\-]/u', '', $raw['organisation']);
            $body = "New enquiry from the EPiTEK website\n\n"
                . 'Reference: ' . $reference . "\n"
                . 'Received: ' . gmdate('Y-m-d H:i') . " UTC\n\n"
                . 'Name: ' . $raw['name'] . "\n"
                . 'Title: ' . $raw['title'] . "\n"
                . 'Organisation: ' . $raw['organisation'] . "\n"
                . 'Email: ' . $raw['email'] . "\n"
                . 'Phone: ' . $raw['phone'] . "\n"
                . 'Country: ' . $raw['country'] . "\n"
                . 'Interests: ' . implode(', ', $record['interests']) . "\n"
                . 'Environment: ' . $raw['environment'] . "\n"
                . 'Timeline: ' . $raw['timeline'] . "\n"
                . 'Briefing: ' . $record['demo'] . "\n\n"
                . "Requirement summary:\n" . $raw['message'] . "\n";
            $headers = [
                'From: EPiTEK Website <' . MAIL_FROM . '>',
                'Reply-To: ' . $raw['email'],
                'Content-Type: text/plain; charset=UTF-8',
                'X-Request-ID: ' . $reference,
            ];
            $mailed = @mail(MAIL_TO, $subject, $body, implode("\r\n", $headers));
        }

        $_SESSION['contact_ref'] = $reference;
        $_SESSION['contact_mailed'] = $mailed;
        header('Location: ' . url('/contact?sent=1#form'), true, 303);
        exit;
    }

    http_response_code(422);
}

$page = [
    'slug'        => 'contact',
    'nav'         => 'contact',
    'path'        => '/contact',
    'title'       => 'Contact & Technical Demo Request | EPiTEK Solutions',
    'description' => 'Request a technical demo, tactical data sheet or SLA consultation from EPiTEK Solutions, East Africa defence and aviation technology integrator.',
    'heroImage'   => 'assets/img/hero-terrain.svg',
    'hideCta'     => true,
    'bodyClass'   => 'Page--contact',
    'schema' => [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact EPiTEK Solutions',
        'url' => SITE_URL . '/contact',
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => SITE_NAME,
            'email' => SITE_EMAIL,
            'url' => SITE_URL . '/',
            'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
        ],
    ],
];

$val = static function (string $key, string $default = '') use ($old): string {
    return isset($old[$key]) ? (string) $old[$key] : $default;
};
$inList = static function (string $key, array $options) use ($old): bool {
    return isset($old[$key]) && in_array($old[$key], $options, true);
};

require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/hero-terrain.svg')) ?>" alt="" width="1600" height="1100" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Contact</span>
      </nav>
      <span class="Eyebrow">Secure enterprise enquiry</span>
      <h1>Start a technical conversation</h1>
      <p class="PageHero_Lead">Tell us the operating problem, the environment and the timeline. A regional technical lead will respond with the right data sheet, briefing or SLA scope — not a generic brochure.</p>
      <div class="PageHero_Meta">
        <span><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Response target: one working day</span>
        <span><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Eligibility screening applies</span>
      </div>
    </div>
  </div>
</section>

<section class="Section" id="form" aria-labelledby="Form_T">
  <div class="Wrap">
    <div class="ContactLayout">
      <div>
        <?php if ($successRef): ?>
          <div class="FormCard">
            <div class="Alert Alert--ok" role="status">
              <svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg>
              <div>
                <strong>Request recorded — reference <?= e($successRef) ?>.</strong><br>
                <?= $mailSent
                    ? 'The regional team has been notified by email. Keep this reference for follow-up.'
                    : 'The request is in the secure intake log. Email ' . e(SITE_EMAIL) . ' with this reference to reach the team directly.' ?>
              </div>
            </div>
            <div class="AsideCard AsideCard--spaced">
              <h2 class="AsideCard_T">What happens next</h2>
              <div class="SLASteps">
                <div class="SLAStep"><span class="SLAStep_N">01</span><span class="SLAStep_T">Eligibility check<small>Organisation and end-use screening</small></span><span class="SLAStep_V">Day 1</span></div>
                <div class="SLAStep"><span class="SLAStep_N">02</span><span class="SLAStep_T">Technical contact<small>A lead confirms scope and release conditions</small></span><span class="SLAStep_V">Day 1–2</span></div>
                <div class="SLAStep"><span class="SLAStep_N">03</span><span class="SLAStep_T">Briefing or data sheet<small>Virtual review or in-country demonstration</small></span><span class="SLAStep_V">Agreed</span></div>
              </div>
              <p class="Note">Do not send classified information through this form. Secure transfer arrangements are agreed before any sensitive exchange.</p>
              <a class="Btn Btn--ghost" href="<?= e(url('/')) ?>">Return to the homepage</a>
            </div>
          </div>
        <?php else: ?>
          <div class="FormCard">
            <?php if (isset($errors['form'])): ?>
              <div class="Alert Alert--err" role="alert">
                <svg class="Ico" aria-hidden="true"><use href="#i-shield"/></svg>
                <div><?= e($errors['form']) ?></div>
              </div>
            <?php elseif ($errors): ?>
              <div class="Alert Alert--err" role="alert">
                <svg class="Ico" aria-hidden="true"><use href="#i-shield"/></svg>
                <div>
                  <strong>Please correct <?= count($errors) ?> item<?= count($errors) === 1 ? '' : 's' ?> below.</strong>
                </div>
              </div>
            <?php endif; ?>

            <form class="Form" method="post" action="<?= e(url('/contact')) ?>#form" data-validate novalidate>
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="form_ts" value="<?= time() ?>">
              <div class="Hp" aria-hidden="true">
                <label for="company_website">Company website</label>
                <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
              </div>

              <fieldset class="Form_Grid Fieldset">
                <legend class="Eyebrow Legend">01 · Identity</legend>
                <div class="Field<?= isset($errors['name']) ? ' is-invalid' : '' ?>">
                  <label class="Label" for="name">Full name <span class="Req">*</span></label>
                  <input class="Input" id="name" name="name" type="text" autocomplete="name" required minlength="2" maxlength="120" value="<?= e($val('name')) ?>"<?= isset($errors['name']) ? ' aria-invalid="true" aria-describedby="err-name"' : '' ?>>
                  <?php if (isset($errors['name'])): ?><span class="Err" id="err-name"><?= e($errors['name']) ?></span><?php endif; ?>
                </div>
                <div class="Field<?= isset($errors['title']) ? ' is-invalid' : '' ?>">
                  <label class="Label" for="title">Official title <span class="Opt">optional</span></label>
                  <input class="Input" id="title" name="title" type="text" autocomplete="organization-title" maxlength="120" value="<?= e($val('title')) ?>">
                  <?php if (isset($errors['title'])): ?><span class="Err"><?= e($errors['title']) ?></span><?php endif; ?>
                </div>
                <div class="Field Field--full<?= isset($errors['organisation']) ? ' is-invalid' : '' ?>">
                  <label class="Label" for="organisation">Verified organisation <span class="Req">*</span></label>
                  <input class="Input" id="organisation" name="organisation" type="text" autocomplete="organization" required minlength="2" maxlength="160" value="<?= e($val('organisation')) ?>" aria-describedby="hint-org">
                  <span class="Hint" id="hint-org">Ministry, air force, civil aviation authority, border service or registered company.</span>
                  <?php if (isset($errors['organisation'])): ?><span class="Err"><?= e($errors['organisation']) ?></span><?php endif; ?>
                </div>
                <div class="Field<?= isset($errors['email']) ? ' is-invalid' : '' ?>">
                  <label class="Label" for="email">Official email <span class="Req">*</span></label>
                  <input class="Input" id="email" name="email" type="email" autocomplete="email" required maxlength="190" value="<?= e($val('email')) ?>" inputmode="email">
                  <?php if (isset($errors['email'])): ?><span class="Err"><?= e($errors['email']) ?></span><?php endif; ?>
                </div>
                <div class="Field<?= isset($errors['phone']) ? ' is-invalid' : '' ?>">
                  <label class="Label" for="phone">Telephone <span class="Opt">optional</span></label>
                  <input class="Input" id="phone" name="phone" type="tel" autocomplete="tel" maxlength="32" value="<?= e($val('phone')) ?>" inputmode="tel">
                  <?php if (isset($errors['phone'])): ?><span class="Err"><?= e($errors['phone']) ?></span><?php endif; ?>
                </div>
                <div class="Field<?= isset($errors['country']) ? ' is-invalid' : '' ?>">
                  <label class="Label" for="country">Operating country <span class="Req">*</span></label>
                  <select class="Select" id="country" name="country" required>
                    <option value="">Select a country</option>
                    <?php foreach ($countryOptions as $country): ?>
                      <option value="<?= e($country) ?>"<?= $val('country') === $country ? ' selected' : '' ?>><?= e($country) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (isset($errors['country'])): ?><span class="Err"><?= e($errors['country']) ?></span><?php endif; ?>
                </div>
              </fieldset>

              <fieldset class="Field Fieldset"<?= isset($errors['interests']) ? ' aria-describedby="err-interests"' : '' ?>>
                <legend class="Eyebrow Legend Legend--wide">02 · Technology interest <span class="Req">*</span></legend>
                <div class="Checks">
                  <?php foreach ($interestOptions as $key => $label): ?>
                    <label class="Check">
                      <input type="checkbox" name="interests[]" value="<?= e($key) ?>"<?= in_array($key, (array) ($old['interests'] ?? []), true) || (!$old && $prefInterest === $key) ? ' checked' : '' ?>>
                      <span><?= e($label) ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
                <?php if (isset($errors['interests'])): ?><span class="Err" id="err-interests"><?= e($errors['interests']) ?></span><?php endif; ?>
              </fieldset>

              <fieldset class="Form_Grid Fieldset">
                <legend class="Eyebrow Legend">03 · Project context</legend>
                <div class="Field">
                  <label class="Label" for="environment">Operating environment</label>
                  <select class="Select" id="environment" name="environment">
                    <option value="">Select environment</option>
                    <?php foreach ($envOptions as $env): ?>
                      <option value="<?= e($env) ?>"<?= $val('environment') === $env ? ' selected' : '' ?>><?= e($env) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (isset($errors['environment'])): ?><span class="Err"><?= e($errors['environment']) ?></span><?php endif; ?>
                </div>
                <div class="Field">
                  <label class="Label" for="timeline">Project timeline</label>
                  <select class="Select" id="timeline" name="timeline">
                    <option value="">Select timeline</option>
                    <?php foreach ($timelineOptions as $timeline): ?>
                      <option value="<?= e($timeline) ?>"<?= $val('timeline') === $timeline ? ' selected' : '' ?>><?= e($timeline) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (isset($errors['timeline'])): ?><span class="Err"><?= e($errors['timeline']) ?></span><?php endif; ?>
                </div>
                <div class="Field Field--full<?= isset($errors['demo']) ? ' is-invalid' : '' ?>">
                  <span class="Label" id="demo-label">Briefing format <span class="Req">*</span></span>
                  <div class="Checks" role="radiogroup" aria-labelledby="demo-label">
                    <?php foreach ($demoOptions as $key => $label): ?>
                      <label class="Check">
                        <input type="radio" name="demo" value="<?= e($key) ?>" required<?= $val('demo', 'virtual') === $key ? ' checked' : '' ?>>
                        <span><?= e($label) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                  <?php if (isset($errors['demo'])): ?><span class="Err"><?= e($errors['demo']) ?></span><?php endif; ?>
                </div>
                <div class="Field Field--full<?= isset($errors['message']) ? ' is-invalid' : '' ?>">
                  <label class="Label" for="message">Requirement summary <span class="Req">*</span></label>
                  <textarea class="Textarea" id="message" name="message" required minlength="20" maxlength="4000" rows="5" aria-describedby="hint-msg"><?= e($val('message')) ?></textarea>
                  <span class="Hint" id="hint-msg">Operational problem, existing systems, site constraints and what a successful outcome looks like. Do not include classified information.</span>
                  <?php if (isset($errors['message'])): ?><span class="Err"><?= e($errors['message']) ?></span><?php endif; ?>
                </div>
              </fieldset>

              <div class="Form_Foot">
                <label class="Check Check_Terms<?= isset($errors['consent']) ? ' is-invalid' : '' ?>">
                  <input type="checkbox" name="consent" value="1" required<?= $val('consent') === '1' ? ' checked' : '' ?>>
                  <span>I confirm this is an official enquiry and agree that the details above may be used to respond. I will not send classified information through this form.</span>
                </label>
                <?php if (isset($errors['consent'])): ?><span class="Err"><?= e($errors['consent']) ?></span><?php endif; ?>
                <div class="Btn_Row">
                  <button class="Btn Btn--primary Btn--lg" type="submit">Submit request <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></button>
                  <a class="Btn Btn--ghost" href="mailto:<?= e(SITE_EMAIL) ?>">Email directly instead</a>
                </div>
                <p class="Form_Note">Your details are used only to answer this enquiry. Technical data sheets and demonstrations are released after eligibility and export-control screening.</p>
              </div>
            </form>
          </div>
        <?php endif; ?>
      </div>

      <aside class="FormAside" aria-label="Contact details and intake information">
        <div class="AsideCard">
          <h2 class="AsideCard_T">Direct contact</h2>
          <p><strong>Primary enquiries</strong><br><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></p>
          <p><strong>Regional headquarters</strong><br>Nairobi, Kenya<br><span class="Muted">Street address and map coordinates provided on request.</span></p>
          <p><strong>Technical support</strong><br>Routed through the support desk after agreement. See <a href="<?= e(url('/services#support-desk')) ?>">SLA tiers</a>.</p>
          <a class="Foot_Soc Foot_Soc--accent" href="<?= e(SITE_LINKEDIN) ?>" rel="noopener" target="_blank">
            <svg class="Ico" aria-hidden="true"><use href="#i-linkedin"/></svg> Follow on LinkedIn
          </a>
        </div>

        <div class="AsideCard">
          <h2 class="AsideCard_T">Intake protocol</h2>
          <ul class="AsideList">
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Enquiries are screened for organisation and end use.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Controlled data is shared only after authorisation.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Secure email and transfer protocols are agreed before sensitive exchange.</li>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> This form is not a procurement submission or a legal offer.</li>
          </ul>
        </div>

        <div class="AsideCard">
          <h2 class="AsideCard_T">Regional nodes</h2>
          <dl class="Directory">
            <div><dt>Nairobi</dt><dd>Coordination</dd></div>
            <div><dt>Kampala</dt><dd>Engineering</dd></div>
            <div><dt>Dodoma</dt><dd>Field ops</dd></div>
            <div><dt>Kigali</dt><dd>Training</dd></div>
            <div><dt>Addis Ababa</dt><dd>Liaison</dd></div>
          </dl>
          <p class="Note">Node addresses and named contacts are provided to qualified buyers during onboarding.</p>
        </div>
      </aside>
    </div>
  </div>
</section>

<section class="Section Section--alt" id="compliance" aria-labelledby="Compliance_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Procurement compliance</span>
      <h2 id="Compliance_T">Sovereign acquisition, export control and secure communication</h2>
      <p class="Head_B">Written for procurement and legal reviewers who need to know how an enquiry becomes a contract.</p>
    </div>
    <div class="Grid Grid--3">
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Acquisition rules</span>
          <h3 class="Card_T">National frameworks first</h3>
          <p class="Card_D">Engagements follow the buyer's sovereign procurement rules, including tender, due-diligence and end-user requirements. EPiTEK participates as a registered supplier where required.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Export control</span>
          <h3 class="Card_T">Dual-use authorisations</h3>
          <p class="Card_D">Military and dual-use items are screened against supplier-country and national regimes. Where a licence is required, it is planned into the delivery timeline.</p>
        </div>
      </article>
      <article class="Card Card--flat Reveal">
        <div class="Card_B">
          <span class="Card_K">Communications</span>
          <h3 class="Card_T">Secure exchange protocol</h3>
          <p class="Card_D">Sensitive documents are exchanged through agreed secure channels, not public web forms or ordinary email. Classified material requires a separate, formally agreed handling arrangement.</p>
        </div>
      </article>
    </div>
    <p class="Note Note--spaced">Statements of partnership, authorisation or certification on this website are for orientation and are supported by documentation during due diligence. This website does not constitute an offer, a licence or an authorisation to export controlled goods or technical data.</p>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
