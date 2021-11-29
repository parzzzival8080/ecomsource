<?php
/**
 * Plugin Name: Sw Author
 * Plugin URI: http://www.wpthemego.com/
 * Description: A plugin for managing book author.
 * Version: 1.0.2
 * Author: wpthemego
 * Author URI: http://www.wpthemego.com/
 * Requires at least: 4.1
 * Tested up to: WorPress 5.2.x
 *
 * Text Domain: sw_author
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// define plugin path
if ( ! defined( 'SAPATH' ) ) {
	define( 'SAPATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SBFILE' ) ) {
	define( 'SBFILE', __FILE__ );
}// define plugin URL
if ( ! defined( 'SAURL' ) ) {
	define( 'SAURL', plugins_url(). '/sw-author' );
}

// define plugin theme path
if ( ! defined( 'SATHEME' ) ) {
	define( 'SATHEME', plugin_dir_path( __FILE__ ). 'includes/themes' );
}

function sw_author_construct(){
	global $woocommerce;
	
	/* Load text domain */
	load_plugin_textdomain( 'sw_author', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	
	/* Include Widget File and hook */
	include( SAPATH . '/admin/author-taxonomy.php' );

	include( SAPATH . '/includes/functions.php' );
}
add_action( 'plugins_loaded', 'sw_author_construct', 20 );


