<?php
/**
 * Gettting order related data
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
 * Class_CedShopeeOrders
 *
 * @since 1.0.0
 * @param object $_instance Class instance.
 */
class Class_CedShopeeOrders {

	/**
	 * The instance variable of this class.
	 *
	 * @since    1.0.0
	 * @var      object    $_instance    The instance variable of this class.
	 */

	public static $_instance;

	/**
	 * Class_CedShopeeOrders Instance.
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
	 * Class_CedShopeeOrders construct.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->load_dependency();
	}

	/**
	 * Class_CedShopeeOrders loading dependency.
	 *
	 * @since 1.0.0
	 */
	public function load_dependency() {
		$file = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
		if ( file_exists( $file ) ) {
			include_once $file;
		}

		$this->shopee_send_http_request_instance = new Class_Ced_Shopee_Send_Http_Request();
	}

	/**
	 * Function for getting ordres from shopee
	 *
	 * @since 1.0.0
	 * @param int $shop_id Shopee Shop Id.
	 */
	public function ced_shopee_get_the_orders( $shop_id ) {
		$ced_shopee_store_id   = $shop_id;
		$ced_shopee_partner_id = '841326';
		$ced_shopee_secret_id  = '72fb4a83abed2478ed8be6aa17fa33d198f6b08869b46ee2644ec6f35046f840';

		$last_created_order         = get_option( 'ced_shopee_last_order_created_time', '' );
		$last_created_order         = date_i18n( 'F d, Y h:i', strtotime( $last_created_order ) );
		$current_time               = current_time( 'F-i-j h:i:s' );
		$parameters['order_status'] = 'READY_TO_SHIP';
		$parameters['partner_id']   = $ced_shopee_partner_id;
		$parameters['shopid']       = $ced_shopee_store_id;
		$parameters['timestamp']    = time();
		$action                     = 'orders/get';
		$actiondetails              = 'orders/detail';
		$result                     = $this->shopee_send_http_request_instance->send_http_request( $action, $parameters, $ced_shopee_store_id );

		$count             = 0;
		$orders_fetched[0] = $result['orders'];
		while ( 1 == $result['more'] ) {
			$count++;
			$offset                          = $count * 100;
			$parameters['pagination_offset'] = $offset;
			$result                          = $this->shopee_send_http_request_instance->send_http_request( $action, $parameters, $ced_shopee_store_id );
			array_push( $orders_fetched, $result['orders'] );
		}

		$ordersn = array();
		foreach ( $orders_fetched as $key => $result1 ) {
			foreach ( $result1 as $key1 => $value ) {
				array_push( $ordersn, $value['ordersn'] );
			}
		}

		$ordersn = array_chunk( $ordersn, 50 );

		foreach ( $ordersn as $key2 => $value2 ) {
			$details_parametes['ordersn_list'] = $value2;
			$details_parametes['partner_id']   = $ced_shopee_partner_id;
			$details_parametes['shopid']       = $ced_shopee_store_id;
			$details_parametes['timestamp']    = time();
			$orders                            = $this->shopee_send_http_request_instance->send_http_request( $actiondetails, $details_parametes, $ced_shopee_store_id );
			if ( isset( $orders['orders'] ) && ! empty( $orders['orders'] ) ) {
				$this->create_local_order( $orders['orders'], $shop_id );
				update_option( 'cedShopeeOrders_fetched', true );
			}
		}
	}

	/**
	 * Function for creating a local order
	 *
	 * @since 1.0.0
	 * @param array $orders Order Details.
	 * @param int   $shop_id Shopee Shop Id.
	 */
	public function create_local_order( $orders, $shop_id = '' ) {
		if ( is_array( $orders ) && ! empty( $orders ) ) {
			$order_items_info = array();
			foreach ( $orders as $order ) {
				$ship_to_first_name = isset( $order['recipient_address']['name'] ) ? $order['recipient_address']['name'] : '';

				$ship_to_address1      = isset( $order['recipient_address']['full_address'] ) ? $order['recipient_address']['full_address'] : '';
				$ship_to_address2      = isset( $order['second_line'] ) ? $order['second_line'] : '';
				$ship_to_city_name     = isset( $order['recipient_address']['city'] ) ? $order['recipient_address']['city'] : '';
				$customer_phone_number = isset( $order['recipient_address']['phone'] ) ? $order['recipient_address']['phone'] : '';
				$ship_to_state_code    = isset( $order['recipient_address']['state'] ) ? $order['recipient_address']['state'] : '';
				$ship_to_zip_code      = isset( $order['recipient_address']['zipcode'] ) ? $order['recipient_address']['zipcode'] : '';
				$shipping_address      = array(
					'first_name' => $ship_to_first_name,
					'address_1'  => $ship_to_address1,
					'address_2'  => $ship_to_address2,
					'city'       => $ship_to_city_name,
					'state'      => $ship_to_state_code,
					'postcode'   => $ship_to_zip_code,
				);

				$bill_to_first_name = $ship_to_first_name;
				$bill_email_address = isset( $order['buyer_email'] ) ? $order['buyer_email'] : '';
				$bill_phone_number  = $customer_phone_number;
				$currency           = isset( $order['currency'] ) ? $order['currency'] : '';
				$shipping           = ! empty( $order['actual_shipping_cost'] ) ? $order['actual_shipping_cost'] : 0;

				$billing_address = array(
					'first_name' => $bill_to_first_name,
					'address_1'  => $ship_to_address1,
					'address_2'  => $ship_to_address2,
					'city'       => $ship_to_city_name,
					'state'      => $ship_to_state_code,
					'email'      => $bill_email_address,
					'phone'      => $bill_phone_number,
				);
				$address         = array(
					'shipping' => $shipping_address,
					'billing'  => $billing_address,
				);

				$order_number             = $order['ordersn'];
				$order_status             = $order['order_status'];
				$transactions_per_reciept = $order['items'];
				$item_array               = array();
				foreach ( $transactions_per_reciept as $transaction ) {
					$id = false;
					if ( isset( $transaction['variation_id'] ) && 0 != $transaction['variation_id'] ) {
						$listing_id = $transaction['variation_id'];
					} else {
						$listing_id = $transaction['item_id'];
					}
					$ordered_qty = isset( $transaction['variation_quantity_purchased'] ) ? $transaction['variation_quantity_purchased'] : 1;
					$base_price  = isset( $transaction['variation_discounted_price'] ) ? $transaction['variation_discounted_price'] : '';
					if ( empty( (int) $base_price ) ) {
						$base_price = isset( $transaction['variation_original_price'] ) ? $transaction['variation_original_price'] : '';
					}
					$sku = isset( $transaction['variation_sku'] ) ? $transaction['variation_sku'] : '';
					if ( empty( $sku ) ) {
						$sku = isset( $transaction['item_sku'] ) ? $transaction['item_sku'] : '';
					}
					$cancel_qty = 0;
					if ( $listing_id ) {
						$local_product = get_posts(
							array(
								'numberposts'  => -1,
								'post_type'    => array( 'product', 'product_variation' ),
								'meta_key'     => '_ced_shopee_listing_id_' . $shop_id,
								'meta_value'   => isset( $listing_id ) ? $listing_id : '',
								'meta_compare' => '=',
							)
						);

						$local_item_id = wp_list_pluck( $local_product, 'ID' );
						if ( ! empty( $local_item_id ) && isset( $local_item_id[0] ) ) {
							$id = $local_item_id[0];
						}
					}
					$item         = array(
						'OrderedQty' => $ordered_qty,
						'CancelQty'  => $cancel_qty,
						'UnitPrice'  => $base_price,
						'ID'         => $id,
						'Sku'        => $sku,
					);
					$item_array[] = $item;
				}

				$final_tax        = 1;
				$order_items_info = array(
					'OrderNumber'    => $order_number,
					'OrderStatus'    => $order_status,
					'ItemsArray'     => $item_array,
					'tax'            => $final_tax,
					'ShippingAmount' => $shipping,
				);
				$order_items      = $transactions_per_reciept;

				$merchant_order_id = $order_number;
				$purchase_order_id = $order_number;
				$fulfillment_node  = '';
				$order_detail      = isset( $order ) ? $order : array();
				$shopee_order_meta = array(
					'merchant_order_id' => $merchant_order_id,
					'purchaseOrderId'   => $purchase_order_id,
					'fulfillment_node'  => $fulfillment_node,
					'order_detail'      => $order_detail,
					'curr'              => $currency,
					'order_items'       => $order_items,
				);

				$creation_date = $order['create_time'];

				$order_id = $this->create_order( $address, $order_items_info, 'shopee', $shopee_order_meta, $creation_date, $shop_id );
			}
		}
	}

	/**
	 * Function for creating order in woocommerce
	 *
	 * @since 1.0.0
	 * @param array  $address Shipping and billing address.
	 * @param array  $order_items_info Order items details.
	 * @param string $framework_name Framework name.
	 * @param array  $order_meta Order meta details.
	 * @param string $creation_date Order creation date.
	 * @param int    $shop_id Shopee Shop Id.
	 */
	public function create_order( $address = array(), $order_items_info = array(), $framework_name = 'shopee', $order_meta = array(), $creation_date = '', $shop_id ) {
		$order_id      = '';
		$order_created = false;
		if ( count( $order_items_info ) ) {
			$order_number = isset( $order_items_info['OrderNumber'] ) ? $order_items_info['OrderNumber'] : 0;
			$order_id     = $this->is_shopee_order_exists( $order_number );

			if ( $order_id ) {

				if ( isset( $order_items_info['OrderStatus'] ) && ! empty( $order_items_info['OrderStatus'] ) ) {
					if ( 'COMPLETED' == $order_items_info['OrderStatus'] ) {
						$_order = wc_get_order( $order_id );
						$_order->update_status( 'wc-completed' );
						update_post_meta( $order_id, '_shopee_umb_order_status', 'Completed' );
					} elseif ( 'CANCELLED' == $order_items_info['OrderStatus'] ) {
						$_order = wc_get_order( $order_id );
						$_order->update_status( 'wc-cancelled' );
						update_post_meta( $order_id, '_shopee_umb_order_status', 'Cancelled' );
					}
				}
				if ( isset( $order_items_info['tracking_no'] ) && ! empty( $order_items_info['tracking_no'] ) ) {
					update_post_meta( $order_id, 'ced_shopee_tracking_no', $order_items_info['tracking_no'] );
				}

				return 'exist';
			}

			if ( count( $order_items_info ) ) {
				$items_array = isset( $order_items_info['ItemsArray'] ) ? $order_items_info['ItemsArray'] : array();
				if ( is_array( $items_array ) ) {
					foreach ( $items_array as $item_info ) {
						$pro_id          = isset( $item_info['ID'] ) ? intval( $item_info['ID'] ) : 0;
						$sku             = isset( $item_info['Sku'] ) ? $item_info['Sku'] : '';
						$mfr_part_number = isset( $item_info['MfrPartNumber'] ) ? $item_info['MfrPartNumber'] : '';
						$upc             = isset( $item_info['UPCCode'] ) ? $item_info['UPCCode'] : '';
						$asin            = isset( $item_info['ASIN'] ) ? $item_info['ASIN'] : '';
						$params          = array( '_sku' => $sku );

						if ( ! $pro_id && ! empty( $sku ) ) {
							$pro_id = wc_get_product_id_by_sku( $sku );
						}
						if ( ! $pro_id ) {
							$pro_id = $sku;
						}
						$qty                    = isset( $item_info['OrderedQty'] ) ? intval( $item_info['OrderedQty'] ) : 0;
						$unit_price             = isset( $item_info['UnitPrice'] ) ? floatval( $item_info['UnitPrice'] ) : 0;
						$extend_unit_price      = isset( $item_info['ExtendUnitPrice'] ) ? floatval( $item_info['ExtendUnitPrice'] ) : 0;
						$extend_shipping_charge = isset( $item_info['ExtendShippingCharge'] ) ? floatval( $item_info['ExtendShippingCharge'] ) : 0;
						$_product               = wc_get_product( $pro_id );

						if ( is_wp_error( $_product ) ) {
							continue;
						} elseif ( is_null( $_product ) ) {
							continue;
						} elseif ( ! $_product ) {
							continue;
						} else {
							if ( ! $order_created ) {
								$order_data = array(
									'status'        => apply_filters( 'woocommerce_default_order_status', 'pending' ),
									'customer_note' => __( 'Order from ', 'woocommerce-shopee-integration' ) . $framework_name . '[' . $shop_id . ']',
									'created_via'   => $framework_name,
								);

								$order = wc_create_order( $order_data );

								if ( is_wp_error( $order ) ) {
									continue;
								} elseif ( false === $order ) {
									continue;
								} else {
									if ( WC()->version < '3.0.0' ) {
										$order_id = $order->id;
									} else {
										$order_id = $order->get_id();
									}
									$order_created = true;
								}
							}
							$_product->set_price( $unit_price );
							$order->add_product( $_product, $qty );
							$order->set_currency( $order_meta['curr'] );
							$order->calculate_totals();
						}
					}
				}

				if ( ! $order_created ) {
					return false;
				}

				$order_item_amount = isset( $order_items_info['OrderItemAmount'] ) ? $order_items_info['OrderItemAmount'] : 0;
				$shipping_amount   = isset( $order_items_info['ShippingAmount'] ) ? $order_items_info['ShippingAmount'] : 0;
				$discount_amount   = isset( $order_items_info['DiscountAmount'] ) ? $order_items_info['DiscountAmount'] : 0;
				$refund_amount     = isset( $order_items_info['RefundAmount'] ) ? $order_items_info['RefundAmount'] : 0;
				$ship_service      = isset( $order_items_info['ShipService'] ) ? $order_items_info['ShipService'] : 'Shipping';

				if ( ! empty( $shipping_amount ) ) {
					$ship_params = array(
						'ShippingCost' => $shipping_amount,
						'ShipService'  => $ship_service,
					);
					$this->add_shipping_charge( $order, $ship_params );
				}

				$shipping_address = isset( $address['shipping'] ) ? $address['shipping'] : '';
				if ( is_array( $shipping_address ) && ! empty( $shipping_address ) ) {
					if ( WC()->version < '3.0.0' ) {
						$order->set_address( $shipping_address, 'shipping' );
					} else {
						$type = 'shipping';
						foreach ( $shipping_address as $key => $value ) {
							if ( ! empty( $value ) ) {
								update_post_meta( $order->get_id(), "_{$type}_" . $key, $value );
								if ( is_callable( array( $order, "set_{$type}_{$key}" ) ) ) {
									$order->{"set_{$type}_{$key}"}( $value );
								}
							}
						}
					}
				}

				$new_fee            = new stdClass();
				$new_fee->name      = 'Tax';
				$new_fee->amount    = (float) ( $order_items_info['tax'] );
				$new_fee->tax_class = '';
				$new_fee->taxable   = 0;
				$new_fee->tax       = '';
				$new_fee->tax_data  = array();
				if ( WC()->version < '3.0.0' ) {
					$item_id = $order->add_fee( $new_fee );
				} else {
					$item_id = $order->add_item( $new_fee );
				}

				$billing_address = isset( $address['billing'] ) ? $address['billing'] : '';
				if ( is_array( $billing_address ) && ! empty( $billing_address ) ) {
					if ( WC()->version < '3.0.0' ) {
						$order->set_address( $shipping_address, 'billing' );
					} else {
						$type = 'billing';
						foreach ( $billing_address as $key => $value ) {
							if ( ! empty( $value ) ) {
								update_post_meta( $order->get_id(), "_{$type}_" . $key, $value );
								if ( is_callable( array( $order, "set_{$type}_{$key}" ) ) ) {
									$order->{"set_{$type}_{$key}"}( $value );
								}
							}
						}
					}
				}
				wc_reduce_stock_levels( $order->get_id() );
				$order->set_payment_method( 'check' );

				if ( WC()->version < '3.0.0' ) {
					$order->set_total( $discount_amount, 'cart_discount' );
				} else {
					$order->set_total( $discount_amount );
				}

				$order->calculate_totals();

				update_post_meta( $order_id, '_ced_shopee_order_id', $order_number );
				update_post_meta( $order_id, '_is_ced_shopee_order', 1 );
				update_post_meta( $order_id, '_shopee_umb_order_status', 'Fetched' );
				update_post_meta( $order_id, '_umb_shopee_marketplace', $framework_name );
				update_post_meta( $order_id, 'ced_shopee_order_shop_id', $shop_id );
				update_option( 'ced_shopee_last_order_created_time', $creation_date );
				$order->update_status( 'processing' );
				$order->set_currency( $order_items_info['curr'] );
				if ( count( $order_meta ) ) {
					foreach ( $order_meta as $order_key => $order_value ) {
						update_post_meta( $order_id, $order_key, $order_value );
					}
				}
			}

			return $order_id;
		}
		return false;
	}

	/**
	 * Get conditional product id.
	 *
	 * @since 1.0.0
	 * @param array $params Parameters to find product in woocommerce.
	 */
	public function umb_get_product_by( $params ) {
		global $wpdb;

		$where = '';
		if ( count( $params ) ) {
			$flag = false;
			foreach ( $params as $meta_key => $meta_value ) {
				if ( ! empty( $meta_value ) && ! empty( $meta_key ) ) {
					if ( ! $flag ) {
						$where .= 'meta_key="' . sanitize_key( $meta_key ) . '" AND meta_value="' . $meta_value . '"';
						$flag   = true;
					} else {
						$where .= ' OR meta_key="' . sanitize_key( $meta_key ) . '" AND meta_value="' . $meta_value . '"';
					}
				}
			}
			if ( $flag ) {
				$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE %s LIMIT 1", $where ) );
				if ( $product_id ) {
					return $product_id;
				}
			}
		}
		return false;
	}

	/**
	 * Function to check  if order already exists
	 *
	 * @since 1.0.0
	 * @param int $order_number Shopee Order Id.
	 */
	public function is_shopee_order_exists( $order_number = 0 ) {
		global $wpdb;
		if ( $order_number ) {
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_ced_shopee_order_id' AND meta_value=%s LIMIT 1", $order_number ) );
			if ( $order_id ) {
				return $order_id;
			}
		}
		return false;
	}

	/**
	 * Function to add shipping data
	 *
	 * @since 1.0.0
	 * @param object $order Order details.
	 * @param array  $ship_params Shipping details.
	 */
	public static function add_shipping_charge( $order, $ship_params = array() ) {
		$ship_name = isset( $ship_params['ShipService'] ) ? ( $ship_params['ShipService'] ) : 'Shipping';
		$ship_cost = isset( $ship_params['ShippingCost'] ) ? $ship_params['ShippingCost'] : 0;
		$ship_tax  = isset( $ship_params['ShippingTax'] ) ? $ship_params['ShippingTax'] : 0;

		$item = new WC_Order_Item_Shipping();

		$item->set_method_title( $ship_name );
		$item->set_method_id( $ship_name );
		$item->set_total( $ship_cost );
		$order->add_item( $item );

		$order->calculate_totals();
		$order->save();
	}
}
