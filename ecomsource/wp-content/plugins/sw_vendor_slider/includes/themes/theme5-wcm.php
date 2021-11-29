<?php 
	if( !class_exists('WCMp') ){
		return;
	}
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_wcmp_vendor_'.$this->generateID();
	
	
	if( $category ){
		if( !is_array( $category ) ){
			$category = explode( ',', $category );
		}
?>
	<div id="<?php echo esc_attr( $widget_id.rand() ) ?>" class="responsive-slider sw-vendor-container-slider6 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title1 != '') { ?>
			<div class="box-title"><h3 class="custom-font"><?php echo esc_html( $title1 ); ?></h3></div>
		<?php } ?>
		<div class="resp-slider-container">
			<div class="slider responsive">
				<?php 
					foreach( $category as $j => $userid ){ 
						$user = get_userdata( $userid );
						$count = count_user_posts( $userid, 'product');
						$store_icon_src = wp_get_attachment_image_src( get_user_meta( $user->ID, '_vendor_image', true ), array( 50, 50 ) );
						if( $user ) {
							global $WCMp;
							$vendor = get_wcmp_vendor($userid);
				?>
				<?php	if( ( $j % $item_row ) == 0 ) { ?>
					<div class="item item-vendor">
				<?php } ?>
					<div class="item-wraps">
					<?php 
						$default = array(
							'post_type' 			=> 'product',		
							'post_status' 			=> 'publish',
							'ignore_sticky_posts'   => 1,
							'showposts'				=> 3,
							'orderby' 				=> 'date',
							'author' => $user->ID,
						);
						if( sw_woocommerce_version_check( '3.0' ) ){	
								$default['tax_query'][] = array(						
									'taxonomy' => 'product_visibility',
									'field'    => 'name',
									'terms'    => 'featured',
									'operator' => 'IN',	
								);
							}else{
								$default['meta_query'] = array(
									array(
										'key' 		=> '_featured',
										'value' 	=> 'yes'
									)					
								);				
							}
						$list = new WP_Query( $default );
						if( $list->have_posts() ) {
					?>
						<div class="item-product clearfix">
							<?php 
								$key = 0;
								$count_items = 0;
								$count_items = ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
								while( $list->have_posts() ) : $list->the_post();
								global $product, $post;
								$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
								if( $key % 3 == 0 ){
							?>
								<div class="item product item-product-vendor <?php echo esc_attr( $class )?>">
							<?php } ?>
									<div class="item-wrap">
										<div class="item-detail">	
											<div class="item-img products-thumb">
													<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_post_thumbnail( $product->get_id(), 'shop_catalog', array( 'alt' => $post->post_title ) ); ?></a>
													<!-- add to cart, wishlist, compare -->
													<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
											</div>
											<div class="item-content">
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
											</div>
										</div>
									</div>
								<?php if( ( $key+1 ) % 3 == 0 || ( $key+1 ) == $count_items ){?> </div><?php } ?>								
							<?php $key++; endwhile; wp_reset_postdata(); ?>
						</div>
					<?php } ?>
						<div class="item-bottom clearfix">
							<div class="item-user">
								<div class="item-user-img clearfix">
									<a href="<?php echo $vendor->get_permalink(); ?>"><img src="<?php echo esc_url( $store_icon_src ) ?>" /></a>
								</div>
								<h4>
									<a href="<?php echo $vendor->get_permalink(); ?>"><?php echo $vendor->page_title ?></a>
									<span><?php echo $count. esc_html__(' products','sw_vendor_slider'); ?></span>
								</h4>
							</div>
						</div>
					</div>
				<?php if( ( $j+1 ) % $item_row == 0 || ( $j+1 ) == count( $category ) ){?> </div><?php  } ?>
				<?php }} ?>
			</div>
		</div>
	</div>
<?php }else{
	echo '<div class="alert alert-warning alert-dismissible" role="alert">
	<a class="close" data-dismiss="alert">&times;</a>
	<p>'. esc_html__( 'There is not vendor on this component', 'sw_vendor_slider' ) .'</p>
	</div>';
}
