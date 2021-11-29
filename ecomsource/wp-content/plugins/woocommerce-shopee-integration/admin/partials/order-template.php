<?php
/**
 * Shipment Order Template
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
global $post;

$order_id                = isset( $post->ID ) ? intval( $post->ID ) : '';
$umb_shopee_order_status = get_post_meta( $order_id, '_shopee_umb_order_status', true );

$merchant_order_id = get_post_meta( $order_id, 'merchant_order_id', true );
$purchase_order_id = get_post_meta( $order_id, 'purchaseOrderId', true );
$fulfillment_node  = get_post_meta( $order_id, 'fulfillment_node', true );
$order_detail      = get_post_meta( $order_id, 'order_detail', true );
$order_item        = get_post_meta( $order_id, 'order_items', true );

if ( isset( $order_item[0] ) ) {
	$order_items = $order_item;
} else {
	$order_items[0] = $order_item['orderLine'];
}

$number_items            = 0;
$umb_shopee_order_status = get_post_meta( $order_id, '_shopee_umb_order_status', true );
if ( empty( $umb_shopee_order_status ) || 'Fetched' == $umb_shopee_order_status ) {
	$umb_shopee_order_status = 'Created';
}

?>

<div id="umb_shopee_order_settings" class="panel woocommerce_options_panel">
	<div class="ced_shopee_loader" class="loading-style-bg" style="display: none;">
		<img src="<?php echo esc_url( CED_SHOPEE_URL . 'admin/images/loading.gif' ); ?>">
	</div>

	<div class="options_group">
		<p class="form-field">
			<h3><center>
			<?php
			esc_html_e( 'shopee ORDER STATUS : ', 'woocommerce-shopee-integration' );
			echo esc_attr( strtoupper( $umb_shopee_order_status ) );
			?>
			</center></h3>
		</p>
	</div>
	<div class="options_group umb_shopee_options"> 
		<?php
		if ( 'Cancelled' == $umb_shopee_order_status ) {
			?>
			<h1 style="text-align:center;"><?php esc_html_e( 'ORDER CANCELLED ', 'woocommerce-shopee-integration' ); ?></h1>
			<?php
		}
		if ( 'Created' == $umb_shopee_order_status ) {
			?>
			<p class="form-field">
				<label><?php esc_html_e( 'Select Order Action:', 'woocommerce-shopee-integration' ); ?></label>
				<input type="button" class="button primary " value="Ship Order" data-order_id = "<?php echo esc_attr( $order_id ); ?>" id="umb_shopee_ack_action"/>
			</p>
			<?php
		} elseif ( 'Acknowledged' == $umb_shopee_order_status ) {
			?>
			<input type="hidden" id="shopee_orderid" value="<?php echo esc_attr( $purchase_order_id ); ?>" readonly>
			<input type="hidden" id="woocommerce_orderid" value="<?php echo esc_attr( $order_id ); ?>">
			<h2 class="title"><?php esc_html_e( 'Shipment Information', 'woocommerce-shopee-integration' ); ?>:                   
			</h2>  			
			<div id="ced_shopee_complete_order_shipping">
				<table class="wp-list-table widefat fixed striped">
					<tbody>
						<tr>
							<td><b><?php esc_html_e( 'Reference Order Id on shopee', 'woocommerce-shopee-integration' ); ?></b></td>
							<td><?php echo esc_attr( $merchant_order_id ); ?></td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Tracking Number', 'woocommerce-shopee-integration' ); ?></b></td>
							<td><input type="text" id="umb_shopee_tracking_number" value=""></td>
						</tr>
					</tbody>
				</table>	
			</div>
			<input data-order_id ="<?php echo esc_attr( $order_id ); ?>" type="button" class="button" id="ced_shopee_shipment_submit" value="Submit Shipment">
			<?php
		} elseif ( 'Shipped' == $umb_shopee_order_status ) {
			$order_detais  = get_post_meta( $order_id, '_umb_order_details', true );
			$trackingno    = '';
			$delivery_date = '';
			if ( is_array( $order_detais ) && ! empty( $order_detais ) ) {
				$trackingno    = $order_detais['trackingNo'];
				$delivery_date = $order_detais['deliveryDate'];
			}
			?>
			<input type="hidden" id="shopee_orderid" value="<?php echo esc_attr( $purchase_order_id ); ?>" readonly>
			<input type="hidden" id="woocommerce_orderid" value="<?php echo esc_attr( $order_id ); ?>">
			<h2 class="title"><?php esc_html_e( 'Shipment Information', 'woocommerce-shopee-integration' ); ?>:                   
			</h2>  			
			<div id="ced_shopee_complete_order_shipping">
				<table class="wp-list-table widefat fixed striped">
					<tbody>
						<tr>
							<td><b><?php esc_html_e( 'Reference Order Id on shopee.com', 'woocommerce-shopee-integration' ); ?></b></td>
							<td><?php echo esc_attr( $merchant_order_id ); ?></td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Order Placed on shopee.com', 'woocommerce-shopee-integration' ); ?></b></td>
							<td><?php echo esc_attr( gmdate( 'l, F jS Y \a\t g:ia', strtotime( $order_detail['CreatedTime'] ) ) ); ?></td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Shipping carrier used', 'woocommerce-shopee-integration' ); ?></b></td>
							<td>
								<?php echo esc_attr( $order_detail['ShippingDetails']['ShippingServiceOptions']['ShippingService'] ); ?>
							</td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Shipping Service Cost', 'woocommerce-shopee-integration' ); ?></b></td>
							<td>
								<?php echo esc_attr( $order_detail['ShippingDetails']['ShippingServiceOptions']['ShippingServiceCost'] ); ?>
							</td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Shipping Time Min', 'woocommerce-shopee-integration' ); ?></b></td>
							<td>
								<?php echo esc_attr( $order_detail['ShippingDetails']['ShippingServiceOptions']['ShippingTimeMin'] ); ?>
							</td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Shipping Time Max', 'woocommerce-shopee-integration' ); ?></b></td>
							<td>
								<?php echo esc_attr( $order_detail['ShippingDetails']['ShippingServiceOptions']['ShippingTimeMax'] ); ?>
							</td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Tracking Number', 'woocommerce-shopee-integration' ); ?></b></td>
							<?php if ( ! empty( $trackingno ) ) { ?>
								<td><?php echo esc_attr( $trackingno0 ); ?></td>
							<?php } ?>
							<td><input type="text" id="umb_shopee_tracking_number" value=""></td>
						</tr>
						<tr>
							<td><b><?php esc_html_e( 'Ship To Date', 'woocommerce-shopee-integration' ); ?></b></td>
							<?php if ( ! empty( $delivery_date ) ) { ?>
								<td><?php echo esc_attr( $delivery_date ); ?></td>
							<?php } ?>
							<td><input class=" input-text required-entry"  type="text" id="umb_shopee_ship_date_order" name="ship_date" /></td>
						</tr>
					</tbody>
				</table>	
			</div>
			<?php
		}
		?>
	</div>    
</div>    
