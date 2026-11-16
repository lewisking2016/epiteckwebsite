<?php
declare(strict_types=1);

/**
 * The editable "pages" of the site, as the console lists them.
 *
 * One group = one public page (or the FAQ block). Each group carries its public
 * URL so the editor can offer "view page", and a stable id used in the URL of
 * the edit screen. Defaults always come from the pristine copies, so "reset"
 * restores the wording that shipped.
 *
 * @return array<int,array{id:string,label:string,path:string,fields:array<int,array{key:string,label:string,type:string,default:string}>}>
 */
function content_field_groups(): array
{
    global $TEXT;
    global $CAPABILITIES_DEFAULT, $SERVICES_DEFAULT, $FAQ_DEFAULT, $COMPANY_DEFAULT;
    global $CAPABILITIES, $SERVICES, $FAQ, $COMPANY;

    $caps    = $CAPABILITIES_DEFAULT ?? $CAPABILITIES ?? [];
    $svcs    = $SERVICES_DEFAULT ?? $SERVICES ?? [];
    $faq     = $FAQ_DEFAULT ?? $FAQ ?? [];
    $company = $COMPANY_DEFAULT ?? $COMPANY ?? [];

    /** Group label => [id, public path] for the plain page copy. */
    $map = [
        'Home page'       => ['home', '/'],
        'Capabilities hub' => ['capabilities', '/capabilities'],
        'Services hub'    => ['services', '/services'],
        'Training page'   => ['training', '/training'],
        'Standards page'  => ['standards', '/standards'],
        'Contact page'    => ['contact', '/contact'],
        'Apply page'      => ['apply', '/apply'],
        'Logistics page'  => ['logistics', '/distributor-integration'],
        'About page'      => ['about', '/about'],
    ];

    $groups = [];

    // Plain page copy, grouped in the order the site is navigated.
    foreach ($map as $label => [$id, $path]) {
        $groups[$label] = ['id' => $id, 'label' => $label, 'path' => $path, 'fields' => []];
    }

    foreach (($TEXT ?? []) as $key => $def) {
        $groupLabel = (string) $def[3];
        if (!isset($groups[$groupLabel])) {
            $groups[$groupLabel] = [
                'id'     => strtolower(preg_replace('/[^a-z0-9]+/i', '-', $groupLabel) ?? 'group'),
                'label'  => $groupLabel,
                'path'   => '/',
                'fields' => [],
            ];
        }
        $groups[$groupLabel]['fields'][] = [
            'key'     => $key,
            'label'   => (string) $def[0],
            'type'    => (string) $def[1],
            'default' => (string) $def[2],
        ];
    }

    // Structured lists: one group per capability and per service.
    foreach ($caps as $cap) {
        $groups['Capability — ' . (string) $cap['name']] = [
            'id'     => 'cap-' . $cap['slug'],
            'label'  => 'Capability — ' . (string) $cap['name'],
            'path'   => '/capabilities/' . $cap['slug'],
            'fields' => [
                ['key' => 'cap.' . $cap['slug'] . '.name', 'label' => 'Area name', 'type' => 'text', 'default' => (string) $cap['name']],
                ['key' => 'cap.' . $cap['slug'] . '.tagline', 'label' => 'Tagline', 'type' => 'text', 'default' => (string) $cap['tagline']],
                ['key' => 'cap.' . $cap['slug'] . '.summary', 'label' => 'Card summary', 'type' => 'textarea', 'default' => (string) $cap['summary']],
                ['key' => 'cap.' . $cap['slug'] . '.intro', 'label' => 'Opening paragraph', 'type' => 'textarea', 'default' => (string) $cap['intro']],
            ],
        ];
    }

    foreach ($svcs as $svc) {
        $groups['Service — ' . (string) $svc['title']] = [
            'id'     => 'svc-' . $svc['slug'],
            'label'  => 'Service — ' . (string) $svc['title'],
            'path'   => '/services/' . $svc['slug'],
            'fields' => [
                ['key' => 'svc.' . $svc['slug'] . '.title', 'label' => 'Service name', 'type' => 'text', 'default' => (string) $svc['title']],
                ['key' => 'svc.' . $svc['slug'] . '.summary', 'label' => 'Card summary', 'type' => 'textarea', 'default' => (string) $svc['summary']],
                ['key' => 'svc.' . $svc['slug'] . '.intro', 'label' => 'Opening paragraph', 'type' => 'textarea', 'default' => (string) $svc['intro']],
            ],
        ];
    }

    $faqFields = [];
    foreach ($faq as $i => $item) {
        $n = $i + 1;
        $faqFields[] = ['key' => 'faq.' . $n . '.q', 'label' => 'Question ' . $n, 'type' => 'text', 'default' => (string) $item['q']];
        $faqFields[] = ['key' => 'faq.' . $n . '.a', 'label' => 'Answer ' . $n, 'type' => 'textarea', 'default' => (string) $item['a']];
    }
    $groups['FAQ'] = ['id' => 'faq', 'label' => 'FAQ', 'path' => '/services', 'fields' => $faqFields];

    return array_values($groups);
}

/** Every registered field, keyed for validation. */
function content_field_keys(): array
{
    $keys = [];
    foreach (content_field_groups() as $group) {
        foreach ($group['fields'] as $field) {
            $field['group'] = $group['label'];
            $keys[$field['key']] = $field;
        }
    }
    return $keys;
}

/** One group by its URL id, or null. */
function content_group(string $id): ?array
{
    foreach (content_field_groups() as $group) {
        if ($group['id'] === $id) {
            return $group;
        }
    }
    return null;
}

/** How many fields in a group have been edited. */
function content_group_edited(array $group): int
{
    $n = 0;
    foreach ($group['fields'] as $field) {
        if (content_has($field['key'])) {
            $n++;
        }
    }
    return $n;
}
