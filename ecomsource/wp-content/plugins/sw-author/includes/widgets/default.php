<?php 
	/**
	* Layout default widget
	* Author: WpThemeGo
	**/
	
	if( !is_singular( 'book_author' ) ){
		return;
	}
	
	$query_args = array(
		'posts_per_page' => $numberposts,
		'post_status'    => 'publish',
		'post_type'      => 'product',
		'orderby'        => $orderby,
		'order'          => $order,
		'meta_query' => array(
			array(
				'key' => 'author_product',
				'value' => get_the_ID(),
				'compare' => 'LIKE'
			)
		)
	);
	$list = new WP_Query( $query_args );		
	
	if( $list->have_posts() ) {
?>
<div id="<?php echo 'slider_' . $widget_id; ?>" class="sw-woo-container-slider responsive-slider author-product-slider loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
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
															
							<?php do_action('sw_author_listing_product'); ?>
							
							<h4 class="custom-font"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>		
							<!-- price -->
							<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price custom-font">
									<span>
										<?php echo $price_html; ?>
									</span>
									
								</div>
							<?php } ?>
								<!-- rating  -->
							<?php 
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="reviews-content">
								<div class="star pull-left"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*16 ).'px"></span>' : ''; ?></div>
								<div class="woocommerce-review-link" >(<?php echo  esc_html( $review_count ) ; ?>)</div>
							</div>								
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
			<p>'. esc_html__( 'There is not item in this category', 'sw_author' ) .'</p>
		</div>';
	}