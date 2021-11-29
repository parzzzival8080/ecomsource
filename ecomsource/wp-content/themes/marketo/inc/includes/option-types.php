<?php

if (!defined('ABSPATH'))
    wp_die('Direct access forbidden.');

function marketo_action_theme_include_custom_option_types() {
    if (is_admin()) {
        $dir = MARKETO_INC . '/includes';
        require_once $dir . '/option-types/new-icon/class-fw-option-type-new-icon.php';
    }
}

add_action('fw_option_types_init', 'marketo_action_theme_include_custom_option_types');




