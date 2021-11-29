<?php
/**
 * Woocommerce_Shopee_Integration_I18n
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Woocommerce_Shopee_Integration_I18n.
 *
 * @since 1.0.0
 */
class Woocommerce_Shopee_Integration_I18n {


	/**
	 * Woocommerce_Shopee_Integration_I18n load_plugin_textdomain.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'woocommerce-shopee-integration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
