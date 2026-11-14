<?php
declare(strict_types=1);

/**
 * EPI — the site assistant.
 *
 * Fixed answer set only: visitors pick a question, EPI replies from this file.
 * There is deliberately no free-text field, no model call and no network request,
 * so every answer is predictable, reviewable and cannot invent a claim.
 *
 * @return array<string,array{label:string,questions:array<int,array{id:string,q:string,a:string,link?:string,linkLabel?:string}>}>
 */
function epi_questions(): array
{
    return [
        'company' => [
            'label' => 'The company',
            'questions' => [
                [
                    'id' => 'what',
                    'q' => 'What does EPiTEK do?',
                    'a' => 'EPiTEK Solutions is the East African regional support arm for STM. We cover aviation mission software and avionics integration, air-force and unmanned systems support, command and surveillance integration, and depot-level sustainment for air forces and civil aviation operators.',
                    'link' => '/partnerships-stm',
                    'linkLabel' => 'How the STM partnership works',
                ],
                [
                    'id' => 'where',
                    'q' => 'Where do you operate?',
                    'a' => 'From Nairobi, Kenya, across five markets: Kenya, Uganda, Tanzania, Rwanda and Ethiopia. Work is delivered on site with the operator and supported from the regional base.',
                    'link' => '/#coverage',
                    'linkLabel' => 'See the operating map',
                ],
                [
                    'id' => 'who',
                    'q' => 'Who do you work with?',
                    'a' => 'Air forces, civil aviation authorities, government buyers and the STM portfolio. Institutional buyers are screened for eligibility and end use before technical material is released.',
                    'link' => '/contact#compliance',
                    'linkLabel' => 'Compliance and eligibility',
                ],
            ],
        ],
        'services' => [
            'label' => 'Services',
            'questions' => [
                [
                    'id' => 'domains',
                    'q' => 'Which operational domains do you cover?',
                    'a' => 'Four: air force and unmanned systems, aviation software and avionics, command and surveillance, and depot and sustainment. Each is delivered with the operator rather than sold off a shelf.',
                    'link' => '/#portfolio',
                    'linkLabel' => 'Technology portfolio',
                ],
                [
                    'id' => 'uas',
                    'q' => 'Do you support UAS and ISR?',
                    'a' => 'Yes. Tactical UAS support, ISR payload integration and squadron-level employment, including operations in contested and GNSS-degraded environments.',
                    'link' => '/#domain-air',
                    'linkLabel' => 'Air force and unmanned systems',
                ],
                [
                    'id' => 'software',
                    'q' => 'What aviation software do you integrate?',
                    'a' => 'Mission planning, digital moving maps, terrain-aided navigation and cockpit situational awareness for fixed and rotary wing fleets, integrated with the aircraft and ground systems already in service.',
                    'link' => '/#domain-aviation',
                    'linkLabel' => 'Aviation software and avionics',
                ],
                [
                    'id' => 'sustainment',
                    'q' => 'What depot and sustainment work do you do?',
                    'a' => 'Depot-level MRO, spares and battery management, field dispatch and operator training, with the aim of keeping fleets serviceable between major inspections.',
                    'link' => '/#domain-support',
                    'linkLabel' => 'Depot and sustainment',
                ],
            ],
        ],
        'training' => [
            'label' => 'Training',
            'questions' => [
                [
                    'id' => 'courses',
                    'q' => 'What training is available?',
                    'a' => 'Four programmes: standards and shared airspace, UAS operator certification, avionics and depot maintenance, and instructor and assessor. Organisations can also request a tailored briefing.',
                    'link' => '/services#training',
                    'linkLabel' => 'Training and services',
                ],
                [
                    'id' => 'apply',
                    'q' => 'How do I apply for training?',
                    'a' => 'Use the application form and choose the training track. You will get a reference number immediately and the team aims to respond within one working day.',
                    'link' => '/apply',
                    'linkLabel' => 'Open the application form',
                ],
                [
                    'id' => 'org',
                    'q' => 'Can organisations request training?',
                    'a' => 'Yes. The same form has an organisation track for unit-level or fleet-level training requirements, including requests that start as an RFP.',
                    'link' => '/apply',
                    'linkLabel' => 'Organisation request',
                ],
            ],
        ],
        'working' => [
            'label' => 'Working with us',
            'questions' => [
                [
                    'id' => 'briefing',
                    'q' => 'How do I request a briefing?',
                    'a' => 'Send the requirement through the contact form with the operating environment and timeline. Enquiries are handled by the regional team in Nairobi, with a one working day response target.',
                    'link' => '/contact',
                    'linkLabel' => 'Contact the team',
                ],
                [
                    'id' => 'export',
                    'q' => 'Do you handle export compliance?',
                    'a' => 'Yes. Engagements run under national procurement rules and dual-use export control, with End-User Certificates supported and NDAs available before technical exchange.',
                    'link' => '/privacy',
                    'linkLabel' => 'How data is handled',
                ],
                [
                    'id' => 'nda',
                    'q' => 'Can we talk under NDA?',
                    'a' => 'Yes. Say so in your first message and the team will put the agreement in place before any technical detail is exchanged.',
                    'link' => '/contact',
                    'linkLabel' => 'Start the conversation',
                ],
                [
                    'id' => 'pricing',
                    'q' => 'Do you publish pricing?',
                    'a' => 'No. Scope, fleet size and support level change the shape of every engagement, so pricing is quoted against the requirement rather than listed on the site.',
                    'link' => '/contact',
                    'linkLabel' => 'Request a quote',
                ],
            ],
        ],
    ];
}

/** Render the assistant. Included once, from the global footer. */
function epi_render(): void
{
    $groups = epi_questions();
    ?>
<div class="Epi" data-epi>
  <button class="Epi_Launch" type="button" aria-expanded="false" aria-controls="Epi_Panel" aria-label="Open EPI, the EPiTEK assistant" data-epi-open>
    <span class="Epi_Launch_Avatar" aria-hidden="true">E</span>
    <span class="Epi_Launch_Txt"><b>EPI</b><span>Ask a question</span></span>
    <span class="Epi_Launch_Ping" aria-hidden="true"></span>
  </button>

  <section class="Epi_Panel" id="Epi_Panel" role="dialog" aria-labelledby="Epi_T" hidden>
    <header class="Epi_Hd">
      <span class="Epi_Avatar" aria-hidden="true">E</span>
      <div class="Epi_Hd_Txt">
        <h2 class="Epi_T" id="Epi_T">EPI</h2>
        <p>EPiTEK assistant · instant answers</p>
      </div>
      <button class="Epi_Close" type="button" aria-label="Close assistant" data-epi-close>
        <svg class="Ico" aria-hidden="true"><use href="#i-close"/></svg>
      </button>
    </header>

    <div class="Epi_Body" data-epi-body>
      <div class="Epi_Msg Epi_Msg--bot">
        <p>Hello — I am EPI. I answer the questions we are asked most about EPiTEK, our services and training. Pick one to start.</p>
      </div>

      <?php foreach ($groups as $group): ?>
        <div class="Epi_Group">
          <p class="Epi_Group_L"><?= e($group['label']) ?></p>
          <div class="Epi_Chips">
            <?php foreach ($group['questions'] as $question): ?>
              <button class="Epi_Chip" type="button"
                      data-epi-q="<?= e($question['id']) ?>"
                      data-epi-a="<?= e($question['a']) ?>"
                      data-epi-link="<?= e(($question['link'] ?? '') !== '' ? url($question['link']) : '') ?>"
                      data-epi-link-label="<?= e($question['linkLabel'] ?? '') ?>"><?= e($question['q']) ?></button>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <footer class="Epi_Foot">
      <a class="Epi_Foot_A" href="<?= e(url('/contact')) ?>">Talk to the team</a>
      <button class="Epi_Foot_Btn" type="button" data-epi-reset>Start over</button>
    </footer>
  </section>

  <p class="Epi_Status" role="status" aria-live="polite" data-epi-status></p>
</div>
    <?php
}
