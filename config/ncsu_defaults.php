<?php 

$site_url = parse_url( get_site_url(), PHP_URL_HOST);

// List of aXe rules: https://github.com/dequelabs/axe-core/blob/develop/doc/rule-descriptions.md
// List of additional rules: https://design.oit.ncsu.edu/docs/a11y-helper/rules

$list_of_tests = array(
    // aXe tests
    'accesskeys',
    'area-alt',
    'aria-allowed-attr',
    'aria-hidden-body',
    'aria-required-attr',
    'aria-required-children',
    'aria-required-parent',
    'aria-roles',
    'aria-valid-attr-value',
    'aria-valid-attr',
    'audio-caption',
    'blink',
    'button-name',
    'bypass',
    'checkboxgroup',
    'color-contrast',
    'definition-list',
    'dlitem',
    'document-title',
    'duplicate-id',
    'empty-heading',
    'frame-title-unique',
    'frame-title',
    'heading-order',
    'hidden-content',
    'href-no-hash',
    'html-has-lang',
    'html-lang-valid',
    'image-alt',
    'image-redundant-alt',
    'input-image-alt',
    'label-title-only',
    'label',
    'layout-table',
    'link-in-text-block',
    'link-name',
    'list',
    'listitem',
    'marquee',
    'meta-refresh',
    'meta-viewport-large',
    'meta-viewport',
    'object-alt',
    'p-as-heading',
    'radiogroup',
    'region',
    'scope-attr-valid',
    'server-side-image-map',
    'skip-link',
    'tabindex',
    'table-duplicate-name',
    'table-fake-caption',
    'td-has-header',
    'td-header-attrs',
    'th-has-data-cells',
    'valid-lang',
    'video-caption',
    'video-description',

    // Additional tests
    'ncsu_skipped_heading',
    'ncsu_multiple_h1',
    'ncsu_empty_alt',
    'ncsu_reminder_alt',
    'ncsu_reminder_table',
    );

$ncsu_defaults = array();

// Replace each help link with a Go Link for statistics gathering
foreach ( $list_of_tests as $test ) {
    $ncsu_defaults[$test] = array(
        'helpUrl' => 'http://go.ncsu.edu/a11y_' . $test . ':' . $site_url
    );
}
