<?php
/**
 * Curl requests
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class_Ced_Shopee_Send_Http_Request
 *
 * @since 1.0.0
 */
class Class_Ced_Shopee_Send_Http_Request {

			/**
			 * Shopee Shop Id.
			 *
			 * @since    1.0.0
			 * @var      int    $shop_id   Shopee Shop Id.
			 */
	public $shop_id;
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
	 * Class_Ced_Shopee_Send_Http_Request construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->load_depenedency();
		$this->end_point_url = $this->ced_shopee_config_instance->end_point_url;
		$this->partner_id    = $this->ced_shopee_config_instance->partner_id;
		$this->secret_key    = $this->ced_shopee_config_instance->secret_key;
	}

	/**
	 * Function for sending curl request
	 *
	 * @since 1.0.0
	 * @param string $action Action to be performed.
	 * @param array  $parameters Parameters required on shopee.
	 * @param int    $store Shopee Shop Id.
	 */
	public function send_http_request( $action = '', $parameters = array(), $store = '' ) {
		$api_url = $this->end_point_url . $action;
		if ( ! is_array( $parameters ) ) {
			$parameters = $this->add_basic_parameter( $parameters, $store );
			$header     = $this->prepare_header( $api_url, $parameters );
		} else {
			$parameters = $this->add_basic_parameter( $parameters, $store );
			$header     = $this->prepare_header( $api_url, $parameters );
		}
		if ( ! empty( $store ) ) {
			$parameters['partner_id'] = (int) $this->partner_id;
			$parameters['shopid']     = (int) $store;
			$parameters['timestamp']  = time();
			$header                   = $this->prepare_header( $api_url, $parameters );
		}
		$connection = curl_init();
		curl_setopt( $connection, CURLOPT_URL, $api_url );
		curl_setopt( $connection, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $connection, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $connection, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $connection, CURLOPT_POST, 1 );
		curl_setopt( $connection, CURLOPT_POSTFIELDS, json_encode( $parameters ) );
		curl_setopt( $connection, CURLOPT_RETURNTRANSFER, 1 );

		$response = curl_exec( $connection );
		curl_close( $connection );

		$response = $this->parse_response( $response );
		return $response;
	}

	/**
	 * Function for sending curl request
	 *
	 * @since 1.0.0
	 * @param string $action Action to be performed.
	 * @param int    $offset Offset required.
	 * @param int    $itemid Shopee Item id.
	 * @param int    $shop_id Shopee Shop Id.
	 */
	public function send_http_request_to_import( $action = '', $offset = '', $itemid = '', $shop_id ) {
		$parameters = $this->add_basic_parameter( array(), $shop_id );
		if ( empty( $itemid ) ) {
			if ( empty( $offset ) ) {
				$parameters['pagination_offset'] = 0;
			} else {
				$parameters['pagination_offset'] = $offset;
			}
			$parameters['pagination_entries_per_page'] = 100;
		} else {
			$parameters['item_id'] = $itemid;
		}
		$api_url    = $this->end_point_url . $action;
		$header     = $this->prepare_header( $api_url, $parameters );
		$connection = curl_init();
		curl_setopt( $connection, CURLOPT_URL, $api_url );
		curl_setopt( $connection, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $connection, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $connection, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $connection, CURLOPT_POST, 1 );
		curl_setopt( $connection, CURLOPT_POSTFIELDS, json_encode( $parameters ) );
		curl_setopt( $connection, CURLOPT_RETURNTRANSFER, 1 );

		$response = curl_exec( $connection );
		curl_close( $connection );
		$response = $this->parse_response( $response );
		return $response;
	}

	/**
	 * Function for add_basic_parameter
	 *
	 * @since 1.0.0
	 * @param array $parameters Parameters required by shopee.
	 * @param int   $shop_id Shopee Shop Id.
	 */
	public function add_basic_parameter( $parameters = array(), $shop_id = '' ) {
		$parameters['partner_id'] = (int) $this->partner_id;
		$parameters['shopid']     = (int) $shop_id;
		$parameters['timestamp']  = time();
		return $parameters;
	}

	/**
	 * Function for prepare_post_data
	 *
	 * @since 1.0.0
	 * @param string $action Action to be performed.
	 * @param array  $order_data Order Details.
	 */
	public function prepare_post_data( $action = '', $order_data ) {
		if ( empty( $action ) ) {
			return array( 'error' => 'Missing_Action' );
		}

		if ( 'fetchOrder' == $action ) {
			$url       = $this->end_point_url . $this->merchant_id . '/orders/new/';
			$post_data = array( 'url' => $url );
		} elseif ( 'acknowledgeOrder' == $action ) {
			$url          = $this->end_point_url . $this->merchant_id . '/orders/' . $order_data['siroopOrderId'] . '/received/';
			$request_body = array( 'merchantOrderRef' => $order_data['merchant_order_reference'] );
			$method_post  = 1;
			$post_data    = array(
				'url'         => $url,
				'methodPost'  => $method_post,
				'requestBody' => $request_body,
			);
		} elseif ( 'acceptOrderItem' == $action ) {
			$url          = $this->end_point_url . $this->merchant_id . '/orders/' . $order_data['siroopOrderId'] . '/items/' . $order_data['itemId'] . '/accept/';
			$method_post  = 1;
			$request_body = array();
			$post_data    = array(
				'url'         => $url,
				'methodPost'  => $method_post,
				'requestBody' => $request_body,
			);
		} elseif ( 'declineOrderItem' == $action ) {
			$url         = $this->end_point_url . $this->merchant_id . '/orders/' . $order_data['siroopOrderId'] . '/items/' . $order_data['itemId'] . '/decline/';
			$method_post = 1;
			if ( ! isset( $order_data['cancelReasonText'] ) || empty( $order_data['cancelReasonText'] ) ) {
				$cancel_reason_text = 'The product is not available';
			} else {
				$cancel_reason_text = $order_data['cancelReasonText'];
			}

			$request_body = array(
				'reason'  => $order_data['cancelReason'],
				'message' => $cancel_reason_text,
			);
			$post_data    = array(
				'url'         => $url,
				'methodPost'  => $method_post,
				'requestBody' => $request_body,
			);
		} elseif ( 'cancelOrderItem' == $action ) {
			$url         = $this->end_point_url . $this->merchant_id . '/orders/' . $order_data['siroopOrderId'] . '/items/' . $order_data['itemId'] . '/cancel/';
			$method_post = 1;
			if ( ! isset( $order_data['cancelReasonText'] ) || empty( $order_data['cancelReasonText'] ) ) {
				$cancel_reason_text = 'The product is not available';
			} else {
				$cancel_reason_text = $order_data['cancelReasonText'];
			}

			$request_body = array(
				'reason'  => $order_data['cancelReason'],
				'message' => $cancel_reason_text,
			);
			$post_data    = array(
				'url'         => $url,
				'methodPost'  => $method_post,
				'requestBody' => $request_body,
			);
		} elseif ( 'shipOrderItem' == $action ) {
			$url                         = $this->end_point_url . $this->merchant_id . '/orders/' . $order_data['siroopOrderId'] . '/items/' . $order_data['itemId'] . '/shipped/';
			$method_post                 = 1;
			$request_body['trackings'][] = array(
				'carrier' => $order_data['carrier'],
				'code'    => $order_data['trackingCode'],
			);
			$post_data                   = array(
				'url'         => $url,
				'methodPost'  => $method_post,
				'requestBody' => $request_body,
			);
		} elseif ( 'delivery_note' == $action ) {
			$url          = $this->end_point_url . $this->merchant_id . '/deliverynote/';
			$method_post  = 1;
			$request_body = array( 'orderId' => $order_data['siroopOrderId'] );
			$post_data    = array(
				'url'         => $url,
				'methodPost'  => $method_post,
				'requestBody' => $request_body,
			);
		}

		return $post_data;
	}

	/**
	 * Function for prepare_header
	 *
	 * @since 1.0.0
	 * @param string $api_url The url for performing several actions.
	 * @param array  $parmaeters Parameters required by shopee.
	 */
	public function prepare_header( $api_url = '', $parmaeters = array() ) {
		$authorisation = $api_url . '|' . json_encode( $parmaeters );
		$authorisation = rawurlencode( hash_hmac( 'sha256', $authorisation, $this->secret_key, false ) );

		$header = array(
			'Content-Type: application/json',
			'Authorization: ' . $authorisation,
		);
		return $header;
	}

	/**
	 * Function for parse_response
	 *
	 * @since 1.0.0
	 * @param string $response Response from shopee.
	 */
	public function parse_response( $response ) {
		if ( ! empty( $response ) ) {
			return json_decode( $response, true );
		}
	}

	/**
	 * Function load_depenedency
	 *
	 * @since 1.0.0
	 */
	public function load_depenedency() {
		$http_request_file = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-config.php';
		if ( file_exists( $http_request_file ) ) {
			include_once $http_request_file;
			$this->ced_shopee_config_instance = Class_Ced_Shopee_Config::get_instance();
		}
	}
}
