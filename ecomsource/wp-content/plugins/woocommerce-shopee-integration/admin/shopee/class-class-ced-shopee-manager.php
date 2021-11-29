<?php
/**
 * Manage all single product related functionality required for listing product on marketplaces.
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}


if ( ! class_exists( 'Class_Ced_Shopee_Manager' ) ) {
	/**
	 * Class_Ced_Shopee_Manager.
	 *
	 * @since 1.0.0
	 */
	class Class_Ced_Shopee_Manager {

		/**
		 * The instance variable of this class.
		 *
		 * @since    1.0.0
		 * @var      object    $_instance    The instance variable of this class.
		 */
		private static $_instance;
		/**
		 * The authorization variable of this class.
		 *
		 * @since    1.0.0
		 * @var      object    $authorization_obj    The authorization variable of this class.
		 */
		private static $authorization_obj;
		/**
		 * The client variable of this class.
		 *
		 * @since    1.0.0
		 * @var      object    $client_obj    The client variable of this class.
		 */
		private static $client_obj;

		/**
		 * Class_Ced_Shopee_Manager Instance.
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Class_Ced_Shopee_Manager construct.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->load_dependency();
			add_action( 'admin_init', array( $this, 'ced_shopee_get_shop_data' ), 13 );
			add_action( 'updated_post_meta', array( $this, 'ced_update_product_on_shopee' ), 10, 4 );
			add_action( 'admin_init', array( $this, 'ced_shopee_set_schedules' ) );
			add_action('woocommerce_thankyou' , array($this,'ced_shopee_update_inventory_on_order_creation') , 10 , 1 );
			add_filter( 'woocommerce_duplicate_product_exclude_meta', array( $this, 'woocommerce_duplicate_product_exclude_meta' ) , 11 , 1 );
		}

		public function woocommerce_duplicate_product_exclude_meta( $metakeys = array() ) {
			$shop_id  = get_option( 'ced_shopee_shop_id', '' );
			$metakeys[] = '_ced_shopee_listing_id_' . $shop_id;
			return $metakeys;
		    }

		public function ced_shopee_set_schedules() {

			if ( isset( $_GET['shop_id'] ) && ! empty( $_GET['shop_id'] ) ) {
				$shop_id = sanitize_text_field( $_GET['shop_id'] );
				if ( ! wp_get_schedule( 'ced_shopee_get_order_update_' . $shop_id ) ) {
					wp_schedule_event( time(), 'ced_shopee_15min', 'ced_shopee_get_order_update_' . $shop_id );
				}
			}
		}

		public function ced_shopee_update_inventory_on_order_creation( $order_id ) {
			if ( empty( $order_id ) ) {
				return;
			}
			$product_ids   = array();
			$inventory_log = array();
			$order_obj     = wc_get_order( $order_id );
			$order_items   = $order_obj->get_items();
			if ( is_array( $order_items ) && !empty( $order_items ) ) {
				foreach ($order_items as $key => $value) {
					$product_id    = $value->get_data()['product_id'];
					$product_ids[] = $product_id;
				}
			}
			if (is_array($product_ids) && !empty($product_ids)) {
				$shop_id = get_option('ced_shopee_shop_id', '');
				$response        = $this->prepare_product_html_for_update_stock( $product_ids, $shop_id, true );
				$inventory_log[] = $response;
			}
		}

		


		public function ced_update_product_on_shopee( $meta_id, $product_id, $metakey, $metavalue ) {

			if ( '_stock' == $metakey ) {
				$shop_id  = get_option( 'ced_shopee_shop_id', '' );
				$_product = wc_get_product( $product_id );

				if ( $_product->get_type() == 'variation' ) {
					$product_id = $_product->get_parent_id();

				}
				$this->prepare_product_html_for_update_stock( $product_id, $shop_id, true );

			} elseif ( '_price' == $metakey || '_regular_price' ==  $metakey ) {
				$shop_id  = get_option( 'ced_shopee_shop_id', '' );
				$_product = wc_get_product( $product_id );

				if ( $_product->get_type() == 'variation' ) {
					$product_id = $_product->get_parent_id();

				}
				$this->prepare_product_html_for_update_price( $product_id, $shop_id, true );
			}
		}

		/**
		 * Class_Ced_Shopee_Manager cedShopeeGetOrderStatus.
		 *
		 * @since 1.0.0
		 */
		public function ced_shopee_get_shop_data() {
			if ( isset( $_GET['shop_id'] ) && ! empty( $_GET['shop_id'] ) && ! isset( $_GET['section'] ) ) {
				$shop_id = sanitize_text_field( wp_unslash( $_GET['shop_id'] ) );

				$file_shop = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-shop.php';
				if ( file_exists( $file_shop ) ) {
					include_once $file_shop;
				}

				$file_category = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-category.php';
				if ( file_exists( $file_category ) ) {
					include_once $file_category;
				}

				$shopee_shop_instance = Class_Ced_Shopee_Shop::get_instance();
				$shopee_category      = Class_Ced_Shopee_Category::get_instance();

				$shop_details = $shopee_shop_instance->get_shopee_store_data( $shop_id );

				if ( isset( $shop_details['status'] ) && '200' == $shop_details['status'] ) {
					$shop_details = isset( $shop_details['response'] ) ? $shop_details['response'] : array();
					$this->save_shopee_shop_details( $shop_details );
					$shopee_category = $shopee_category->get_shopee_category( $shop_id );
					if ( isset( $shopee_category ) ) {
						update_option( 'ced_shopee_categories' . $shop_id, $shopee_category );
						$this->ced_shopee_store_categories( $shopee_category, $shop_id );
					} else {
						echo json_encode( array( 'status' => __( 'Unable To Fetch Categories', 'woocommerce-shopee-integration' ) ) );
					}
				}
			}
		}

		/**
		 * Class_Ced_Shopee_Manager save_shopee_shop_details.
		 *
		 * @since 1.0.0
		 * @param array $shop_details Shop Details.
		 */
		public function save_shopee_shop_details( $shop_details = array() ) {
			if ( ! empty( $shop_details ) ) {
				global $wpdb;
				$table_name = 'wp_ced_shopee_accounts';
				$result     = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_accounts WHERE `shop_id`= %d ', ( $shop_details['shop_id'] ) ), 'ARRAY_A' );
				if ( empty( $result ) && count( $result ) < 1 ) {
					$wpdb->insert(
						$table_name,
						array(
							'name'           => urlencode( $shop_details['shop_name'] ),
							'account_status' => 'active',
							'shop_id'        => $shop_details['shop_id'],
							'location'       => $shop_details['country'],
						)
					);
				}
			}
		}

		/**
		 * Class_Ced_Shopee_Manager prepare_store_authorisation_url.
		 *
		 * @since 1.0.0
		 */
		public function prepare_store_authorisation_url() {
			$ced_shopee_partner_id = $this->partner_id;
			$ced_shopee_secret_id  = $this->secret_key;
			$api_url               = 'https://partner.shopeemobile.com/api/v1/shop/auth_partner';
			$parameters            = array();

			$parameters['redirect'] = admin_url( 'admin.php?page=ced_shopee' );
			$parameters['id']       = $ced_shopee_partner_id;
			$authorisation          = hash( 'sha256', $ced_shopee_secret_id . admin_url( 'admin.php?page=ced_shopee' ) );

			$parameters['token'] = $authorisation;

			$header  = array(
				'Content-Type: application/json',
				'Authorization: ' . $authorisation,
			);
			$api_url = add_query_arg( $parameters, $api_url );
			return $api_url;
		}

		/**
		 * Class_Ced_Shopee_Manager ced_shopee_create_auto_profiles.
		 *
		 * @since 1.0.0
		 * @param array $shopee_mapped_categories Mapped Categories.
		 * @param array $shopee_mapped_categories_name Mapped Categories Name.
		 * @param int   $shopee_store_id Shopee Shop Id.
		 */
		public function ced_shopee_create_auto_profiles( $shopee_mapped_categories = array(), $shopee_mapped_categories_name = array(), $shopee_store_id = '' ) {
			global $wpdb;

			$woo_store_categories           = get_terms( 'product_cat' );
			$already_mapped_categories      = get_option( 'ced_woo_shopee_mapped_categories' . $shopee_store_id, array() );
			$already_mapped_categories_name = get_option( 'ced_woo_shopee_mapped_categories_name' . $shopee_store_id, array() );

			if ( ! empty( $shopee_mapped_categories ) ) {
				foreach ( $shopee_mapped_categories as $key => $value ) {
					$profile_already_created = get_term_meta( $key, 'ced_shopee_profile_created_' . $shopee_store_id, true );
					$created_profile_id      = get_term_meta( $key, 'ced_shopee_profile_id_' . $shopee_store_id, true );

					if ( 'yes' == $profile_already_created && ! empty( $created_profile_id ) ) {
						$new_profile_need_to_be_created = $this->check_if_new_profile_need_to_be_created( $key, $value, $shopee_store_id );

						if ( ! $new_profile_need_to_be_created ) {
							continue;
						} else {
							$this->reset_mapped_category_data( $key, $value, $shopee_store_id );
						}
					}

					$woo_categories      = array();
					$category_attributes = array();

					$profile_name = isset( $shopee_mapped_categories_name[ $value ] ) ? $shopee_mapped_categories_name[ $value ] : 'Profile for Shopee - Category Id : ' . $value;
			

					$profile_exists = $this->ced_shopee_check_profile_exists( $key, $value, $shopee_store_id, $profile_name );

					if ( ! $profile_exists ) {
						continue;
					}

					$is_active = 1;
					$marketplace_name = 'Shopee';
					$woo_categories = array($key);
					$profile_data = array();
					$profile_data = $this->ced_shopee_prepare_profile_data( $shopee_store_id, $value );

					$profile_details = array(
						'profile_name'   => urlencode( $profile_name ),
						'profile_status' => 'active',
						'profile_data'   => json_encode( $profile_data ),
						'shop_id'        => $shopee_store_id,
						'woo_categories' => json_encode( $woo_categories ),
					);
					$profile_id      = $this->insert_shopee_profile( $profile_details );

					foreach ( $woo_categories as $key12 => $value12 ) {
						update_term_meta( $value12, 'ced_shopee_profile_created_' . $shopee_store_id, 'yes' );
						update_term_meta( $value12, 'ced_shopee_profile_id_' . $shopee_store_id, $profile_id );
						update_term_meta( $value12, 'ced_shopee_mapped_category_' . $shopee_store_id, $value );
					}
				}
			}
		}

		public function ced_shopee_check_profile_exists( $wooCategoryId = '', $ShopeeCategoryId = '', $ShopeeStoreId = '', $profileName = '' ) {

			global $wpdb;
			$profileTableName = $wpdb->prefix . 'wp_ced_shopee_profiles';
			$check            = false;
			$woo_category     = array();

			$profile_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ced_shopee_profiles WHERE %d", 1 ), 'ARRAY_A' );

			foreach ( $profile_data as $key => $value ) {
				if ( $value['profile_name'] == urlencode( $profileName ) ) {
					$woo_category = json_decode( $value['woo_categories'] );
					$id           = $value['id'];
					if ( ! empty( $woo_category ) ) {
						foreach ( $woo_category as $woo_key => $woo_value ) {
							if ( $wooCategoryId != $woo_value ) {
								$check = true;
								continue;
							}
						}
						if ( true == $check ) {
							$woo_category[] = $wooCategoryId;
							update_term_meta( $wooCategoryId, 'ced_shopee_profile_created_' . $ShopeeStoreId, 'yes' );
							update_term_meta( $wooCategoryId, 'ced_shopee_profile_id_' . $ShopeeStoreId, $value['id'] );
							update_term_meta( $wooCategoryId, 'ced_shopee_mapped_category_' . $ShopeeStoreId, $ShopeeCategoryId );
						}
					}
					$woo_category = json_encode( $woo_category );
					$wpdb->query(
						$wpdb->prepare(
							"UPDATE {$wpdb->prefix}ced_shopee_profiles
    				SET `woo_categories`= %s WHERE `id`= %d",
							$woo_category,
							$id
						)
					);
					return false;
				}
			}
			return true;
		}

		/**
		 * Class_Ced_Shopee_Manager insert_shopee_profile.
		 *
		 * @since 1.0.0
		 * @param array $profile_details Profile details.
		 */
		public function insert_shopee_profile( $profile_details ) {
			global $wpdb;
			$profile_table_name = 'wp_ced_shopee_profiles';

			$wpdb->insert( $profile_table_name, $profile_details );

			$profile_id = $wpdb->insert_id;
			return $profile_id;
		}

		/**
		 * Class_Ced_Shopee_Manager ced_shopee_prepare_profile_data.
		 *
		 * @since 1.0.0
		 * @param int $shopee_store_id Shopee Shop Id.
		 * @param int $shopee_category_id Shopee category Id.
		 */
		public function ced_shopee_prepare_profile_data( $shopee_store_id, $shopee_category_id ) {
			include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-category.php';
			$shopee_category_instance = Class_Ced_Shopee_Category::get_instance();

			$global_settings             = get_option( 'ced_shopee_global_settings', array() );
			$shopee_shop_global_settings = isset( $global_settings[ $shopee_store_id ] ) ? $global_settings[ $shopee_store_id ] : array();
			$attributes                  = wc_get_attribute_taxonomies();

			$category_attributes = $shopee_category_instance->ced_shopee_get_category_attributes( $shopee_store_id, $shopee_category_id );

			$profile_data                                    = array();
			$profile_data['_umb_shopee_category']['default'] = $shopee_category_id;
			$profile_data['_umb_shopee_category']['metakey'] = null;

			$profile_data['_ced_shopee_weight']['default'] = isset( $shopee_shop_global_settings['ced_shopee_package_weight'] ) ? $shopee_shop_global_settings['ced_shopee_package_weight'] : '';
			$profile_data['_ced_shopee_weight']['metakey'] = null;

			$profile_data['_ced_shopee_markup_type']['default'] = isset( $shopee_shop_global_settings['ced_shopee_product_markup_type'] ) ? $shopee_shop_global_settings['ced_shopee_product_markup_type'] : '';
			$profile_data['_ced_shopee_markup_type']['metakey'] = null;

			$profile_data['_ced_shopee_markup_price']['default'] = isset( $shopee_shop_global_settings['ced_shopee_product_markup'] ) ? $shopee_shop_global_settings['ced_shopee_product_markup'] : '';
			$profile_data['_ced_shopee_markup_price']['metakey'] = null;

			$profile_data['_ced_shopee_conversion_rate']['default'] = isset( $shopee_shop_global_settings['ced_shopee_conversion_rate'] ) ? $shopee_shop_global_settings['ced_shopee_conversion_rate'] : '';
			$profile_data['_ced_shopee_conversion_rate']['metakey'] = null;

			$profile_data['_ced_shopee_package_length']['default'] = isset( $shopee_shop_global_settings['ced_shopee_package_length'] ) ? $shopee_shop_global_settings['ced_shopee_package_length'] : '';
			$profile_data['_ced_shopee_package_length']['metakey'] = null;

			$profile_data['_ced_shopee_package_width']['default'] = isset( $shopee_shop_global_settings['ced_shopee_package_width'] ) ? $shopee_shop_global_settings['ced_shopee_package_width'] : '';
			$profile_data['_ced_shopee_package_width']['metakey'] = null;

			$profile_data['_ced_shopee_package_height']['default'] = isset( $shopee_shop_global_settings['ced_shopee_package_height'] ) ? $shopee_shop_global_settings['ced_shopee_package_height'] : '';
			$profile_data['_ced_shopee_package_height']['metakey'] = null;

			$profile_data['_ced_shopee_product_type']['default'] = isset( $shopee_shop_global_settings['ced_shopee_product_type'] ) ? $shopee_shop_global_settings['ced_shopee_product_type'] : '';
			$profile_data['_ced_shopee_product_type']['metakey'] = null;

			if ( isset( $shopee_shop_global_settings['ced_shopee_product_type'] ) && 'Yes' == $shopee_shop_global_settings['ced_shopee_product_type'] ) {
				$profile_data['_ced_shopee_days_to_ship']['default'] = isset( $shopee_shop_global_settings['ced_shopee_days_to_ship'] ) ? $shopee_shop_global_settings['ced_shopee_days_to_ship'] : '';
				$profile_data['_ced_shopee_days_to_ship']['metakey'] = null;
			}

			$profile_data['_ced_shopee_logistics']['default'] = isset( $shopee_shop_global_settings['ced_shopee_logistics'] ) ? $shopee_shop_global_settings['ced_shopee_logistics'] : '';
			$profile_data['_ced_shopee_logistics']['metakey'] = null;

			$profile_data['_ced_shopee_condition']['default'] = isset( $shopee_shop_global_settings['ced_shopee_product_condition'] ) ? $shopee_shop_global_settings['ced_shopee_product_condition'] : '';
			$profile_data['_ced_shopee_condition']['metakey'] = null;

			if ( is_array( $category_attributes ) && ! empty( $category_attributes ) ) {
				if ( isset( $category_attributes['attributes'] ) ) {
					foreach ( $category_attributes['attributes'] as $key => $value ) {
						foreach ( $attributes as $key11 => $value11 ) {
							if ( strpos( strtolower( $value11->attribute_label ), strtolower( $value['attribute_name'] ) ) !== false || strpos( strtolower( $value['attribute_name'] ), strtolower( $value11->attribute_label ) ) !== false ) {
								$profile_data[ $shopee_category_id . '_' . $value['attribute_id'] ]['metakey'] = 'umb_pattr_' . $value11->attribute_name;
								$profile_data[ $shopee_category_id . '_' . $value['attribute_id'] ]['default'] = '';
							}
						}
					}
				}
			}
			return $profile_data;
		}

		/**
		 * Class_Ced_Shopee_Manager check_if_new_profile_need_to_be_created.
		 *
		 * @since 1.0.0
		 * @param int $woo_category_id Woocommerce Category Id.
		 * @param int $shopee_category_id Shopee category Id.
		 * @param int $shopee_store_id Shopee Shop Id.
		 */
		public function check_if_new_profile_need_to_be_created( $woo_category_id = '', $shopee_category_id = '', $shopee_store_id = '' ) {
			$old_shopee_category_mapped = get_term_meta( $woo_category_id, 'ced_shopee_mapped_category_' . $shopee_store_id, true );
			if ( $old_shopee_category_mapped == $shopee_category_id ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * Class_Ced_Shopee_Manager reset_mapped_category_data.
		 *
		 * @since 1.0.0
		 * @param int $woo_category_id Woocommerce Category Id.
		 * @param int $shopee_category_id Shopee category Id.
		 * @param int $shopee_store_id Shopee Shop Id.
		 */
		public function reset_mapped_category_data( $woo_category_id = '', $shopee_category_id = '', $shopee_store_id = '' ) {
			update_term_meta( $woo_category_id, 'ced_shopee_mapped_category_' . $shopee_store_id, $shopee_category_id );
			delete_term_meta( $woo_category_id, 'ced_shopee_profile_created_' . $shopee_store_id );
			$created_profile_id = get_term_meta( $woo_category_id, 'ced_shopee_profile_id_' . $shopee_store_id, true );
			delete_term_meta( $woo_category_id, 'ced_shopee_profile_id_' . $shopee_store_id );
			$this->remove_category_mapping_from_profile( $created_profile_id, $woo_category_id );
		}

		/**
		 * Class_Ced_Shopee_Manager remove_category_mapping_from_profile.
		 *
		 * @since 1.0.0
		 * @param int $created_profile_id Profile Id.
		 * @param int $woo_category_id Woocommerce Category Id.
		 */
		public function remove_category_mapping_from_profile( $created_profile_id = '', $woo_category_id = '' ) {
			global $wpdb;
			$profile_table_name = 'wp_ced_shopee_profiles';
			$profile_data       = $wpdb->get_results( $wpdb->prepare( "SELECT `woo_categories` FROM {$wpdb->prefix}ced_shopee_profiles WHERE `id`= %d ", $created_profile_id ), 'ARRAY_A' );
			if ( is_array( $profile_data ) ) {
				$profile_data   = isset( $profile_data[0] ) ? $profile_data[0] : $profile_data;
				$woo_categories = isset( $profile_data['woo_categories'] ) ? json_decode( $profile_data['woo_categories'], true ) : array();
				if ( is_array( $woo_categories ) && ! empty( $woo_categories ) ) {
					$categories = array();
					foreach ( $woo_categories as $key => $value ) {
						if ( $value != $woo_category_id ) {
							$categories[] = $value;
						}
					}
					$categories = json_encode( $categories );
					$wpdb->update( $profile_table_name, array( 'woo_categories' => $categories ), array( 'id' => $created_profile_id ) );
				}
			}
		}

		/**
		 * Class_Ced_Shopee_Manager ced_shopee_fetch_logistics.
		 *
		 * @since 1.0.0
		 * @param int $shop_id Shopee Shop Id.
		 */
		public function ced_shopee_fetch_logistics( $shop_id = '' ) {
			$file_logistics = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-shop.php';
			if ( file_exists( $file_logistics ) ) {
				include_once $file_logistics;
			}

			$shopee_shop_instance = Class_Ced_Shopee_Shop::get_instance();
			$shop_logistics       = $shopee_shop_instance->get_shopee_store_logistics( $shop_id );

			return $shop_logistics;
		}

		/**
		 * Class_Ced_Shopee_Manager load_dependency.
		 *
		 * @since 1.0.0
		 */
		public function load_dependency() {

			$file_config = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-config.php';
			if ( file_exists( $file_config ) ) {
				include_once $file_config;
			}
			$file_products = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-products.php';
			if ( file_exists( $file_products ) ) {
				include_once $file_products;
			}

			$this->ced_shopee_config_instance = Class_Ced_Shopee_Config::get_instance();
			$this->shopee_products_instance   = Class_Ced_Shopee_Products::get_instance();
			$this->partner_id                 = $this->ced_shopee_config_instance->partner_id;
			$this->secret_key                 = $this->ced_shopee_config_instance->secret_key;
		}

		/**
		 * Class_Ced_Shopee_Manager prepare_product_html_for_upload.
		 *
		 * @since 1.0.0
		 * @param array $pro_ids Product Ids.
		 * @param int   $shop_id Shopee Shop ID.
		 */
		public function prepare_product_html_for_upload( $pro_ids = array(), $shop_id ) {
			if ( ! is_array( $pro_ids ) ) {
				$pro_ids = array( $pro_ids );
			}
			$response = $this->shopee_products_instance->ced_shopee_prepare_data_for_uploading( $pro_ids, $shop_id );
			return $response;
		}

		/**
		 * Class_Ced_Shopee_Manager prepare_product_html_for_update.
		 *
		 * @since 1.0.0
		 * @param array $pro_ids Product Ids.
		 * @param int   $shop_id Shopee Shop ID.
		 */
		public function prepare_product_html_for_update( $pro_ids = array(), $shop_id ) {
			if ( ! is_array( $pro_ids ) ) {
				$pro_ids = array( $pro_ids );
			}
			$response = $this->shopee_products_instance->ced_shopee_prepare_data_for_updating( $pro_ids, $shop_id );
			return $response;
		}

		/**
		 * Class_Ced_Shopee_Manager prepare_product_html_for_update_stock.
		 *
		 * @since 1.0.0
		 * @param array $pro_ids Product Ids.
		 * @param int   $shop_id Shopee Shop ID.
		 * @param bool  $not_ajax Whether ajax or not.
		 */
		public function prepare_product_html_for_update_stock( $pro_ids = array(), $shop_id, $not_ajax = false ) {
			if ( ! is_array( $pro_ids ) ) {
				$pro_ids = array( $pro_ids );
			}
			$response = $this->shopee_products_instance->ced_shopee_prepare_data_for_updating_stock( $pro_ids, $shop_id, $not_ajax );
			return $response;
		}

		/**
		 * Class_Ced_Shopee_Manager prepare_product_html_for_delete.
		 *
		 * @since 1.0.0
		 * @param array $pro_ids Product Ids.
		 * @param int   $shop_id Shopee Shop ID.
		 */
		public function prepare_product_html_for_delete( $pro_ids = array(), $shop_id ) {
			if ( ! is_array( $pro_ids ) ) {
				$pro_ids = array( $pro_ids );
			}
			$response = $this->shopee_products_instance->ced_shopee_prepare_data_for_delete( $pro_ids, $shop_id );
			return $response;
		}

		/**
		 * Class_Ced_Shopee_Manager prepare_product_html_for_update_image.
		 *
		 * @since 1.0.0
		 * @param array $pro_ids Product Ids.
		 * @param int   $shop_id Shopee Shop ID.
		 */
		public function prepare_product_html_for_update_image( $pro_ids = array(), $shop_id ) {
			if ( ! is_array( $pro_ids ) ) {
				$pro_ids = array( $pro_ids );
			}
			$response = $this->shopee_products_instance->ced_shopee_prepare_data_for_updating_image( $pro_ids, $shop_id );
			return $response;
		}

		/**
		 * Class_Ced_Shopee_Manager prepare_product_html_for_update_price.
		 *
		 * @since 1.0.0
		 * @param array $pro_ids Product Ids.
		 * @param int   $shop_id Shopee Shop ID.
		 * @param bool  $not_ajax Whether ajax or not.
		 */
		public function prepare_product_html_for_update_price( $pro_ids = array(), $shop_id, $not_ajax = false ) {
			if ( ! is_array( $pro_ids ) ) {
				$pro_ids = array( $pro_ids );
			}
			$response = $this->shopee_products_instance->ced_shopee_prepare_data_for_updating_price( $pro_ids, $shop_id, $not_ajax );
			return $response;
		}

		/**
		 * Class_Ced_Shopee_Manager ced_shopee_store_categories.
		 *
		 * @since 1.0.0
		 * @param array $categories List of Shopee categories.
		 * @param int   $shop_id Shopee Shop Id.
		 */
		public function ced_shopee_store_categories( $categories = array(), $shop_id ) {
			$base_dir    = wp_upload_dir()['basedir'];
			$folder_name = $base_dir . '/cedcommerce/Shopee/Categories/';
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
				$category_first_level_file,
				json_encode( $level1_category ),
				FS_CHMOD_FILE
			);

			$complete_category_list_file = $folder_name . $get_filenames[ $get_location ]['all'];
			$wp_filesystem->put_contents(
				$complete_category_list_file,
				json_encode( $categories['categories'] ),
				FS_CHMOD_FILE
			);

			return $category_first_level_file;
		}
	}
}
