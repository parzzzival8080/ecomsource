<?php
if (!defined('ABSPATH')) die('Direct access forbidden.');
/**
 * customizer option: optimization
 */


//Block Library Enable/Disable Option
$fields[]= array(
    'type'        => 'switch',
    'label'       =>esc_html__( 'Load Block Library css files?', 'marketo' ),
    'description' => esc_attr__( 'Do you want to load block library css files?', 'marketo' ),
    'settings'    => 'optimization_blocklibrary_enable',
    'section'     => 'optimization_section',
    'default'     => 1,
    'choices'     => array(
        'on'  => esc_attr__( 'Enable', 'marketo' ),
        'off' => esc_attr__( 'Disable', 'marketo' ),
    ),
);

//Fontawesome Enable/Disable Option
$fields[]= array(
    'type'        => 'switch',
    'label'       =>esc_html__( 'Load Fontawesome icons?', 'marketo' ),
    'description' => esc_attr__( 'Do you want to load font awesome icons?', 'marketo' ),
    'settings'    => 'optimization_fontawesome_enable',
    'section'     => 'optimization_section',
    'default'     => 1,
    'choices'     => array(
        'on'  => esc_attr__( 'Enable', 'marketo' ),
        'off' => esc_attr__( 'Disable', 'marketo' ),
    ),
);

//Elementor Icons Enable/Disable Option
$fields[]= array(
    'type'        => 'switch',
    'label'       =>esc_html__( 'Load Elementor Icons?', 'marketo' ),
    'description' => esc_attr__( 'Do you want to load elementor icons?', 'marketo' ),
    'settings'    => 'optimization_elementoricons_enable',
    'section'     => 'optimization_section',
    'default'     => 1,
    'choices'     => array(
        'on'  => esc_attr__( 'Enable', 'marketo' ),
        'off' => esc_attr__( 'Disable', 'marketo' ),
    ),
);

//Elementskit Icons Enable/Disable Option
$fields[]= array(
    'type'        => 'switch',
    'label'       =>esc_html__( 'Load Elementskit Icons?', 'marketo' ),
    'description' => esc_attr__( 'Do you want to load elementskit icons?', 'marketo' ),
    'settings'    => 'optimization_elementkitsicons_enable',
    'section'     => 'optimization_section',
    'default'     => 1,
    'choices'     => array(
        'on'  => esc_attr__( 'Enable', 'marketo' ),
        'off' => esc_attr__( 'Disable', 'marketo' ),
    ),
);

//Dash Icons Enable/Disable Option
$fields[]= array(
    'type'        => 'switch',
    'label'       =>esc_html__( 'Load Dash Icons?', 'marketo' ),
    'description' => esc_attr__( 'Do you want to load dash icons?', 'marketo' ),
    'settings'    => 'optimization_dashicons_enable',
    'section'     => 'optimization_section',
    'default'     => 1,
    'choices'     => array(
        'on'  => esc_attr__( 'Enable', 'marketo' ),
        'off' => esc_attr__( 'Disable', 'marketo' ),
    ),
);

$fields[]= array(
    'type'        => 'switch',
    'label'       =>esc_html__( 'Load Google Map API?', 'marketo' ),
    'description' => esc_attr__( 'If you are using google map api', 'marketo' ),
    'settings'    => 'optimization_google_api_enable',
    'section'     => 'optimization_section',
    'default'     => 1,
    'choices'     => array(
        'on'  => esc_attr__( 'Enable', 'marketo' ),
        'off' => esc_attr__( 'Disable', 'marketo' ),
    ),
);


