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
        <?php foreach ($CAPABILITIES as $cap): ?>
          <a class="DChip" href="<?= e(url($cap['path'])) ?>"><?= e($cap['short']) ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <a class="DomainBar_A" href="<?= e(url('/capabilities')) ?>">
      All capabilities <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg>
    </a>
  </div>
</div>
