<?php 

/**
* Layout Default
* @version     1.0.0
**/
$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();
$term_name = esc_html__( 'All Categories', 'sw_woocommerce' );
$viewall = get_permalink( wc_get_page_id( 'shop' ) );

$default = array(
	'post_type' => 'product',		
	'post_status' => 'publish',
	'showposts' => $numberposts,
	'meta_key'	=> 'post_views_count',
	'orderby'   => 'meta_value_num'
	);
if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );	
	$term_name = $term->name;
	$viewall = get_term_link( $term->term_id, 'product_cat' );
	$default['tax_query'] = array(
		array(
			'taxonomy'  => 'product_cat',
			'field'     => 'slug',
			'terms'     => $category )
		);	
}
$default = sw_check_product_visiblity( $default );
$list = new WP_Query( $default );

if ( $list -> have_posts() ){ ?>
<div id="<?php echo 'slider_' . $widget_id.rand(); ?>" class="sw-woo-container-slider responsive-slider most-viewed5 <?php echo esc_attr( $style ); ?> loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-dots="true" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<?php if( $title1 != '' ){
		$titles = strpos($title1, ' ');
	?>
	<div class="block-title clearfix">
		<h3><p><?php echo substr( $title1, 0, $titles ) ?></p><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
	</div>
	<?php } ?>         
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
					<div class="item-wrap4">
						<div class="item-detail">										
							<div class="item-img products-thumb">		
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
									<?php 
									$id = get_the_ID();
									if ( has_post_thumbnail() ){
										echo get_the_post_thumbnail( $post->ID, 'shop_thumbnail', array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'shop_thumbnail', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
									}else{
										echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
									}
									?>
								</a>
								<?php echo emarket_quickview(); ?>
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
								<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>
								<!-- price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>
								<div class="item-button">
								<?php woocommerce_template_loop_add_to_cart(); ?>
									<?php
									if ( class_exists( 'YITH_WCWL' ) ){
									echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
									} ?>
									<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ 
									?>
									<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'sw_woocommerce' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','sw-woocomerce'); ?></a>
									<?php } ?>
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
	}
?>
