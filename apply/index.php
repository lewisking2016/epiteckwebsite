<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/pipeline.php';
session_start_secure();

$errors = [];
$old = [];
$successRef = null;

if (!empty($_SESSION['apply_ref'])) {
    $successRef = (string) $_SESSION['apply_ref'];
    unset($_SESSION['apply_ref']);
}

$countryOptions = ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia', 'Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = [
        'type'         => (string) ($_POST['type'] ?? 'training'),
        'name'         => trim((string) ($_POST['name'] ?? '')),
        'email'        => trim((string) ($_POST['email'] ?? '')),
        'phone'        => trim((string) ($_POST['phone'] ?? '')),
        'country'      => trim((string) ($_POST['country'] ?? '')),
        'organisation' => trim((string) ($_POST['organisation'] ?? '')),
        'role_title'   => trim((string) ($_POST['role_title'] ?? '')),
        'programme'    => trim((string) ($_POST['programme'] ?? '')),
        'start_window' => trim((string) ($_POST['start_window'] ?? '')),
        'link_url'     => trim((string) ($_POST['link_url'] ?? '')),
        'message'      => trim((string) ($_POST['message'] ?? '')),
        'consent'      => isset($_POST['consent']) ? '1' : '',
        'company_website' => (string) ($_POST['company_website'] ?? ''),
    ];
    $formTs = (int) ($_POST['form_ts'] ?? 0);
    $old = $raw;

    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $errors['form'] = 'Your session expired. Review the form and submit again.';
    }
    if ($raw['company_website'] !== '') {
        $errors['form'] = 'The application could not be processed.';
    }
    if ($formTs <= 0 || (time() - $formTs) < 3) {
        $errors['form'] = 'Please take a moment to review the form, then submit again.';
    }
    if (!rate_limit('apply-form', 5, 3600)) {
        $errors['form'] = 'Too many submissions from this connection. Please try again later.';
    }
    if (!isset(APPLICATION_TYPES[$raw['type']])) {
        $errors['type'] = 'Choose what you are applying for.';
    }
    if (mb_strlen($raw['name']) < 2 || mb_strlen($raw['name']) > 120) {
        $errors['name'] = 'Enter your full name.';
    }
    if (!filter_var($raw['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($raw['email']) > 190) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if ($raw['phone'] !== '' && !preg_match('/^[0-9+()\-.\\s]{6,32}$/', $raw['phone'])) {
        $errors['phone'] = 'Enter a valid telephone number.';
    }
    if (!in_array($raw['country'], $countryOptions, true)) {
        $errors['country'] = 'Select your country.';
    }
    if (mb_strlen($raw['organisation']) > 160) {
        $errors['organisation'] = 'Keep the organisation under 160 characters.';
    }
    if ($raw['type'] === 'organisation' && mb_strlen($raw['organisation']) < 2) {
        $errors['organisation'] = 'Name the organisation requesting the briefing.';
    }
    if ($raw['type'] === 'training') {
        if (!in_array($raw['programme'], TRAINING_PROGRAMMES, true)) {
            $errors['programme'] = 'Choose the programme you are applying for.';
        }
        if ($raw['start_window'] !== '' && !in_array($raw['start_window'], START_WINDOWS, true)) {
            $errors['start_window'] = 'Choose a valid start window.';
        }
        if ($raw['link_url'] !== '' && !filter_var($raw['link_url'], FILTER_VALIDATE_URL)) {
            $errors['link_url'] = 'Enter a full link (https://…).';
        }
    }
    if (mb_strlen($raw['message']) < 20 || mb_strlen($raw['message']) > 4000) {
        $errors['message'] = 'Summarise your background or requirement in 20–4000 characters.';
    }
    if ($raw['consent'] !== '1') {
        $errors['consent'] = 'Confirm that the application may be processed.';
    }
    if (preg_match('/[\r\n]/', $raw['name'] . $raw['email'] . $raw['organisation'] . $raw['role_title'])) {
        $errors['form'] = 'Invalid characters submitted.';
    }

    if (!$errors) {
        $reference = new_reference($raw['type'] === 'organisation' ? 'ORG' : 'APP');
        try {
            db_exec(
                'INSERT INTO applications (reference, type, name, email, phone, organisation, role_title, country, programme, start_window, link_url, message, status, source, ip_hash, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $reference,
                    $raw['type'],
                    $raw['name'],
                    $raw['email'],
                    $raw['phone'],
                    $raw['organisation'],
                    $raw['role_title'],
                    $raw['country'],
                    $raw['type'] === 'training' ? $raw['programme'] : '',
                    $raw['type'] === 'training' ? $raw['start_window'] : '',
                    $raw['type'] === 'training' ? $raw['link_url'] : '',
                    $raw['message'],
                    'received',
                    'apply-form',
                    hash('sha256', client_ip() . '|' . SITE_NAME),
                    gmdate('Y-m-d H:i:s'),
                ]
            );
            $_SESSION['apply_ref'] = $reference;
            header('Location: ' . url('/apply?sent=1#form'), true, 303);
            exit;
        } catch (Throwable $e) {
            $errors['form'] = 'The application could not be saved. Please try again, or contact the team through the contact page.';
        }
    }

    http_response_code(422);
}

$page = [
    'slug'        => 'apply',
    'nav'         => '',
    'path'        => '/apply',
    'title'       => 'Apply for training or request a briefing | EPiTEK Solutions',
    'description' => 'Apply for aviation and UAS training with EPiTEK Solutions in East Africa, or ask for a capability briefing for your organisation.',
    'heroImage'   => 'assets/img/avionics.svg',
    'hideCta'     => true,
];

$val = static function (string $key, string $default = '') use ($old): string {
    return isset($old[$key]) ? (string) $old[$key] : $default;
};
$selectedType = $val('type', 'training');

require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/avionics.svg')) ?>" alt="" width="860" height="640" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Apply</span>
      </nav>
      <span class="Eyebrow">Training &amp; briefings</span>
      <h1>Apply for a place, or request a briefing</h1>
      <p class="PageHero_Lead">One form for two things: an individual applying for a training track, and an organisation asking for a capability briefing or proposal. Applications are screened before a place or a date is confirmed.</p>
    </div>
  </div>
</section>

<section class="Section" id="form" aria-labelledby="Apply_T">
  <div class="Wrap">
    <?php if ($successRef !== null): ?>
      <div class="Alert Alert--ok" role="status">
        <svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg>
        <div>
          <strong>Application received — reference <?= e($successRef) ?>.</strong><br>
          Keep this reference. Screening comes first; a technical lead will come back to you with the next step.
        </div>
      </div>
      <div class="Btn_Row">
        <a class="Btn Btn--primary" href="<?= e(url('/services')) ?>">Training &amp; services <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="Btn Btn--ghost" href="<?= e(url('/')) ?>">Back to the homepage</a>
      </div>
    <?php else: ?>
      <div class="Head">
        <span class="Eyebrow">Application</span>
        <h2 id="Apply_T">Two tracks, one intake</h2>
        <p class="Head_B">Choose the training track if you are applying as an individual or a nominated operator. Choose the organisation track if you are requesting a briefing, a demonstration or a proposal.</p>
      </div>

      <?php if (isset($errors['form'])): ?>
        <div class="Alert Alert--err" role="alert">
          <svg class="Ico" aria-hidden="true"><use href="#i-shield"/></svg>
          <div><?= e($errors['form']) ?></div>
        </div>
      <?php elseif ($errors): ?>
        <div class="Alert Alert--err" role="alert">
          <svg class="Ico" aria-hidden="true"><use href="#i-shield"/></svg>
          <div><strong>Please correct <?= count($errors) ?> item<?= count($errors) === 1 ? '' : 's' ?> below.</strong></div>
        </div>
      <?php endif; ?>

      <form class="Form" method="post" action="<?= e(url('/apply')) ?>#form">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="form_ts" value="<?= time() ?>">
        <div class="Hp" aria-hidden="true">
          <label for="company_website">Company website</label>
          <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
        </div>

        <fieldset class="Fieldset">
          <legend class="Eyebrow Legend">01 · Track</legend>
          <div class="Checks">
            <?php foreach (APPLICATION_TYPES as $key => $label): ?>
              <label class="Check">
                <input type="radio" name="type" value="<?= e($key) ?>" required<?= $selectedType === $key ? ' checked' : '' ?>>
                <span><?= e($label) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
          <?php if (isset($errors['type'])): ?><span class="Err"><?= e($errors['type']) ?></span><?php endif; ?>
        </fieldset>

        <fieldset class="Form_Grid Fieldset">
          <legend class="Eyebrow Legend">02 · Applicant</legend>
          <div class="Field<?= isset($errors['name']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="name">Full name <span class="Req">*</span></label>
            <input class="Input" id="name" name="name" type="text" autocomplete="name" required minlength="2" maxlength="120" value="<?= e($val('name')) ?>">
            <?php if (isset($errors['name'])): ?><span class="Err"><?= e($errors['name']) ?></span><?php endif; ?>
          </div>
          <div class="Field<?= isset($errors['email']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="email">Email <span class="Req">*</span></label>
            <input class="Input" id="email" name="email" type="email" autocomplete="email" required maxlength="190" value="<?= e($val('email')) ?>">
            <?php if (isset($errors['email'])): ?><span class="Err"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
          <div class="Field<?= isset($errors['phone']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="phone">Telephone <span class="Opt">optional</span></label>
            <input class="Input" id="phone" name="phone" type="tel" autocomplete="tel" maxlength="32" value="<?= e($val('phone')) ?>">
            <?php if (isset($errors['phone'])): ?><span class="Err"><?= e($errors['phone']) ?></span><?php endif; ?>
          </div>
          <div class="Field<?= isset($errors['country']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="country">Country <span class="Req">*</span></label>
            <select class="Select" id="country" name="country" required>
              <option value="">Select a country</option>
              <?php foreach ($countryOptions as $country): ?>
                <option value="<?= e($country) ?>"<?= $val('country') === $country ? ' selected' : '' ?>><?= e($country) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['country'])): ?><span class="Err"><?= e($errors['country']) ?></span><?php endif; ?>
          </div>
          <div class="Field<?= isset($errors['organisation']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="organisation">Organisation <span class="Opt">optional for individuals</span></label>
            <input class="Input" id="organisation" name="organisation" type="text" autocomplete="organization" maxlength="160" value="<?= e($val('organisation')) ?>">
            <?php if (isset($errors['organisation'])): ?><span class="Err"><?= e($errors['organisation']) ?></span><?php endif; ?>
          </div>
          <div class="Field">
            <label class="Label" for="role_title">Role or rank <span class="Opt">optional</span></label>
            <input class="Input" id="role_title" name="role_title" type="text" autocomplete="organization-title" maxlength="120" value="<?= e($val('role_title')) ?>">
          </div>
        </fieldset>

        <fieldset class="Form_Grid Fieldset">
          <legend class="Eyebrow Legend">03 · Details</legend>
          <div class="Field<?= isset($errors['programme']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="programme">Training programme</label>
            <select class="Select" id="programme" name="programme">
              <option value="">Select a programme</option>
              <?php foreach (TRAINING_PROGRAMMES as $programme): ?>
                <option value="<?= e($programme) ?>"<?= $val('programme') === $programme ? ' selected' : '' ?>><?= e($programme) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['programme'])): ?><span class="Err"><?= e($errors['programme']) ?></span><?php endif; ?>
          </div>
          <div class="Field<?= isset($errors['start_window']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="start_window">Preferred start</label>
            <select class="Select" id="start_window" name="start_window">
              <option value="">No preference</option>
              <?php foreach (START_WINDOWS as $window): ?>
                <option value="<?= e($window) ?>"<?= $val('start_window') === $window ? ' selected' : '' ?>><?= e($window) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['start_window'])): ?><span class="Err"><?= e($errors['start_window']) ?></span><?php endif; ?>
          </div>
          <div class="Field Field--full<?= isset($errors['link_url']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="link_url">CV or portfolio link <span class="Opt">optional</span></label>
            <input class="Input" id="link_url" name="link_url" type="url" maxlength="255" placeholder="https://" value="<?= e($val('link_url')) ?>">
            <span class="Hint">A hosted CV or profile link. Do not link to controlled or classified material.</span>
            <?php if (isset($errors['link_url'])): ?><span class="Err"><?= e($errors['link_url']) ?></span><?php endif; ?>
          </div>
          <div class="Field Field--full<?= isset($errors['message']) ? ' is-invalid' : '' ?>">
            <label class="Label" for="message">Background or requirement <span class="Req">*</span></label>
            <textarea class="Textarea" id="message" name="message" required minlength="20" maxlength="4000" rows="6" aria-describedby="hint-apply"><?= e($val('message')) ?></textarea>
            <span class="Hint" id="hint-apply">Applicants: current role, experience and what you want to qualify on. Organisations: the operational problem, existing systems and timeline.</span>
            <?php if (isset($errors['message'])): ?><span class="Err"><?= e($errors['message']) ?></span><?php endif; ?>
          </div>
        </fieldset>

        <div class="Form_Foot">
          <label class="Check Check_Terms<?= isset($errors['consent']) ? ' is-invalid' : '' ?>">
            <input type="checkbox" name="consent" value="1" required<?= $val('consent') === '1' ? ' checked' : '' ?>>
            <span>I confirm the details are accurate and may be used to assess this application or request. I will not send classified information through this form.</span>
          </label>
          <?php if (isset($errors['consent'])): ?><span class="Err"><?= e($errors['consent']) ?></span><?php endif; ?>
          <div class="Btn_Row">
            <button class="Btn Btn--primary Btn--lg" type="submit">Submit application <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></button>
            <a class="Btn Btn--ghost" href="<?= e(url('/contact')) ?>">Ask a question instead</a>
          </div>
          <p class="Form_Note">Applications are screened for eligibility before a place or briefing date is confirmed. Details are handled as described in the <a href="<?= e(url('/privacy')) ?>">privacy notice</a>.</p>
        </div>
      </form>
    <?php endif; ?>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
