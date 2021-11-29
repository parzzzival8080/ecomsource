<?php 

/**
	* Layout Child Category
	* @version     1.0.0
**/

$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();
$viewall = get_permalink( wc_get_page_id( 'shop' ) );
$default = array();
if( $category != '' ){
	$default = array(
		'post_type' => 'product',
		'tax_query' => array(
		array(
			'taxonomy'  => 'product_cat',
			'field'     => 'slug',
			'terms'     => $category ) ),
		'orderby' => $orderby,
		'order' => $order,
		'post_status' => 'publish',
		'showposts' => $numberposts
	);
}else{
	$default = array(
		'post_type' => 'product',
		'orderby' => $orderby,
		'order' => $order,
		'post_status' => 'publish',
		'showposts' => $numberposts
	);
}

$term_name = '';
$term = get_term_by( 'slug', $category, 'product_cat' );
if( $term ) :
	$term_name = $term->name;
	$viewall = get_term_link( $term->term_id, 'product_cat' );
endif;
$nav_id = 'child_'.rand().time();
$list = new WP_Query( $default );
if ( $list -> have_posts() ){
	$x_array = array();
		$key = 0;
		foreach( $list->posts as $item ){
			$m_array = array();
			$terms = get_the_terms( $item->ID, 'product_brand' );
			if( $terms && taxonomy_exists( 'product_brand' ) ){
				foreach( $terms as $x => $bterm ){
					$m_array[$x] = $bterm -> term_id;
				}
				$x_array[$key] = implode( ',', $m_array );
				$key ++; 
			}		
		}
		$z_array = implode( ',', $x_array );
?>
	<div id="<?php echo 'slider_' . $widget_id.rand(); ?>" class="responsive-slider sw-top-brand-product" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="childcat-slider-content clearfix">
			<!-- Product tab listing -->
			<?php 
			$banner_links = explode( ',', $banner_links );
			if( $image != '' ) :
				$image = explode( ',', $image );	
			endif;
			?>
			<div class="wrap-banner">
				<!-- Brand -->
				<?php if( count( $x_array ) > 0 ) : ?>
				<div id="<?php echo esc_attr( 'brand_' . $widget_id ); ?>" class="brand-categories" data-lg="1" data-md="1" data-sm="1" data-xs="1" data-mobile="1" data-arrow="false" data-fade="false">
					<div class="slider">
							<?php 
								$x_array = array_unique( explode( ',', $z_array ) );
								foreach( $x_array as $key => $cat ) {
								if( $key > 0 ){
									break;
								}
									$thumbnail_id 	= absint( get_term_meta( intval( $cat ), 'thumbnail_bid', true ) );
									$thumb = wp_get_attachment_image( $thumbnail_id, 'medium' );
									$thubnail = ( $thumb != '' ) ? $thumb : '<img src="http://placehold.it/170x90" alt=""/>';
							?>
							<div class="item-brand">
								<a href="<?php echo get_term_link( intval( $cat ), 'product_brand' ); ?>"><?php echo $thubnail ?></a>
							</div>
							<?php
								}
							?>
						</div>
					</div>
				<?php endif; ?>	
			</div>
			<div class="box-title">
				<h3><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></h3>
			</div>
			<div class="resp-slider-container">
				<div class="slider">	
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
						<div class="item-wrap">
							<div class="item-detail">										
								<div class="item-img products-thumb">			
									<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
								</div>																	
							</div>
						</div>
					<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
					<?php $i++; endwhile; wp_reset_postdata();?>
				</div>
			</div> 
		</div>
	</div>
	<?php
	}else{
		echo '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'There is not product in this category', 'sw_woocommerce' ) .'</p>
	</div>';
	}
?>