 <?php 

/**
** Layout Bunled product Default
* @version     1.0.0
**/

$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();
$term_name = esc_html__( 'All Categories', 'sw_product_bundles' );
$paged 			= (get_query_var('paged')) ? get_query_var('paged') : 1;
$default = array(
	'post_type' => 'product',	
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts,
	'paged' => $paged
);
$default['tax_query'][] = array(						
		'taxonomy' => 'product_type',
		'field'    => 'name',
		'terms'    => 'bundle',
		'operator' => 'IN',	
	);
//var_dump($category);
if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );
	if( $term ) :
		$term_name = $term->name;
	endif; 
	
	$default['tax_query'][] = array(
		array(    
			'taxonomy'  => 'product_cat',
			'field'     => 'slug',
			'terms'     => $category ));
}
$id = 'sw_bundle_'.$this->generateID();
$nav_id = 'nav_tabs_res'.rand().time();
$list = new WP_Query( $default );
?>
<div class="bundle-slider-style4" id="<?php echo $category.'_'.$id; ?>" >
	<div class="resp-tab" style="position:relative;">
		<?php if( $title1 != ''): ?>
			<div class="box-title"><h3 class="custom-font"><span><?php echo ( $title1 != '' ) ? $title1 :''; ?></span></h3></div>
		<?php endif; ?>
			<div class="resp-content clearfix">
				<?php
						$i    =  0;
						$count_items = 0;
						$count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts;
						
						while($list->have_posts()): $list->the_post();	
						global $product, $post;
						$ids = $product->get_id();
						$sale_price = ( $product->is_on_sale() ? $product->get_sale_price():0 );
						$regular_price = $product->get_regular_price();
						$save_price = $regular_price - $sale_price;
						$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
					?>
				<div class="wrap-content">
					<div class="item-content-left clearfix">
						<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php swpb_bunlde_trim_words( get_the_title(), $title_length ); ?></a></h4>
						<?php  $bundles = apply_filters( 'swpb/load/bundle', $product->get_id() );
						echo '<div class="included">'.count( $bundles ). esc_html__(' items','sw_product_bundles').'</div>'; ?>
						<div class="product-info">
							<?php 
								if( $product->get_stock_quantity() != 0 ){ 
								$available 	 = $product->get_stock_quantity();
								$total_sales = get_post_meta( $post->ID, 'total_sales', true );
								$bar_width 	 = intval( $available ) / intval( $available + $total_sales ) * 100;
							?>
							<div class="emarket-stock clearfix">
								<div class="stock-sold"><?php esc_html_e( 'Sold number: ', 'sw_woocommerce' ) ?><span><?php echo $total_sales; ?></span></div>
							</div>
							<?php } ?>
							<div class="created-date"><?php echo '<span class="title-date">Created date: </span>'.get_the_date('F j, Y', $product->get_id()); ?></div>
							<div class="update-date"><?php echo '<span class="title-date">Updated: </span>'.$product->get_date_modified()->date('F j, Y'); ?></div>
						</div>
						<a class="see-all" href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><i class="fa fa-angle-double-right"></i><?php echo esc_html__('See detail','sw_product_bundles'); ?></a>
					</div>
					<div class="item-content-right clearfix">
						<div id="<?php echo esc_attr( 'tab_cat_'.$ids.'_' .$widget_id ); ?>" class="woo-tab-container-slider clearfix">
							<div class="resp-slider-container">
									<div class="responsive-slider loading clearfix" id="product_thumbnail_<?php echo esc_attr( $post->ID ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false" data-dots="false" data-vertical="false">
										<div class="slider responsive">	
											<?php	
												$bundles = apply_filters( 'swpb/load/bundle', $product->get_id() );
												foreach ( $bundles as $key => $value )  { 
												$bundle = wc_get_product( $key ); 
												$product_url = "";
												if ( get_post_type( $key ) == 'product_variation' ) {
													$product_url = get_the_permalink( wp_get_post_parent_id( $key ) );
												} else {
													$product_url = get_the_permalink( $key );
												}

											?>
												<div class="item">
													<div class="item-wrap">
														<div class="item-thumbnail">
															<a href="<?php echo esc_url( $product_url ); ?>"><?php echo $bundle->get_image( 'shop_catalog' ); ?></a>
															<?php
																$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $bundle->get_type() : $bundle->product_type;
																if( $product_type != 'variable' ) {
																	$forginal_price 	= get_post_meta( $bundle->get_id(), '_regular_price', true );	
																	$fsale_price 		= get_post_meta( $bundle->get_id(), '_sale_price', true );
																	if( $fsale_price > 0 && $bundle->is_on_sale() ){ 
																		$sale_off = 100 - ( ( $fsale_price/$forginal_price ) * 100 ); 
																		$html = '<div class="sale-off">';
																		$html .= '-' . round( $sale_off ).'%';
																		$html .= '</div>';
																		echo apply_filters( 'sw_label_sales', $html );
																	} 
																}else{
																	echo '<div>';
																	wc_get_template( 'single-product/sale-flash.php' );
																	echo '</div>';
																}
															?>
														</div>
														<div class="item-content">	
															<h4><a href="<?php echo esc_url( $product_url ); ?>"> <?php echo esc_html( $value['title'] ); ?></a></h4>
															<!-- rating  -->
															<?php 
															$rating_count = $bundle->get_rating_count();
															$review_count = $bundle->get_review_count();
															$average      = $bundle->get_average_rating();
															?>
															<?php if (  wc_review_ratings_enabled() ) { ?>
															<div class="reviews-content">
																<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*13 ).'px"></span>' : ''; ?></div>
															</div>  
															<?php } ?>
															<!-- end rating  -->  
															<div class="price"><?php echo $bundle->get_price_html();?></div>
														</div>
													</div>
												</div>
												<?php } ?>
										</div>
									</div>
									<div class="item-right">
										<!-- price -->
										<?php if ( $price_html = $product->get_price_html() ){?>
										<div class="wrap-price">
											<span class="regular-price">
												<?php echo $product->get_price_html();?>
											</span>
											<span class="combo-title">
												<?php echo esc_html__('Combo Price:','sw_product_bundles'); ?>
											</span>
											<?php if ( $sale_price ): ?>
											<span class="sale-price">
												<?php echo $product->get_price_html();?>
											</span>
											<?php endif; ?>
											<?php if ( $sale_price ): ;?>
												<span class="save-price"><?php echo esc_html__('Save','sw_product_bundles')?> <?php echo get_woocommerce_currency_symbol(); ?><?php echo sprintf( '%01.2f', $save_price); ?></span>
											<?php endif; ?>
										</div>
										<?php } ?>
										<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php echo esc_html__('Get this Combo','sw_product_bundles'); ?></a></h4>								
									</div>
							</div>
						</div>
					</div>
				</div>
				<?php $i++; endwhile;?>
			</div>
			<!--Pagination-->
			<?php if ($list->max_num_pages > 1) : ?>
				<div class="pagination-page nav-pag">
						<?php 
							echo paginate_links( array(
								'base' => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ),
								'format' => '?paged=%#%',
								'current' => max( 1, get_query_var('paged') ),
								'total' => $list->max_num_pages,
								'end_size' => 1,
								'mid_size' => 1,
								'prev_text' => esc_html__( 'Prev', 'sw_product_bundles' ),
								'next_text' => esc_html__( 'Next', 'sw_product_bundles' ),
								'type' => 'list',
								
							) );
						?>
				</div>
			<?php endif; wp_reset_postdata(); ?>
		</div>
	</div>