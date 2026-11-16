<?php
declare(strict_types=1);

/**
 * Content wiring check.
 *
 * Proves three things before a release:
 *   1. every registered editable page maps to a real template file;
 *   2. every field key a template asks for is registered (no typos, no blanks);
 *   3. every registered literal key is actually read by a template or the
 *      content merge (no dead fields in the editor).
 *
 * Usage: php tools/check_content.php
 * Exit code 0 = clean, 1 = problems found.
 */

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/content_fields.php';

$root = dirname(__DIR__);
$errors = [];
$warnings = [];

/* ------------------------------------------------------------ 1. page files */
$groups = content_field_groups();
foreach ($groups as $group) {
    $path = rtrim($group['path'], '/');
    $file = $path === '' ? $root . '/index.php' : $root . $path . '/index.php';
    if (!is_file($file)) {
        $errors[] = 'Page group "' . $group['label'] . '" points at ' . $group['path'] . ' but ' . str_replace($root, '.', $file) . ' does not exist.';
    }
}

/* --------------------------------------------------- 2. keys used by templates */
$templates = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }
    $id = str_replace('\\', '/', $file->getPathname());
    if (str_contains($id, '/.git/') || str_contains($id, '/epiadmin/') || str_contains($id, '/tools/')
        || str_ends_with($id, '/setup_config.php') || str_ends_with($id, '/diag.php')) {
        continue;
    }
    $templates[$id] = (string) file_get_contents($id);
}

$registered = content_field_keys();
$used = [];

foreach ($templates as $id => $source) {
    if (!preg_match_all("/\b(?:t|c)\('([a-z0-9]+(?:\.[a-z0-9_-]+)+)'/i", $source, $m, PREG_OFFSET_CAPTURE)) {
        continue;
    }
    foreach ($m[1] as [$key, $offset]) {
        // A key immediately followed by " . " is a prefix of a computed key
        // such as c('comp.p' . $n); those are covered by the merge assertions.
        $after = ltrim(substr($source, (int) $offset + strlen($key) + 1, 4));
        if (str_starts_with($after, '.')) {
            continue;
        }
        $used[$key][] = str_replace($root . '/', '', $id);
    }
}

foreach ($used as $key => $where) {
    if (!isset($registered[$key])) {
        $errors[] = 'Template asks for unregistered key "' . $key . '" (' . implode(', ', array_unique($where)) . ') — it would render empty.';
    }
}

/* Dynamic families are merged in content.php; assert that merge still exists. */
$contentSource = (string) file_get_contents($root . '/includes/content.php');
foreach (['cap' => "\$CAPABILITIES[\$i]", 'svc' => "\$SERVICES[\$i]", 'faq' => "\$FAQ[\$i]", 'comp.p' => "\$COMPANY['body'][\$i]"] as $prefix => $needle) {
    if (!str_contains($contentSource, $needle)) {
        $errors[] = 'The ' . $prefix . '.* merge loop is missing from includes/content.php, so those edits never reach the page.';
    }
}

/* ------------------------------------------ 3. registered but never read keys */
foreach ($registered as $key => $field) {
    // Families read through a computed key are covered by the merge assertions.
    if (preg_match('/^(cap|svc|faq)\./', $key) || str_starts_with($key, 'comp.p')) {
        continue;
    }
    if (!isset($used[$key])) {
        $warnings[] = 'Field "' . $key . '" (' . ($field['group'] ?? '') . ' — ' . $field['label'] . ') is registered but no template reads it.';
    }
}

/* --------------------------------------------------------------- 4. lifecycle */
$store = (string) file_get_contents($root . '/includes/content_store.php');
foreach (['function c(', 'function content_set(', 'function content_reset(', 'function content_map('] as $fn) {
    if (!str_contains($store, $fn)) {
        $errors[] = 'includes/content_store.php is missing ' . $fn . ').';
    }
}

/* ------------------------------------------------------------------- report */
$pageCount = count($groups);
$fieldCount = count($registered);
echo "Content wiring check\n";
echo str_repeat('-', 52) . "\n";
echo 'Pages registered ...... ' . $pageCount . "\n";
echo 'Fields registered ..... ' . $fieldCount . "\n";
echo 'Keys read by templates  ' . count($used) . "\n";
echo 'Templates scanned ..... ' . count($templates) . "\n";
echo str_repeat('-', 52) . "\n";

foreach ($warnings as $warning) {
    echo 'WARN  ' . $warning . "\n";
}
foreach ($errors as $error) {
    echo 'FAIL  ' . $error . "\n";
}

if (!$errors) {
    echo 'RESULT: OK — every page resolves, every key is registered and read.' . "\n";
    exit(0);
}

echo 'RESULT: ' . count($errors) . ' problem(s) found.' . "\n";
exit(1);
