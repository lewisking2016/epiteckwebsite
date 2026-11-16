<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';
require_once dirname(__DIR__) . '/includes/content_fields.php';

$user = admin_require();
$canWrite = admin_can_write($user);
$returnTo = admin_url('content.php');

if (is_post()) {
    if (!$canWrite) {
        admin_flash('err', 'Your role can view the console but not change content.');
    } elseif (!csrf_valid($_POST['csrf'] ?? null)) {
        admin_flash('err', 'Your session expired. Reload and try again.');
    } else {
        $fields = content_field_keys();

        try {
            if (isset($_POST['reset_key'])) {
                $key = (string) $_POST['reset_key'];
                if (isset($fields[$key])) {
                    content_reset($key);
                    audit('content.reset', 'content', 0, ['key' => $key]);
                    admin_flash('ok', 'Restored the built-in text for “' . $fields[$key]['label'] . '”.');
                }
            } else {
                $changed = 0;
                foreach ((array) ($_POST['field'] ?? []) as $key => $value) {
                    $key = (string) $key;
                    if (!isset($fields[$key])) {
                        continue;
                    }
                    $value = trim((string) $value);
                    $default = (string) $fields[$key]['default'];

                    // Empty, or identical to the shipped text: drop the override.
                    if ($value === '' || $value === $default) {
                        if (content_has($key)) {
                            content_reset($key);
                            $changed++;
                        }
                        continue;
                    }

                    if (content_has($key) && c($key) === $value) {
                        continue;
                    }

                    content_set($key, $value, (int) $user['id']);
                    $changed++;
                }

                audit('content.update', 'content', 0, ['fields' => $changed]);
                admin_flash('ok', $changed === 0 ? 'No changes to save.' : $changed . ' field' . ($changed === 1 ? '' : 's') . ' updated.');
            }
        } catch (Throwable $e) {
            error_log('epiadmin content: ' . $e->getMessage());
            admin_flash('err', 'Could not save: the content table is not ready. Open Console → Upgrade to create it.');
        }
    }

    redirect($returnTo);
}

$user = admin_current() ?? $user;
$groups = content_field_groups();

admin_head('Site content', 'content', $user);
admin_page_head(
    'Site content',
    'The words on the public pages. Empty a field, or use Reset, to restore the built-in text.',
    '<a class="A_Btn A_Btn--sm" href="' . e(url('/')) . '" target="_blank" rel="noopener">View site</a>'
);
?>

<?php if (!$canWrite): ?>
  <div class="A_Flash A_Flash--warn">Your role can read this screen but not change it. Ask an owner account to make edits.</div>
<?php endif; ?>

<form class="A_Form" method="post" action="<?= e($returnTo) ?>">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

  <?php foreach ($groups as $group => $fields): ?>
    <section class="A_Panel" style="margin-bottom:18px">
      <div class="A_Panel_H">
        <h2 class="A_Panel_T"><?= e((string) $group) ?></h2>
      </div>
      <div class="A_Detail_B">
        <?php foreach ($fields as $field): ?>
          <?php $edited = content_has($field['key']); ?>
          <div class="A_Field">
            <label class="A_Label" for="f-<?= e($field['key']) ?>">
              <?= e($field['label']) ?>
              <?php if ($edited): ?><span class="Pill Pill--ok">edited</span><?php endif; ?>
            </label>

            <?php if ($field['type'] === 'textarea'): ?>
              <textarea class="A_Input" id="f-<?= e($field['key']) ?>" name="field[<?= e($field['key']) ?>]" rows="3"<?= $canWrite ? '' : ' readonly' ?>><?= e(c($field['key'], $field['default'])) ?></textarea>
            <?php else: ?>
              <input class="A_Input" id="f-<?= e($field['key']) ?>" type="text" name="field[<?= e($field['key']) ?>]" value="<?= e(c($field['key'], $field['default'])) ?>"<?= $canWrite ? '' : ' readonly' ?>>
            <?php endif; ?>

            <?php if ($edited && $canWrite): ?>
              <p class="A_Hint">
                Built-in text: <?= e($field['default']) ?>
                <button class="A_Btn A_Btn--sm" type="submit" name="reset_key" value="<?= e($field['key']) ?>" formnovalidate>Reset</button>
              </p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <?php if ($canWrite): ?>
    <div class="A_Panel">
      <div class="A_Detail_B" style="display:flex;gap:12px;align-items:center;justify-content:flex-end">
        <span class="A_Note">Changes go live as soon as you save.</span>
        <button class="A_Btn A_Btn--primary" type="submit" name="action" value="save">Save changes</button>
      </div>
    </div>
  <?php endif; ?>
</form>

<?php admin_foot(); ?>
