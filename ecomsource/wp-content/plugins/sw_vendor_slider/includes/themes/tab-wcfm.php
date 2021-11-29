<?php 
	if(!class_exists('WCFMmp')) {
		return;
	}
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_wcfm_vendor_'.$this->generateID();
	$nav_id = 'nav_tabs_wcfm'.rand().time();
	
	if( $category ){
		if( !is_array( $category ) ){
			$category = explode( ',', $category );
		}
?>
<div id="<?php echo esc_attr( $widget_id.rand() ) ?>" class="sw-tab-vendor-slider clearfix">
		<div class="resp-tab" style="position:relative;">
		<?php if( $title1 != '') { ?>
			<div class="box-title">
				<h3><span><?php echo esc_html( $title1 ); ?></span></h3>
				<?php if( $description != '') { ?> <div class="description"><?php echo ( $description != '' ) ? $description : ''; ?></div><?php } ?>
			</div>
			<?php } ?>
			<div class="top-tab-slider">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr($nav_id); ?>"  aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="fa fa-bar"></span>
					<span class="fa fa-bar"></span>
					<span class="fa fa-bar"></span>
				</button>
				<ul class="nav nav-tabs" id="<?php echo esc_attr( $nav_id ); ?>">
					<?php 
					$i = 1;
					foreach( $category as $j => $userid ){
						$user = get_userdata( $userid );
						$count = count_user_posts( $userid, 'product');
							global $WCFMmp;
							$store_user  = wcfmmp_get_store( $userid );
							$store_info  = $store_user->get_shop_info();
							
							$gravatar = $store_user->get_avatar();
							$store_url       = wcfmmp_get_store_url( $userid );
							$store_name      = wcfm_get_vendor_store_name( $userid );
					?>
							<li class="<?php if( $i == 1 ){ echo 'active'; }?>">
								<a href="#<?php echo esc_attr( 'wcfm_user_'.$userid. '_' .$widget_id ); ?>" data-toggle="tab">
									<span class="image"><img width="50" src="<?php echo esc_url( $gravatar ) ?>" /></span>
									<span class="vendor-name"><?php echo $store_name; ?></span>
								</a>
							</li>	
						<?php $i ++; ?>
					<?php } ?>
				</ul>
			</div>
			<div class="tab-content">
				<?php 
						$i = 0;
						foreach( $category as $j => $userid ){ 
							$user = get_userdata( $userid );
							$count = count_user_posts( $userid, 'product');
							if( $user ) {
								global $WCFMmp;
								$store_user  = wcfmmp_get_store( $userid );
								$store_info  = $store_user->get_shop_info();
								
								$gravatar  = $store_user->get_avatar();
								$store_url       = wcfmmp_get_store_url( $userid );
								$store_name      = wcfm_get_vendor_store_name( $userid );
					?>
								<div class="tab-pane <?php if( $i == 0 ){echo 'active'; }?>" id="<?php echo esc_attr( 'wcfm_user_'.$userid. '_' .$widget_id ); ?>">
									<div id="<?php echo esc_attr( 'tab_cat_'.$userid.'_' .$widget_id ); ?>" class="woo-tab-container-slider responsive-slider loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false" data-dots="false" data-vertical="false">
											<div class="slider responsive">	
												<?php 
													$default = array(
														'post_type' 			=> 'product',		
														'post_status' 			=> 'publish',
														'ignore_sticky_posts'   => 1,
														'showposts'				=>  $numberposts,
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
															$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
															while( $list->have_posts() ) : $list->the_post();
															global $product, $post;
															$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
																if( $key % $item_row == 0 ){
															?>
																<div class="item <?php echo esc_attr( $class )?> product">
																	<?php } ?>
																	<div class="item-wrap">
																		<div class="item-detail">										
																			<div class="item-img products-thumb">			
																				<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
																				<!-- add to cart, wishlist, compare -->
																				<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
																			</div>										
																			<div class="item-content">
																				<!-- end rating  -->
																				<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>																
																				<!-- price -->
																				<?php if ( $price_html = $product->get_price_html() ){?>
																				<div class="item-price">
																					<span>
																						<?php echo $price_html; ?>
																					</span>
																				</div>
																				<?php } ?>
																				<?php
																						$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_type() : $product->product_type;
																						echo sw_label_new();
																						if( $product_type != 'variable' ) {
																							$forginal_price 	= get_post_meta( $post->ID, '_regular_price', true );	
																							$fsale_price 		= get_post_meta( $post->ID, '_sale_price', true );
																							if( $fsale_price > 0 && $product->is_on_sale() ){ 
																								$sale_off = 100 - ( ( $fsale_price/$forginal_price ) * 100 ); 
																								$html = '<div class="sale-off2 ' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
																								$html .= ''.round( $sale_off ).'% '. esc_html__('off','sw_vendor_slider');
																								$html .= '</div>';
																								echo apply_filters( 'sw_label_sales', $html );
																							} 
																						}else{
																							echo '<div class="' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
																							wc_get_template( 'single-product/sale-flash.php' );
																							echo '</div>';
																						}
																				?>
																					<?php $total_sales = get_post_meta( $post->ID, 'total_sales', true ); ?>
																					<div class="stock-sold"><span><?php echo $total_sales; ?><?php echo esc_html__(' Sold','sw_vendor_slider');?></span></div>
																			</div>							
																		</div>
																	</div>
															<?php if( ( $key+1 ) % $item_row == 0 || ( $key+1 ) == $count_items ){?> </div><?php } ?>
															<?php $key++; endwhile; wp_reset_postdata();?>
												<?php } ?>
										</div>
										<a class="view-all" href="<?php echo esc_url( $store_url ); ?>"><?php echo esc_html__('View all ','sw_vendor_slider');?><?php echo $store_name; ?><?php echo esc_html__(' products...','sw_vendor_slider');?></a>
								</div>
							</div>
					<?php } $i++; } ?>
			</div>
		</div>
	</div>
<?php }else{
	echo '<div class="alert alert-warning alert-dismissible" role="alert">
	<a class="close" data-dismiss="alert">&times;</a>
	<p>'. esc_html__( 'There is not vendor on this component', 'sw_vendor_slider' ) .'</p>
	</div>';
}
