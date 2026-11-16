<?php
declare(strict_types=1);

/**
 * Editorial content, kept separate from markup so it can be reviewed safely.
 * One page = one subject. Long-form material lives on its own page.
 */

$CAPABILITIES = [
    [
        'slug'    => 'air-force',
        'path'    => '/capabilities/air-force',
        'name'    => 'Air force & unmanned systems',
        'short'   => 'Air force',
        'tagline' => 'Squadron support and uncrewed systems',
        'summary' => 'Squadron-level technical support, surveillance payloads and uncrewed systems integration for mixed fleets across East Africa.',
        'intro'   => 'Regional air forces fly small, mixed fleets where every airframe carries more tasking than it was bought for. We work to that reality: keeping crews supported, payloads serviceable and uncrewed systems integrated into the chain of command that already exists.',
        'img'     => 'assets/img/tactical-uas.svg',
        'alt'     => 'Technical schematic of a quad-rotor unmanned system with payload and navigation annotations',
        'meta'    => ['Squadron support', 'UAS integration', 'ISR payloads'],
        'sections' => [
            [
                'h' => 'What we support',
                'p' => 'Support is scoped around the aircraft and systems already on strength, not a preferred product list.',
                'list' => [
                    'Squadron-level technical support and fault diagnosis',
                    'Surveillance payload integration and calibration',
                    'Uncrewed system commissioning and acceptance testing',
                    'Operator and maintainer training with a retained competency file',
                ],
            ],
            [
                'h' => 'How an engagement runs',
                'p' => 'We start with a written inventory and a fault history, then agree what "serviceable" means for each platform before work begins. Deliverables are documented, so a later audit can follow the same trail.',
            ],
        ],
    ],
    [
        'slug'    => 'aviation-software',
        'path'    => '/capabilities/aviation-software',
        'name'    => 'Aviation software & avionics',
        'short'   => 'Aviation',
        'tagline' => 'Mission planning, moving maps, cockpit awareness',
        'summary' => 'Mission planning, digital moving maps and cockpit situational awareness that modernise a fleet without replacing the airframe.',
        'intro'   => 'Most of the value in a fleet upgrade sits in the cockpit, not in a new airframe. We integrate mission software and avionics around the aircraft you already operate, and hand over the interface documentation an audit will ask for.',
        'img'     => 'assets/img/avionics.svg',
        'alt'     => 'Cockpit mission display with moving map, route and telemetry readouts',
        'meta'    => ['Mission planning', 'Moving maps', 'Avionics integration'],
        'sections' => [
            [
                'h' => 'What we deliver',
                'p' => 'Software and integration work scoped to the platform, the role and the airspace it flies in.',
                'list' => [
                    'Digital moving map with terrain and obstacle awareness',
                    'Route and mission planning, including off-line planning',
                    'Terrain-aided navigation for degraded satellite conditions',
                    'Cockpit situational awareness displays',
                    'Interface control and installation documentation',
                ],
            ],
            [
                'h' => 'Integration without a rebuild',
                'p' => 'Work is sequenced so the aircraft stays available: survey the existing avionics, agree the interfaces, install and test on the ground, then fly a defined acceptance profile. Nothing is declared complete until the receiving unit signs the record.',
            ],
        ],
    ],
    [
        'slug'    => 'command-surveillance',
        'path'    => '/capabilities/command-surveillance',
        'name'    => 'Command & surveillance',
        'short'   => 'Command',
        'tagline' => 'Sensors, command posts and coastal awareness',
        'summary' => 'Radar, sensor and command-post integration into a single operational picture, including coastal surveillance and hardened mission networks.',
        'intro'   => 'A sensor is only useful if the picture reaches the person making the decision. We integrate radar, electro-optical payloads and command posts so that air, land and maritime tasking share one picture instead of three disconnected ones.',
        'img'     => 'assets/img/c4isr.svg',
        'alt'     => 'Network diagram linking command, radar and surveillance nodes in a common operating picture',
        'meta'    => ['Sensor integration', 'Command posts', 'Coastal awareness'],
        'sections' => [
            [
                'h' => 'Where we work',
                'p' => 'Integration is planned around the operational question, then engineered backwards to the sensors and links that answer it.',
                'list' => [
                    'Radar and electro-optical sensor integration',
                    'Command-post layout, displays and operating procedures',
                    'Coastal and maritime surveillance coverage planning',
                    'Hardened mission networks and link management',
                ],
            ],
            [
                'h' => 'Coverage before equipment',
                'p' => 'Coverage studies come first: what must be seen, from where, with what reaction time. Only then does the sensor mix follow. This keeps procurement tied to a requirement rather than to a catalogue.',
            ],
        ],
    ],
    [
        'slug'    => 'depot-sustainment',
        'path'    => '/capabilities/depot-sustainment',
        'name'    => 'Depot & sustainment',
        'short'   => 'Sustainment',
        'tagline' => 'Depot maintenance, spares and field teams',
        'summary' => 'Depot-level maintenance, calibration, spares holding and mobile engineering teams that shorten the repair loop.',
        'intro'   => 'Availability is decided after delivery, in the depot. We build the maintenance loop around the fleet: what is repaired at depot, what is repaired forward, what is held as spares, and who signs the aircraft back to service.',
        'img'     => 'assets/img/depot.svg',
        'alt'     => 'Depot maintenance bench with calibration, battery management and traceability callouts',
        'meta'    => ['Depot MRO', 'Spares & calibration', 'Field teams'],
        'sections' => [
            [
                'h' => 'The maintenance loop',
                'p' => 'Each element is defined in writing so the boundary between operator, depot and supplier is never in question during a fault.',
                'list' => [
                    'Depot-level maintenance and structural repair',
                    'Sensor, antenna and test-equipment calibration',
                    'Spares modelling, holding and dispatch',
                    'Battery management and service-life tracking',
                    'Mobile engineering teams for forward repair',
                ],
            ],
            [
                'h' => 'Records that survive handover',
                'p' => 'Every intervention produces a record: what failed, what was replaced, what was measured, who released the asset. Those records are the difference between a maintained fleet and a fleet that is assumed to be maintained.',
            ],
        ],
    ],
];

$SERVICES = [
    [
        'slug'    => 'avionics-maintenance',
        'path'    => '/services/avionics-maintenance',
        'title'   => 'Avionics maintenance engineering',
        'short'   => 'Avionics maintenance',
        'summary' => 'Line-replaceable unit diagnostics, sensor calibration and structural repair protocols at depot or on site.',
        'intro'   => 'Fault isolation done properly the first time, with the test evidence to prove the unit is serviceable before it goes back on the aircraft.',
        'img'     => 'assets/img/depot.svg',
        'alt'     => 'Maintenance bench with calibration and traceability callouts',
        'meta'    => ['Depot or on site', 'Calibration included'],
        'sections' => [
            [
                'h' => 'Included in the scope',
                'p' => 'Scoped per platform and per test equipment available at the site.',
                'list' => [
                    'Line-replaceable unit fault isolation and repair',
                    'Sensor and antenna calibration',
                    'Structural repair protocols',
                    'Return-to-service documentation',
                ],
            ],
        ],
    ],
    [
        'slug'    => 'compliance-training',
        'path'    => '/services/compliance-training',
        'title'   => 'ICAO & EUROCONTROL compliance training',
        'short'   => 'Compliance training',
        'summary' => 'Training for air traffic coordinators and air force staff on shared-airspace deconfliction and evidence retention.',
        'intro'   => 'Shared airspace is a procedure problem before it is an equipment problem. This track gives civil and military staff the same vocabulary and the same record-keeping discipline.',
        'img'     => 'assets/img/avionics.svg',
        'alt'     => 'Mission planning display used for airspace coordination training',
        'meta'    => ['Civil and military', 'In-country delivery'],
        'sections' => [
            [
                'h' => 'Course content',
                'p' => 'Delivered as a cohort course with an assessment at the end of each module.',
                'list' => [
                    'Shared-airspace deconfliction procedures',
                    'ICAO standards and recommended practices',
                    'EUROCONTROL interoperability and coordination',
                    'Assessment and evidence records',
                ],
            ],
        ],
    ],
    [
        'slug'    => 'uas-training',
        'path'    => '/services/uas-training',
        'title'   => 'UAS operator certification',
        'short'   => 'UAS operator training',
        'summary' => 'Simulation and live flight training covering emergency recovery, payload management and multi-platform command.',
        'intro'   => 'Operators are certified against a written standard, with simulation used for the situations that are unsafe or expensive to rehearse in the air.',
        'img'     => 'assets/img/tactical-uas.svg',
        'alt'     => 'Unmanned system schematic used in operator training',
        'meta'    => ['Simulation first', 'Assessed per operator'],
        'sections' => [
            [
                'h' => 'Competencies assessed',
                'p' => 'Each operator closes the course with an individual competency file held by the receiving unit.',
                'list' => [
                    'Mission planning and pre-flight discipline',
                    'Emergency and lost-link recovery',
                    'Payload release and safety drills',
                    'Multi-platform command',
                ],
            ],
        ],
    ],
    [
        'slug'    => 'support-desk',
        'path'    => '/services/support-desk',
        'title'   => 'Technical support desk',
        'short'   => 'Support desk',
        'summary' => 'Tiered response windows, named escalation owners and a duty engineer for critical platform failures.',
        'intro'   => 'A support agreement that names the person who answers, the window they answer within, and the point at which a spares decision is taken.',
        'img'     => 'assets/img/c4isr.svg',
        'alt'     => 'Command and support network diagram',
        'meta'    => ['Tier 1 target under 2 h', 'Monthly reporting'],
        'sections' => [
            [
                'h' => 'What the agreement covers',
                'p' => 'Terms are agreed per platform and per deployment, then written into the SLA.',
                'list' => [
                    'Tier 1 response under two hours (target)',
                    'Remote diagnostics and configuration support',
                    'Spares dispatch and repair tracking',
                    'Monthly service reporting',
                ],
            ],
        ],
    ],
    [
        'slug'    => 'advisory',
        'path'    => '/services/advisory',
        'title'   => 'Technical advisory',
        'short'   => 'Advisory',
        'summary' => 'Needs analysis and requirement definition for ministries, civil aviation authorities and border services.',
        'intro'   => 'Most procurement problems begin as a requirement problem. Advisory work produces a defensible requirement before equipment is discussed.',
        'img'     => 'assets/img/hero-terrain.svg',
        'alt'     => 'Contour map used for coverage and requirement studies',
        'meta'    => ['Decision document', 'Cost envelope included'],
        'sections' => [
            [
                'h' => 'Typical studies',
                'p' => 'Each engagement closes with an options appraisal, a cost envelope and a risk register.',
                'list' => [
                    'Airspace and fleet capability studies',
                    'Border and perimeter coverage design',
                    'Maritime domain awareness studies',
                    'Capability roadmap and cost modelling',
                ],
            ],
        ],
    ],
];

$STANDARDS = [
    ['standard' => 'ICAO', 'scope' => 'Airspace and aviation standards', 'apply' => 'Interface, documentation and training alignment for civil and military airspace users', 'status' => 'Aligned'],
    ['standard' => 'EUROCONTROL', 'scope' => 'ATM interoperability', 'apply' => 'Shared-airspace deconfliction and coordination procedures', 'status' => 'Referenced'],
    ['standard' => 'NATO STANAG', 'scope' => 'Coalition interoperability', 'apply' => 'Tactical data link and command-interface conventions', 'status' => 'Referenced'],
    ['standard' => 'ISO 9001', 'scope' => 'Quality management', 'apply' => 'Quality processes mapped to audited procedures; certificates supplied on request', 'status' => 'Aligned'],
    ['standard' => 'AS9100', 'scope' => 'Aerospace quality', 'apply' => 'Aerospace-grade process controls where applicable; certificates supplied on request', 'status' => 'Aligned'],
    ['standard' => 'National export controls', 'scope' => 'Military and dual-use items', 'apply' => 'End-user screening and authorisation tracking before any release', 'status' => 'Required'],
];

$METRICS = [
    ['value' => '5', 'label' => 'Countries served', 'detail' => 'Kenya, Uganda, Tanzania, Rwanda and Ethiopia'],
    ['value' => '4', 'label' => 'Capability areas', 'detail' => 'One regional programme office'],
    ['value' => '< 2 h', 'label' => 'Tier 1 response target', 'detail' => 'Service objective, agreed per agreement'],
    ['value' => '1 day', 'label' => 'Enquiry response', 'detail' => 'Working day, regional team'],
];

$COUNTRIES = [
    ['code' => 'KE', 'name' => 'Kenya', 'role' => 'Regional coordination', 'note' => 'Nairobi'],
    ['code' => 'UG', 'name' => 'Uganda', 'role' => 'Engineering support', 'note' => 'Kampala'],
    ['code' => 'TZ', 'name' => 'Tanzania', 'role' => 'Field operations', 'note' => 'Dodoma'],
    ['code' => 'RW', 'name' => 'Rwanda', 'role' => 'Training delivery', 'note' => 'Kigali'],
    ['code' => 'ET', 'name' => 'Ethiopia', 'role' => 'Programme liaison', 'note' => 'Addis Ababa'],
];

$WHY = [
    ['n' => '01', 'title' => 'Engineering in theatre', 'desc' => 'Engineers and instructors based in the region, not flown in per project.'],
    ['n' => '02', 'title' => 'One programme office', 'desc' => 'Requirement, integration, training and sustainment under a single accountable team.'],
    ['n' => '03', 'title' => 'Evidence, not slogans', 'desc' => 'Acceptance tests, calibration records and training files documented and available for audit.'],
    ['n' => '04', 'title' => 'Built for sovereign procurement', 'desc' => 'Work delivered within national procurement rules, end-user controls and dual-use authorisation.'],
];

$TRAINING_PATHS = [
    ['phase' => 'Foundation', 'title' => 'Standards and shared airspace', 'desc' => 'ICAO and EUROCONTROL framework, airspace coordination and civil-military deconfliction.'],
    ['phase' => 'Operator', 'title' => 'UAS operator certification', 'desc' => 'Mission planning, emergency and lost-link recovery, payload management and multi-platform command.'],
    ['phase' => 'Maintainer', 'title' => 'Avionics and depot maintenance', 'desc' => 'LRU diagnostics, sensor calibration, structural repair protocols and return-to-service documentation.'],
    ['phase' => 'Instructor', 'title' => 'Instructor and assessor', 'desc' => 'Train-the-trainer, assessment design and the evidence file that keeps a capability alive after handover.'],
];

$FAQ = [
    [
        'q' => 'What does EPiTEK actually do?',
        'a' => 'We support aviation and air-force fleets in East Africa: mission software and avionics integration, uncrewed systems support, command and surveillance integration, and depot-level sustainment with training.',
    ],
    [
        'q' => 'Which countries are covered?',
        'a' => 'Kenya, Uganda, Tanzania, Rwanda and Ethiopia, coordinated from a single programme office in Nairobi.',
    ],
    [
        'q' => 'Can you support a fleet you did not supply?',
        'a' => 'Yes, subject to technical assessment and access to the necessary documentation, test equipment and spares. An initial inspection establishes what is realistic.',
    ],
    [
        'q' => 'How are export controls handled?',
        'a' => 'Every engagement is screened against national acquisition rules, end-user requirements and dual-use authorisation before technical material or a demonstration is released.',
    ],
];

$COMPANY = [
    'eyebrow' => 'The company',
    'title'   => 'A regional engineering team, not a catalogue',
    'lead'    => 'EPiTEK Solutions supports aviation and air-force fleets across East Africa. Our work is maintenance, integration and training — delivered close to the aircraft rather than from another continent.',
    'body'    => [
        'The company was formed around a simple observation: equipment arrives, but the capability to keep it serviceable often does not. Documentation goes missing, calibration capability is absent, and the first failure waits weeks for a part.',
        'We are structured to close that gap. One programme office holds the requirement, the integration engineering and the sustainment obligation, so a customer is not left coordinating three suppliers during a fault.',
    ],
    'list' => [
        'Aviation engineers and instructors based in the region',
        'Integration into the fleet and infrastructure already in service',
        'Documentation and competency records retained by the receiving unit',
    ],
];
