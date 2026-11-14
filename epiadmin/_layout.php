<?php
declare(strict_types=1);

/** Shared chrome, icons and small view components for the console. */

require_once dirname(__DIR__) . '/includes/repository.php';

/** Inline icon from the console sprite. */
function ai(string $name, string $class = ''): string
{
    return '<svg class="AIco' . ($class !== '' ? ' ' . e($class) : '') . '" aria-hidden="true" focusable="false"><use href="#a-' . e($name) . '"/></svg>';
}

function admin_sprite(): void
{
    ?>
<svg class="A_Sprite" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
  <symbol id="a-grid" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/></g></symbol>
  <symbol id="a-inbox" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M4 13.5V6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v7"/><path d="M4 13.5h4l1.4 2.2h5.2L16 13.5h4v4A2.5 2.5 0 0117.5 20h-11A2.5 2.5 0 014 17.5z"/></g></symbol>
  <symbol id="a-file" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M6.5 3.5h7l4.5 4.5v12.5h-11.5z"/><path d="M13.5 3.5V8H18M9 13h6M9 16.5h4.5"/></g></symbol>
  <symbol id="a-pulse" viewBox="0 0 24 24"><path d="M3 12.5h3.5l2-5 3 10 2.5-6 1.7 3.5H21" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></symbol>
  <symbol id="a-key" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><circle cx="8.5" cy="8.5" r="3.8"/><path d="M11.4 11.4L20 20M17 17l-2 2M14 14l-2 2"/></g></symbol>
  <symbol id="a-external" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M13 5h6v6M19 5l-8 8"/><path d="M18 14.5v4A1.5 1.5 0 0116.5 20h-11A1.5 1.5 0 014 18.5v-11A1.5 1.5 0 015.5 6H10"/></g></symbol>
  <symbol id="a-logout" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5H6.5A1.5 1.5 0 005 6.5v11A1.5 1.5 0 006.5 19H10"/><path d="M14 8l4 4-4 4M9 12h9"/></g></symbol>
  <symbol id="a-search" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><circle cx="11" cy="11" r="6.2"/><path d="M15.6 15.6L20 20"/></g></symbol>
  <symbol id="a-arrow" viewBox="0 0 24 24"><path d="M5 12h13M12.5 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></symbol>
  <symbol id="a-check" viewBox="0 0 24 24"><path d="M4.5 12.5l5 5L20 6.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>
    <?php
}

function admin_head(string $title, string $active, array $user): void
{
    $counts = nav_counts();
    $nav = [
        'overview'     => ['label' => 'Overview', 'href' => admin_url('index.php'), 'icon' => 'grid'],
        'enquiries'    => ['label' => 'Enquiries', 'href' => admin_url('enquiries.php'), 'icon' => 'inbox', 'count' => $counts['enquiries']],
        'applications' => ['label' => 'Applications', 'href' => admin_url('applications.php'), 'icon' => 'file', 'count' => $counts['applications']],
        'activity'     => ['label' => 'Activity', 'href' => admin_url('activity.php'), 'icon' => 'pulse'],
    ];
    $initial = strtoupper(mb_substr((string) ($user['name'] ?? 'U'), 0, 1));
    $flashes = admin_flash_take();
    ?><!DOCTYPE html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<meta name="color-scheme" content="light">
<title><?= e($title) ?> · EPiTEK Console</title>
<link rel="icon" href="<?= e(asset('assets/img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="stylesheet" href="<?= e(asset('assets/css/fonts.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
</head>
<body class="A">
<?php admin_sprite(); ?>
<a class="A_Skip" href="#A_Main">Skip to content</a>
<div class="A_Shell">
  <aside class="A_Side">
    <a class="A_Brand" href="<?= e(admin_url('index.php')) ?>">
      <img src="<?= e(asset('assets/img/epitek-logo-white.svg')) ?>" alt="EPiTEK Solutions" width="150" height="24">
      <span class="A_Brand_Tag">Console</span>
    </a>

    <nav class="A_Nav" aria-label="Console">
      <?php foreach ($nav as $key => $item): ?>
        <a class="A_Nav_A<?= $key === $active ? ' is-on' : '' ?>" href="<?= e($item['href']) ?>"<?= $key === $active ? ' aria-current="page"' : '' ?>>
          <?= ai($item['icon']) ?>
          <span class="A_Nav_Txt"><?= e($item['label']) ?></span>
          <?php if (!empty($item['count'])): ?><span class="A_Nav_Count"><?= number_format((int) $item['count']) ?></span><?php endif; ?>
        </a>
      <?php endforeach; ?>
      <a class="A_Nav_A<?= $active === 'security' ? ' is-on' : '' ?>" href="<?= e(admin_url('password.php')) ?>"<?= $active === 'security' ? ' aria-current="page"' : '' ?>>
        <?= ai('key') ?>
        <span class="A_Nav_Txt">Security</span>
      </a>
    </nav>

    <div class="A_Side_Foot">
      <a class="A_User" href="<?= e(admin_url('password.php')) ?>">
        <span class="A_User_Avatar" aria-hidden="true"><?= e($initial) ?></span>
        <span class="A_User_Txt">
          <b><?= e((string) $user['name']) ?></b>
          <span><?= e(ROLES[$user['role']] ?? (string) $user['role']) ?></span>
        </span>
      </a>
      <div class="A_Side_Actions">
        <a class="A_Side_Link" href="<?= e(url('/')) ?>" target="_blank" rel="noopener">View site <?= ai('external') ?></a>
        <form method="post" action="<?= e(admin_url('logout.php')) ?>">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <button class="A_Side_Btn" type="submit"><?= ai('logout') ?> Sign out</button>
        </form>
      </div>
    </div>
  </aside>

  <main class="A_Main" id="A_Main">
    <div class="A_Flash_Stack" aria-live="polite">
      <?php if (admin_uses_setup_password($user)): ?>
        <div class="A_Flash A_Flash--warn">
          <span>This account still uses its setup password. Set your own to lock the console down.</span>
          <a class="A_Flash_A" href="<?= e(admin_url('password.php')) ?>">Change password <?= ai('arrow') ?></a>
        </div>
      <?php endif; ?>
      <?php foreach ($flashes as $flash): ?>
        <div class="A_Flash A_Flash--<?= e($flash['tone']) ?>" role="status"><?= e($flash['message']) ?></div>
      <?php endforeach; ?>
    </div>
<?php
}

function admin_foot(): void
{
    ?>
    <p class="A_Foot_Note">EPiTEK Console · <?= e(SITE_DOMAIN) ?> · times shown in UTC · session ends when you sign out.</p>
  </main>
</div>
</body>
</html>
<?php
}

/** Section heading with optional actions. */
function admin_page_head(string $title, string $sub = '', string $aside = ''): void
{
    ?>
    <header class="A_Head">
      <div class="A_Head_Txt">
        <h1 class="A_H1"><?= e($title) ?></h1>
        <?php if ($sub !== ''): ?><p class="A_Sub"><?= e($sub) ?></p><?php endif; ?>
      </div>
      <?php if ($aside !== ''): ?><div class="A_Head_Aside"><?= $aside ?></div><?php endif; ?>
    </header>
<?php
}

/** Render pagination controls for a list view. */
function pager_html(int $page, int $pages, callable $href): string
{
    if ($pages <= 1) {
        return '';
    }
    $out = '<nav class="A_Pager" aria-label="Pagination"><span>Page ' . $page . ' of ' . $pages . '</span><span class="A_Pager_Links">';
    if ($page > 1) {
        $out .= '<a class="A_Pager_A" href="' . e($href($page - 1)) . '" rel="prev">Previous</a>';
    }
    if ($page < $pages) {
        $out .= '<a class="A_Pager_A" href="' . e($href($page + 1)) . '" rel="next">Next</a>';
    }
    return $out . '</span></nav>';
}

function stage_pill(string $label, string $tone = ''): string
{
    return '<span class="Pill' . ($tone !== '' ? ' Pill--' . e($tone) : '') . '">' . e($label) . '</span>';
}

function enquiry_tone(string $status): string
{
    return match ($status) {
        'new', 'received' => 'new',
        'won', 'enrolled', 'offer' => 'ok',
        'lost', 'declined' => 'muted',
        default => 'work',
    };
}

/** Nice-looking empty state used by every list. */
function admin_empty(string $title, string $body, string $cta = ''): void
{
    ?>
    <div class="A_Empty">
      <p class="A_Empty_T"><?= e($title) ?></p>
      <p class="A_Empty_B"><?= e($body) ?></p>
      <?php if ($cta !== ''): ?><div class="A_Empty_A"><?= $cta ?></div><?php endif; ?>
    </div>
<?php
}
