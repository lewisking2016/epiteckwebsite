<?php
declare(strict_types=1);

/**
 * EPiTEK Solutions — site configuration.
 * Edit brand, contact and mail settings here. No secrets belong in this file.
 */

const SITE_NAME      = 'EPiTEK Solutions';
const SITE_LEGAL     = 'EPiTEK Solutions';
const SITE_DOMAIN    = 'epitexsolution.africa';
const SITE_URL       = 'https://epitexsolution.africa';
const SITE_TAGLINE   = 'Regional aviation and air-force support arm in East Africa';
const SITE_EMAIL     = ''; // public mailbox — set once the new address exists; empty hides every email link
const SITE_PHONE     = ''; // dispatch line — add when published
const SITE_LINKEDIN  = ''; // company page URL — add when published
const MAIL_FROM      = ''; // set with SITE_EMAIL
const MAIL_TO        = ''; // set with SITE_EMAIL

// Set true on the live host once mail()/SMTP delivery is configured. While false,
// enquiries are validated, rate-limited and stored in storage/leads.jsonl instead.
const MAIL_ENABLED   = false;

/** Base path so the site works both at a domain root and in a local subfolder. */
$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', (string) realpath($_SERVER['DOCUMENT_ROOT'])) : '';
$appRoot = str_replace('\\', '/', (string) realpath(dirname(__DIR__)));
$basePath = '';
if ($docRoot !== '' && $appRoot !== '' && str_starts_with($appRoot, $docRoot)) {
    $basePath = rtrim(substr($appRoot, strlen($docRoot)), '/');
}
define('BASE_PATH', $basePath);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/content.php';

$DOMAINS = [
    'air' => [
        'label' => 'Air Force & Unmanned Systems',
        'short' => 'Air force',
        'desc'  => 'Squadron support, tactical UAS and ISR payloads for contested and GNSS-degraded environments.',
    ],
    'aviation' => [
        'label' => 'Aviation Software & Avionics',
        'short' => 'Aviation',
        'desc'  => 'Mission planning, digital moving maps, terrain-aided navigation and cockpit situational awareness for fixed and rotary wing fleets.',
    ],
    'cyber' => [
        'label' => 'Command, Control & Surveillance',
        'short' => 'C4ISR',
        'desc'  => 'Radar, sensor and command-post integration, coastal surveillance and hardened mission networks.',
    ],
    'support' => [
        'label' => 'Depot & Sustainment',
        'short' => 'Sustainment',
        'desc'  => 'Depot-level MRO, spares, battery management, field dispatch and operator training.',
    ],
];

$NAV = [
    ['label' => 'Home', 'href' => '/', 'slug' => 'home'],
    ['label' => 'Distributor hub', 'href' => '/distributor-integration', 'slug' => 'distributor-integration'],
    ['label' => 'Partnership', 'href' => '/partnerships-stm', 'slug' => 'partnerships-stm'],
    ['label' => 'Services', 'href' => '/services', 'slug' => 'services'],
];

// Show errors locally, log them on the live host. Never display in production.
$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
$isLocal = str_starts_with($host, 'localhost') || str_starts_with($host, '127.0.0.1') || str_starts_with($host, '[::1]');
ini_set('display_errors', $isLocal ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
