<?php 
	$widget_id =  'sw_woo_tab_'. $this->generateID();
	if( !is_array( $select_order ) ){
		$select_order = explode( ',', $select_order );
	}
	$viewall = get_permalink( wc_get_page_id( 'shop' ) );	
	if( $category != '' ){
		$term = get_term_by( 'slug', $category, 'product_cat' );
		if( $term ) :
			$term_name = $term->name;
			$viewall = get_term_link( $term->term_id, 'product_cat' );
		endif;
	}
	$widget_id = 'tab4_'.rand().time();
?>
<div class="sw-wootab-slider sw-ajax sw-woo-tab-style5 <?php echo isset( $style ) ? esc_attr( $style ) : ''; ?> clearfix" id="<?php echo esc_attr( $widget_id ); ?>" >
	<div class="tab-left">
		<div class="resp-wrap clearfix">
			<div class="box-category-top">
				<div class="block-title clearfix">
					<h3><span><?php echo ( $title1 != '' ) ? $title1 : ''; ?></span></h3>
				</div>
				<?php 
					if( $term ) :
						$termchild 		= get_terms( 'product_cat', array( 'parent' => $term->term_id, 'hide_empty' => 0, 'number' => $number_child ) );
					if( count( $termchild ) > 0 ){
					?>		
					<div class="childcat-content"  id="<?php echo 'child_' . $widget_id; ?>">
						<?php 					
							echo '<ul>';
							foreach ( $termchild as $key => $child ) {
								echo '<li><a href="' . get_term_link( $child->term_id, 'product_cat' ) . '">' . $child->name . '</a></li>';
							}
							echo '</ul>';
						?>
					</div>
					<?php } ?>
					<?php endif; ?>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#<?php echo 'child_' . $widget_id; ?>"  aria-expanded="false">				
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>	
					</button>
			</div>
			<!-- Product tab listing -->
			<?php 
				if( $image != '' ) :
				$banner_links = explode( ',', $banner_links );
				$image = explode( ',', $image );	
			?>
				<div class="banner-category">
					<div id="<?php echo esc_attr( 'banner_' . $widget_id ); ?>" class="banner-slider" data-lg="1" data-md="1" data-sm="1" data-xs="1" data-mobile="1" data-arrow="false" data-fade="false">
						<div class="banner-responsive">
							<?php if(is_array($image) || is_object($image)): ?>
								<?php foreach( $image as $key => $img ) : ?>
									<div class="item">
										<a href="<?php echo esc_url( $banner_links[$key] ); ?>"><?php echo wp_get_attachment_image( $img, 'large' ); ?></a>
									</div>
								<?php endforeach;?>
							<?php endif; ?>
						</div>
					</div>									
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="resp-tab" style="position:relative;">
		<div class="top-tab-slider clearfix">
			<ul class="nav nav-tabs">
				<?php 
					$active = $tab_active -1;
					$tab_title = '';
					foreach( $select_order as $i  => $so ){						
						switch ($so) {
						case 'latest':
							$tab_title = __( 'Latest Products', 'sw_woocommerce' );
						break;
						case 'rating':
							$tab_title = __( 'Top Rating', 'sw_woocommerce' );
						break;
						case 'bestsales':
							$tab_title = __( 'Best Selling', 'sw_woocommerce' );
						break;						
						default:
							$tab_title = __( 'Featured Products', 'sw_woocommerce' );
						}
					?>
					<li <?php echo ( $i == $active )? 'class="active loaded"' : ''; ?>>
						<a href="#<?php echo esc_attr( $so. '_' .$widget_id ) ?>" data-type="so_ajax" data-layout="<?php echo esc_attr( isset( $widget_template ) ? $widget_template : $layout );?>" data-row="<?php echo esc_attr( $item_row ) ?>" data-length="<?php echo esc_attr( $title_length ) ?>" data-ajaxurl="<?php echo esc_url( sw_ajax_url() ) ?>" data-category="<?php echo esc_attr( $category ) ?>" data-toggle="tab" data-sorder="<?php echo esc_attr( $so ); ?>" data-catload="ajax" data-number="<?php echo esc_attr( $numberposts ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
							<?php echo esc_html( $tab_title ); ?>
						</a>
					</li>			
				<?php } ?>
			</ul>
		</div>		
		<div class="tab-content clearfix">	
		<!-- Product tab slider -->						
			<div class="tab-pane active" id="<?php echo esc_attr( $select_order[$active]. '_' .$widget_id ) ?>">
			<?php 
				$default = array();			
				if( $select_order[$active] == 'latest' ){
					$default = array(
						'post_type'	=> 'product',
						'paged'		=> 1,
						'showposts'	=> $numberposts,
						'orderby'	=> 'date'
					);						
				}
				if( $select_order[$active] == 'rating' ){
					$default = array(
						'post_type'		=> 'product',							
						'post_status' 	=> 'publish',
						'no_found_rows' => 1,					
						'showposts' 	=> $numberposts						
					);
					if( sw_woocommerce_version_check( '3.0' ) ){	
						$default['meta_key'] = '_wc_average_rating';	
						$default['orderby'] = 'meta_value_num';
					}else{	
						add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
					}
				}
				if( $select_order[$active] == 'bestsales' ){
					$default = array(
						'post_type' 			=> 'product',							
						'post_status' 			=> 'publish',
						'ignore_sticky_posts'   => 1,
						'showposts'				=> $numberposts,
						'meta_key' 		 		=> 'total_sales',
						'orderby' 		 		=> 'meta_value_num'						
					);
				}
				if( $select_order[$active] == 'featured' ){
					$default = array(
						'post_type'	=> 'product',
						'post_status' 			=> 'publish',
						'ignore_sticky_posts'	=> 1,
						'showposts' 		=> $numberposts,						
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
				}
				if( $category != '' ){
					$default['tax_query'][] = array(
						'taxonomy'	=> 'product_cat',
						'field'		=> 'slug',
						'terms'		=> $category,
						'operator' 	=> 'IN'
					);
				}
				$default = sw_check_product_visiblity( $default );
				
				$list = new WP_Query( $default );
				if( $select_order[$active] == 'rating' && ! sw_woocommerce_version_check( '3.0' ) ){			
					remove_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
				}
				if( $list->have_posts() ) :
			?>
				<div id="<?php echo esc_attr( 'tab_'. $select_order[$active]. '_' .$widget_id ); ?>" class="woo-tab-container-slider responsive-slider loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
					<div class="resp-slider-container">
							<div class="slider responsive">
						<?php 
							$count_items 	= 0;
							$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
							$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
							$i 				= 0;
							$j				= 0;
							while($list->have_posts()): $list->the_post();
							global $product, $post;	
							$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
							if( $i % $item_row == 0 ){
						?>
							<div class="item <?php echo esc_attr( $class )?> product clearfix">
						<?php } ?>
							<div class="item-wrap">
								<div class="item-detail">										
									<div class="item-img products-thumb">	
										<?php 
												$forginal_price = get_post_meta( get_the_ID(), '_regular_price', true );	
												$fsale_price 		= get_post_meta( get_the_ID(), '_sale_price', true );
										?>
										<?php if( $fsale_price > 0){ 
											$sale_off = 100 - (($fsale_price/$forginal_price)*100); ?>
											<div class="sale-off">
												<?php echo '-'.round($sale_off).'%';?>
											</div>
										<?php } ?>			
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
											<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*15 ).'px"></span>' : ''; ?></div>
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
							<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
						<?php $i++; $j++; endwhile; wp_reset_postdata();?>
						</div>
					</div>
				</div>
				<a class="view-all" href="<?php echo esc_url( $viewall ); ?>"><?php echo esc_html__('View all','sw_woocommerce'); ?> <i class="fa fa-angle-double-right"></i></a>
			<?php 
				else :
					echo '<div class="alert alert-warning alert-dismissible" role="alert">
					<a class="close" data-dismiss="alert">&times;</a>
					<p>'. esc_html__( 'There is not product on this tab', 'sw_woocommerce' ) .'</p>
					</div>';
				endif;				
			?>
			</div>
		<!-- End product tab slider -->										
		</div>		
	</div>
</div>