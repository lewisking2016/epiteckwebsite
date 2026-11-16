<?php
declare(strict_types=1);

/**
 * Field registry for the console editor.
 *
 * Pulls the labelled page copy from includes/text.php and the structured lists
 * (capabilities, services, FAQ) from includes/content.php. Defaults come from
 * the pristine copies, so "reset" always restores the text that shipped.
 *
 * @return array<string,array<int,array{key:string,label:string,type:string,default:string}>>
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

    $groups = [];

    foreach (($TEXT ?? []) as $key => $def) {
        $groups[(string) $def[3]][] = [
            'key'     => $key,
            'label'   => (string) $def[0],
            'type'    => (string) $def[1],
            'default' => (string) $def[2],
        ];
    }

    foreach ($caps as $cap) {
        $group = 'Capability — ' . (string) $cap['name'];
        $groups[$group][] = ['key' => 'cap.' . $cap['slug'] . '.name', 'label' => 'Area name', 'type' => 'text', 'default' => (string) $cap['name']];
        $groups[$group][] = ['key' => 'cap.' . $cap['slug'] . '.tagline', 'label' => 'Tagline', 'type' => 'text', 'default' => (string) $cap['tagline']];
        $groups[$group][] = ['key' => 'cap.' . $cap['slug'] . '.summary', 'label' => 'Card summary', 'type' => 'textarea', 'default' => (string) $cap['summary']];
        $groups[$group][] = ['key' => 'cap.' . $cap['slug'] . '.intro', 'label' => 'Opening paragraph', 'type' => 'textarea', 'default' => (string) $cap['intro']];
    }

    foreach ($svcs as $svc) {
        $group = 'Service — ' . (string) $svc['title'];
        $groups[$group][] = ['key' => 'svc.' . $svc['slug'] . '.title', 'label' => 'Service name', 'type' => 'text', 'default' => (string) $svc['title']];
        $groups[$group][] = ['key' => 'svc.' . $svc['slug'] . '.summary', 'label' => 'Card summary', 'type' => 'textarea', 'default' => (string) $svc['summary']];
        $groups[$group][] = ['key' => 'svc.' . $svc['slug'] . '.intro', 'label' => 'Opening paragraph', 'type' => 'textarea', 'default' => (string) $svc['intro']];
    }

    foreach ($faq as $i => $item) {
        $n = $i + 1;
        $groups['FAQ'][] = ['key' => 'faq.' . $n . '.q', 'label' => 'Question ' . $n, 'type' => 'text', 'default' => (string) $item['q']];
        $groups['FAQ'][] = ['key' => 'faq.' . $n . '.a', 'label' => 'Answer ' . $n, 'type' => 'textarea', 'default' => (string) $item['a']];
    }

    foreach (($company['body'] ?? []) as $i => $paragraph) {
        $n = $i + 1;
        $groups['About page'][] = [
            'key'     => 'comp.p' . $n,
            'label'   => 'Paragraph ' . $n,
            'type'    => 'textarea',
            'default' => (string) $paragraph,
        ];
    }

    return $groups;
}

/** All registered field keys, used to validate form submissions. */
function content_field_keys(): array
{
    $keys = [];
    foreach (content_field_groups() as $fields) {
        foreach ($fields as $field) {
            $keys[$field['key']] = $field;
        }
    }
    return $keys;
}
