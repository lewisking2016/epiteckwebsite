<?php
declare(strict_types=1);

/**
 * Editable page copy.
 *
 * Each entry: key => [label, input type, default value, group].
 * The default is what the site shows until someone edits the text in the
 * console. Keep the wording here identical to what the page should say out of
 * the box, so an empty `content` table changes nothing.
 */

$TEXT = [
    // ---------------------------------------------------------------- home
    'home.badge'  => ['Badge above the headline', 'text', 'AVIATION & AIR-FORCE SUPPORT · EAST AFRICA', 'Home page'],
    'home.h1_a'   => ['Headline — first line', 'text', 'Aviation and air-force support,', 'Home page'],
    'home.h1_hl'  => ['Headline — highlighted line', 'text', 'delivered in region', 'Home page'],
    'home.lead'   => ['Intro paragraph', 'textarea', 'We keep East African fleets serviceable: mission software and avionics integration, uncrewed systems support, command integration, training and depot sustainment.', 'Home page'],
    'home.cap_h2' => ['Capabilities heading', 'text', 'Four areas. Four pages.', 'Home page'],
    'home.cap_lead' => ['Capabilities intro', 'textarea', 'Each area is its own conversation, so each has its own page with the scope and the delivery model.', 'Home page'],
    'home.model_h2' => ['Operating model heading', 'text', 'One programme office, from requirement to overhaul', 'Home page'],
    'home.model_p'  => ['Operating model paragraph', 'textarea', 'An air force does not buy software, a sensor and a spare part in isolation. It buys availability — the aircraft serviceable on the day the tasking arrives, with the documentation to prove it.', 'Home page'],

    // ------------------------------------------------- capability hub page
    'capabilities.h1'     => ['Page heading', 'text', 'What we support', 'Capabilities hub'],
    'capabilities.lead'   => ['Page intro', 'textarea', 'Each area has its own page, because each one is a different conversation. Start with the one that matches your requirement.', 'Capabilities hub'],
    'capabilities.h2'     => ['Section heading', 'text', 'Pick the area you are working on', 'Capabilities hub'],
    'capabilities.lead2'  => ['Section intro', 'textarea', 'Engineering, training and sustainment run underneath all four. The difference is the platform and the operational question.', 'Capabilities hub'],

    // ---------------------------------------------------- services hub page
    'services.h1'    => ['Page heading', 'text', 'Five services that keep a fleet flying', 'Services hub'],
    'services.lead'  => ['Page intro', 'textarea', 'Maintenance, training, support and advisory work, each scoped in writing and closed with a record the receiving unit keeps.', 'Services hub'],
    'services.h2'    => ['Section heading', 'text', 'Choose a service', 'Services hub'],
    'services.lead2' => ['Section intro', 'textarea', 'Every service has its own page with the scope, what is included and how it is delivered.', 'Services hub'],

    // ------------------------------------------------------------- other pages
    'training.h1'   => ['Page heading', 'text', 'A pathway, not a single course', 'Training page'],
    'training.lead' => ['Page intro', 'textarea', 'Four role-based tracks take a cohort from standards awareness to instructor sign-off, each closing with an individual competency file.', 'Training page'],

    'standards.h1'   => ['Page heading', 'text', 'Standards are the baseline. Evidence is the deliverable.', 'Standards page'],
    'standards.lead' => ['Page intro', 'textarea', '“Aligned” is not “certified”. We keep the distinction visible and hand over the evidence file an audit team will ask for.', 'Standards page'],

    'contact.h1'   => ['Page heading', 'text', 'Start a technical conversation', 'Contact page'],
    'contact.lead' => ['Page intro', 'textarea', 'Tell us the operating problem, the environment and the timeline. A regional technical lead will respond with the right data sheet, briefing or SLA scope — not a generic brochure.', 'Contact page'],

    'apply.h1'   => ['Page heading', 'text', 'Apply for a place, or request a briefing', 'Apply page'],
    'apply.lead' => ['Page intro', 'textarea', 'One form for two things: an individual applying for a training track, and an organisation asking for a capability briefing or proposal. Applications are screened before a place or a date is confirmed.', 'Apply page'],

    'logistics.h1'   => ['Page heading', 'text', 'From clearance to commissioned system', 'Logistics page'],
    'logistics.lead' => ['Page intro', 'textarea', 'Getting equipment into the country is the easy part. Making it work with the sensors, radios and command posts already in service is where programmes usually stall.', 'Logistics page'],

    // ------------------------------------------------------------ the company
    'comp.title' => ['About heading', 'text', 'A regional engineering team, not a catalogue', 'About page'],
    'comp.lead'  => ['About intro', 'textarea', 'EPiTEK Solutions supports aviation and air-force fleets across East Africa. Our work is maintenance, integration and training — delivered close to the aircraft rather than from another continent.', 'About page'],
];

/** Effective text for a key, falling back to the built-in default. */
function t(string $key): string
{
    global $TEXT;
    $default = isset($TEXT[$key]) ? (string) $TEXT[$key][2] : '';
    return c($key, $default);
}

/** Link to the console editor, focused on one page group. */
function edit_link(string $group): string
{
    return url('/epiadmin/content.php?group=' . rawurlencode($group));
}
