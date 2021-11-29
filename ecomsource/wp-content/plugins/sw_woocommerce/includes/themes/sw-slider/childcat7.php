<?php 

/**
	* Layout Child Category
	* @version     1.0.0
**/
if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Please select a category for SW Woo Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
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

$list = new WP_Query( $default );
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo 'slider_' . $widget_id; ?>" class="responsive sw-child-cat8 <?php echo ( $image != '' ? '' :'no-banner' );?> clearfix">
		<div class="child-top clearfix">
			<div class="box-title pull-left">
				<h3><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></h3>
			</div>
			<?php 
			if( $term ) :
				$termchild 		= get_terms( 'product_cat', array( 'parent' => $term->term_id, 'hide_empty' => 0, 'number' => $number_child ) );
			if( count( $termchild ) > 0 ){
			?>		
			<div class="childcat-content pull-right"  id="<?php echo 'child_' . $widget_id; ?>">
				<?php 					
					echo '<ul>';
					echo '<li>' .esc_html__('Top ', 'sw_woocommerce') . $numberposts. '</li>';
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
		<div class="childcat-slider-content clearfix">
			<!-- Product tab listing -->
			<?php 
			$banner_links = explode( ',', $banner_links );
			if( $image != '' ) :
				$image = explode( ',', $image );	
			?>

			<div class="banner-category pull-left">
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
				//echo esc_html__('Please enter the banner image ID for layout.','sw_woocommerce');
			 endif;?>
			<div class="resp-slider-container">
				<div class="slider clearfix">	
				<?php 
					$count_items 	= 0;
					$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
					$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
					$i 				= 0;
					while($list->have_posts()): $list->the_post();global $product, $post;
					$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
				?>
					<div class="item <?php echo esc_attr( $class )?> product">
						<div class="item-wrap">
							<div class="item-detail">										
								<div class="item-img products-thumb">			
									<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
									<!-- add to cart, wishlist, compare -->
									<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
								</div>										
								<div class="item-content">
									<?php if ( $price_html = $product->get_price_html() ){?>
										<div class="item-price">
											<span>
												<?php echo $price_html; ?>
											</span>
										</div>
									<?php } ?>	
									<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>								
									<!-- rating  -->
									<?php 
									$rating_count = $product->get_rating_count();
									$review_count = $product->get_review_count();
									$average      = $product->get_average_rating();
									?>
									<?php if (  wc_review_ratings_enabled() ) { ?>
									<div class="reviews-content">
										<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*12 ).'px"></span>' : ''; ?></div><div class="rating-count"><?php echo $rating_count; ?></div>
									</div>	
									<?php } ?>
									<!-- end rating  -->
								</div>								
							</div>
						</div>
					 </div>
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