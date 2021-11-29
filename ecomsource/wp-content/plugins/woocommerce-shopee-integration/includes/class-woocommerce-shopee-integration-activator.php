<?php
/**
 * Plugin Activate
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
	 * Woocommerce_Shopee_Integration_Activator .
	 *
	 * @since 1.0.0
	 */
class Woocommerce_Shopee_Integration_Activator {

	/**
	 * Woocommerce_Shopee_Integration_Activator activate.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$table_name = 'wp_ced_shopee_accounts';

		$create_accounts_table =
			"CREATE TABLE $table_name (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			account_status VARCHAR(255) NOT NULL,
			shop_id BIGINT(20) DEFAULT NULL,
			location VARCHAR(50) NOT NULL,
			shop_data TEXT DEFAULT NULL,
			PRIMARY KEY (id)
		);";
		dbDelta( $create_accounts_table );

		$table_name = 'wp_ced_shopee_profiles';

		$create_profile_table =
			"CREATE TABLE $table_name (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			profile_name VARCHAR(255) NOT NULL,
			profile_status VARCHAR(255) NOT NULL,
			shop_id BIGINT(20) DEFAULT NULL,
			profile_data TEXT DEFAULT NULL,
			woo_categories TEXT DEFAULT NULL,
			PRIMARY KEY (id)
		);";
		dbDelta( $create_profile_table );
	}
}
