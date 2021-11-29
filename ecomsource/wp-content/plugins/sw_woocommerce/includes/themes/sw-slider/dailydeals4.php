<?php ; 
wp_reset_postdata();
$viewall = get_permalink( wc_get_page_id( 'shop' ) );	
$default = array(
	'post_type'		=> 'product',		
	'post_status' 	=> 'publish',
	'no_found_rows' => 1,					
	'showposts' 	=> $numberposts	,
	'orderby' 				=> $orderby,
	'order' 				=> $order,
    'meta_query'     => array(
		array(
			'key'           => '_sale_price',
			'value'         => 0,
			'compare'       => '>',
			'type'          => 'numeric'
		)
	)		
);
if( $category != '' ){	
	$default['tax_query'] = array(
		array(
			'taxonomy'	=> 'product_cat',
			'field'		=> 'slug',
			'terms'		=> $category,
		)
	);
}
$default = sw_check_product_visiblity( $default );

$term_name = '';
$term = get_term_by( 'slug', $category, 'product_cat' );
if( $term ) :
	$term_name = $term->name;
	$viewall = get_term_link( $term->term_id, 'product_cat' );
endif;
$id = 'sw_toprated_'.rand().time();
$list = new WP_Query( $default );
$countdown_time = strtotime( $date );
$today = time();

if ( $list -> have_posts() ){
?>
	<div id="<?php echo $id; ?>" class="sw-woo-container-slider  responsive-slider dailydeals-product4 clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-dots="false" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="resp-slider-container">
			<div class="box-slider-top">
				<?php if( $title1 != '' ){
					$titles = strpos($title1, ' ');
				?>
				<div class="block-title clearfix">
					<h3><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
					<div class="description1"><?php echo esc_html__('Sale up to','sw_woocommerce')?><br><span><?php echo ( $title2 != '' ) ? $title2 : $term_name; ?></span></div>
				</div>
				<?php } ?>
				<div class="wrap-link clearfix">
					<div class="description"><?php echo ( $description != '' ) ? $description : ''; ?></div>
					<?php if( $countdown_time > $today  ): ?><div class="item-countdown2" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>"></div><?php else: ?><span class="message-cd"><?php echo esc_html__('Please select a time for layout.','sw_woocommerce'); ?></span><?php endif; ?>
				</div>	
			</div>
			<div class="slider responsive">			
			<?php
					$i = 1;
					$count_items 	= 0;
					$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
					$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
					$i 				= 0;
					while($list->have_posts()): $list->the_post();global $product, $post;
					if( $i % $item_row == 0 ){
				?>
				<div class="item product">
				<?php } ?>
					<?php include( WCTHEME . '/default-item7.php' ); ?>
				<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
			    <?php $i++; endwhile; wp_reset_postdata();?>
			</div>
		</div>					
	</div>
<?php
}	
?>