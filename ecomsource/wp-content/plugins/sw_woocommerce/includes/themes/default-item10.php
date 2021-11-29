<?php 

/**
	* Layout Theme Default
	* @version     1.0.0
**/
?>
<div class="item-wrap style2">
	<div class="item-detail">										
		<div class="item-img products-thumb">	
			<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
			<!-- add to cart, wishlist, compare -->
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>										
		<div class="item-content">
			<?php
				$userid = (int)$post->post_author;
				$user =  get_userdata( $userid );
				
				if( class_exists( 'WC_Vendors' ) && in_array('vendor', $user->roles) ):
			?>
				<a class="shop-user" href="<?php echo esc_url( WCV_Vendors::get_vendor_shop_page( $user->ID ) ); ?>"><?php echo $user->display_name; ?></a>
				
			<?php elseif( class_exists( 'WeDevs_Dokan' ) && in_array('seller', $user->roles) ):
				$store_url  = dokan_get_store_url( $userid );
			?>
				<a class="shop-user" href="<?php echo esc_url( $store_url ); ?>"><?php echo $user->display_name; ?></a>
				
			<?php elseif( class_exists('WCMp') && in_array('dc_vendor', $user->roles) ):
				global $WCMp;
				$vendor = get_wcmp_vendor( $userid );
			?>
				<a class="shop-user" href="<?php echo esc_url( $vendor->get_permalink() ); ?>"><?php echo $vendor->page_title ?></a>
				
			<?php elseif( class_exists('WCFMmp') && in_array('wcfm_vendor', $user->roles) ):
				global $WCFMmp;
				$store_url       = wcfmmp_get_store_url( $userid );
				$store_name      = wcfm_get_vendor_store_name( $userid );
			?>
				<a class="shop-user" href="<?php echo esc_url( $store_url ); ?>"><?php echo $store_name; ?></a>	
			
			<?php endif; ?>
			<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>								
			<!-- rating  -->
			<?php 
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();
			?>
			<?php if (  wc_review_ratings_enabled() ) { ?>
			<div class="reviews-content">
				<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*13 ).'px"></span>' : ''; ?></div>
			</div>	
			<?php } ?>
			<!-- end rating  -->
			<!-- price -->
			<?php if ( $price_html = $product->get_price_html() ){?>
				<div class="item-price">
					<span>
						<?php echo $price_html; ?>
					</span>
				</div>
			<?php } ?>
			<?php 
					$forginal_price = get_post_meta( get_the_ID(), '_regular_price', true );	
					$fsale_price 		= get_post_meta( get_the_ID(), '_sale_price', true );
			?>
			<?php if( $fsale_price > 0){ 
				$sale_off = 100 - (($fsale_price/$forginal_price)*100); ?>
				<div class="sale-off">
					<?php echo '-'.round($sale_off).'% '.esc_html__('Off', 'sw_woocommerce');?>
				</div>
			<?php } ?>	
		</div>								
	</div>
</div>