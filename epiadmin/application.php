<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once __DIR__ . '/_layout.php';

$user = admin_require();
$id = (int) ($_GET['id'] ?? 0);
$row = application_find($id);

if ($row && is_post()) {
    if (!csrf_valid($_POST['csrf'] ?? null)) {
        admin_flash('err', 'Your session expired. Reload and try again.');
    } elseif (!admin_can_write($user)) {
        admin_flash('err', 'Your role is read-only.');
    } else {
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'status') {
            $status = (string) ($_POST['status'] ?? '');
            $previous = application_set_status($id, $status);
            admin_flash($previous === null ? 'err' : 'ok', $previous === null
                ? 'Unknown stage for this application type.'
                : 'Stage set to ' . application_stage((string) $row['type'], $status) . '.');
        } elseif ($action === 'owner') {
            record_set_owner('application', $id, (int) ($_POST['owner_id'] ?? 0));
            admin_flash('ok', 'Owner updated.');
        } elseif ($action === 'note') {
            admin_flash(
                record_add_note('application', $id, (int) $user['id'], (string) ($_POST['body'] ?? '')) ? 'ok' : 'err',
                'Note added.'
            );
        }
    }
    redirect(admin_url('application.php?id=' . $id));
}

if (!$row) {
    http_response_code(404);
    admin_head('Not found', 'applications', $user);
    admin_page_head('Application not found', 'That reference does not exist.');
    admin_empty('Nothing here', 'The record may have been removed, or the link is wrong.', '<a class="A_Btn" href="' . e(admin_url('applications.php')) . '">Back to applications</a>');
    admin_foot();
    exit;
}

$stages = application_stages((string) $row['type']);
$notes = record_notes('application', $id);
$users = admin_users();
$canWrite = admin_can_write($user);
$isTraining = $row['type'] === 'training';
$owner = null;
foreach ($users as $candidate) {
    if ((int) $row['owner_id'] === (int) $candidate['id']) {
        $owner = $candidate;
    }
}

admin_head('Application ' . $row['reference'], 'applications', $user);
admin_page_head(
    (string) $row['name'],
    $row['reference'] . ' · ' . (APPLICATION_TYPES[$row['type']] ?? (string) $row['type']) . ' · received ' . human_date((string) $row['created_at'], 'j M Y, H:i') . ' UTC',
    '<a class="A_Btn A_Btn--sm" href="' . e(admin_url('applications.php')) . '">Back</a>'
    . ($row['email'] !== '' ? '<a class="A_Btn A_Btn--sm A_Btn--primary" href="mailto:' . e((string) $row['email']) . '?subject=' . e(rawurlencode('EPiTEK — ' . $row['reference'])) . '">Reply by email</a>' : '')
);
?>

<div class="A_Detail">
  <div class="A_Stack">
    <section class="A_Panel">
      <div class="A_Panel_H">
        <h2 class="A_Panel_T"><?= $isTraining ? 'Training application' : 'Organisation request' ?></h2>
        <?= stage_pill(application_stage((string) $row['type'], (string) $row['status']), enquiry_tone((string) $row['status'])) ?>
      </div>
      <div class="A_Detail_B">
        <p class="A_Quote"><?= e((string) $row['message']) ?></p>
        <dl class="A_DL">
          <dt>Email</dt><dd><?= $row['email'] !== '' ? '<a href="mailto:' . e((string) $row['email']) . '">' . e((string) $row['email']) . '</a>' : '—' ?></dd>
          <dt>Telephone</dt><dd><?= e(display_name((string) $row['phone'])) ?></dd>
          <dt>Country</dt><dd><?= e(display_name((string) $row['country'])) ?></dd>
          <dt>Organisation</dt><dd><?= e(display_name((string) $row['organisation'])) ?></dd>
          <dt>Role</dt><dd><?= e(display_name((string) $row['role_title'])) ?></dd>
          <?php if ($isTraining): ?>
            <dt>Programme</dt><dd><?= e(display_name((string) $row['programme'])) ?></dd>
            <dt>Preferred start</dt><dd><?= e(display_name((string) $row['start_window'])) ?></dd>
            <dt>CV / portfolio</dt>
            <dd><?php if ($row['link_url'] !== ''): ?><a href="<?= e((string) $row['link_url']) ?>" target="_blank" rel="noopener noreferrer"><?= e((string) $row['link_url']) ?></a><?php else: ?>—<?php endif; ?></dd>
          <?php endif; ?>
          <dt>Source</dt><dd><?= e(display_name((string) $row['source'])) ?></dd>
        </dl>
      </div>
    </section>

    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Internal notes</h2><span class="A_Note"><?= count($notes) ?> recorded</span></div>
      <?php if ($notes): ?>
        <div class="A_Note_List">
          <?php foreach ($notes as $note): ?>
            <article class="A_Note_Item">
              <p><?= e((string) $note['body']) ?></p>
              <span><?= e(display_name($note['user_name'] ?? '')) ?> · <?= e(human_date((string) $note['created_at'], 'j M Y, H:i')) ?></span>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="A_Panel_Empty">No notes yet. Record screening decisions here.</p>
      <?php endif; ?>
      <?php if ($canWrite): ?>
        <form class="A_Form" method="post" action="<?= e(admin_url('application.php?id=' . $id)) ?>">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="note">
          <label class="A_Label" for="body">Add a note</label>
          <textarea class="A_Textarea" id="body" name="body" maxlength="4000" required></textarea>
          <div><button class="A_Btn A_Btn--primary A_Btn--sm" type="submit">Save note</button></div>
        </form>
      <?php endif; ?>
    </section>
  </div>

  <div class="A_Stack">
    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Stage</h2></div>
      <div class="A_Detail_B">
        <?php if ($canWrite): ?>
          <form class="A_Form" method="post" action="<?= e(admin_url('application.php?id=' . $id)) ?>">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="status">
            <label class="A_Label" for="status">Pipeline stage</label>
            <select class="A_Select" id="status" name="status">
              <?php foreach ($stages as $key => $label): ?>
                <option value="<?= e($key) ?>"<?= $row['status'] === $key ? ' selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
            <div><button class="A_Btn A_Btn--primary A_Btn--sm" type="submit">Update stage</button></div>
          </form>
        <?php else: ?>
          <p class="A_Note">Read-only role. Stage changes require an editor or owner account.</p>
        <?php endif; ?>
      </div>
    </section>

    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Owner</h2></div>
      <div class="A_Detail_B">
        <?php if ($canWrite): ?>
          <form class="A_Form" method="post" action="<?= e(admin_url('application.php?id=' . $id)) ?>">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="owner">
            <label class="A_Label" for="owner_id">Assigned to</label>
            <select class="A_Select" id="owner_id" name="owner_id">
              <option value="0">Unassigned</option>
              <?php foreach ($users as $candidate): ?>
                <option value="<?= (int) $candidate['id'] ?>"<?= (int) $row['owner_id'] === (int) $candidate['id'] ? ' selected' : '' ?>><?= e((string) $candidate['name']) ?></option>
              <?php endforeach; ?>
            </select>
            <div><button class="A_Btn A_Btn--sm" type="submit">Assign</button></div>
          </form>
        <?php else: ?>
          <p class="A_Detail_Value"><?= e($owner['name'] ?? 'Unassigned') ?></p>
        <?php endif; ?>
      </div>
    </section>

    <section class="A_Panel">
      <div class="A_Panel_H"><h2 class="A_Panel_T">Record</h2></div>
      <div class="A_Detail_B">
        <dl class="A_DL A_DL--tight">
          <dt>Reference</dt><dd class="mono"><?= e((string) $row['reference']) ?></dd>
          <dt>Received</dt><dd><?= e(human_date((string) $row['created_at'], 'j M Y, H:i')) ?> UTC</dd>
          <dt>Updated</dt><dd><?= e(human_date($row['updated_at'] ?? null, 'j M Y, H:i')) ?> UTC</dd>
          <dt>Owner</dt><dd><?= e($owner['name'] ?? 'Unassigned') ?></dd>
        </dl>
      </div>
    </section>
  </div>
</div>

<?php admin_foot(); ?>
