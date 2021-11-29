<?php 

/**
	* Layout Tab Category Default
	* @version     1.0.0
**/

	$tag_id = 'sw_woo_rplisting_'. rand().time();
	$nav_id = 'nav_tabs_res'.rand().time();
	
$term_name = esc_html__( 'All Categories', 'sw_woocommerce' );
?>
<div class="sw-woo-tab-listing style1" id="<?php echo esc_attr( $tag_id ); ?>" >
		<?php if( $title1 != '' ){ ?>
			<div class="box-title"><h3><?php echo  $title1; ?></h3></div>
		<?php } ?>
		<div class="tab-listing-container row">
			<?php
				$default = array(
					'post_type' => 'product',		
					'orderby' => $orderby,
					'order' => $order,
					'post_status' => 'publish',
					'showposts' => $numberposts
					);
				if( $category != '' ){
					$term = get_term_by( 'slug', $category, 'product_cat' );	
					if( $term ) :
						$term_name = $term->name;
					endif;

					$default['tax_query'] = array(
						array(
							'taxonomy'  => 'product_cat',
							'field'     => 'slug',
							'terms'     => $category )
						);	
				}
				$default = sw_check_product_visiblity( $default );
				
				$attribute = '';
				$col1 = 12 / $columns;
				$col2 = 12 / $columns1;
				$col3	= 12 / $columns2;
				$col4	= 12 / $columns3;
				$attribute .= 'item item-listing product col-lg-'.$col1.' col-md-'.$col2.' col-sm-'.$col3.' col-xs-'.$col4.' clearfix';
				$list = new WP_Query( $default );
				$max_page = $list -> max_num_pages;
				if( $list->have_posts() ) : 			 
					while($list->have_posts()): $list->the_post();
					global $product, $post;
					$attribute .= ( $product->get_price_html() ) ? '' : ' item-nonprice';
				?>
					<div class="<?php echo esc_attr( $attribute ); ?>">
						<div class="item-wrap">
							<div class="item-detail">										
								<div class="item-img products-thumb">			
									<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
									<!-- add to cart, wishlist, compare -->
									<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
								</div>										
								<div class="item-content">
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
								</div>								
							</div>
						</div>
					</div>
							<?php endwhile; wp_reset_postdata();?>
					<div class="item item-listing item-more">
						<a href="javascript:void(0)" data-ajaxurl="<?php echo esc_url( sw_ajax_url() ) ?>" data-layout="<?php echo esc_attr( isset( $widget_template ) ? $widget_template : $layout );?>" data-maxpage="<?php echo esc_attr( $max_page ) ?>" data-attributes="<?php echo esc_attr( $attribute ) ?>" data-number="<?php echo esc_attr( $numberposts ) ?>" data-orderby="<?php echo esc_attr( $orderby ) ?>" data-order="<?php echo esc_attr( $order ) ?>" data-category="<?php echo esc_attr( $category ) ?>" data-label-loaded="<?php esc_attr_e( 'All Item', 'sw_woocommerce' ); ?>" data-label="<?php esc_html_e( 'Load More', 'sw_woocommerce' ); ?>"></a>
					</div>
				<?php 
					else :
						esc_html_e( 'There is no product on this category', 'sw_woocommerce' );
					endif;
				?>			
		</div>
	</div>
