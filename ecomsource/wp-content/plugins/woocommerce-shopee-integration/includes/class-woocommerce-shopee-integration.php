<?php
/**
 * Woocommerce_Shopee_Integration
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
 * Woocommerce_Shopee_Integration.
 *
 * @since 1.0.0
 */
class Woocommerce_Shopee_Integration {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Woocommerce_Shopee_Integration_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-shopee-integration';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Woocommerce_Shopee_Integration load_dependencies.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-shopee-integration-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-shopee-integration-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopee-integration-admin.php';

		$this->loader = new Woocommerce_Shopee_Integration_Loader();
	}


	/**
	 * Woocommerce_Shopee_Integration set_locale.
	 *
	 * @since 1.0.0
	 */
	private function set_locale() {
		$plugin_i18n = new Woocommerce_Shopee_Integration_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Woocommerce_Shopee_Integration_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ced_shopee_add_menus', 22 );
		$this->loader->add_filter( 'ced_add_marketplace_menus_array', $plugin_admin, 'ced_shopee_add_marketplace_menus_to_array', 13 );
		$this->loader->add_action( 'wp_ajax_ced_shopee_authorise_account', $plugin_admin, 'ced_shopee_authorise_account' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_change_account_status', $plugin_admin, 'ced_shopee_change_account_status' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_fetch_next_level_category', $plugin_admin, 'ced_shopee_fetch_next_level_category' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_fetch_next_level_category_add_profile', $plugin_admin, 'ced_shopee_fetch_next_level_category_add_profile' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_map_categories_to_store', $plugin_admin, 'ced_shopee_map_categories_to_store' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_fetch_logistics', $plugin_admin, 'ced_shopee_fetch_logistics' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_category_refresh_button', $plugin_admin, 'ced_shopee_category_refresh_button' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_preview_product_detail', $plugin_admin, 'ced_shopee_preview_product_detail' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_upload_by_popup', $plugin_admin, 'ced_shopee_upload_by_popup' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_quick_update_by_action', $plugin_admin, 'ced_shopee_quick_update_by_action' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_process_bulk_action', $plugin_admin, 'ced_shopee_process_bulk_action' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_create_custom_profile', $plugin_admin, 'ced_shopee_create_custom_profile' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_profiles_on_pop_up', $plugin_admin, 'ced_shopee_profiles_on_pop_up' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_save_profile_through_popup', $plugin_admin, 'ced_shopee_save_profile_through_popup' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_search_product_name', $plugin_admin, 'ced_shopee_search_product_name' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_get_product_metakeys', $plugin_admin, 'ced_shopee_get_product_metakeys' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_process_metakeys', $plugin_admin, 'ced_shopee_process_metakeys' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_acknowledge_order', $plugin_admin, 'ced_shopee_acknowledge_order' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_ship_order', $plugin_admin, 'ced_shopee_ship_order' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'ced_shopee_add_order_metabox' );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'my_shopee_cron_schedules' );
		$this->loader->add_action( 'wp_ajax_ced_shopee_get_orders', $plugin_admin, 'ced_shopee_get_orders' );
		$this->loader->add_filter( 'ced_marketplaces_logged_array', $plugin_admin, 'ced_shopee_marketplace_to_be_logged' );
		global $wpdb;
		$active_shops = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_accounts WHERE `account_status` = %s ', 'active' ), 'ARRAY_A' );
		foreach ( $active_shops as $key => $value ) {
			$this->loader->add_action( 'ced_shopee_inventory_scheduler_job_' . $value['shop_id'], $plugin_admin, 'ced_shopee_inventory_schedule_manager' );
			$this->loader->add_action( 'ced_shopee_order_scheduler_job_' . $value['shop_id'], $plugin_admin, 'ced_shopee_order_schedule_manager' );
			$this->loader->add_action( 'ced_shopee_get_order_update_' . $value['shop_id'], $plugin_admin, 'ced_shopee_get_order_update' );
			// $this->loader->add_action( 'ced_shopee_order_status_scheduler_job_' . $value['shop_id'], $plugin_admin, 'ced_shopee_order_status_schedule_manager' );
			$this->loader->add_action( 'ced_shopee_auto_import_cron_' . $value['shop_id'], $plugin_admin, 'ced_shopee_get_products_to_import' );
			$this->loader->add_action( 'ced_shopee_sync_existing_products_' . $value['shop_id'], $plugin_admin, 'ced_shopee_sync_existing_products' );
		}
	}

	/**
	 * Woocommerce_Shopee_Integration run.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


	/**
	 * Woocommerce_Shopee_Integration get_plugin_name.
	 *
	 * @since 1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}


	/**
	 * Woocommerce_Shopee_Integration get_loader.
	 *
	 * @since 1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}


	/**
	 * Woocommerce_Shopee_Integration get_version.
	 *
	 * @since 1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
}
