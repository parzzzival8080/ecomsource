<?php 
/**
	* Layout Countdown Default
	* @version     1.0.0
**/

$term_name = esc_html__( 'All Categories', 'sw_woocommerce' );
$paged 			= (get_query_var('paged')) ? get_query_var('paged') : 1;
// Default ordering args
$ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $order ); 
	if ( isset( $_GET['orderby'] ) ) {
		$getorderby =  $_GET['orderby'];
	}else{
		$getorderby = $orderby;
	}
	if ($getorderby == 'date') {
		$orderby = 'date';
		$order = 'desc';
	}
	elseif ($getorderby == 'price') {
		$orderby = 'meta_value_num';
		$meta_key  = '_price';
		$order = 'asc';
	}
	 elseif ($getorderby == 'price-desc') {
		$orderby = 'meta_value_num';
		$meta_key  = '_price';
		$order = 'desc';
	}
	elseif ($getorderby == '_discount_amount') {
		$meta_key = '_discount_amount';
		$orderby = 'meta_value_num';
		$order = 'desc';
	}
	elseif ($getorderby == 'best-sale') {
		$orderby = 'meta_value_num';
		$meta_key  = 'total_sales';
		$order = 'desc';
	}
	elseif ($getorderby == 'most-viewed') {
		$orderby = 'meta_value_num';
		$meta_key  = 'post_views_count';
		$order = 'desc';
	}

$default = array(
	'post_type' => 'product',	
	'meta_query' => array(		
		array(
			'key' => '_sale_price',
			'value' => 0,
			'compare' => '>',
			'type' => 'DECIMAL(10,5)'
		),
		array(
			'key' => '_sale_price_dates_from',
			'value' => time(),
			'compare' => '<',
			'type' => 'NUMERIC'
		),
		array(
			'key' => '_sale_price_dates_to',
			'value' => time(),
			'compare' => '>',
			'type' => 'NUMERIC'
		)
	),
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts,
	'paged' => $paged
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
			'terms'     => $category ));
}

if ( isset( $ordering_args['meta_key'] ) ) { 
	$default['meta_key'] = $ordering_args['meta_key']; 
}
					
$default = sw_check_product_visiblity( $default );

$id = 'sw_countdown_'.$this->generateID();
$list = new WP_Query( $default );

if ( $list -> have_posts() ){ $count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts; ?>
	
	<div id="<?php echo $category.'_'.$id; ?>" class="sw-woo-container-slider countdown-slider-style5 clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false">       
		<div class="top-content clearfix">
			<div class="show-number pull-left"><?php echo esc_html__('Showing to 1 - ','sw_woocommerce'); ?><?php echo $count_items; ?><?php echo esc_html__(' of ','sw_woocommerce');?><?php echo $list->found_posts; ?><?php echo esc_html__(' results','sw_woocommerce');?></div>
			<form action="" class="woocommerce-ordering pull-right" method="get">
				<div class="wrap-form clearfix">
					<span class="title"><?php echo esc_html__('Sort by','sw_woocommerce');?></span>
					<select name="orderby" class="orderby">
						<?php
							$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
								'date'      => __( 'Latest', 'sw_woocommerce' ),
								'price'     => __( 'Price: low to high', 'sw_woocommerce' ),
								'price-desc'     => __( 'Price Desc: high to low', 'sw_woocommerce' ),
								'_discount_amount'     => __( 'Discount: high to low', 'sw_woocommerce' ),
								'best-sale'     => __( 'Best Saler: hight to low', 'sw_woocommerce' ),
								'most-viewed'     => __( 'Most Viewed: hight to low', 'sw_woocommerce' ),
							) );

							foreach ( $catalog_orderby as $id => $name )
								echo '<option value="' . esc_attr( $id ) . '" ' . selected( $getorderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
						?>
					</select>
					<?php
						// Keep query string vars intact
						foreach ( $_GET as $key => $val ) {
							if ( 'orderby' === $key || 'submit' === $key )
								continue;

							if ( is_array( $val ) ) {
								foreach( $val as $innerVal ) {
									echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
								}

							} else {
								echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
							}
						}
					?>
				</div>
			</form>
		</div>
		<div class="resp-slider-container">
			<?php if( $title1 != '' ){?>
				<div class="box-title">
					<h3><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
				</div>
			<?php } ?>
			<div class="slider">	
			<?php 
				$count_items = 0;
				$count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts;
				$i = 0;
				while($list->have_posts()): $list->the_post();					
				global $product, $post;
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
				$start_time 	= get_post_meta( $post->ID, '_sale_price_dates_from', true );
				$countdown_date = get_post_meta( $post->ID, '_sale_price_dates_to', true );
				$discount = get_post_meta( $product->get_id(), '_discount_amount', true );
				if( $i % $item_row == 0 ){
			?>
				<div class="item-countdown product <?php echo esc_attr( $class )?>" id="<?php echo 'product_'.$id.$post->ID; ?>">
				<?php } ?>
					<div class="item-wrap">
						<div class="item-detail">
							<div class="item-image-countdown">
								<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>	
								<div class="item-bottom clearfix">
									<?php echo emarket_quickview() ;?>
									<?php
									if ( class_exists( 'YITH_WCWL' ) ){
										echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
									} ?>
									<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ ?>
									<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'sw_woocommerce' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','sw-woocomerce'); ?></a>
									<?php } ?>
								</div>			
							</div>
							<div class="item-content">
								<?php 
									if( $product->get_stock_quantity() != 0 ){ 
									$available 	 = $product->get_stock_quantity();
									$total_sales = get_post_meta( $post->ID, 'total_sales', true );
									$bar_width 	 = intval( $available ) / intval( $available + $total_sales ) * 100;
								?>
								<div class="emarket-stock clearfix">
									<div class="stock-avail"><?php esc_html_e( 'Number sale: ', 'sw_woocommerce' ) ?><span><span><?php echo $total_sales; ?>/<?php echo $available; ?></span></div>
								</div>
								<div class="sales-bar clearfix">
									<div class="sales-bar-total">
										<span style="width: <?php echo esc_attr( $bar_width . '%' ); ?>"></span>
									</div>
								</div>
								<?php } ?>
								
								<?php if( $countdown_date ) : ?>
								<div class="product-countdown" data-date="<?php echo esc_attr( sw_timezone_offset( $countdown_date ) ); ?>"  data-starttime="<?php echo esc_attr( $start_time ); ?>"></div>		
								<?php endif; ?>
								
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
								<!-- Price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
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