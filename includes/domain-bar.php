<?php
/**
 * Persistent operational-domain switcher.
 * On pages with filterable content it filters in place; elsewhere the same
 * controls link to the corresponding home sections.
 */
$barFilter = !empty($page['filterDomains']);
?>
<div class="DomainBar" data-domain-bar>
  <div class="Wrap DomainBar_In">
    <span class="DomainBar_L" id="DomainBar_Label">
      <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-globe"/></svg>
      <span class="DomainBar_LTxt">Operational domain</span>
    </span>
    <?php if ($barFilter): ?>
      <div class="DomainBar_C" role="group" aria-labelledby="DomainBar_Label" data-domain-controls>
        <button class="DChip is-on" type="button" data-domain="all" aria-pressed="true">All</button>
        <?php foreach ($DOMAINS as $key => $domain): ?>
          <button class="DChip" type="button" data-domain="<?= e($key) ?>" aria-pressed="false"><?= e($domain['short']) ?></button>
        <?php endforeach; ?>
      </div>
      <span class="DomainBar_Count" data-domain-count aria-live="polite"></span>
    <?php else: ?>
      <div class="DomainBar_C">
        <a class="DChip" href="<?= e(url('/#domain-air')) ?>">Autonomous</a>
        <a class="DChip" href="<?= e(url('/#domain-aviation')) ?>">Aviation</a>
        <a class="DChip" href="<?= e(url('/#domain-cyber')) ?>">C4ISR</a>
        <a class="DChip" href="<?= e(url('/#domain-support')) ?>">Sustainment</a>
      </div>
    <?php endif; ?>
    <a class="DomainBar_A" href="<?= e(url('/distributor-integration')) ?>">
      Integration hub <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg>
    </a>
  </div>
</div>
