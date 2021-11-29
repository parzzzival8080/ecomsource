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
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts
	);
if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );	
	if( $term ) :
		$term_name = $term->name;
		$viewall = get_term_link( $term->term_id, 'product_cat' );
	endif;

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
<div id="<?php echo 'slider_' . $widget_id; ?>" class="sw-woo-container-slider responsive-slider woo-slider-style4 clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<?php if( $title1 != '' ){?>
	<div class="box-title">
		<h4><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h4>
		<h3><span><?php echo ( $title2 != '' ) ? $title2 : ''; ?></span></h3>
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
			$terms_id = get_the_terms( $post->ID, 'product_cat' );

			$term_str = '';
			
			foreach( $terms_id as $key => $value ) :
				$term_str .= '<a href="'. get_term_link( $value->term_id, 'product_cat' ) .'">'. esc_html( $value->name ) .'</a>';
			endforeach;
			
			$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
			if( $i % $item_row == 0 ){
				?>
				<div class="item <?php echo esc_attr( $class )?> product">
					<?php } ?>
					<div class="item-wrap style1">
						<div class="item-detail">										
							<div class="item-img products-thumb">			
								<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
								<!-- add to cart, wishlist, compare -->
								<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</div>										
							<div class="item-content">
								<div class="categories-name">
									<?php echo  $term_str; ?>
								</div>
								<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>								
								<!-- price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
									<div class="item-price">
										<span>
											<?php echo $price_html; ?>
										</span>
									</div>
								<?php } ?>	
							</div>								
						</div>
					</div>
					<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
					<?php $i++; endwhile; wp_reset_postdata();?>
				</div>
				<a class="view-all" href="<?php echo esc_url( $viewall ); ?>"><?php echo esc_html__('View All Products','sw_woocommerce'); ?></a>
			</div>            
		</div>
		<?php
	}else{
		echo '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Has no product in this category', 'sw_woocommerce' ) .'</p>
	</div>';
}
?>
