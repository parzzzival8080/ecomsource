<?php
/**
 * Tbay Framework Plugin
 *
 * A simple, truly extensible and fully responsive options framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     Tbay Framework Pro
 * Plugin URI:      https://thembay.com/tbay-framework-pro-plugin/
 * Description:     Tbay Framework. A plugin required to activate the functionality in the themes.
 * Author:          Team Thembay
 * Author URI:      https://thembay.com/
 * Version:         1.4.7
 * Text Domain:     tbay-framework
 * License:         GPL3+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     languages
 */

define( 'TBAY_FRAMEWORK_VERSION', '1.4.7');
define( 'TBAY_FRAMEWORK_URL', plugin_dir_url( __FILE__ ) ); 
define( 'TBAY_FRAMEWORK_DIR', plugin_dir_path( __FILE__ ) );
define( 'TBAY_FRAMEWORK_REDUX_ACTIVED', true );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

require_once( TBAY_FRAMEWORK_DIR . 'plugin-update-checker/plugin-update-checker.php' );
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://plugins.thembay.com/update/tbay-framework-pro/plugin.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'tbay-framework-pro'
);

/**
 * Custom Post type
 *
 */
add_action( 'init', 'tbay_framework_register_post_types', 1 );

/**
 * functions
 *
 */
require TBAY_FRAMEWORK_DIR . 'functions.php';
require TBAY_FRAMEWORK_DIR . 'functions-preset.php';
/**
 * Widgets Core
 *
 */
require TBAY_FRAMEWORK_DIR . 'classes/class-tbay-widgets.php';
add_action( 'widgets_init',  'tbay_framework_widget_init' );

require TBAY_FRAMEWORK_DIR . 'classes/class-tbay-megamenu.php';
require TBAY_FRAMEWORK_DIR . 'classes/createplaceholder.php';
/**
 * Init
 *
 */
if( ! function_exists( 'tbay_framework_init' ) ) {
	function tbay_framework_init() {
		$demo_mode = apply_filters( 'tbay_framework_register_demo_mode', false );
		if ( $demo_mode ) {
			tbay_framework_init_redux();
		}
		$enable_tax_fields = apply_filters( 'tbay_framework_enable_tax_fields', false );
		if ( $enable_tax_fields ) {
			if ( !class_exists( 'Taxonomy_MetaData_CMB2' ) ) {
				require_once TBAY_FRAMEWORK_DIR . 'libs/cmb2/taxonomy/Taxonomy_MetaData_CMB2.php';
			}
		}
	}
	add_action( 'init', 'tbay_framework_init', 100 );
}