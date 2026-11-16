<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';

$page = [
    'slug'        => 'training',
    'nav'         => 'services',
    'path'        => '/training',
    'title'       => 'Training pathway | EPiTEK Solutions',
    'description' => 'Four role-based training tracks for air force and civil aviation staff: standards, UAS operator certification, depot maintenance and instructor development.',
    'heroImage'   => 'assets/img/avionics.svg',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url('assets/img/avionics.svg')) ?>" alt="" width="860" height="640" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Training</span>
      </nav>
      <span class="Eyebrow">Training</span>
      <h1><?= e(t('training.h1')) ?></h1>
      <p class="PageHero_Lead"><?= e(t('training.lead')) ?></p>
      <div class="PageHero_Act">
        <a class="Btn Btn--light" href="<?= e(url('/apply')) ?>">Apply for a place <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
        <a class="Btn Btn--ghost-light" href="<?= e(url('/services')) ?>">All services</a>
      </div>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Ladder_T">
  <div class="Wrap">
    <div class="Head">
      <span class="Eyebrow">Progression</span>
      <h2 id="Ladder_T">From fundamentals to instructor</h2>
      <p class="Head_B">Tracks can be taken individually or as a full programme. Simulation covers what is unsafe or expensive to rehearse in the air.</p>
    </div>

    <div class="Ladder">
      <?php foreach ($TRAINING_PATHS as $path): ?>
        <div class="Ladder_Row">
          <span class="Ladder_Ph"><?= e($path['phase']) ?></span>
          <h3 class="Ladder_T"><?= e($path['title']) ?></h3>
          <p class="Ladder_D"><?= e($path['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="Section" aria-labelledby="Del_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow">Delivery</span>
        <h2 id="Del_T">Where the training happens</h2>
        <p>Courses run at the regional depot, at the customer's facility, or in a mixed format: simulation-based practical work first, then on-site on-the-job training.</p>
        <ul class="Split_List">
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Instructor travel and facility requirements agreed in the training plan.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Assessment against a written standard, not attendance.</li>
          <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> Competency records handed to the receiving unit and retained.</li>
        </ul>
        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/apply')) ?>">Apply for a place <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="Btn Btn--ghost" href="<?= e(url('/contact')) ?>">Ask about a cohort</a>
        </div>
      </div>
      <div class="Split_Media">
        <div class="MediaCard">
          <span class="MediaTag">TRAINING · SIMULATION FIRST</span>
          <img src="<?= e(url('assets/img/avionics.svg')) ?>" alt="Training display with a planned route, waypoints and aircraft state" width="860" height="640" loading="lazy" decoding="async">
          <div class="MediaCard_Cap"><span>Assessment per trainee</span><span>RECORDED</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
