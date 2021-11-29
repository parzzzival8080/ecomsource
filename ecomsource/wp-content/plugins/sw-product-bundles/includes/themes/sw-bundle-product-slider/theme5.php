<?php 
/**
	* Layout Bunled product Default
	* @version     1.0.0
**/
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
$id = 'sw_countdown_'.$this->generateID();
$list = new WP_Query( $default );
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $category.'_'.$id; ?>" class="sw-woo-container-slider bundle-slider style4 clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false" data-dots="false">
		<div class="resp-slider-container">
			<?php if( $title1 != '' ){?>
			<div class="top-tab-slider clearfix">
				<div class="box-title">
					<h3><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
				</div>
			</div>
			<?php } ?>
			<div class="slider clearfix">	
			<?php 
				$count_items = 0;
				$count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts;
				$i = 0;
				while($list->have_posts()): $list->the_post();					
				global $product, $post;
				//var_dump($list);
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
				if( $i % $item_row == 0 ){
			?>
				<div class="item-countdown item product product-images <?php echo esc_attr( $class )?>" id="<?php echo 'product_'.$id.$post->ID; ?>" data-vertical="false">
				<?php } ?>
					<div class="item-wrap">
						<div class="item-detail">							
							<div class="item-image-bundle products-thumb-big item-img products-thumb">
							    <?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
								<!-- add to cart, wishlist, compare -->
								<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</div>
							<!--- pack thumail -->
						    <div class="item-pack">
								<div class="slider product-responsive-thumbnail" id="product_thumbnail_<?php echo esc_attr( $post->ID ); ?>">											 
									<?php	
									    $bundles = apply_filters( 'swpb/load/bundle', $product->get_id() );
										foreach ( $bundles as $key => $value )  { 
                                        $bundle = wc_get_product( $key ); 
									?>
									<div class="item-thumbnail-product">
										<div class="thumbnail-wrapper">
											<?php echo $bundle->get_image( 'thumbnail' ); ?>
										</div>
									</div>
									<?php } ?> 
								</div>	
							</div>	
							<!-- end pack thumnail -->	
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
								<a class="get-combo" href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php echo esc_html__('Get this Combo','sw_product_bundles'); ?></a>
							</div>
						</div>
					</div>
				<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php }?>
			<?php $i ++; endwhile;?>
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
<?php
	} 
?>