<?php 

/**
	* Layout Best Sales
	* @version     1.0.0
**/


$viewall = get_permalink( wc_get_page_id( 'shop' ) );	
$term_name = esc_html__( 'All Categories', 'sw_woocommerce' );
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
$id = 'sw_bestsales_'.$this->generateID();
$list = new WP_Query( $default );
if ( $list -> have_posts() ){
?>
	<div id="<?php echo $id; ?>" class="sw-woo-container-slider responsive-slider woo-slider-style7 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-dots="false" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title1 != '' ){ ?>
		<div class="box-title">
			<h3><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></h3>
		</div>
		<?php } ?>
		<div class="resp-slider-container">			
			<div class="slider responsive">	
				<?php 
					$count_items 	= 0;
					$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
					$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
					$i 				= 0;
					while($list->have_posts()): $list->the_post(); global $product, $post;
						
					$terms_id = get_the_terms( $post->ID, 'product_cat' );
					$term_str = '';
					
					foreach( $terms_id as $key => $value ) :
						$term_str .= '<a href="'. get_term_link( $value->term_id, 'product_cat' ) .'">'. esc_html( $value->name ) .'</a>';
					endforeach;
				
					$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
					if( $i % $item_row == 0 ){
					?>
					<div class="item product <?php echo esc_attr( $class )?>">
					<?php } ?>
						<div class="item-wrap12">
							<div class="item-detail">										
								<div class="item-img products-thumb">			
									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
										<?php 
										$id = get_the_ID();
										if ( has_post_thumbnail() ){
											echo get_the_post_thumbnail( $post->ID, 'shop_catalog', array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'shop_catalog', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
										}else{
											echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
										}
										?>
									</a>
									<?php wc_get_template( 'single-product/sale-flash.php' ); ?>
								</div>										
								<div class="item-content">	
									<div class="categories-name">
										<?php echo  $term_str; ?>
									</div>
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
									<div class="sale-price clearfix">
										<?php sw_label_sales();?>
										<!-- price -->
										<?php if ( $price_html = $product->get_price_html() ){?>
										<div class="item-price">
											<span>
												<?php echo $price_html; ?>
											</span>
										</div>
										<?php } ?>
									</div>
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
				<?php $i++; endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
<?php
}	
?>