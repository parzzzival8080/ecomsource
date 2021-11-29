<?php 
/**
 * Plugin Name: Sw tools plugin
 * Plugin URI: http://www.wpthemego.com/
 * Description: A plugin that allows user to create quick links for product categories, account information, shopping cart, search and more...
 * Version: 1.0.1
 * Author: wpthemego
 * Author URI: http://www.wpthemego.com/
 * Requires at least: 4.1
 * WC tested up to: 5.1
 * Text Domain: sw-tools-plugin
 * Domain Path: /languages/
 */
 
// define plugin path
if ( ! defined( 'SWTOOLPLUGINPATH' ) ) {
	define( 'SWTOOLPLUGINPATH', plugin_dir_path( __FILE__ ) );
}


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! defined( 'SWTOOLPLUGINFILE' ) ) {
	define( 'SWTOOLPLUGINFILE', __FILE__ );
}

// define plugin theme path
if ( ! defined( 'SWTOOLPLUGINTHEME' ) ) {
	define( 'SWTOOLPLUGINTHEME', plugin_dir_path( __FILE__ ). 'inc/templates' );
}

require_once( SWTOOLPLUGINPATH . '/inc/functions.php' );
require_once( SWTOOLPLUGINPATH . '/admin/admin-settings.php' );

//plugin loaded hook
add_action('plugins_loaded', 'swquicktools_construct', 0);
function swquicktools_construct(){
	/*
	** Load text domain
	*/
    load_plugin_textdomain( 'sw-tools-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	add_action( 'wp_enqueue_scripts', 'swquicktools_tools_plugin_script', 99 );	
}

function swquicktools_tools_plugin_script(){
	wp_enqueue_style( 'swquicktools_tools_plugin_css', plugins_url('css/style.css', __FILE__) );
	wp_enqueue_script('swquicktools_tools_plugin_js', plugins_url('js/style.js', __FILE__), array('jquery'));
	wp_enqueue_style( 'swquicktools_font-awesome', plugins_url('css/font-awesome/css/font-awesome.min.css', __FILE__) );
}