<?php 

/**
** Layout Bunled product Default
* @version     1.0.0
**/

$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();
$term_name = esc_html__( 'All Categories', 'sw_product_bundles' );
$default = array(
	'post_type' => 'product',	
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts	
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
$id = 'sw_countdown_'.$this->generateID();
$nav_id = 'nav_tabs_res'.rand().time();
$list = new WP_Query( $default );
?>
<div class="sw-tab-bunled-product" id="<?php echo esc_attr( 'category_bunled' . $widget_id ); ?>" >
	<div class="resp-tab" style="position:relative;">
		<?php if( $title1 != ''): ?>
			<div class="box-title"><h3 class="custom-font"><span><?php echo ( $title1 != '' ) ? $title1 :''; ?></span></h3></div>
		<?php endif; ?>
				<div class="tab-content">
					<?php
						$i    =  0;
						while($list->have_posts()): $list->the_post();	
						global $product, $post;
						$ids = $product->get_id();
						$sale_price = ( $product->is_on_sale() ? $product->get_sale_price():0 );
						$regular_price = $product->get_regular_price();
						$save_price = $regular_price - $sale_price;
						$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
					?>
					<div class="tab-pane <?php if( $i == 0 ){echo 'active'; }?>" id="<?php echo  esc_attr( $ids.'_'.$widget_id ); ?>">
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
						<?php $i++; endwhile; wp_reset_postdata();?>
						</div>
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
									while($list->have_posts()): $list->the_post();	
										global $product, $post;
										$ids = $product->get_id();
											?>
											<li class="<?php if( $i == 1 ){ echo 'active'; }?>">
													<a href="#<?php echo esc_attr( $ids. '_' .$widget_id ); ?>" data-toggle="tab">
													<?php echo get_the_title() ?>
												</a>
											</li>	
										<?php $i ++; ?>
									<?php endwhile; wp_reset_postdata(); ?>
								</ul>
							</div>
					</div>
				</div>