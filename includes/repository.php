<?php
declare(strict_types=1);

/**
 * Data access for the console. Everything that reads or writes enquiries,
 * applications, notes and activity lives here so the page files stay thin.
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/pipeline.php';
require_once __DIR__ . '/http.php';

/** ---------------------------------------------------------------- filters */

/** @return array{status:string,q:string,country:string,page:int,per:int} */
function enquiry_filters(array $input): array
{
    $status = (string) ($input['status'] ?? '');
    return [
        'status'  => isset(ENQUIRY_STAGES[$status]) ? $status : '',
        'q'       => trim((string) ($input['q'] ?? '')),
        'country' => trim((string) ($input['country'] ?? '')),
        'page'    => max(1, (int) ($input['page'] ?? 1)),
        'per'     => 25,
    ];
}

/** @return array{type:string,status:string,q:string,page:int,per:int} */
function application_filters(array $input): array
{
    $type = (string) ($input['type'] ?? '');
    $status = (string) ($input['status'] ?? '');
    $validStatuses = isset(APPLICATION_TYPES[$type])
        ? application_stages($type)
        : array_merge(APPLICATION_STAGES['training'], APPLICATION_STAGES['organisation']);
    return [
        'type'   => isset(APPLICATION_TYPES[$type]) ? $type : '',
        'status' => isset($validStatuses[$status]) ? $status : '',
        'q'      => trim((string) ($input['q'] ?? '')),
        'page'   => max(1, (int) ($input['page'] ?? 1)),
        'per'    => 25,
    ];
}

/** @return array{where:string,params:array<int,mixed>} */
function enquiry_where(array $f): array
{
    $where = [];
    $params = [];
    if ($f['status'] !== '') {
        $where[] = 'status = ?';
        $params[] = $f['status'];
    }
    if ($f['country'] !== '') {
        $where[] = 'country = ?';
        $params[] = $f['country'];
    }
    if ($f['q'] !== '') {
        $where[] = '(name LIKE ? OR organisation LIKE ? OR email LIKE ? OR reference LIKE ? OR interests LIKE ?)';
        $like = '%' . $f['q'] . '%';
        array_push($params, $like, $like, $like, $like, $like);
    }
    return ['where' => $where ? ' WHERE ' . implode(' AND ', $where) : '', 'params' => $params];
}

/** @return array{where:string,params:array<int,mixed>} */
function application_where(array $f): array
{
    $where = [];
    $params = [];
    if ($f['type'] !== '') {
        $where[] = 'type = ?';
        $params[] = $f['type'];
    }
    if ($f['status'] !== '') {
        $where[] = 'status = ?';
        $params[] = $f['status'];
    }
    if ($f['q'] !== '') {
        $where[] = '(name LIKE ? OR email LIKE ? OR organisation LIKE ? OR programme LIKE ? OR reference LIKE ?)';
        $like = '%' . $f['q'] . '%';
        array_push($params, $like, $like, $like, $like, $like);
    }
    return ['where' => $where ? ' WHERE ' . implode(' AND ', $where) : '', 'params' => $params];
}

/** ---------------------------------------------------------------- lists */

/** @return array{rows:array<int,array<string,mixed>>,page:int,pages:int,total:int}
 */
function enquiries_page(array $f): array
{
    $query = enquiry_where($f);
    $total = db_value('SELECT COUNT(*) FROM enquiries' . $query['where'], $query['params']);
    $pager = paginate($total, (int) $f['page'], (int) $f['per']);
    $rows = db_all(
        'SELECT * FROM enquiries' . $query['where'] . ' ORDER BY created_at DESC, id DESC LIMIT '
        . $pager['per'] . ' OFFSET ' . $pager['offset'],
        $query['params']
    );
    return ['rows' => $rows, 'page' => $pager['page'], 'pages' => $pager['pages'], 'total' => $total];
}

/** @return array{rows:array<int,array<string,mixed>>,page:int,pages:int,total:int} */
function applications_page(array $f): array
{
    $query = application_where($f);
    $total = db_value('SELECT COUNT(*) FROM applications' . $query['where'], $query['params']);
    $pager = paginate($total, (int) $f['page'], (int) $f['per']);
    $rows = db_all(
        'SELECT * FROM applications' . $query['where'] . ' ORDER BY created_at DESC, id DESC LIMIT '
        . $pager['per'] . ' OFFSET ' . $pager['offset'],
        $query['params']
    );
    return ['rows' => $rows, 'page' => $pager['page'], 'pages' => $pager['pages'], 'total' => $total];
}

/** @return array<int,array{country:string,n:int}> */
function enquiry_countries(): array
{
    return db_all("SELECT country, COUNT(*) AS n FROM enquiries WHERE country <> '' GROUP BY country ORDER BY country");
}

/** ---------------------------------------------------------------- records */

function enquiry_find(int $id): ?array
{
    return $id > 0 ? db_one('SELECT * FROM enquiries WHERE id = ?', [$id]) : null;
}

function application_find(int $id): ?array
{
    return $id > 0 ? db_one('SELECT * FROM applications WHERE id = ?', [$id]) : null;
}

/** @return array<int,array<string,mixed>> */
function record_notes(string $type, int $id): array
{
    return db_all(
        'SELECT n.*, u.name AS user_name FROM notes n LEFT JOIN users u ON u.id = n.user_id
         WHERE n.subject_type = ? AND n.subject_id = ? ORDER BY n.created_at DESC, n.id DESC',
        [$type, $id]
    );
}

function record_add_note(string $type, int $id, int $userId, string $body): bool
{
    $body = trim($body);
    if ($body === '') {
        return false;
    }
    $now = gmdate('Y-m-d H:i:s');
    db_id(
        'INSERT INTO notes (subject_type, subject_id, user_id, body, created_at) VALUES (?, ?, ?, ?, ?)',
        [$type, $id, $userId, mb_substr($body, 0, 4000), $now]
    );
    db_exec('UPDATE ' . ($type === 'application' ? 'applications' : 'enquiries') . ' SET updated_at = ? WHERE id = ?', [$now, $id]);
    audit($type . '.note', $type, $id);
    return true;
}

/** Change an enquiry stage. Returns the previous stage, or null when invalid. */
function enquiry_set_status(int $id, string $status): ?string
{
    if (!isset(ENQUIRY_STAGES[$status])) {
        return null;
    }
    $row = enquiry_find($id);
    if (!$row) {
        return null;
    }
    if ($row['status'] === $status) {
        return (string) $row['status'];
    }
    db_exec('UPDATE enquiries SET status = ?, updated_at = ? WHERE id = ?', [$status, gmdate('Y-m-d H:i:s'), $id]);
    audit('enquiry.status', 'enquiry', $id, ['from' => $row['status'], 'to' => $status]);
    return (string) $row['status'];
}

/** Change an application stage, validating against that type's pipeline. */
function application_set_status(int $id, string $status): ?string
{
    $row = application_find($id);
    if (!$row || !isset(application_stages((string) $row['type'])[$status])) {
        return null;
    }
    if ($row['status'] === $status) {
        return (string) $row['status'];
    }
    db_exec('UPDATE applications SET status = ?, updated_at = ? WHERE id = ?', [$status, gmdate('Y-m-d H:i:s'), $id]);
    audit('application.status', 'application', $id, ['from' => $row['status'], 'to' => $status]);
    return (string) $row['status'];
}

/** Assign or clear the owner of a record. Returns true when stored. */
function record_set_owner(string $type, int $id, int $ownerId): bool
{
    $table = $type === 'application' ? 'applications' : 'enquiries';
    $owner = $ownerId > 0 ? $ownerId : null;
    db_exec('UPDATE ' . $table . ' SET owner_id = ?, updated_at = ? WHERE id = ?', [$owner, gmdate('Y-m-d H:i:s'), $id]);
    audit($type . '.owner', $type, $id, ['owner_id' => $owner]);
    return true;
}

/** ---------------------------------------------------------------- export */

/** @return array{rows:array<int,array<string,mixed>>,headers:array<int,string>,filename:string} */
function export_rows(string $type, string $filter): array
{
    if ($type === 'applications') {
        $rows = $filter !== ''
            ? db_all('SELECT * FROM applications WHERE type = ? ORDER BY created_at DESC', [$filter])
            : db_all('SELECT * FROM applications ORDER BY created_at DESC');
        return [
            'rows' => $rows,
            'headers' => ['reference', 'type', 'status', 'name', 'email', 'phone', 'country', 'organisation', 'role_title', 'programme', 'start_window', 'link_url', 'message', 'created_at'],
            'filename' => 'epitek-applications-' . gmdate('Ymd') . '.csv',
        ];
    }
    $rows = $filter !== ''
        ? db_all('SELECT * FROM enquiries WHERE status = ? ORDER BY created_at DESC', [$filter])
        : db_all('SELECT * FROM enquiries ORDER BY created_at DESC');
    return [
        'rows' => $rows,
        'headers' => ['reference', 'status', 'name', 'title', 'organisation', 'email', 'phone', 'country', 'interests', 'environment', 'timeline', 'briefing', 'message', 'created_at'],
        'filename' => 'epitek-enquiries-' . gmdate('Ymd') . '.csv',
    ];
}

/** ---------------------------------------------------------------- dashboard */

/** Everything the overview screen needs, in one pass. */
function dashboard_data(): array
{
    $since30 = gmdate('Y-m-d H:i:s', time() - 30 * 86400);
    $since7  = gmdate('Y-m-d H:i:s', time() - 7 * 86400);
    $today   = gmdate('Y-m-d 00:00:00');
    $stale   = gmdate('Y-m-d H:i:s', time() - 3 * 86400);

    $data = [
        'enquiries_total'   => db_value('SELECT COUNT(*) FROM enquiries'),
        'enquiries_30'      => db_value('SELECT COUNT(*) FROM enquiries WHERE created_at >= ?', [$since30]),
        'enquiries_7'       => db_value('SELECT COUNT(*) FROM enquiries WHERE created_at >= ?', [$since7]),
        'enquiries_today'   => db_value('SELECT COUNT(*) FROM enquiries WHERE created_at >= ?', [$today]),
        'enquiries_open'    => db_value("SELECT COUNT(*) FROM enquiries WHERE status NOT IN ('won','lost')"),
        'applications_total'=> db_value('SELECT COUNT(*) FROM applications'),
        'applications_30'   => db_value('SELECT COUNT(*) FROM applications WHERE created_at >= ?', [$since30]),
        'applications_open' => db_value("SELECT COUNT(*) FROM applications WHERE status NOT IN ('won','lost','enrolled','declined')"),
        'enrolled'          => db_value("SELECT COUNT(*) FROM applications WHERE status = 'enrolled'"),
        'enquiries_stale'   => db_value("SELECT COUNT(*) FROM enquiries WHERE status NOT IN ('won','lost') AND created_at < ?", [$stale]),
        'applications_stale'=> db_value("SELECT COUNT(*) FROM applications WHERE status IN ('received','screening') AND created_at < ?", [$stale]),
    ];

    $data['enquiries_recent'] = db_all('SELECT * FROM enquiries ORDER BY created_at DESC, id DESC LIMIT 6');
    $data['applications_recent'] = db_all('SELECT * FROM applications ORDER BY created_at DESC, id DESC LIMIT 6');
    $data['activity'] = db_all(
        'SELECT a.*, u.name AS user_name FROM activity a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC, a.id DESC LIMIT 8'
    );

    $data['enquiries_by_stage'] = [];
    foreach (db_all('SELECT status, COUNT(*) AS n FROM enquiries GROUP BY status') as $row) {
        $data['enquiries_by_stage'][(string) $row['status']] = (int) $row['n'];
    }
    $data['applications_by_stage'] = [];
    foreach (db_all('SELECT type, status, COUNT(*) AS n FROM applications GROUP BY type, status') as $row) {
        $data['applications_by_stage'][(string) $row['type']][(string) $row['status']] = (int) $row['n'];
    }

    // Fourteen-day intake series for the mini bars.
    $series = [];
    for ($i = 13; $i >= 0; $i--) {
        $day = gmdate('Y-m-d', time() - $i * 86400);
        $series[$day] = ['label' => gmdate('j M', strtotime($day)), 'enquiries' => 0, 'applications' => 0];
    }
    foreach (db_all('SELECT created_at FROM enquiries WHERE created_at >= ?', [gmdate('Y-m-d 00:00:00', time() - 13 * 86400)]) as $row) {
        $day = substr((string) $row['created_at'], 0, 10);
        if (isset($series[$day])) {
            $series[$day]['enquiries']++;
        }
    }
    foreach (db_all('SELECT created_at FROM applications WHERE created_at >= ?', [gmdate('Y-m-d 00:00:00', time() - 13 * 86400)]) as $row) {
        $day = substr((string) $row['created_at'], 0, 10);
        if (isset($series[$day])) {
            $series[$day]['applications']++;
        }
    }
    $data['series'] = array_values($series);

    $data['attention'] = array_merge(
        array_map(static fn ($r) => $r + ['kind' => 'enquiry'], db_all(
            "SELECT * FROM enquiries WHERE status NOT IN ('won','lost') AND created_at < ? ORDER BY created_at ASC LIMIT 5",
            [$stale]
        )),
        array_map(static fn ($r) => $r + ['kind' => 'application'], db_all(
            "SELECT * FROM applications WHERE status IN ('received','screening') AND created_at < ? ORDER BY created_at ASC LIMIT 5",
            [$stale]
        ))
    );

    return $data;
}

/** Small counters shown beside the navigation labels. */
function nav_counts(): array
{
    static $counts = null;
    if ($counts !== null) {
        return $counts;
    }
    $counts = [
        'enquiries'    => db_value("SELECT COUNT(*) FROM enquiries WHERE status NOT IN ('won','lost')"),
        'applications' => db_value("SELECT COUNT(*) FROM applications WHERE status NOT IN ('won','lost','enrolled','declined')"),
    ];
    return $counts;
}

/** @return array{rows:array<int,array<string,mixed>>,page:int,pages:int,total:int} */
function activity_page(int $page, int $per = 40): array
{
    $total = db_value('SELECT COUNT(*) FROM activity');
    $pager = paginate($total, $page, $per);
    $rows = db_all(
        'SELECT a.*, u.name AS user_name FROM activity a LEFT JOIN users u ON u.id = a.user_id
         ORDER BY a.created_at DESC, a.id DESC LIMIT ' . $pager['per'] . ' OFFSET ' . $pager['offset']
    );
    return ['rows' => $rows, 'page' => $pager['page'], 'pages' => $pager['pages'], 'total' => $total];
}
