<?php
/**
 * Wordpress-plugin
 * Plugin Name:       Shopee Integration for WooCommerce
 * Plugin URI:        https://cedcommerce.com
 * Description:       Shopee Integration for WooCommerce allows merchants to list their products on shopee marketplace and manage the orders from the woocommerce store.
 * Version:           1.0.8
 * Author:            CedCommerce
 * Author URI:        https://cedcommerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-shopee-integration
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.7
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.1
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * Activate_woocommerce_shopee_integration.
 *
 * @since 1.0.0
 */
function activate_woocommerce_shopee_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-shopee-integration-activator.php';
	Woocommerce_Shopee_Integration_Activator::activate();
}

/**
 * Deactivate_woocommerce_shopee_integration.
 *
 * @since 1.0.0
 */
function deactivate_woocommerce_shopee_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-shopee-integration-deactivator.php';
	Woocommerce_Shopee_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_shopee_integration' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_shopee_integration' );

define( 'CED_SHOPEE_LOG_DIRECTORY', wp_upload_dir()['basedir'] . '/ced_shopee_log_directory' );
define( 'CED_SHOPEE_VERSION', '1.0.0' );
define( 'CED_SHOPEE_PREFIX', 'ced_shopee' );
define( 'CED_SHOPEE_DIRPATH', plugin_dir_path( __FILE__ ) );
define( 'CED_SHOPEE_URL', plugin_dir_url( __FILE__ ) );
define( 'CED_SHOPEE_ABSPATH', untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) );
define( 'CED_SHOPEE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );


require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-shopee-integration.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/ced-shopee-core-functions.php';

/**
 * Run_woocommerce_shopee_integration.
 *
 * @since 1.0.0
 */
function run_woocommerce_shopee_integration() {
	$plugin = new Woocommerce_Shopee_Integration();
	$plugin->run();
}



/**
 * Ced_admin_notice_example_activation_hook_ced_shopee.
 *
 * @since 1.0.0
 */
function ced_admin_notice_example_activation_hook_ced_shopee() {
	set_transient( 'ced-shopee-admin-notice', true, 5 );
}




/**
 * Ced_shopee_admin_notice_activation.
 *
 * @since 1.0.0
 */
function ced_shopee_admin_notice_activation() {
	if ( get_transient( 'ced-shopee-admin-notice' ) ) {?>
		<div class="updated notice is-dismissible">
			<p>Welcome to WooCommerce Shopee Integration. Start listing, syncing, managing, & automating your WooCommerce and Shopee store to boost sales.</p>
			<a href="admin.php?page=ced_shopee" class ="ced_configuration_plugin_main">Connect to Shopee</a>
		</div>
		<?php
		delete_transient( 'ced-shopee-admin-notice' );
	}
}

/**
 * Check WooCommerce is Installed and Active.
 *
 * @since 1.0.0
 */
if ( ced_shopee_check_woocommerce_active() ) {

	run_woocommerce_shopee_integration();
	register_activation_hook( __FILE__, 'ced_admin_notice_example_activation_hook_ced_shopee' );
	add_action( 'admin_notices', 'ced_shopee_admin_notice_activation' );
} else {

	add_action( 'admin_init', 'deactivate_ced_shopee_woo_missing' );
}
