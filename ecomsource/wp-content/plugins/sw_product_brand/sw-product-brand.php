<?php 
/**
 * Plugin Name: Sw Product Brand
 * Plugin URI: http://www.wpthemego.com/
 * Description: Display brand of product.
 * Version: 1.0.10
 * Author: WpThemeGo
 * Author URI: http://www.wpthemego.com/
 * Requires at least: 4.1
 * Tested up to: WorPress 5.0.x and WooCommerce 3.5.x
 *
 * Text Domain: sw_product_brand
 * Domain Path: /languages/
 * WC tested up to: 5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// define plugin path
if ( ! defined( 'PBRPATH' ) ) {
	define( 'PBRPATH', plugin_dir_path( __FILE__ ) );
}

// define plugin URL
if ( ! defined( 'PBURL' ) ) {
	define( 'PBURL', plugins_url(). '/sw_product_brand' );
}

// define plugin theme path
if ( ! defined( 'PBTHEME' ) ) {
	define( 'PBTHEME', plugin_dir_path( __FILE__ ). 'includes/themes' );
}

function sw_product_brand_construct(){
	global $woocommerce;

	if ( ! isset( $woocommerce ) || ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'sw_product_brand_admin_notice' );
		return;
	}
	
	add_action( 'wp_enqueue_scripts', 'sw_brand_enqueue_script', 99 );

	/* Load text domain */
	load_plugin_textdomain( 'sw_product_brand', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	
	require_once( PBRPATH . '/includes/functions.php' );
}
add_action( 'plugins_loaded', 'sw_product_brand_construct', 20 );

/*
** Load script
*/
function sw_brand_enqueue_script(){	
	if( !is_admin() ){
		if ( !wp_script_is('slick_slider') ){
			wp_register_script( 'slick-slider', plugins_url( 'js/slick.min.js', __FILE__ ),array(), null, true );
			wp_enqueue_script('slick-slider');			
		}
	}
}

function sw_product_brand_admin_notice(){
	?>
	<div class="error">
		<p><?php _e( 'Sw Woocommerce is enabled but not effective. It requires WooCommerce in order to work.', 'sw_product_brand' ); ?></p>
	</div>
<?php
}