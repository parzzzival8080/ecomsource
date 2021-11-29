<?php 

/**
	* Layout Top Rated
	* @version     1.0.0
**/


$term_name = esc_html__( 'Top Rated Products', 'sw_woocommerce' );
$default = array(
	'post_type'		=> 'product',		
	'post_status' 	=> 'publish',
	'no_found_rows' => 1,					
	'showposts' 	=> $numberposts						
);
if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );	
	if( $term ) :
		$term_name = $term->name;
	endif;
	
	$default['tax_query'] = array(
		array(
			'taxonomy'	=> 'product_cat',
			'field'		=> 'slug',
			'terms'		=> $category,
			'operator' 	=> 'IN'
		)
	);
}
$default = sw_check_product_visiblity( $default );

if( sw_woocommerce_version_check( '3.0' ) ){	
	$default['meta_key'] = '_wc_average_rating';	
	$default['orderby'] = 'meta_value_num';
}else{	
	add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
}

$id = 'sw_toprated_'.$this->generateID();
$list = new WP_Query( $default );

if ( $list -> have_posts() ){
?>
	<div id="<?php echo $id; ?>" class="sw-woo-container-slider responsive-slider toprated-product2 clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-dots="true" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title1 != '' ){?>
		<div class="box-title">
			<h3 class="custom-font"><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
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
				<div class="item product <?php echo esc_attr( $class )?>">
			<?php } ?>
					<div class="item-wrap9">
						<div class="item-detail">
							<div class="item-img products-thumb">
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
									<?php 
									$id = get_the_ID();
									if ( has_post_thumbnail() ){
										echo get_the_post_thumbnail( $post->ID, 'large', array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'large', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
									}else{
										echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
									}
									?>
								</a>
								<!-- add to cart, wishlist, compare -->
								<div class="item-button">
									<div class="wrap">
									<?php
										if ( class_exists( 'YITH_WCWL' ) ){
										echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
										} ?>
										<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ 
										?>
										<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'sw_woocommerce' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','sw-woocomerce'); ?></a>
										<?php } ?>
										<?php echo emarket_quickview(); ?>
									</div>
								</div>
							</div>
							<div class="item-content">
								<?php do_action('sw_author_listing_product'); ?>
								<h4 class="custom-font"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>">
										<?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>
								<!-- price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price custom-font">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>
								<?php woocommerce_template_loop_add_to_cart(); ?>
							</div>
						</div>
					</div>
			<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
			
			<?php 
				$i++; endwhile;
				remove_filter( 'posts_clauses',  array( $this, 'order_by_rating_post_clauses' ) );
				wp_reset_postdata();
			?>
			</div>
		</div>					
	</div>
<?php
}	else{
		echo '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'There is not product in this category', 'sw_woocommerce' ) .'</p>
	</div>';
	}
?>