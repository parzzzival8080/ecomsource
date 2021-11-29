<div class="sw-mini-cart-ajax">
<?php if ( ! WC()->cart->is_empty()): ?>
	<div class="notification gray">
		<p><?php echo esc_html__( 'There are','sw-tools-plugin'); ?> <span class="text-color"><?php echo esc_attr($woocommerce->cart->cart_contents_count);echo esc_html__( ' item(s)','sw-tools-plugin'); ?> </span><?php echo esc_html__( 'in your cart','sw-tools-plugin'); ?></p>
	</div>
	<table class="table table-striped">
		<?php foreach($woocommerce->cart->get_cart() as $cart_item_key => $cart_item ):
			  $_product =  wc_get_product( $cart_item['data']->get_id());
			  $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
		?>
			<tr id="element<?php echo esc_attr($_product->get_id()); ?>">
				<td class="text-left first">
					<?php
					$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

					if ( ! $product_permalink ) {
						echo esc_attr($thumbnail); // PHPCS: XSS ok.
					} else {
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
					}
					?>
				</td>
				<td class="text-left">
					<a href="<?php echo esc_attr($product_permalink); ?>"><?php echo esc_attr($_product->get_name()); ?></a>
				</td>
				<td class="text-right">x<?php echo esc_attr($cart_item['quantity']); ?></td>
				<td class="text-right total-price">
				<?php
					echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
				?>
				</td>
				<td class="text-right last">
				<a href="<?php echo esc_attr(wc_get_cart_remove_url( $cart_item['key'] )); ?>" class="remove_from_cart_button" data-product_id="<?php echo esc_attr($_product->get_id()); ?>" data-cart_item_key="<?php echo esc_attr($cart_item['key']); ?>"><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<div class="cart-bottom">
		<table class="table table-striped">												
			<tr class="cart-subtotal">
				<td class="text-left"><strong><?php esc_html_e( 'Subtotal', 'sw-tools-plugin' ); ?></strong></td>
				<td class="text-right" data-title="<?php esc_attr_e( 'Subtotal', 'sw-tools-plugin' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>
			<?php foreach ( $woocommerce->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<td class="text-left"><strong><?php wc_cart_totals_coupon_label( $coupon ); ?></strong></td>
				<td class="text-right" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
			<?php endforeach; ?>														
			<tr class="order-total">
				<td class="text-left"><strong><?php esc_html_e( 'Total', 'sw-tools-plugin' ); ?></strong></td>
				<td class="text-right" data-title="<?php esc_attr_e( 'Total', 'sw-tools-plugin' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>														
		</table> 
		<p class="text-center">
			<a href="<?php echo esc_attr(wc_get_cart_url()); ?>" class="btn btn-view-cart"><?php echo esc_html__( 'View Cart','sw-tools-plugin'); ?></strong></a>
			<a href="<?php echo esc_attr(wc_get_checkout_url()); ?>" class="btn btn-checkout"><strong><?php echo esc_html__( 'Checkout','sw-tools-plugin'); ?></strong></a>
		</p>
	</div>											
<?php else: ?>	
	<div class="notification gray">
		<i class="fa fa-shopping-cart info-icon"></i>
		<p><?php echo esc_html__( 'Your shopping cart is empty!','sw-tools-plugin'); ?></p>
	</div>
<?php endif; ?>	
</div>

<script>
	(function($) {	
		$(document).ready(function($) {
			$('.sw-mini-cart-ajax .remove_from_cart_button').on('click', function() {
				$('#element'+$(this).data('product_id')).addClass('sw-loading-cart');
				$(this).html('<i class="fa fa-spinner" aria-hidden="true"></i>');
			});			
		})
	})(jQuery);			
</script>