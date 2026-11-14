<?php
declare(strict_types=1);

/**
 * Editorial content, kept separate from markup so it can be reviewed and edited safely.
 * Position: EPiTEK is STM's regional aviation and air-force support arm in East Africa.
 */

$COMPETENCIES = [
    [
        'n' => '01',
        'title' => 'Aviation software & avionics integration',
        'desc' => 'Mission planning, airborne moving maps and avionics integration into existing fixed and rotary wing fleets, delivered with the interface documentation an audit expects.',
        'domains' => ['aviation', 'air'],
    ],
    [
        'n' => '02',
        'title' => 'Fleet sustainment and depot support',
        'desc' => 'Depot-level maintenance, calibration, spares and field dispatch that shorten the repair loop for aircraft and unmanned systems.',
        'domains' => ['support'],
    ],
    [
        'n' => '03',
        'title' => 'Training and competency records',
        'desc' => 'Operator, maintainer and instructor training delivered in region, each cohort closed with an individual competency file retained by the receiving unit.',
        'domains' => ['aviation', 'support'],
    ],
    [
        'n' => '04',
        'title' => 'Procurement and logistics',
        'desc' => 'Hardware and software acquisition that follows national procurement frameworks, from requirement definition to delivery, acceptance and end-user documentation.',
        'domains' => ['support'],
    ],
];

$PORTFOLIO = [
    [
        'slug' => 'focusflite',
        'name' => 'FocusFlite',
        'role' => 'Mission software',
        'desc' => 'Modular mission software for rotary and fixed-wing platforms: digital moving map, route planning, cockpit situational awareness and legacy cockpit modernisation.',
        'img' => 'assets/img/avionics.svg',
        'alt' => 'Cockpit mission display with moving map, flight route and telemetry readouts',
        'domains' => ['aviation'],
        'tag' => 'Aviation software',
        'cta' => 'Book software architecture briefing',
    ],
    [
        'slug' => 'togan',
        'name' => 'TOGAN',
        'role' => 'Reconnaissance UAS',
        'desc' => 'Surveillance and reconnaissance platform for border patrol, dense terrain and wide-area overwatch, with onboard computer vision for contested environments.',
        'img' => 'assets/img/c4isr.svg',
        'alt' => 'Network diagram linking command, radar, UAS and naval nodes in a common operating picture',
        'domains' => ['air', 'cyber'],
        'tag' => 'ISR',
        'cta' => 'Request tactical data sheet',
    ],
    [
        'slug' => 'kargu',
        'name' => 'KARGU',
        'role' => 'Tactical loitering munition',
        'desc' => 'Rotary-wing loitering munition for precision engagement and reconnaissance, designed to keep working when satellite navigation is jammed or degraded.',
        'img' => 'assets/img/uas-kargu.svg',
        'alt' => 'Technical schematic of a quad-rotor tactical unmanned aerial system with EO/IR payload annotations',
        'domains' => ['air'],
        'tag' => 'Autonomous systems',
        'cta' => 'Request tactical data sheet',
    ],
    [
        'slug' => 'boyga',
        'name' => 'BOYGA',
        'role' => 'Precision payload UAS',
        'desc' => 'Mortar payload delivery from the air for asymmetric operations, with stabilised drop and operator-controlled release.',
        'img' => 'assets/img/depot.svg',
        'alt' => 'Line drawing of a UAS on a maintenance bench with calibration and battery management callouts',
        'domains' => ['air'],
        'tag' => 'Payload delivery',
        'cta' => 'Request tactical data sheet',
    ],
    [
        'slug' => 'alpagub',
        'name' => 'ALPAGU-B',
        'role' => 'Fixed-wing loitering munition',
        'desc' => 'Fixed-wing loitering munition for extended endurance surveillance and precision engagement over perimeter and corridor sectors.',
        'img' => 'assets/img/hero-terrain.svg',
        'alt' => 'Dark terrain contour map with navigation vectors and waypoint markers',
        'domains' => ['air'],
        'tag' => 'Autonomous systems',
        'cta' => 'Request tactical data sheet',
    ],
];

$STANDARDS = [
    ['standard' => 'ICAO', 'scope' => 'Airspace and aviation standards', 'apply' => 'Interface, documentation and training alignment for civil and military airspace users', 'status' => 'Aligned'],
    ['standard' => 'EUROCONTROL', 'scope' => 'ATM interoperability', 'apply' => 'Shared-airspace deconfliction and coordination procedures', 'status' => 'Referenced'],
    ['standard' => 'NATO STANAG', 'scope' => 'Coalition interoperability', 'apply' => 'Tactical data links and command-and-control interface conventions', 'status' => 'Referenced'],
    ['standard' => 'ISO 9001', 'scope' => 'Quality management', 'apply' => 'Quality processes mapped to audited procedures; certificates supplied on request', 'status' => 'Aligned'],
    ['standard' => 'AS9100', 'scope' => 'Aerospace quality', 'apply' => 'Aerospace-grade process controls where applicable; certificates supplied on request', 'status' => 'Aligned'],
    ['standard' => 'National export-control regimes', 'scope' => 'Military and dual-use items', 'apply' => 'End-user screening and authorisation tracking before any release', 'status' => 'Required'],
];

$METRICS = [
    ['value' => '5', 'label' => 'Countries served', 'detail' => 'Kenya, Uganda, Tanzania, Rwanda and Ethiopia'],
    ['value' => '4', 'label' => 'Capability areas', 'detail' => 'One regional programme office'],
    ['value' => '< 2 h', 'label' => 'Tier 1 response target', 'detail' => 'Service objective, agreed per support agreement'],
    ['value' => '1 day', 'label' => 'Standard enquiry response', 'detail' => 'Working day, regional technical team'],
];

$SERVICES = [
    [
        'slug' => 'avionics-maintenance',
        'title' => 'Avionics maintenance engineering',
        'desc' => 'Line-replaceable unit diagnostics, field sensor calibration and structural repair protocols, performed at depot or on site.',
        'items' => ['LRU fault isolation and repair', 'Sensor and antenna calibration', 'Structural repair protocols', 'Return-to-service documentation'],
        'domains' => ['support', 'aviation'],
    ],
    [
        'slug' => 'icao-compliance',
        'title' => 'ICAO and EUROCONTROL compliance modules',
        'desc' => 'Training for air traffic coordinators and air force staff on shared airspace deconfliction, standards and evidence retention.',
        'items' => ['Shared airspace deconfliction', 'ICAO standards and recommended practices', 'EUROCONTROL interoperability and coordination', 'Assessment and evidence records'],
        'domains' => ['aviation'],
    ],
    [
        'slug' => 'uas-pilot',
        'title' => 'Tactical UAS operator certification',
        'desc' => 'Simulation and live flight training covering emergency recovery, payload management and multi-platform command, delivered against a documented competency record.',
        'items' => ['Mission planning and pre-flight discipline', 'Emergency and lost-link recovery', 'Payload release and safety drills', 'Multi-platform command'],
        'domains' => ['air'],
    ],
    [
        'slug' => 'support-desk',
        'title' => 'Technical support desk',
        'desc' => 'SLA tiers with defined response windows, escalation paths and a duty engineer for critical platform failures.',
        'items' => ['Tier 1 response under two hours (target)', 'Remote diagnostics and configuration support', 'Spares dispatch and repair tracking', 'Monthly service reporting'],
        'domains' => ['support'],
    ],
    [
        'slug' => 'advisory',
        'title' => 'Technical advisory and aviation consulting',
        'desc' => 'Needs analysis for ministries and civil aviation authorities across airspace modernisation, border security and maritime domain awareness.',
        'items' => ['Airspace and fleet capability studies', 'Border security perimeter design', 'Maritime domain awareness studies', 'Capability roadmap and cost modelling'],
        'domains' => ['cyber', 'support'],
    ],
];

$COUNTRIES = [
    ['code' => 'KE', 'name' => 'Kenya', 'role' => 'Regional coordination', 'note' => 'Nairobi'],
    ['code' => 'UG', 'name' => 'Uganda', 'role' => 'Engineering support', 'note' => 'Kampala'],
    ['code' => 'TZ', 'name' => 'Tanzania', 'role' => 'Field operations', 'note' => 'Dodoma'],
    ['code' => 'RW', 'name' => 'Rwanda', 'role' => 'Training delivery', 'note' => 'Kigali'],
    ['code' => 'ET', 'name' => 'Ethiopia', 'role' => 'Programme liaison', 'note' => 'Addis Ababa'],
];

$WHY = [
    ['n' => '01', 'title' => 'A regional arm, not a distant supplier', 'desc' => 'Mission software, integration, training and sustainment sit under one regional programme office, not four vendors.'],
    ['n' => '02', 'title' => 'Engineering in theatre', 'desc' => 'Factory-trained engineers and test facilities positioned to respond within the region, not across a continent.'],
    ['n' => '03', 'title' => 'Evidence, not slogans', 'desc' => 'Acceptance tests, calibration records and training files are documented and available for audit.'],
    ['n' => '04', 'title' => 'Built for sovereign procurement', 'desc' => 'Work delivered within national procurement rules, end-user controls and dual-use authorisations.'],
];

$BRIEFS = [
    [
        'topic' => 'Aviation software',
        'title' => 'Modernising a legacy cockpit without replacing the airframe',
        'desc' => 'Where mission software creates value first, and how to scope a digital moving map upgrade around existing avionics.',
        'read' => 'Request this brief',
    ],
    [
        'topic' => 'Standards',
        'title' => 'ICAO and EUROCONTROL alignment: what procurement teams should ask for',
        'desc' => 'How to read a compliance statement, which evidence to request, and where alignment ends and certification begins.',
        'read' => 'Request this brief',
    ],
    [
        'topic' => 'Sustainment',
        'title' => 'Designing a regional maintenance depot that actually reduces downtime',
        'desc' => 'Spares modelling, calibration capability, battery management and the handover files that keep fleets serviceable.',
        'read' => 'Request this brief',
    ],
];

$TRAINING_PATHS = [
    ['phase' => 'Foundation', 'title' => 'Standards and shared airspace', 'desc' => 'ICAO and EUROCONTROL framework, airspace coordination and civil-military deconfliction for air force and civil staff.'],
    ['phase' => 'Operator', 'title' => 'UAS operator certification', 'desc' => 'Mission planning, emergency and lost-link recovery, payload management and multi-platform command, assessed per operator.'],
    ['phase' => 'Maintainer', 'title' => 'Avionics and depot maintenance', 'desc' => 'LRU diagnostics, sensor calibration, structural repair protocols and return-to-service documentation.'],
    ['phase' => 'Instructor', 'title' => 'Instructor and assessor', 'desc' => 'Train-the-trainer, assessment design and the evidence file that keeps a capability alive after handover.'],
];

$FAQ = [
    [
        'q' => 'What is EPiTEK\u2019s role in East Africa?',
        'a' => 'EPiTEK Solutions is the regional support arm for the STM portfolio in East Africa: distribution, integration, training and lifecycle support for aviation and unmanned systems. Partnership and authorisation statements are supported by documentation on request during procurement.',
    ],
    [
        'q' => 'Which countries are covered?',
        'a' => 'The regional footprint covers Kenya, Uganda, Tanzania, Rwanda and Ethiopia. Delivery models combine regional coordination, in-country engineering support and depot-level maintenance.',
    ],
    [
        'q' => 'Can EPiTEK support an existing fleet rather than a new acquisition?',
        'a' => 'Yes. Sustainment, avionics diagnostics, calibration, spare-parts management and training can be contracted independently of a new platform purchase.',
    ],
    [
        'q' => 'How are export controls handled?',
        'a' => 'Every engagement is screened against national sovereign acquisition rules, end-user requirements and dual-use export authorisations before a technical data sheet or demonstration is released.',
    ],
];
