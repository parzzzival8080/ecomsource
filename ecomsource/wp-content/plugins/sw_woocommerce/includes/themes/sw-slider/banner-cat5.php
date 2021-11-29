<?php 

/**
	* Layout Child Category
	* @version     1.0.0
**/
if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Please select a category for SW Woo Slider. Layout ', 'sw_woocommerce' ).'</p>
	</div>';
}

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
		'ignore_sticky_posts'   => 1,
		'meta_key' 		 		=> 'total_sales',
		'orderby' 		 		=> 'meta_value_num '. $orderby ,
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

$list = new WP_Query( $default );
if ( $list -> have_posts() ){
	$count_items 	= 0;
	$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
	$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
	$slider_box = ( $count_items <= $columns ) ? '' : 'slider-box';
?>
	<div id="<?php echo 'slider_' . $widget_id.rand(); ?>" class="sw-banner-cat-product5 responsive-slider <?php echo esc_attr( $style ); ?> loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-dots="false" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		
			<!-- Product tab listing -->
			<div class="resp-slider-container <?php echo esc_attr( $slider_box )?>">
				<div class="box-title">
					<h3><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
					<a class="view-all" href="<?php echo esc_url( $viewall );?>"><?php echo esc_html__('View all','sw_woocommerce');?></a>
				</div>
				<div class="slider responsive">	
				<?php 

					$i 				= 0;
					while($list->have_posts()): $list->the_post();global $product, $post;
					$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
					
					$terms_id = get_the_terms( $post->ID, 'product_cat' );
					$term_str = '';
					
					foreach( $terms_id as $key => $value ) :
						$term_str .= '<a href="'. get_term_link( $value->term_id, 'product_cat' ) .'">'. esc_html( $value->name ) .'</a>';
					endforeach;
			
					if( $i % $item_row == 0 ){
					?>
					<div class="item <?php echo esc_attr( $class )?> product">
						<?php } ?>
						<?php include( WCTHEME . '/default-item9.php' ); ?>
						<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
						<?php $i++; endwhile; wp_reset_postdata();?>
				</div>
			</div>
			<?php 
			$banner_links = explode( ',', $banner_links );
			if( $image != '' ) :
				$image = explode( ',', $image );	
			?>

			<div class="banner-category">
				<div id="<?php echo esc_attr( 'banner_' . $widget_id ); ?>" class="banner-slider" data-lg="1" data-md="1" data-sm="1" data-xs="1" data-mobile="1" data-arrow="false" data-fade="false">
					<div class="banner-responsive">
						<?php foreach( $image as $key => $img ) : ?>
							<div class="item">
								<a href="<?php echo esc_url( isset( $banner_links[$key] ) ? $banner_links[$key] : '#' ); ?>"><?php echo wp_get_attachment_image( $img, 'large' ); ?></a>
							</div>
						<?php endforeach;?>
					</div>
				</div>									
			</div>
			<?php else:
				echo esc_html__('Please enter the banner image ID for layout.','sw_woocommerce');
			 endif;?>
		
	</div>
	<?php
	}else{
		echo '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'There is not product in this category', 'sw_woocommerce' ) .'</p>
	</div>';
	}
?>