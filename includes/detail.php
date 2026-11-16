<?php
declare(strict_types=1);

/**
 * Shared renderer for single-subject pages (one capability, one service).
 * Keeps every detail page a short, focused read instead of folding
 * everything into the home page.
 */
function detail_render(array $item, string $nav, string $sectionLabel, string $sectionHref): void
{
    global $CAPABILITIES, $DOMAINS;

    $root = dirname(__DIR__);
    $path = $item['path'];
    $name = (string) ($item['name'] ?? $item['title'] ?? '');
    $tagline = (string) ($item['tagline'] ?? $item['short'] ?? '');

    $page = [
        'slug'        => $item['slug'],
        'nav'         => $nav,
        'path'        => $path,
        'title'       => $name . ' | ' . SITE_NAME,
        'description' => $item['summary'],
        'heroImage'   => $item['img'],
        'hideCta'     => false,
        'schema'      => [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $name,
            'description' => $item['summary'],
            'provider' => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL . '/'],
            'areaServed' => ['Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Ethiopia'],
            'url' => SITE_URL . $path,
        ],
    ];

    require $root . '/includes/header.php';
    ?>
<section class="PageHero">
  <img class="PageHero_Img" src="<?= e(url($item['img'])) ?>" alt="" width="860" height="640" decoding="async">
  <span class="PageHero_Veil" aria-hidden="true"></span>
  <div class="Wrap">
    <div class="PageHero_In">
      <nav class="Crumb" aria-label="Breadcrumb">
        <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span>
        <a href="<?= e(url($sectionHref)) ?>"><?= e($sectionLabel) ?></a><span aria-hidden="true">/</span>
        <span><?= e($name) ?></span>
      </nav>
      <span class="Eyebrow"><?= e($tagline) ?></span>
      <h1><?= e($name) ?></h1>
      <p class="PageHero_Lead"><?= e($item['intro']) ?></p>
      <div class="PageHero_Meta">
        <?php foreach (($item['meta'] ?? []) as $point): ?>
          <span><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> <?= e($point) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php
$detailSections = $item['sections'] ?? [];
$detailLast = count($detailSections) - 1;
foreach ($detailSections as $i => $section): ?>
<section class="Section<?= ($i % 2 === 1 && $i < $detailLast) ? ' Section--alt' : '' ?>" aria-labelledby="S<?= $i ?>_T">
  <div class="Wrap">
    <div class="Split">
      <div class="Split_Body">
        <span class="Eyebrow"><?= e($sectionLabel) ?></span>
        <h2 id="S<?= $i ?>_T"><?= e($section['h']) ?></h2>
        <?php if (!empty($section['p'])): ?><p><?= e($section['p']) ?></p><?php endif; ?>
        <?php if (!empty($section['list'])): ?>
        <ul class="Split_List">
          <?php foreach ($section['list'] as $line): ?>
            <li><svg class="Ico" aria-hidden="true"><use href="#i-check"/></svg> <?= e($line) ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <div class="Btn_Row">
          <a class="Btn Btn--primary" href="<?= e(url('/contact')) ?>">Discuss this requirement <svg class="Ico Ico--sm" aria-hidden="true"><use href="#i-arrow"/></svg></a>
          <a class="Btn Btn--ghost" href="<?= e(url($sectionHref)) ?>">All <?= e(strtolower($sectionLabel)) ?></a>
        </div>
      </div>
      <div class="Split_Media">
        <div class="MediaCard">
          <span class="MediaTag"><?= e(strtoupper($name)) ?></span>
          <img src="<?= e(url($item['img'])) ?>" alt="<?= e($item['alt']) ?>" width="860" height="640" loading="lazy" decoding="async">
          <div class="MediaCard_Cap"><span>Regional delivery</span><span>EAST AFRICA</span></div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endforeach; ?>
    <?php
    require $root . '/includes/footer.php';
}

/** Find one entry by slug. Returns null when the slug is unknown. */
function detail_item(array $items, string $slug): ?array
{
    foreach ($items as $item) {
        if (($item['slug'] ?? '') === $slug) {
            return $item;
        }
    }
    return null;
}
