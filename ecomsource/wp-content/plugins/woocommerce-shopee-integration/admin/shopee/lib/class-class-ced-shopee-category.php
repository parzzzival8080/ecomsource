<?php
/**
 * Gettting category related data
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Class_Ced_Shopee_Category' ) ) {

	/**
	 * Class_Ced_Shopee_Category .
	 *
	 * @since 1.0.0
	 */
	class Class_Ced_Shopee_Category {

		/**
		 * The instance variable of this class.
		 *
		 * @since    1.0.0
		 * @var      object    $_instance    The instance variable of this class.
		 */
		public static $_instance;

		/**
		 * Class_Ced_Shopee_Category Instance.
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
		 * Class_Ced_Shopee_Category construct.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->load_dependency();
		}


		/**
		 * Class_Ced_Shopee_Category loading dependency.
		 *
		 * @since 1.0.0
		 */
		public function load_dependency() {
			require_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
			$this->shopee_send_http_request_instance = new Class_Ced_Shopee_Send_Http_Request();
		}

		/**
		 * Function for getting category specific attributes
		 *
		 * @since 1.0.0
		 * @param int $shop_id Shopee Shop Id.
		 * @param int $shopee_category_id Shopee Category Id.
		 */
		public function ced_shopee_get_category_attributes( $shop_id = '', $shopee_category_id = '' ) {

			if ( empty( $shopee_category_id ) ) {
				return;
			}

			$action                    = 'item/attributes/get';
			$parameters['category_id'] = (int) $shopee_category_id;

			$category_attributes = $this->shopee_send_http_request_instance->send_http_request( $action, $parameters, $shop_id );
			return $category_attributes;
		}

		/**
		 * Function for getting shopee categories
		 *
		 * @since 1.0.0
		 * @param int $shop_id Shopee Shop Id.
		 */
		public function get_shopee_category( $shop_id = '' ) {
			if ( empty( $shop_id ) ) {
				return;
			}

			$action     = 'item/categories/get';
			$categories = $this->shopee_send_http_request_instance->send_http_request( $action, array(), $shop_id );
			return $categories;
		}

		/**
		 * Function for getting updated shopee categories
		 *
		 * @since 1.0.0
		 * @param int $shop_id Shopee Shop Id.
		 */
		public function get_refreshed_shopee_category( $shop_id = '' ) {
			if ( empty( $shop_id ) ) {
				return;
			}

			$action     = 'item/categories/get';
			$categories = $this->shopee_send_http_request_instance->send_http_request( $action, array(), $shop_id );
			return $categories;
		}
	}
}
