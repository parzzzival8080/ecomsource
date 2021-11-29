<?php 
	/**
	* Layout Related Author Product
	* Author: WpThemeGo
	**/
	if( !is_singular( 'product' ) ){
		return;
	}
	global $post;
	$authors = get_post_meta( $post->ID, 'author_product', true );
	
	if( !is_array( $authors ) && count( $authors ) < 1 ){
		return;
	}		
?>
<div class="author-related-product">
	<div class="author-related-left">
		<a href="<?php echo get_permalink( $authors[0] ); ?>">
			<?php echo get_the_post_thumbnail( $authors[0], 'shop_catalog' ); ?>
			
		</a>
		<h4><?php echo get_the_title( $authors[0] ); ?></h4>
	</div>
	<div class="author-related-content">
		<div class="author-description">
			<?php echo get_the_excerpt( $authors[0] ); ?>
			<div class="more"><a href="<?php echo get_permalink( $authors[0] ); ?>"><?php echo esc_html__( 'learn more', 'sw_author' ); ?></a></div>
		</div>
		<?php
			$query_args = array(
				'posts_per_page' => $numberposts,
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'orderby'        => $orderby,
				'order'          => $order,
				'meta_query' => array(
					array(
						'key' => 'author_product',
						'value' => $authors[0],
						'compare' => 'LIKE'
					)
				)
			);
			$list = new WP_Query( $query_args );		
			
			if( $list->have_posts() ) {
		?>
		<div id="<?php echo esc_attr( 'slider_' . $widget_id ); ?>" class="sw-woo-container-slider responsive-slider author-product-slider loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
			<div class="resp-slider-container">
				<div class="slider responsive">	
				<?php 
					$count_items 	= 0;
					$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
					$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
					$i 				= 0;
					while($list->have_posts()): $list->the_post();global $product, $post;
					$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
					if( $i % $item_row == 0 ){
				?>
					<div class="item <?php echo esc_attr( $class )?> product">
				<?php } ?>
						<div class="item-wrap">
							<div class="item-detail">
								<div class="item-img products-thumb">
									<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
									<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>			
								</div>
								<div class="item-content">
												
									<!-- price -->
									<?php if ( $price_html = $product->get_price_html() ){?>
										<div class="item-price custom-font">
											<span>
												<?php echo $price_html; ?>
											</span>
											
										</div>
									<?php } ?>
																	
								</div>
							</div>
						</div>
					<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
				<?php $i++; endwhile; wp_reset_postdata();?>
				</div>
			</div>            
		</div>
		<?php 
			}else{
				echo '<div class="alert alert-warning alert-dismissible" role="alert">
					<a class="close" data-dismiss="alert">&times;</a>
					<p>'. esc_html__( 'There is not item in this author', 'sw_author' ) .'</p>
				</div>';
			}
		?>
	</div>
</div>


