<?php
declare(strict_types=1);

/** Shared vocabulary for the dashboard, the public application form and the enquiry intake. */

const ENQUIRY_STAGES = [
    'new'       => 'New',
    'screening' => 'Screening',
    'briefing'  => 'Briefing',
    'quoted'    => 'Quoted',
    'won'       => 'Won',
    'lost'      => 'Closed lost',
];

const APPLICATION_STAGES = [
    'training' => [
        'received'   => 'Received',
        'screening'  => 'Screening',
        'assessment' => 'Assessment',
        'offer'      => 'Offer',
        'enrolled'   => 'Enrolled',
        'declined'   => 'Declined',
    ],
    'organisation' => [
        'received'  => 'Received',
        'screening' => 'Screening',
        'briefing'  => 'Briefing',
        'proposal'  => 'Proposal',
        'won'       => 'Won',
        'lost'      => 'Closed lost',
    ],
];

const APPLICATION_TYPES = [
    'training'     => 'Training enrolment',
    'organisation' => 'Organisation / RFP',
];

const TRAINING_PROGRAMMES = [
    'Standards and shared airspace',
    'UAS operator certification',
    'Avionics and depot maintenance',
    'Instructor and assessor',
    'Other / not sure yet',
];

const START_WINDOWS = [
    'As soon as possible',
    'Next intake (under 3 months)',
    '3–6 months',
    '6–12 months',
    'Not yet defined',
];

const ROLES = [
    'owner'  => 'Owner',
    'editor' => 'Editor',
    'viewer' => 'Viewer',
];

function enquiry_stage(string $status): string
{
    return ENQUIRY_STAGES[$status] ?? ucfirst($status);
}

/** @return array<string,string> */
function application_stages(string $type): array
{
    return APPLICATION_STAGES[$type] ?? APPLICATION_STAGES['training'];
}

function application_stage(string $type, string $status): string
{
    $stages = application_stages($type);
    return $stages[$status] ?? ucfirst($status);
}

/** Status considered "open" (still being worked). */
function is_open_enquiry(string $status): bool
{
    return !in_array($status, ['won', 'lost'], true);
}

function is_open_application(string $type, string $status): bool
{
    $closed = $type === 'organisation' ? ['won', 'lost'] : ['enrolled', 'declined'];
    return !in_array($status, $closed, true);
}

function new_reference(string $prefix): string
{
    return $prefix . '-' . gmdate('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

function human_date(?string $value, string $format = 'j M Y'): string
{
    if (!$value) {
        return '—';
    }
    $ts = strtotime($value);
    return $ts ? date($format, $ts) : '—';
}

function relative_date(?string $value): string
{
    if (!$value) {
        return '—';
    }
    $ts = strtotime($value);
    if (!$ts) {
        return '—';
    }
    $days = (int) floor((time() - $ts) / 86400);
    if ($days <= 0) {
        return 'today';
    }
    if ($days === 1) {
        return 'yesterday';
    }
    if ($days < 30) {
        return $days . ' days ago';
    }
    return human_date($value);
}
