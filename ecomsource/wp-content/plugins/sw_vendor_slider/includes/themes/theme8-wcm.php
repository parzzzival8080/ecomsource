<?php 
	if( !class_exists('WCMp') ){
		return;
	}
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_wcmp_vendor_'.$this->generateID();
	$viewall = get_permalink( wc_get_page_id( 'shop' ) );
	
	if( $category ){
		if( !is_array( $category ) ){
			$category = explode( ',', $category );
		}
?>
	<div id="<?php echo esc_attr( $widget_id.rand() ) ?>" class="responsive-slider sw-vendor-container-slider9 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title1 != '') { ?>
			<div class="box-title clearfix">
				<h3 class="pull-left"><span><?php echo ( $title1 != '' ) ? $title1 :''; ?></span></h3>
				<a class="view-all pull-right" href="<?php echo esc_url( $viewall );?>"><?php echo esc_html__('View more','sw_vendor_slider');?></a>
			</div>
		<?php } ?>
		<div class="resp-slider-container">
			<div class="slider responsive">
				<?php 
					foreach( $category as $j => $userid ){ 
						$user = get_userdata( $userid );
						$count = count_user_posts( $userid, 'product');
						$store_icon_src = wp_get_attachment_image_src( get_user_meta( $user->ID, '_vendor_image', true ), array( 60, 60 ) );
						$vendor_banner = get_user_meta( $user->ID,'_vendor_banner', true );
						$image = $vendor_banner ? wp_get_attachment_image( $vendor_banner, 'large' ) : '<img src="https://place-hold.it/270x180" />';
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
									'showposts'				=> 1,
									'meta_key' 		 		=> 'total_sales',
									'orderby' 		 		=> 'meta_value_num',
									'author' => $user->ID,
								);
								$list = new WP_Query( $default );
								if( $list->have_posts() ) {
							?>
									<?php 
										$key = 0;
										$count_items = 0;
										$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
										//$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
										while( $list->have_posts() ) : $list->the_post();
										global $product, $post;
										$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
										?>
											<div class="item <?php echo esc_attr( $class )?> product">
												<div class="item-wrap style2">
													<div class="item-detail">										
														<div class="item-img products-thumb">			
															<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
															<!-- add to cart, wishlist, compare -->
															<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
														</div>										
														<div class="item-content">
															<!-- end rating  -->
															<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_vendor_trim_words( get_the_title(), $length ); ?></a></h4>															
															
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
											</div>
								<?php  endwhile; wp_reset_postdata();?>
							<?php } ?>
							<div class="item-user">
								<div class="item-user-img clearfix">
									<a href="<?php echo $vendor->get_permalink(); ?>"><img src="<?php echo esc_url( $store_icon_src ) ?>" /></a>
								</div>
								<div class="item-content">
								<a href="<?php echo $vendor->get_permalink(); ?>"><?php echo $vendor->page_title ?></a>
									<span><?php echo $count. esc_html__(' products','sw_vendor_slider'); ?></span>
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
