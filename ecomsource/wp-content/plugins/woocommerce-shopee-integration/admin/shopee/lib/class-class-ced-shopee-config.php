<?php
/**
 * Gettting mandatory data
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
	 * Class_Ced_Shopee_Config
	 *
	 * @since 1.0.0
	 */
class Class_Ced_Shopee_Config {

	/**
	 * The instance variable of this class.
	 *
	 * @since    1.0.0
	 * @var      object    $_instance    The instance variable of this class.
	 */
	public static $_instance;

	/**
	 * The endpoint variable.
	 *
	 * @since    1.0.0
	 * @var      string    $end_point_url    The endpoint variable.
	 */
	public $end_point_url;
	/**
	 * The partner id variable.
	 *
	 * @since    1.0.0
	 * @var      string    $partner_id   The partner id variable.
	 */
	public $partner_id;

	/**
	 * The secret key variable
	 *
	 * @since    1.0.0
	 * @var      string    $secret_key    The secret key variable.
	 */
	public $secret_key;



	/**
	 * Class_Ced_Shopee_Config Instance.
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
	 * Class_Ced_Shopee_Config constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->partner_id    = '841326';
		$this->secret_key    = '72fb4a83abed2478ed8be6aa17fa33d198f6b08869b46ee2644ec6f35046f840';
		$this->end_point_url = 'https://partner.shopeemobile.com/api/v1/';
	}
}

