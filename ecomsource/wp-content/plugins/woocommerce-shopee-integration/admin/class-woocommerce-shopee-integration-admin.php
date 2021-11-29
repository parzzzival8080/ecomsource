<?php
/**
 * Admin related operations
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
 * Woocommerce_Shopee_Integration_Admin enqueue_styles.
 *
 * @since 1.0.0
 * @param object $file_system Object to access filesystems.
 * @param string $plugin_name Plugin Name.
 * @param string $version Plugin version
 */
class Woocommerce_Shopee_Integration_Admin {

		/**
		 * Plugin Name.
		 *
		 * @since    1.0.0
		 * @var      string    $plugin_name    Plugin Name.
		 */
		private $plugin_name;
	/**
	 * Plugin Version.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    Plugin Version.
	 */
	private $version;
		/**
		 * Accessing Filesystem
		 *
		 * @since    1.0.0
		 * @var      object    $file_system    Accessing Filesystem.
		 */
		private $file_system;
	/**
	 * Woocommerce_Shopee_Integration_Admin construct.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name Plugin Name.
	 * @param string $version Plugin version.
	 */
	public function __construct( $plugin_name, $version ) {
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->load_dependency();
		$this->shopid = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
		require_once ABSPATH . '/wp-admin/includes/file.php';
		require_once CED_SHOPEE_DIRPATH . 'admin/shopee/class-class-ced-shopee-manager.php';
		add_action( 'manage_edit-shop_order_columns', array( $this, 'ced_shopee_add_table_columns' ) );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'ced_shopee_manage_table_columns' ), 10, 2 );
		
	}


	public function ced_shopee_add_table_columns( $columns ) {
		$modified_columns = array();
		foreach ( $columns as $key => $value ) {
			$modified_columns[ $key ] = $value;
			if ( 'order_number' == $key ) {
				$modified_columns['order_from'] = '<span title="Order source">Order source</span>';
			}
		}
		return $modified_columns;
	}


	public function ced_shopee_manage_table_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'order_from':
				$_ced_shopee_order_id = get_post_meta( $post_id, '_ced_shopee_order_id', true );
				if ( ! empty( $_ced_shopee_order_id ) ) {
					$shopee_icon = CED_SHOPEE_URL . 'admin/images/shopee-card.png';
					echo '<p><img src="' . esc_url( $shopee_icon ) . '" height="35" width="60"></p>';
				}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin enqueue_styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Shopee_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Shopee_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'ced-boot-css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '2.0.0', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-shopee-integration-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin enqueue_scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Shopee_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Shopee_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full.js', array( 'jquery' ), '4.0.3' );
		wp_enqueue_script( 'select2' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-shopee-integration-admin.js', array( 'jquery' ), $this->version, false );
		$ajax_nonce     = wp_create_nonce( 'ced-shopee-ajax-seurity-string' );
		$localize_array = array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => $ajax_nonce,
			'shop_id'    => isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '',
		);
		wp_localize_script( $this->plugin_name, 'ced_shopee_admin_obj', $localize_array );

		global $wp_filesystem;
		$url = wp_nonce_url( 'ced-shopee-seurity-string', 'woocommerce-shopee-integration' );

		$creds = request_filesystem_credentials( $url, '', false, wp_upload_dir()['basedir'], null );
		if ( false === $creds ) {
			esc_html_e( 'Credentials are required to save the file.', 'woocommerce-shopee-integration' );
			exit();
		}

		$wp_upload_dir = wp_upload_dir();

		if ( ! WP_Filesystem( $creds, $wp_upload_dir['basedir'] ) ) {
			request_filesystem_credentials( $url, '', true, false, null );
			esc_html_e( 'Credentials are required to save the file', 'woocommerce-shopee-integration' );
			exit();
		}

		WP_Filesystem();

		$this->file_system = $wp_filesystem;

		$this->file_system->mkdir( $wp_upload_dir['basedir'] . '/cedcommerce' );
		$this->file_system->mkdir( $wp_upload_dir['basedir'] . '/cedcommerce/Shopee' );
		$this->file_system->mkdir( $wp_upload_dir['basedir'] . '/cedcommerce/Shopee/Categories' );

	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_add_menus.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_add_menus() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['cedcommerce-integrations'] ) ) {
			add_menu_page( __( 'CedCommerce', 'woocommerce-shopee-integration' ), __( 'CedCommerce', 'woocommerce-shopee-integration' ), 'manage_woocommerce', 'cedcommerce-integrations', array( $this, 'ced_marketplace_listing_page' ), plugins_url( 'woocommerce-shopee-integration/admin/images/logo1.png' ), 12 );
			$menus = apply_filters( 'ced_add_marketplace_menus_array', array() );
			if ( is_array( $menus ) && ! empty( $menus ) ) {
				foreach ( $menus as $key => $value ) {
					add_submenu_page( 'cedcommerce-integrations', $value['name'], $value['name'], 'manage_woocommerce', $value['menu_link'], array( $value['instance'], $value['function'] ) );
				}
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin my_shopee_cron_schedules.
	 *
	 * @since 1.0.0
	 * @param array $schedules Cron Schedules.
	 */
	public function my_shopee_cron_schedules( $schedules ) {
		if ( ! isset( $schedules['ced_shopee_1min'] ) ) {
			$schedules['ced_shopee_1min'] = array(
				'interval' => 1 * 60,
				'display'  => __( 'Once every 1 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_shopee_2min'] ) ) {
			$schedules['ced_shopee_2min'] = array(
				'interval' => 2 * 60,
				'display'  => __( 'Once every 2 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_shopee_4min'] ) ) {
			$schedules['ced_shopee_4min'] = array(
				'interval' => 4 * 60,
				'display'  => __( 'Once every 4 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_shopee_6min'] ) ) {
			$schedules['ced_shopee_6min'] = array(
				'interval' => 6 * 60,
				'display'  => __( 'Once every 6 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_shopee_10min'] ) ) {
			$schedules['ced_shopee_10min'] = array(
				'interval' => 10 * 60,
				'display'  => __( 'Once every 10 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_shopee_15min'] ) ) {
			$schedules['ced_shopee_15min'] = array(
				'interval' => 15 * 60,
				'display'  => __( 'Once every 15 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_shopee_30min'] ) ) {
			$schedules['ced_shopee_30min'] = array(
				'interval' => 30 * 60,
				'display'  => __( 'Once every 30 minutes' ),
			);
		}
		return $schedules;
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_add_marketplace_menus_to_array.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function ced_shopee_add_marketplace_menus_to_array( $menus = array() ) {
		$menus[] = array(
			'name'            => 'Shopee',
			'slug'            => 'woocommerce-shopee-integration',
			'menu_link'       => 'ced_shopee',
			'instance'        => $this,
			'function'        => 'ced_shopee_accounts_page',
			'card_image_link' => CED_SHOPEE_URL . 'admin/images/shopee-card.png',
		);
		return $menus;
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_marketplace_to_be_logged.
	 *
	 * @since 1.0.0
	 * @param array $marketplaces List of marketplaces.
	 */
	public function ced_shopee_marketplace_to_be_logged( $marketplaces = array() ) {
		$marketplaces[] = array(
			'name'             => 'Shopee',
			'marketplace_slug' => 'shopee',
		);
		return $marketplaces;
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_marketplace_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function ced_marketplace_listing_page() {
		$active_marketplaces = apply_filters( 'ced_add_marketplace_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require CED_SHOPEE_DIRPATH . 'admin/partials/marketplaces.php';
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_accounts_page.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_accounts_page() {
		$file_accounts = CED_SHOPEE_DIRPATH . 'admin/partials/class-ced-shopee-account-table.php';
		if ( file_exists( $file_accounts ) ) {
			include_once $file_accounts;
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_authorise_account.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_authorise_account() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$api_url = $this->ced_shopee_manager->prepare_store_authorisation_url();
			echo json_encode(
				array(
					'status' => '200',
					'apiUrl' => $api_url,
				)
			);
			die;
		}
	}


	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_change_account_status.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_change_account_status() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			global $wpdb;
			$table_name = 'wp_ced_shopee_accounts';
			$id         = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			$status     = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'active';
			$wpdb->update( $table_name, array( 'account_status' => $status ), array( 'id' => $id ) );
			echo json_encode( array( 'status' => '200' ) );
			die;
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_fetch_next_level_category.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_fetch_next_level_category() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			global $wpdb;
			$store_category_id        = isset( $_POST['store_id'] ) ? sanitize_text_field( wp_unslash( $_POST['store_id'] ) ) : '';
			$shopee_store_id          = isset( $_POST['shopee_store_id'] ) ? sanitize_text_field( wp_unslash( $_POST['shopee_store_id'] ) ) : '';
			$shopee_category_name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			$shopee_category_id       = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			$level                    = isset( $_POST['level'] ) ? sanitize_text_field( wp_unslash( $_POST['level'] ) ) : '';
			$next_level               = intval( $level ) + 1;
			$base_dir                 = wp_upload_dir()['basedir'];
			$folder_name              = $base_dir . '/cedcommerce/Shopee/Categories/';
			$get_location             = ced_shopee_account_location( $shopee_store_id );
			$get_filename             = ced_shopee_category_files();
			$category_next_level_file = $folder_name . $get_filename[ $get_location ]['all'];
			if ( file_exists( $category_next_level_file ) ) {
				$shopee_category_list = file_get_contents( $category_next_level_file );
				$shopee_category_list = json_decode( $shopee_category_list, true );
			} else {
				$category_next_level_file = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/json/MY_categoryList.json';
				$shopee_category_list     = file_get_contents( $category_next_level_file );
				$shopee_category_list     = json_decode( $shopee_category_list, true );
			}

			$next_level_category_array = array();
			if ( ! empty( $shopee_category_list ) ) {
				foreach ( $shopee_category_list as $key => $value ) {
					if ( isset( $value['parent_id'] ) && $value['parent_id'] == $shopee_category_id ) {
						$next_level_category_array[] = $value;
					}
				}
			}
			if ( is_array( $next_level_category_array ) && ! empty( $next_level_category_array ) ) {
				$select_html_begin = "<td data-catlevel='catLevelVal'><select class='ced_shopee_levelcatLevelVal_category ced_shopee_select_category select_boxes_cat_map' name='ced_shopee_levelcatLevelVal_category[]' data-level='catLevelVal' data-storeCategoryID='storeCategoryIdVal' data-shopeeStoreId='shopeeStoreIdVal'><option value=''>--Select--</option>";

				$select_html_end = '</select></td>';

				foreach ( $next_level_category_array as $key => $value ) {
					if ( ! empty( $value['category_name'] ) ) {
						$select_html_options = "<option value='categoryIdVal'>categoryNameVal</option>";

						$select_html_options = str_replace( 'categoryNameVal', $value['category_name'], $select_html_options );
						$select_html_options = str_replace( 'categoryIdVal', $value['category_id'], $select_html_options );

						$select_html_begin = $select_html_begin . $select_html_options;
					}
				}

				$select_html_begin = str_replace( 'catLevelVal', $next_level, $select_html_begin );
				$select_html_begin = str_replace( 'storeCategoryIdVal', $store_category_id, $select_html_begin );
				$select_html_begin = str_replace( 'shopeeStoreIdVal', $shopee_store_id, $select_html_begin );

				$select_html = $select_html_begin . $select_html_end;
				echo json_encode( $select_html );
				die;
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_fetch_next_level_category_add_profile.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_fetch_next_level_category_add_profile() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			global $wpdb;
			$shopee_store_id          = isset( $_POST['shopee_store_id'] ) ? sanitize_text_field( wp_unslash( $_POST['shopee_store_id'] ) ) : '';
			$shopee_category_name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			$shopee_category_id       = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			$level                    = isset( $_POST['level'] ) ? sanitize_text_field( wp_unslash( $_POST['level'] ) ) : '';
			$next_level               = intval( $level ) + 1;
			$base_dir                 = wp_upload_dir()['basedir'];
			$folder_name              = $base_dir . '/cedcommerce/Shopee/Categories/';
			$get_location             = ced_shopee_account_location( $shopee_store_id );
			$get_filename             = ced_shopee_category_files();
			$category_next_level_file = $folder_name . $get_filename[ $get_location ]['all'];
			if ( file_exists( $category_next_level_file ) ) {
				$shopee_category_list = file_get_contents( $category_next_level_file );
				$shopee_category_list = json_decode( $shopee_category_list, true );
			} else {
				$category_next_level_file = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/json/MY_categoryList.json';
				$shopee_category_list     = file_get_contents( $category_next_level_file );
				$shopee_category_list     = json_decode( $shopee_category_list, true );
			}

			$select_html               = '';
			$next_level_category_array = array();
			if ( ! empty( $shopee_category_list ) ) {
				foreach ( $shopee_category_list as $key => $value ) {
					if ( isset( $value['parent_id'] ) && $value['parent_id'] == $shopee_category_id ) {
						$next_level_category_array[] = $value;
					}
				}
			}
			if ( is_array( $next_level_category_array ) && ! empty( $next_level_category_array ) ) {
				$select_html_begin = "<td data-catlevel='catLevelVal'><select class='ced_shopee_levelcatLevelVal_category ced_shopee_select_category_on_add_profile select_boxes_cat_map' name='ced_shopee_levelcatLevelVal_category[]' data-level='catLevelVal' data-shopeeStoreId='shopeeStoreIdVal'><option value=''>--Select--</option>";

				$select_html_end = '</select></td>';

				foreach ( $next_level_category_array as $key => $value ) {
					if ( ! empty( $value['category_name'] ) ) {
						$select_html_options = '';
						$select_html_options = "<option value='categoryIdVal'>categoryNameVal</option>";

						$select_html_options = str_replace( 'categoryNameVal', $value['category_name'], $select_html_options );
						$select_html_options = str_replace( 'categoryIdVal', $value['category_id'], $select_html_options );

						$select_html_begin = $select_html_begin . $select_html_options;
					}
				}

				$select_html_begin = str_replace( 'catLevelVal', $next_level, $select_html_begin );
				$select_html_begin = str_replace( 'shopeeStoreIdVal', $shopee_store_id, $select_html_begin );

				$select_html = $select_html_begin . $select_html_end;

				echo json_encode( $select_html );
				die;
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_map_categories_to_store.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_map_categories_to_store() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {

			$sanitized_array = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

			$shopee_category_array          = isset( $sanitized_array['shopee_category_array'] ) ? ( $sanitized_array['shopee_category_array'] ) : '';
			$store_category_array           = isset( $sanitized_array['store_category_array'] ) ? ( $sanitized_array['store_category_array'] ) : '';
			$shopee_category_name           = isset( $sanitized_array['shopee_category_name'] ) ? ( $sanitized_array['shopee_category_name'] ) : '';
			$shopee_store_id                = isset( $_POST['shopee_store_id'] ) ? sanitize_text_field( wp_unslash( $_POST['shopee_store_id'] ) ) : '';
			$shopee_saved_category          = get_option( 'ced_shopee_saved_category', array() );
			$already_mapped_categories      = array();
			$already_mapped_categories_name = array();
			$shopee_mapped_categories       = array_combine( $store_category_array, $shopee_category_array );
			$shopee_mapped_categories       = array_filter( $shopee_mapped_categories );
			$already_mapped_categories      = get_option( 'ced_woo_shopee_mapped_categories' . $shopee_store_id, array() );
			if ( is_array( $shopee_mapped_categories ) && ! empty( $shopee_mapped_categories ) ) {
				foreach ( $shopee_mapped_categories as $key => $value ) {
					$already_mapped_categories[ $shopee_store_id ][ $key ] = $value;
				}
			}

			update_option( 'ced_woo_shopee_mapped_categories' . $shopee_store_id, $already_mapped_categories );
			$shopee_mapped_categories_name  = array_combine( $shopee_category_array, $shopee_category_name );
			$shopee_mapped_categories_name  = array_filter( $shopee_mapped_categories_name );
			$already_mapped_categories_name = get_option( 'ced_woo_shopee_mapped_categories_name' . $shopee_store_id, array() );
			if ( is_array( $shopee_mapped_categories_name ) && ! empty( $shopee_mapped_categories_name ) ) {
				foreach ( $shopee_mapped_categories_name as $key => $value ) {
					$already_mapped_categories_name[ $shopee_store_id ][ $key ] = $value;
				}
			}
			update_option( 'ced_woo_shopee_mapped_categories_name' . $shopee_store_id, $already_mapped_categories_name );
			$this->ced_shopee_manager->ced_shopee_create_auto_profiles( $shopee_mapped_categories, $shopee_mapped_categories_name, $shopee_store_id );
			wp_die();
		}
	}



	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_fetch_logistics.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_fetch_logistics() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$store_id = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';

			$is_shop_inactive = ced_shopee_inactive_shops( $store_id );
			if ( $is_shop_inactive ) {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Shop is Not Active',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}

			$get_logistics              = array();
			$get_logistics              = get_option( 'ced_shopee_logistic_data', array() );
			$get_logistics[ $store_id ] = $this->ced_shopee_manager->ced_shopee_fetch_logistics( $store_id );
			update_option( 'ced_shopee_logistic_data', $get_logistics );
			if ( ! empty( $get_logistics ) ) {
				$logistics_selecthtml  = '';
				$logistics_selecthtml .= "<select name='ced_shopee_global_settings[ced_shopee_logistics][]' class='select_boxes' multiple=''>";
				$logistics_selecthtml .= "<option value=''>" . __( '--Select--', 'woocommerce-shopee-integration' ) . '</option>';
				foreach ( $get_logistics[ $store_id ]['logistics'] as $key => $value ) {
					$logistics_selecthtml .= '<option value=' . $value['logistic_id'] . '>' . $value['logistic_name'] . '</option>';
				}
				$logistics_selecthtml .= '</select>';
			}
			echo json_encode(
				array(
					'status'       => '200',
					'logisticHtml' => $logistics_selecthtml,
				)
			);
			die;
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_profiles_on_pop_up.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_profiles_on_pop_up() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$store_id = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';
			$prod_id  = isset( $_POST['prodId'] ) ? sanitize_text_field( wp_unslash( $_POST['prodId'] ) ) : '';
			global $wpdb;
			$profiles = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_profiles WHERE `shop_id` = %s ', $store_id ), 'ARRAY_A' ); ?><div class="ced_shopee_profile_popup_content">
				<div id="profile_pop_up_head_main">
					<h2><?php esc_html_e( 'CHOOSE PROFILE FOR THIS PRODUCT', 'woocommerce-shopee-integration' ); ?></h2>
					<div class="ced_shopee_profile_popup_close">X</div>
				</div>
				<div id="profile_pop_up_head"><h3><?php esc_html_e( 'Available Profiles', 'woocommerce-shopee-integration' ); ?></h3></div>
				<div class="ced_shopee_profile_dropdown">
					<select name="ced_shopee_profile_selected_on_popup" class="ced_shopee_profile_selected_on_popup">
						<option class="profile_options" value=""><?php esc_html_e( '---Select Profile---', 'woocommerce-shopee-integration' ); ?></option>
						<?php
						foreach ( $profiles as $key => $value ) {
							echo '<option  class="profile_options" value="' . esc_attr( $value['id'] ) . '">' . esc_attr( urldecode($value['profile_name'] ) ) . '</option>';
						}
						?>
					</select>
				</div>	
				<div id="ced_shopee_save_profile_through_popup_container">
					<button data-prodId="<?php echo esc_attr( $prod_id ); ?>" class="ced_shopee_custom_button" id="ced_shopee_save_profile_through_popup"  data-shopid="<?php echo esc_attr( $store_id ); ?>"><?php esc_html_e( 'Assign Profile', 'woocommerce-shopee-integration' ); ?></button>
				</div>
			</div>


			<?php
			wp_die();
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_save_profile_through_popup.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_save_profile_through_popup() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$shopid     = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';
			$prod_id    = isset( $_POST['prodId'] ) ? sanitize_text_field( wp_unslash( $_POST['prodId'] ) ) : '';
			$profile_id = isset( $_POST['profile_id'] ) ? sanitize_text_field( wp_unslash( $_POST['profile_id'] ) ) : '';
			if ( empty( $profile_id ) ) {
				echo 'null';
				wp_die();
			}

			update_post_meta( $prod_id, 'ced_shopee_profile_assigned' . $shopid, $profile_id );
			$pro_ids   = array();
			$pro_ids[] = $prod_id;
			update_option( 'ced_shopee_product_ids_in_profile_' . $profile_id, $pro_ids );
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_category_refresh_button.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_category_refresh_button() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$shopid = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';

			$is_shop_inactive = ced_shopee_inactive_shops( $shopid );
			if ( $is_shop_inactive ) {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Shop is Not Active',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}

			$file_category = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-category.php';
			if ( file_exists( $file_category ) ) {
				include_once $file_category;
			}

			$shopee_category = Class_Ced_Shopee_Category::get_instance();
			$shopee_category = $shopee_category->get_refreshed_shopee_category( $shopid );
			if ( isset( $shopee_category ) ) {
				$file_class_shopee = CED_SHOPEE_DIRPATH . 'admin/shopee/class-class-ced-shopee-manager.php';
				if ( file_exists( $file_class_shopee ) ) {
					include_once $file_class_shopee;
				}
				update_option( 'ced_shopee_categories' . $shopid, $shopee_category );
				$class_shopee_instance = Class_Ced_Shopee_Manager::get_instance();
				$stored_categories     = $class_shopee_instance->ced_shopee_store_categories( $shopee_category, $shopid );
			} else {
				echo json_encode( array( 'status' => __( 'Unable To Fetch Categories', 'woocommerce-shopee-integration' ) ) );
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_acknowledge_order.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_acknowledge_order() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : '';
			if ( $order_id ) {
				$order_id = $order_id;
				update_post_meta( $order_id, '_shopee_umb_order_status', 'Acknowledged' );
				echo json_encode( array( 'status' => '200' ) );
				die;
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_ship_order.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_ship_order() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$order_id     = isset( $_POST['order_id'] ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : false;
			$track_number = isset( $_POST['trackNumber'] ) ? sanitize_text_field( wp_unslash( $_POST['trackNumber'] ) ) : false;
			if ( $order_id && $track_number ) {
				$action = 'logistics/init_info/get';
				include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
				$send_request_obj        = new Class_Ced_Shopee_Send_Http_Request();
				$parameters              = array();
				$shopee_ordersn_order_id = get_post_meta( $order_id, '_ced_shopee_order_id', true );
				$shopee_shop_id          = get_post_meta( $order_id, 'ced_shopee_order_shop_id', true );
				$parameters['ordersn']   = $shopee_ordersn_order_id;
				$result                  = $send_request_obj->send_http_request( $action, $parameters, $shopee_shop_id );
				if ( isset( $result['info_needed'] ) ) {
					if ( isset( $result['info_needed']['pickup'] ) ) {
						$parameters['pickup'] = array( 'tracking_no' => $track_number );
					} elseif ( isset( $result['info_needed']['dropoff'] ) ) {
						$parameters['dropoff'] = array( 'tracking_no' => $track_number );
					} elseif ( isset( $result['info_needed']['non_integrated'] ) ) {
						$parameters['non_integrated'] = array( 'tracking_no' => $track_number );
					}
				}
				$action       = 'logistics/init';
				$ship_request = $send_request_obj->send_http_request( $action, $parameters, $shopee_shop_id );
				if ( is_array( $ship_request ) && ! empty( $ship_request ) ) {
					if ( isset( $ship_request['tracking_number'] ) ) {
						update_post_meta( $order_id, '_shopee_umb_order_status', 'Shipped' );
						update_post_meta( $order_id, '_umb_order_details', array( 'trackingNo' => $track_number ) );
						echo json_encode( array( 'status' => '200' ) );
						die;
					} elseif ( $ship_request['msg'] ) {
						echo json_encode(
							array(
								'status' => '400',
								'msg'    => $ship_request['msg'],
							)
						);
						die;
					}
				} else {
					echo json_encode(
						array(
							'status' => '402',
							'msg'    => $ship_request['msg'],
						)
					);
					die;
				}
			} else {
				echo json_encode(
					array(
						'status' => '400',
						'msg'    => __(
							'Please fill in all the details',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_preview_product_detail.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_preview_product_detail() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-products.php';
			$product_id = isset( $_POST['prodId'] ) ? sanitize_text_field( wp_unslash( $_POST['prodId'] ) ) : '';
			$shopid     = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';
			if ( isset( $product_id ) && ! empty( $product_id ) ) {
				$shopee_products_instance = Class_Ced_Shopee_Products::get_instance();
				$preview_data             = $shopee_products_instance->get_formatted_data( $product_id, $shopid );
				$image_gallery_id         = array();
				$image_gallery_view       = array();
				$product                  = wc_get_product( $product_id );
				$product_type             = $product->get_type();
				$image                    = $product->get_data();
				$image_id                 = $image['image_id'];
				$image_gallery_id         = $image['gallery_image_ids'];
				$image_view               = wp_get_attachment_image_url( $image_id );
				?>
				<div class="ced_shopee_preview_product_popup_content">
					<div class="ced_shopee_preview_product_popup_header">
						<h5><?php esc_html_e( 'Shopee Product Details', 'woocommerce-shopee-integration' ); ?></h5>
						<span class="ced_shopee_preview_product_popup_close">X</span>
					</div>
					<div class="ced_shopee_preview_product_popup_body">
						<div class="preview_content preview-image-col">
							<div id="preview-image">
								<?php
								echo '<image height="100%" width="100%" src="' . esc_url( $image_view ) . '">';
								?>
							</div>
							<div class="ced_shopee_thumbnail">
								<ul>
									<?php
									if ( isset( $image_gallery_id ) && ! empty( $image_gallery_id ) ) {
										foreach ( $image_gallery_id as $value ) {
											$image_gallery_view = wp_get_attachment_image_url( $value );
											echo '<li>
											<img src="' . esc_url( $image_gallery_view ) . '">
											</li>';
										}
									}
									?>
								</ul>
							</div>	
						</div>
						<div class="preview_content preview_content-col">
							<div id="preview_Right_details_content">
								<h3 class="preview_product_name">
									<?php
									echo esc_attr( $preview_data['name'] ) . '<br>';
									?>
								</h3>
								<?php
								$already_uploaded = get_post_meta( $product_id, '_ced_shopee_listing_id_' . $shopid, true );
								if ( ! $already_uploaded ) {
									?>
									<button class="ced_shopee_preview_product_upload_button ced_shopee_custom_button" id="ced_shopee_preview_product_upload_button" data-id="<?php echo esc_attr( $product_id ); ?>"><?php esc_html_e( 'Upload', 'woocommerce-shopee-integration' ); ?></button>
									<?php
								}
								?>
								<p>
									<?php
									if ( isset( $preview_data['item_sku'] ) ) {
										echo esc_attr( $preview_data['item_sku'] );
									}
									?>
								</p>
								<div id="Price_detail">
									<?php
									$currency_symbol = get_woocommerce_currency_symbol();
									echo esc_attr( $currency_symbol ) . '&nbsp' . esc_attr( $preview_data['price'] );
									?>
								</div>
								<div class="ced_shopee_preview_detail">
									<ul>
										<li>
											<div class="ced_shopee_preview_detail_name"><?php esc_html_e( 'Coins', 'woocommerce-shopee-integration' ); ?></div>
											<div class="ced_shopee_preview_detail_desc">
												<img src="https://cdngarenanow-a.akamaihd.net/shopee/shopee-pcmall-live-my/assets/a507d0e185dbd56e388652d8d8da845d.png" alt="image" width="16" height="16">
												<?php esc_html_e( 'Buy and earn', 'woocommerce-shopee-integration' ); ?><span>70</span> <?php esc_html_e( 'Shopee Coins', 'woocommerce-shopee-integration' ); ?>
											</div>
										</li>
										<li>
											<?php
											if ( 'variable' == $product_type ) {
												$variations = $shopee_products_instance->get_formatted_data_for_variation( $product_id );

												if ( isset( $variations['tier_variation'] ) ) {
													foreach ( $variations['tier_variation'] as $key => $value ) {
														echo '<div class="ced_shopee_variations_wrapper">
														<div>' . esc_attr( $value['name'] );
														foreach ( $value['options'] as $key1 => $value1 ) {
															echo '<span class="ced_shopee_product_variation">' . esc_attr( $value1 ) . '</span>';
														}
														echo '</span></div>';
														echo '</div>';
													}
												}
												?>

												<?php
											}
											?>

										</li>
										<li>
											<div class="ced_shopee_preview_detail_name">Quantity</div>
											<div class="ced_shopee_preview_detail_desc">
												<div class="ced_shopee_qnty_wrapper">
													<span class="ed_shopee_qnty_point">-</span>
													<span class="ed_shopee_qnty_number">1</span>
													<span class="ed_shopee_qnty_point">+</span>
												</div>
												<div class="ced_shopee_product_piece"><?php echo esc_attr( $preview_data['stock'] ); ?> piece available</div>
											</div>
										</li>
									</ul>
									<button class="ced_shopee_preview_btn ced_shopee_add_to_cart"><?php esc_html_e( 'Add to cart', 'woocommerce-shopee-integration' ); ?></button>
									<button class="ced_shopee_preview_btn ced_shopee_preview_buy_now"><?php esc_html_e( 'Buy Now', 'woocommerce-shopee-integration' ); ?></button>
								</div>
							</div>

						</div>
					</div>
					<div class="ced_shopee_product_wrapper">
						<h3 class="ced_shopee_product_title"><?php esc_html_e( 'Product Specifications', 'woocommerce-shopee-integration' ); ?></h3>
						<ul class="ced_shopee_product_listing">
							<li>
								<div class="ced_shopee_product_value"><?php esc_html_e( 'Category', 'woocommerce-shopee-integration' ); ?></div>
								<div class="ced_shopee_product_desc"><?php echo esc_attr( $preview_data['category_id'] ); ?></div>
							</li>
							<li>
								<div class="ced_shopee_product_value"><?php esc_html_e( 'Brand', 'woocommerce-shopee-integration' ); ?></div>
								<?php
								foreach ( $preview_data['attributes'] as $key => $value ) {
									echo '<div class="ced_shopee_product_desc">' . esc_attr( $value['value'] ) . '</div>';
									break;
								}
								?>

							</li>

						</ul>
						<h3 class="ced_shopee_product_title"><?php esc_html_e( 'Product Description', 'woocommerce-shopee-integration' ); ?></h3>
						<p class="ced_shopee_product_para"><?php echo esc_attr( $preview_data['description'] ); ?></p>
					</div>
				</div>
				<?php
			}
			wp_die();
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_upload_by_popup.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_upload_by_popup() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$prod_id = isset( $_POST['prodid'] ) ? sanitize_text_field( wp_unslash( $_POST['prodid'] ) ) : '';
			$shopid  = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';

			$is_shop_inactive = ced_shopee_inactive_shops( $shopid );
			if ( $is_shop_inactive ) {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Currently Shop is not Active . Please activate your Shop in order to perform operations.',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}

			$already_uploaded = get_post_meta( $prod_id, '_ced_shopee_listing_id_' . $shopid, true );
			if ( $already_uploaded ) {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Product Already Uploaded',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			} else {
				$get_product_detail = $this->ced_shopee_manager->prepare_product_html_for_upload( $prod_id, $shopid );
				if ( isset( $get_product_detail['item_id'] ) ) {
					echo json_encode(
						array(
							'status'  => 200,
							'message' => $get_product_detail['item']['name'] . ' Uploaded Successfully',
							'prodid'  => $prod_id,
						)
					);
					die;
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => $get_product_detail['msg'],
							'prodid'  => $prod_id,
						)
					);
					die;
				}
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_quick_update_by_action.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_quick_update_by_action() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$prod_id = isset( $_POST['prodid'] ) ? sanitize_text_field( wp_unslash( $_POST['prodid'] ) ) : '';
			$shopid  = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';

			$is_shop_inactive = ced_shopee_inactive_shops( $shopid );
			if ( $is_shop_inactive ) {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Currently Shop is not Active . Please activate your Shop in order to perform operations.',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}

			$already_uploaded = get_post_meta( $prod_id, '_ced_shopee_listing_id_' . $shopid, true );
			if ( $already_uploaded ) {
				$get_product_detail = $this->ced_shopee_manager->prepare_product_html_for_update( $prod_id, $shopid );
				if ( isset( $get_product_detail['item_id'] ) ) {
					echo json_encode(
						array(
							'status'  => 200,
							'message' => $get_product_detail['item']['name'] . ' Updated Successfully',
						)
					);
					die;
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => $get_product_detail['msg'],
						)
					);
					die;
				}
			} else {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Product Not Found On Shopee',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin load_dependency.
	 *
	 * @since 1.0.0
	 */
	public function load_dependency() {
		include_once CED_SHOPEE_DIRPATH . 'admin/shopee/class-class-ced-shopee-manager.php';
		$this->ced_shopee_manager = Class_Ced_Shopee_Manager::get_instance();
		$file                     = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
		if ( file_exists( $file ) ) {
			include_once $file;
		}
		$this->shopee_send_http_request_instance = new Class_Ced_Shopee_Send_Http_Request();
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_add_order_metabox.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_add_order_metabox() {
		global $post;
		$product = wc_get_product( $post->ID );
		add_meta_box(
			'ced_shopee_manage_orders_metabox',
			__( 'Manage Marketplace Orders', 'woocommerce-shopee-integration' ) . wc_help_tip( __( 'Please send shipping confirmation or order cancellation request.', 'woocommerce-shopee-integration' ) ),
			array( $this, 'ced_shopee_render_orders_metabox' ),
			'shop_order',
			'advanced',
			'high'
		);
	}


	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_render_orders_metabox.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_render_orders_metabox() {
		global $post;
		$order_id = isset( $post->ID ) ? intval( $post->ID ) : '';
		if ( ! is_null( $order_id ) ) {
			$order = wc_get_order( $order_id );

			$order_from  = get_post_meta( $order_id, '_ced_shopee_order_id', true );
			$marketplace = strtolower( $order_from );

			$template_path = CED_SHOPEE_DIRPATH . 'admin/partials/order-template.php';
			if ( file_exists( $template_path ) ) {
				include_once $template_path;
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_inventory_schedule_manager.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_inventory_schedule_manager() {
		$hook           = current_action();
		$shopid         = get_option( $hook );
		$sync_inventory = get_option( 'shopee_auto_syncing' . $shopid, false );
		if ( 'on' != $sync_inventory ) {
			return;
		}
		$shopid           = trim( $shopid );
		$products_to_sync = get_option( 'ced_shopee_chunk_products', array() );
		if ( empty( $products_to_sync ) ) {
			$store_products   = get_posts(
				array(
					'numberposts'  => -1,
					'post_type'    => 'product',
					'meta_key'     => '_ced_shopee_listing_id_' . $shopid,
					'meta_compare' => 'exists',
				)
			);
			$store_products   = wp_list_pluck( $store_products, 'ID' );
			$products_to_sync = array_chunk( $store_products, 20 );
		}
		if ( is_array( $products_to_sync[0] ) && ! empty( $products_to_sync[0] ) ) {
			$get_product_detail = $this->ced_shopee_manager->prepare_product_html_for_update_stock( $products_to_sync[0], $shopid, true );
			$get_product_detail = $this->ced_shopee_manager->prepare_product_html_for_update_price( $products_to_sync[0], $shopid, true );
			unset( $products_to_sync[0] );
			$products_to_sync = array_values( $products_to_sync );
			update_option( 'ced_shopee_chunk_products', $products_to_sync );
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_order_schedule_manager.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_order_schedule_manager() {
		$hook           = current_action();
		$shopid         = get_option( $hook );
		$sync_inventory = get_option( 'shopee_auto_syncing' . $shopid, false );
		if ( 'on' != $sync_inventory ) {
			return;
		}

		$file_orders = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-cedshopeeorders.php';
		if ( file_exists( $file_orders ) ) {
			include_once $file_orders;
		}

		$shopee_orders_instance    = Class_CedShopeeOrders::get_instance();
		$ced_shopee_get_the_orders = $shopee_orders_instance->ced_shopee_get_the_orders( $shopid );
	}

	public function ced_shopee_get_order_update() {

		global $wpdb;
		$current_action = current_action();
		$shop_id        = str_replace( 'ced_shopee_get_order_update_', '', $current_action );
		$shop_id        = trim( $shop_id );
		$ordersn        = get_option( 'ced_shopee_order_to_be_updated', array() );
		$ced_shopee_partner_id = '841326';
		$action                = 'orders/detail';

		if ( empty( $ordersn ) ) {
			$order_ids = $wpdb->get_results( $wpdb->prepare( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key`=%s AND `meta_value`=%s", '_shopee_umb_order_status', 'Fetched' ), 'ARRAY_A' );
			
			if ( ! empty( $order_ids ) ) {
				$order_nos = array();
				foreach ( $order_ids as $key => $value ) {
					$order_id        = $value['post_id'];
					$shopee_order_id = get_post_meta( $order_id, '_ced_shopee_order_id', true );
					if ( isset( $shopee_order_id ) ) {
						$order_nos[] = $shopee_order_id;
					}
				}

				

				if ( ! empty( $order_nos ) ) {
					$ordersn = array_chunk( $order_nos, 30 );

				}
			}
		}
		if ( isset( $ordersn[0] ) && is_array( $ordersn[0] ) && ! empty( $ordersn[0] ) ) {
			$details_parametes['ordersn_list'] = array_values( array_unique( $ordersn[0] ) );
			$details_parametes['partner_id']   = $ced_shopee_partner_id;
			$details_parametes['shopid']       = $shop_id;
			$details_parametes['timestamp']    = time();
			$retrieved_orders                  = $this->shopee_send_http_request_instance->send_http_request( $action, $details_parametes, $shop_id );

			if ( isset( $retrieved_orders['orders'] ) && ! empty( $retrieved_orders['orders'] ) ) {
				$file_orders = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-cedshopeeorders.php';
				if ( file_exists( $file_orders ) ) {
					include_once $file_orders;
				}

				$shopee_orders_instance = Class_CedShopeeOrders::get_instance();
				$shopee_orders_instance->create_local_order( $retrieved_orders['orders'], $shop_id );
			}
			unset( $ordersn[0] );
			$ordersn = array_values( $ordersn );
			update_option( 'ced_shopee_order_to_be_updated', $ordersn );
		}
	}


	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_get_orders.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_get_orders() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$shop_id = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';

			$is_shop_inactive = ced_shopee_inactive_shops( $shop_id );
			if ( $is_shop_inactive ) {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Shop is Not Active',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}

			$file_orders = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-cedshopeeorders.php';
			if ( file_exists( $file_orders ) ) {
				include_once $file_orders;
			}

			$shopee_orders_instance    = Class_CedShopeeOrders::get_instance();
			$ced_shopee_get_the_orders = $shopee_orders_instance->ced_shopee_get_the_orders( $shop_id );
		}
	}

	public function ced_shopee_sync_existing_products() {

		$current_action = current_action();
		$shop_id        = str_replace( 'ced_shopee_sync_existing_products_', '', $current_action );
		if ( empty( (int) $shop_id ) ) {
			$shop_id = get_option( 'ced_shopee_shop_id', '' );
		}
		$items_to_import = array();
		require_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
		$sendRequestObj = new Class_Ced_Shopee_Send_Http_Request();
		$action         = 'items/get';
		$offset         = get_option( 'ced_shopee_get_import_offset', '' );
		if ( empty( $offset ) ) {
			$offset = 0;
		}
		$result = $sendRequestObj->send_http_request_to_import( $action, (int) $offset, '', $shop_id );
		if ( isset( $result['more'] ) && 1 == $result['more'] ) {
			$offset = $offset + 100;
			update_option( 'ced_shopee_get_import_offset', $offset );
		} else {
			update_option( 'ced_shopee_get_import_offset', 0 );
		}

		if ( isset( $result['items'] ) && ! empty( $result['items'] ) ) {
			$items_to_import = $result['items'];
			if ( is_array( $items_to_import ) ) {
				foreach ( $items_to_import as $key1 => $itemid ) {
					if ( ! empty( $itemid['item_sku'] ) ) {
						$id = wc_get_product_id_by_sku( trim( $itemid['item_sku'] ) );
						if ( ! empty( $id ) ) {
							update_post_meta( $id, '_ced_shopee_listing_id_' . $shop_id, trim( $itemid['item_id'] ) );
						}
					}
					if ( ! empty( $itemid['variations'] ) ) {
						foreach ( $itemid['variations'] as $var => $varsku ) {
							$id = wc_get_product_id_by_sku( trim( $varsku['variation_sku'] ) );
							if ( ! empty( $id ) ) {
								$_product = wc_get_product( $id );
								if ( is_object( $_product ) ) {
									if ( $_product->get_type() == 'variation' ) {
										update_post_meta( $_product->get_parent_id(), '_ced_shopee_listing_id_' . $shop_id, trim( $itemid['item_id'] ) );
									}
								}
								update_post_meta( $id, '_ced_shopee_listing_id_' . $shop_id, trim( $varsku['variation_id'] ) );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_get_products_to_import.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_get_products_to_import() {
		
		$current_action = current_action();
		$shop_id        = str_replace( 'ced_shopee_auto_import_cron_', '', $current_action );
		
		include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
		$send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

		$category        = get_option( 'shopee_fetch_all_category_' . $shop_id );
		if ( empty( $category ) ) {
			include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
			$action           = 'item/categories/get';
			$send_request_obj = new Class_Ced_Shopee_Send_Http_Request();
			$categories       = $send_request_obj->send_http_request( $action, array(), $shop_id );
			update_option( 'shopee_fetch_all_category_' . $shop_id, json_encode( $categories ) );
		}
		$product_array_chunk = get_option( '_ced_ced_Shopee_Product_array_ced_shopee_ced_shopee_ced', array() );
		
		if ( empty( $product_array_chunk ) ) {


		$action = 'items/get';
		$result = $send_request_obj->send_http_request_to_import( $action, '', '', $shop_id );
		
		$count              = 0;
		$items_to_import[0] = $result['items'];
		// echo'<pre>';
		// print_r($items_to_import);
		// die("llllllll");
		while ( 1 == $result['more'] ) {
			$count++;
			$offset = $count * 100;
			$result = $send_request_obj->send_http_request_to_import( $action, $offset, '', $shop_id );
			array_push( $items_to_import, $result['items'] );
		}
		$item_id_to_import = array();
		foreach ( $items_to_import as $key => $value ) {
			ini_set('memory_limit',-1);
			ini_set('max_execution_time',-1);
			if ( is_array( $value ) ) {
				foreach ( $value as $key1 => $itemid ) {
					if ( 'DELETED' != $itemid['status'] ) {
						array_push( $item_id_to_import, $itemid['item_id'] );
					}
				}
			}
		}
		update_option( 'shopee_poduct_to_import__ced' . $shop_id, $item_id_to_import );
		$items_to_import = get_option( 'shopee_poduct_to_import__ced' . $shop_id, array() );

			$product_array_chunk = array_chunk( $items_to_import, 10 );
		}

		foreach ( $product_array_chunk[0] as $key => $value ) {
			$action  = 'item/get';
			$results = $send_request_obj->send_http_request_to_import( $action, '', $value, $shop_id );
			$result1 = $results['item'];

			$this->ced_shopee_create_imported_product( $result1, $value, $shop_id );
		}
		unset( $product_array_chunk[0] );
		$product_array_chunk = array_values( $product_array_chunk );
		update_option( '_ced_ced_Shopee_Product_array_ced_shopee_ced_shopee_ced', $product_array_chunk );
		//die('ho gya');
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_create_imported_product.
	 *
	 * @since 1.0.0
	 * @param array $result Data of imported product.
	 * @param int   $value Product listing id.
	 * @param int   $shop_id Shop id.
	 */
	public function ced_shopee_create_imported_product( $result, $value, $shop_id ) {
		$final_product_formate_arrays = $this->ced_shopee_prepare_array( $result, $value, $shop_id );

		$get_listing_ids = get_posts(
			array(
				'numberposts'  => -1,
				'post_type'    => array( 'product', 'product_variation' ),
				'meta_key'     => '_ced_shopee_listing_id_' . $shop_id,
				'meta_value'   => $value,
				'meta_compare' => '=',
			)
		);
		$get_listing_ids = wp_list_pluck( $get_listing_ids, 'ID' );
		if ( ! empty( $get_listing_ids ) ) {
			return false;
		}
		$post = array(
			'post_title'   => $final_product_formate_arrays['parent_or_simple_product']['title'],
			'post_content' => $final_product_formate_arrays['parent_or_simple_product']['description'],
			'post_status'  => 'publish',
			'post_parent'  => '',
			'post_type'    => 'product',
		);

		$post_id = wp_insert_post( $post );
		if ( ! $post_id ) {
			return false;
		}

		update_post_meta( $post_id, '_sku', $final_product_formate_arrays['parent_or_simple_product']['sku'] );
		if ( 1 == $final_product_formate_arrays['is_variation'] || '1' == $final_product_formate_arrays['is_variation'] ) {
			wp_set_object_terms( $post_id, 'variable', 'product_type' );
		} else {
			wp_set_object_terms( $post_id, 'simple', 'product_type' );
		}
		update_post_meta( $post_id, '_visibility', 'visible' );
		update_post_meta( $post_id, '_ced_shopee_listing_id_' . $shop_id, $value );
		update_post_meta( $post_id, '_stock', $final_product_formate_arrays['parent_or_simple_product']['stock'] );
		if ( $final_product_formate_arrays['parent_or_simple_product']['stock'] > 0 ) {
			update_post_meta( $post_id, '_manage_stock', 'yes' );

			update_post_meta( $post_id, '_stock_status', 'instock' );
		} else {
			update_post_meta( $post_id, '_stock_status', 'outofstock' );
			update_post_meta( $post_id, '_manage_stock', 'yes' );
			update_post_meta( $post_id, '_stock', 0 );
		}
		update_post_meta( $post_id, '_weight', $final_product_formate_arrays['parent_or_simple_product']['shipping_weight'] );
		update_post_meta( $post_id, '_regular_price', $final_product_formate_arrays['parent_or_simple_product']['price'] );
		update_post_meta( $post_id, '_price', $final_product_formate_arrays['parent_or_simple_product']['price'] );
		update_post_meta( $post_id, '_length', $final_product_formate_arrays['parent_or_simple_product']['length'] );
		update_post_meta( $post_id, '_width', $final_product_formate_arrays['parent_or_simple_product']['width'] );
		update_post_meta( $post_id, '_height', $final_product_formate_arrays['parent_or_simple_product']['height'] );

		$image_url_array = $final_product_formate_arrays['parent_or_simple_product']['image_link'];
		$image_ids       = array();
		foreach ( $image_url_array as $key1 => $value1 ) {
			$image_url  = $value1;
			$image_name = explode( '/', $image_url );
			$image_name = $image_name[ count( $image_name ) - 1 ];

			$upload_dir = wp_upload_dir();
			$image_url  = str_replace( 'https', 'http', $image_url );
			$image_data = file_get_contents( $image_url );

			$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
			$filename         = basename( $unique_file_name );
			$filename         = $filename . '.jpeg';
			if ( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}

			global $wp_filesystem;
			$url = wp_nonce_url( 'ced-shopee-seurity-string', 'woocommerce-shopee-integration' );

			$creds = request_filesystem_credentials( $url, '', false, '', null );
			if ( false === $creds ) {
				esc_html_e( 'Credentials are required to save the file.', 'your_text_domain' );
				exit();
			}

			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( $url, '', true, false, null );
				esc_html_e( 'Credentials are required to save the file', 'your_text_domain' );
				exit();
			}

			if ( is_array( $categories ) && isset( $categories ) ) {
				$get_location              = ced_shopee_account_location( $shop_id );
				$get_filenames             = ced_shopee_category_files();
				$category_first_level_file = $folder_name . $get_filenames[ $get_location ]['level1'];
				foreach ( $categories['categories'] as $key => $value ) {
					if ( isset( $value['parent_id'] ) && 0 == $value['parent_id'] ) {
						$level1_category[] = $value;
					}
				}
			}
			$wp_filesystem->put_contents(
				$file,
				$image_data,
				FS_CHMOD_FILE
			);

			$wp_filetype = wp_check_filetype( $filename, null );

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

			include_once ABSPATH . 'wp-admin/includes/image.php';

			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

			wp_update_attachment_metadata( $attach_id, $attach_data );

			if ( 0 == $key1 ) {
				set_post_thumbnail( $post_id, $attach_id );
			} else {
				$image_ids[] = $attach_id;
			}
		}

		update_post_meta( $post_id, '_product_image_gallery', implode( ',', $image_ids ) );

		$cat_id                = $final_product_formate_arrays['parent_or_simple_product']['category'];
		$all_shopee_categories = get_option( 'shopee_fetch_all_category_' . $shop_id, true );
		$all_shopee_categories = json_decode( $all_shopee_categories, true );
		foreach ( $all_shopee_categories['categories'] as $key => $value ) {
			if ( $value['category_id'] == $cat_id ) {
				$woo_cat_name = $value['category_name'];
			}
		}

		if ( ! empty( $woo_cat_name ) ) {
			$term = wp_insert_term(
				$woo_cat_name,
				'product_cat',
				array(
					'description' => $woo_cat_name,
				)
			);
			if ( isset( $term->error_data['term_exists'] ) ) {
				$term_id = $term->error_data['term_exists'];
			} elseif ( isset( $term['term_id'] ) ) {
				$term_id = $term['term_id'];
			}
			if ( $term_id ) {
				$term = get_term_by( 'name', $woo_cat_name, 'product_cat' );
				wp_set_object_terms( $post_id, $term_id, 'product_cat' );
			}
		}

		$variations_theme = $final_product_formate_arrays['variations_theme']['attributes_names_val'];

		$count          = 1;
		$attribute_name = $variations_theme;
		// echo'<pre>';
		// print_r($attribute_name);
		foreach ( $attribute_name as $key => $value ) {
			$values                       = array_unique( $value );
			$values                       = array_values( $values );
			$key                          = trim( $key );

			$key                          = str_replace( ' ', '-', $key );
			// $key  = strtolower( $key );
			// $key  = trim( $key );
			
			//$key  = sanitize_title($key);
			$data['attribute_names'][]    = $key;

			$data['attribute_position'][] = $count;
			$implode_values               = array();
			
			foreach ( $values as $key => $valuev ) {
				$trim_value       = trim( $valuev );
				//echo "trim" . $trim_value;
				$value1           = str_replace( '-', ' ', $trim_value );///// this changes make it work
				//echo "str" . $value1;
				$implode_values[] = $value1;
			}
			$data['attribute_values'][]     = implode( '|', $implode_values );
			$data['attribute_visibility'][] = 1;
			$data['attribute_variation'][]  = 1;
			$count++;
		}
		if ( isset( $data['attribute_names'], $data['attribute_values'] ) ) {
			$attribute_names         = $data['attribute_names'];
			$attribute_values        = $data['attribute_values'];
			$attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
			$attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
			$attribute_position      = $data['attribute_position'];
			$attribute_names_max_key = max( array_keys( $attribute_names ) );
			$attributes              = array();
			for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
				if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
					continue;
				}
				$attribute_id   = 0;
				$attribute_name = wc_clean( $attribute_names[ $i ] );
				if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
					$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
				}
				$options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';
				if ( is_array( $options ) ) {
					$options = wp_parse_id_list( $options );
				} else {
					$options = wc_get_text_attributes( $options );
				}

				if ( empty( $options ) ) {
					continue;
				}
				$attribute = new WC_Product_Attribute();
				$attribute->set_id( $attribute_id );
				$attribute->set_name( $attribute_name );
				$attribute->set_options( $options );
				$attribute->set_position( $attribute_position[ $i ] );
				$attribute->set_visible( isset( $attribute_visibility[ $i ] ) );
				$attribute->set_variation( isset( $attribute_variation[ $i ] ) );
				$attributes[] = $attribute;
			}
		}
		if ( 1 == $final_product_formate_arrays['is_variation'] || '1' == $final_product_formate_arrays['is_variation'] ) {
			$product_type = 'variable';
		} else {
			$product_type = 'simple';
		}
		$classname = WC_Product_Factory::get_product_classname( $post_id, $product_type );
		$product   = new $classname( $post_id );
		$product->set_attributes( $attributes );
		$product->save();

		$variations_theme = $final_product_formate_arrays['variations_theme']['attributes_names_val'];
		$variations       = $final_product_formate_arrays['variations'];

		$post_id = $post_id;
		if ( ! is_array( $variations ) ) {
			$variations = array( $variations );
		}
		foreach ( $variations as $index => $variation ) {
			$variation_post = array(

				'post_title'  => 'Variation #' . $index . ' of ' . count( $variations ) . ' for product#' . $post_id,
				'post_name'   => 'product-' . $post_id . '-variation-' . $index,
				'post_status' => 'publish',
				'post_parent' => $post_id,
				'post_type'   => 'product_variation',
				'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index,
			);

			$variation_post_id = wp_insert_post( $variation_post );

			$available_attributes = $variations_theme;
			if ( is_array( $available_attributes ) ) {
				foreach ( $available_attributes as $key => $value ) {
					$values = array_unique( $value );

					$values = array_values( $values );

					wp_set_object_terms( $variation_post_id, $values, $key );
				}
			}
if ( is_array( $variation['variation_att_name'] ) ) {
	//print_r($variation['variation_att_name']);
	//die("kkkoooo");
				foreach ( $variation['variation_att_name'] as $attribute => $value ) {
					$value = trim( $value );
					//$value = str_replace( ' ', '-', $value );
					$attr  = strtolower( $attribute );
					$attr  = trim( $attr );
					$attr  = sanitize_title($attr);
					
					//$attr  = str_replace( ' ', '-', $attr );
					update_post_meta( $variation_post_id, 'attribute_' . $attr, $value );

					$thedata = array(
						$attr => array(
							'name'         => $attr,
							'value'        => $value,
							'is_visible'   => '1',
							'is_variation' => '1',
							'is_taxonomy'  => '1',
						),
					);

					update_post_meta( $variation_post_id, '_product_attributes', $thedata );
				}
			}
			$variation_att_values = $variation['variation_att_value'];
			if ( $variation_att_values['stock'] > 0 ) {
				update_post_meta( $variation_post_id, '_manage_stock', 'yes' );
				update_post_meta( $variation_post_id, '_stock', $variation_att_values['stock'] );
				update_post_meta( $variation_post_id, '_stock_status', 'instock' );
			} else {
				update_post_meta( $variation_post_id, '_stock_status', 'outofstock' );
				update_post_meta( $variation_post_id, '_manage_stock', 'yes' );
				update_post_meta( $variation_post_id, '_stock', 0 );
			}

			update_post_meta( $variation_post_id, '_price', $variation_att_values['price'] );
			update_post_meta( $variation_post_id, '_sku', $variation_att_values['sku'] );

			update_post_meta( $variation_post_id, '_weight', $final_product_formate_arrays['parent_or_simple_product']['shipping_weight'] );

			update_post_meta( $variation_post_id, '_ced_shopee_listing_id_' . $shop_id, $variation_att_values['variation_id'] );
			update_post_meta( $variation_post_id, '_regular_price', $variation_att_values['price'] );

			$classname = WC_Product_Factory::get_product_classname( $variation_post_id, 'variation' );
			$product   = new $classname( $variation_post_id );
			$product->save();
		}
		$classname = WC_Product_Factory::get_product_classname( $post_id, $product_type );
		$product   = new $classname( $post_id );
		$product->save();
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_prepare_array.
	 *
	 * @since 1.0.0
	 * @param array $result Data of imported product.
	 * @param int   $item_id Product id.
	 * @param int   $shop_id Shop id.
	 */
	public function ced_shopee_prepare_array( $result, $item_id, $shop_id ) {
		$product_data                  = array();
		$product_parent                = array();
		$product_parent['id']          = $result['item_id'];
		$product_parent['title']       = $result['name'];
		$product_parent['description'] = $result['description'];
		$product_parent['price']       = $result['original_price'];
		$product_parent['stock']       = $result['stock'];
		$product_parent['sku']         = $result['item_sku'];
		$product_parent['category']    = $result['category_id'];
		$product_parent['length']      = $result['package_length'];
		$product_parent['width']       = $result['package_width'];
		$product_parent['height']      = $result['package_height'];
		foreach ( $result['images'] as $key => $value ) {
			$image_url[] = $value;
		}
		$product_parent['image_link']             = $image_url;
		$product_parent['shipping_weight']        = $result['weight'];
		$product_data['parent_or_simple_product'] = $product_parent;
		$product_data['is_variation']             = $result['has_variation'];

		if ( '1' == $result['has_variation'] ) {
			include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
			$send_request_obj          = new Class_Ced_Shopee_Send_Http_Request();
			$action                    = 'item/tier_var/get';
			$variation_data            = $send_request_obj->send_http_request_to_import( $action, '', $item_id, $shop_id );
			$variation_theme           = array();
			$variation_attribute       = array();
			$variation_attributes_name = array();
			if ( isset( $variation_data['tier_variation'] ) ) {
				foreach ( $variation_data['tier_variation'] as $key => $value ) {
					if ( isset( $value['name'] ) ) {
						$variation_attributes_name[] = $value['name'];
					}
					foreach ( $value['options'] as $k => $v ) {
						$variation_attribute[ $value['name'] ][] = $v;
					}
					$variation_theme['attributes_names_val'] = $variation_attribute;
				}
			}
			if ( ! isset( $variation_attributes_name['0'] ) ) {
				$variation_attributes_name['0'] = 'var_attribute';
			}
			if ( isset( $variation_theme['attributes_names_val'] ) ) {
				$product_data['variations_theme'] = $variation_theme;
			} else {
				$varition_single_att_val = array();
				foreach ( $result['variations'] as $data => $variation ) {
					if ( isset( $variation['name'] ) ) {
						$varition_single_att_val[] = $variation['name'];
					}
				}
				$product_data['variations_theme']['attributes_names_val']['var_attribute'] = $varition_single_att_val;
			}
			foreach ( $result['variations'] as $data => $variation ) {
				$variation_level_data   = array();
				$variation_values_final = array();
				$variation_values       = explode( ',', $variation['name'] );
				if ( isset( $variation_values['1'] ) ) {
					foreach ( $variation_attributes_name as $key => $value ) {
						$variation_values_final[ $value ] = $variation_values[ $key ];
					}
				} elseif ( isset( $variation_values['0'] ) ) {
					$variation_values_final[ $variation_attributes_name['0'] ] = $variation['name'];
				}

				$variation_level_data['variation_att_name']                  = $variation_values_final;
				$variation_level_data['variation_att_value']['sku']          = $variation['variation_sku'];
				$variation_level_data['variation_att_value']['title']        = $result['name'] . '-(' . $variation['name'] . ')';
				$variation_level_data['variation_att_value']['price']        = $variation['original_price'];
				$variation_level_data['variation_att_value']['variation_id'] = $variation['variation_id'];
				$variation_level_data['variation_att_value']['stock']        = $variation['stock'];
				$product_data['variations'][]                                = $variation_level_data;
			}
		}

		return $product_data;
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_process_bulk_action.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_process_bulk_action() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$ced_shopee_manager = $this->ced_shopee_manager;
			$shop_id            = isset( $_POST['shopid'] ) ? sanitize_text_field( wp_unslash( $_POST['shopid'] ) ) : '';

			$is_shop_inactive = ced_shopee_inactive_shops( $shop_id );
			if ( $is_shop_inactive ) {
				echo json_encode(
					array(
						'status'  => 400,
						'message' => __(
							'Shop is Not Active',
							'woocommerce-shopee-integration'
						),
					)
				);
				die;
			}

			$operation  = isset( $_POST['operation_to_be_performed'] ) ? sanitize_text_field( wp_unslash( $_POST['operation_to_be_performed'] ) ) : '';
			$product_id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			if ( 'upload_product' == $operation ) {
				$already_uploaded = get_post_meta( $product_id, '_ced_shopee_listing_id_' . $shop_id, true );

				if ( $already_uploaded ) {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => __(
								'Product Already Uploaded',
								'woocommerce-shopee-integration'
							),
						)
					);
					die;
				} else {
					$get_product_detail = $ced_shopee_manager->prepare_product_html_for_upload( $product_id, $shop_id );
					if ( isset( $get_product_detail['item_id'] ) ) {
						echo json_encode(
							array(
								'status'  => 200,
								'message' => $get_product_detail['item']['name'] . ' Uploaded Successfully',
								'prodid'  => $product_id,
							)
						);
						die;
					} else {
						echo json_encode(
							array(
								'status'  => 400,
								'message' => $get_product_detail['msg'],
								'prodid'  => $product_id,
							)
						);
						die;
					}
				}
			} elseif ( 'update_product' == $operation ) {
				$already_uploaded = get_post_meta( $product_id, '_ced_shopee_listing_id_' . $shop_id, true );
				if ( $already_uploaded ) {
					$get_product_detail = $ced_shopee_manager->prepare_product_html_for_update( $product_id, $shop_id );
					if ( isset( $get_product_detail['item_id'] ) ) {
						echo json_encode(
							array(
								'status'  => 200,
								'message' => $get_product_detail['item']['name'] . ' Updated Successfully',
							)
						);
						die;
					} else {
						echo json_encode(
							array(
								'status'  => 400,
								'message' => $get_product_detail['msg'],
							)
						);
						die;
					}
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => __(
								'Product Not Found On Shopee',
								'woocommerce-shopee-integration'
							),
						)
					);
					die;
				}
			} elseif ( 'remove_product' == $operation ) {
				$already_uploaded = get_post_meta( $product_id, '_ced_shopee_listing_id_' . $shop_id, true );
				if ( $already_uploaded ) {
					$get_product_detail = $ced_shopee_manager->prepare_product_html_for_delete( $product_id, $shop_id );
					if ( isset( $get_product_detail['item_id'] ) ) {
						echo json_encode(
							array(
								'status'  => 200,
								'message' => 'Product ' . $product_id . ' Deleted Successfully',
								'prodid'  => $product_id,
							)
						);
						die;
					} else {
						echo json_encode(
							array(
								'status'  => 400,
								'message' => $get_product_detail['msg'],
							)
						);
						die;
					}
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => __(
								'Product Not Found On Shopee',
								'woocommerce-shopee-integration'
							),
						)
					);
					die;
				}
			} elseif ( 'update_stock' == $operation ) {
				$already_uploaded = get_post_meta( $product_id, '_ced_shopee_listing_id_' . $shop_id, true );
				if ( $already_uploaded ) {
					$get_product_detail = $ced_shopee_manager->prepare_product_html_for_update_stock( $product_id, $shop_id );
					if ( isset( $get_product_detail['item'] ) ) {
						echo json_encode(
							array(
								'status'  => 200,
								'message' => __(
									'Stock Updated Successfully',
									'woocommerce-shopee-integration'
								),
							)
						);
						die;
					} else {
						echo json_encode(
							array(
								'status'  => 400,
								'message' => __(
									$get_product_detail['msg'],
									'woocommerce-shopee-integration'
								),
							)
						);
						die;
					}
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => __(
								'Product Not Found On Shopee',
								'woocommerce-shopee-integration'
							),
						)
					);
					die;
				}
			} elseif ( 'update_image' == $operation ) {
				$already_uploaded = get_post_meta( $product_id, '_ced_shopee_listing_id_' . $shop_id, true );
				if ( $already_uploaded ) {
					$get_product_detail = $ced_shopee_manager->prepare_product_html_for_update_image( $product_id, $shop_id );
					if ( isset( $get_product_detail['images'] ) ) {
						echo json_encode(
							array(
								'status'  => 200,
								'message' => __(
									'Images Updated Successfully',
									'woocommerce-shopee-integration'
								),
							)
						);
						die;
					} else {
						echo json_encode(
							array(
								'status'  => 400,
								'message' => $get_product_detail['msg'],
							)
						);
						die;
					}
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => __(
								'Product Not Found On Shopee',
								'woocommerce-shopee-integration'
							),
						)
					);
					die;
				}
			} elseif ( 'update_price' == $operation ) {
				$already_uploaded = get_post_meta( $product_id, '_ced_shopee_listing_id_' . $shop_id, true );
				if ( $already_uploaded ) {
					$get_product_detail = $ced_shopee_manager->prepare_product_html_for_update_price( $product_id, $shop_id );
					if ( isset( $get_product_detail['item'] ) ) {
						echo json_encode(
							array(
								'status'  => 200,
								'message' => __(
									'Price Updated Successfully',
									'woocommerce-shopee-integration'
								),
							)
						);
						die;
					} else {
						echo json_encode(
							array(
								'status'  => 400,
								'message' => __(
									$get_product_detail['msg'],
									'woocommerce-shopee-integration'
								),
							)
						);
						die;
					}
				} else {
					echo json_encode(
						array(
							'status'  => 400,
							'message' => __(
								'Product Not Found On Shopee',
								'woocommerce-shopee-integration'
							),
						)
					);
					die;
				}
			}
		}
	}

		/**
	 * Woocommerce_shopee_Integration_Admin ced_shopee_get_product_metakeys.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_get_product_metakeys() {
		
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$product_id = isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id'] ) : '';
			include_once CED_SHOPEE_DIRPATH . 'admin/partials/ced-shopee-metakeys-list.php';
		}
	}

	/**
	 * Woocommerce_Shopee_Integration_Admin ced_shopee_search_product_name.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_search_product_name() {
		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$keyword      = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
			$product_list = '';
			if ( ! empty( $keyword ) ) {
				$arguements = array(
					'numberposts' => -1,
					'post_type'   => array( 'product', 'product_variation' ),
					's'           => $keyword,
				);
				$post_data  = get_posts( $arguements );

				if ( ! empty( $post_data ) ) {
					foreach ( $post_data as $key => $data ) {
						$product_list .= '<li class="ced_shopee_searched_product" data-post-id="' . esc_attr( $data->ID ) . '">' . esc_html( __( $data->post_title, 'shopee-woocommerce-integration' ) ) . '</li>';
					}
				} else {
					$product_list .= '<li>No products found.</li>';
				}
			} else {
				$product_list .= '<li>No products found.</li>';
			}
			echo json_encode( array( 'html' => $product_list ) );
			wp_die();
		}
	}

	/**
	 * Woocommerce_shopee_Integration_Admin ced_shopee_process_metakeys.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopee_process_metakeys() {

		$check_ajax = check_ajax_referer( 'ced-shopee-ajax-seurity-string', 'ajax_nonce' );
		if ( $check_ajax ) {
			$metakey   = isset( $_POST['metakey'] ) ? sanitize_text_field( wp_unslash( $_POST['metakey'] ) ) : '';
			$operation = isset( $_POST['operation'] ) ? sanitize_text_field( wp_unslash( $_POST['operation'] ) ) : '';
			if ( ! empty( $metakey ) ) {
				$added_meta_keys = get_option( 'ced_shopee_selected_metakeys', array() );
				if ( 'store' == $operation ) {
					$added_meta_keys[ $metakey ] = $metakey;
				} elseif ( 'remove' == $operation ) {
					unset( $added_meta_keys[ $metakey ] );
				}
				update_option( 'ced_shopee_selected_metakeys', $added_meta_keys );
				echo json_encode( array( 'status' => 200 ) );
				die();
			} else {
				echo json_encode( array( 'status' => 400 ) );
				die();
			}
		}
	}
}
