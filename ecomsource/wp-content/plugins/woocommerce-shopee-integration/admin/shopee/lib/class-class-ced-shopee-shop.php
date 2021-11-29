<?php
/**
 * Shop related operations
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'Class_Ced_Shopee_Shop' ) ) {

	/**
	 * Class_Ced_Shopee_Shop .
	 *
	 * @since 1.0.0
	 */
	class Class_Ced_Shopee_Shop {

		/**
		 * The instance variable of this class.
		 *
		 * @since    1.0.0
		 * @var      object    $_instance    The instance variable of this class.
		 */
		public static $_instance;

		/**
		 * Class_Ced_Shopee_Shop Instance.
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
		 * Class_Ced_Shopee_Shop construct.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->load_dependency();
		}

		/**
		 * Class_Ced_Shopee_Shop load_dependency
		 *
		 * @since 1.0.0
		 */
		public function load_dependency() {
			$file = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}

			$this->shopee_send_http_request_instance = new Class_Ced_Shopee_Send_Http_Request();
		}

		/**
		 * Class_Ced_Shopee_Shop get_shopee_store_data.
		 *
		 * @since 1.0.0
		 * @param int $shop_id Shopee Shop Id.
		 */
		public function get_shopee_store_data( $shop_id = '' ) {
			$action       = 'shop/get';
			$shop_details = $this->shopee_send_http_request_instance->send_http_request( $action, array(), $shop_id );

			if ( is_array( $shop_details ) && ! empty( $shop_details ) ) {
				if ( isset( $shop_details['shop_id'] ) ) {
					return array(
						'status'   => '200',
						'response' => $shop_details,
					);
				} else {
					return array(
						'status'   => '401',
						'response' => $shop_details,
					);
				}
			}
		}

		/**
		 * Class_Ced_Shopee_Shop get_shopee_store_logistics.
		 *
		 * @since 1.0.0
		 * @param int $shop_id Shopee Shop Id.
		 */
		public function get_shopee_store_logistics( $shop_id = '' ) {
			$action    = 'logistics/channel/get';
			$logistics = $this->shopee_send_http_request_instance->send_http_request( $action, array(), $shop_id );

			if ( is_array( $logistics ) && ! empty( $logistics ) ) {
				return $logistics;
			}
		}
	}
}
