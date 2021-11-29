<?php 

/**
	* Layout Best Sales
	* @version     1.0.0
**/


$term_name = esc_html__( 'Best Sales', 'sw_woocommerce' );
$viewall = get_permalink( wc_get_page_id( 'shop' ) );	
$default = array(
	'post_type' 			=> 'product',		
	'post_status' 			=> 'publish',
    'meta_query'     => array(
		array(
			'key'           => '_sale_price',
			'value'         => 0,
			'compare'       => '>',
			'type'          => 'numeric'
		)
	),
	'ignore_sticky_posts'   => 1,
	'showposts'				=> $numberposts,
	'meta_key' 		 		=> 'total_sales',
	'orderby' 		 		=> 'meta_value_num '. $orderby ,
	'order' => $order,
);
if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );
	if( $term ) :
		$term_name = $term->name;
		$viewall = get_term_link( $term->term_id, 'product_cat' );
	endif;
	
	$default['tax_query'] = array(
		array(
			'taxonomy'	=> 'product_cat',
			'field'	=> 'slug',
			'terms'	=> $category,
			'operator' => 'IN'
		)
	);
}
$id = 'sw_bestsales3_'.$this->generateID();
$list = new WP_Query( $default );
$countdown_time = strtotime( $date );
$today = time();

if ( $list -> have_posts() ){
?>
	<div id="<?php echo $id; ?>" class="sw-woo-container-slider responsive-slider best-selling-product3 clearfix loading" data-rtl="true" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="sw-slide-left">
			<div class="box-title clearfix">
				<h3><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></h3>
				<h2><?php echo ( $description != '' ) ? htmlspecialchars_decode( $description ): ''; ?></h2>
			</div>
			<div class="item-countdown-wrap">
				<h4><?php echo esc_html__('Hurry Up! Offer End In:','sw_woocommerce');?></h4>
			<?php if( $countdown_time > $today  ): ?> <div class="item-countdown" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>"></div><?php else: ?><span class="message-cd"><?php echo esc_html__('Please select a time for layout.','sw_woocommerce'); ?></span><?php endif; ?>
			</div>
		</div>
		<div class="resp-slider-container">			
			<div class="slider responsive">	
			<?php 
				$count_items 	= 0;
				$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
				$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
				$i 				= 0;
				while($list->have_posts()): $list->the_post(); global $product, $post;
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
				if( $i % $item_row == 0 ){
			?>
				<div class="item product <?php echo esc_attr( $class )?>">
			<?php } ?>
				<div class="item-wrap6">
					<div class="item-detail">										
						<div class="item-img products-thumb">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php 
								$id = get_the_ID();
								if ( has_post_thumbnail() ){
									echo get_the_post_thumbnail( $post->ID, 'shop_catalog',array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'shop_catalog', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
								}else{
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
								}
								?>
							</a>
							<?php wc_get_template( 'single-product/sale-flash.php' );?>
							<?php echo emarket_quickview(); ?>
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
							<?php 
							if( $product->get_stock_quantity() != 0 ){
							$available 	 = $product->get_stock_quantity();
							$total_sales = get_post_meta( $post->ID, 'total_sales', true );
							$bar_width 	 = intval( $available ) / intval( $available + $total_sales ) * 100;
							?>
							<div class="emarket-stock clearfix">
								<div class="stock-avail pull-left"><?php esc_html_e( 'Available: ', 'sw_woocommerce' ) ?><span><?php echo $available; ?></span></div>						</div>
							<div class="sales-bar clearfix">
								<div class="sales-bar-total">
									<span style="width: <?php echo esc_attr( $bar_width . '%' ); ?>"></span>
								</div>
							</div>
							<?php } ?>
							<!-- add to cart, wishlist, compare -->
							<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
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