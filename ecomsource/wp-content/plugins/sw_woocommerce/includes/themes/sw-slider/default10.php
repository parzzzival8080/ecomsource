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
<div id="<?php echo 'slider_' . $widget_id; ?>" class="sw-woo-container-slider responsive-slider woo-slider-style10  clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<?php if( $title1 != '' ){?>
	<div class="box-title clearfix">
		<h3 class="pull-left"><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
		<a class="view-all pull-right" href="<?php echo esc_url( $viewall );?>"><?php echo esc_html__('View more','sw_woocommerce');?></a>
	</div>
	<?php } ?>         
	<div class="resp-slider-container">
		<div class="slider responsive">	
			<?php 
			$count_items 	= 0;
			$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
			$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
			$i 				= 0;
			$j = 0;
			while($list->have_posts()): $list->the_post();global $product, $post;
			$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
			if( $i % 4 == 0 ){
				?>
				<div class="item <?php echo esc_attr( $class )?> product">
					<?php } ?>
					<div class="item-wrap style2">
						<div class="item-detail">										
							<div class="item-img products-thumb">					
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
									<?php 
									$id = get_the_ID();
									if ( has_post_thumbnail() ){
										echo get_the_post_thumbnail( $post->ID, 'shop_single', array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'shop_single', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
									}else{
										echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
									}
									?>
								</a>
								<!-- add to cart, wishlist, compare -->
								<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</div>										
							<div class="item-content">
								<?php
									$userid = (int)$post->post_author;
									$user =  get_userdata( $userid );
									
									if( class_exists( 'WC_Vendors' ) && in_array('vendor', $user->roles) ):
								?>
									<a class="shop-user" href="<?php echo esc_url( WCV_Vendors::get_vendor_shop_page( $user->ID ) ); ?>"><?php echo $user->display_name; ?></a>
									
								<?php elseif( class_exists( 'WeDevs_Dokan' ) && in_array('seller', $user->roles) ):
									$store_url  = dokan_get_store_url( $userid );
								?>
									<a class="shop-user" href="<?php echo esc_url( $store_url ); ?>"><?php echo $user->display_name; ?></a>
									
								<?php elseif( class_exists('WCMp') && in_array('dc_vendor', $user->roles) ):
									global $WCMp;
									$vendor = get_wcmp_vendor( $userid );
								?>
									<a class="shop-user" href="<?php echo esc_url( $vendor->get_permalink() ); ?>"><?php echo $vendor->page_title ?></a>
									
								<?php elseif( class_exists('WCFMmp') && in_array('wcfm_vendor', $user->roles) ):
									global $WCFMmp;
									$store_url       = wcfmmp_get_store_url( $userid );
									$store_name      = wcfm_get_vendor_store_name( $userid );
								?>
									<a class="shop-user" href="<?php echo esc_url( $store_url ); ?>"><?php echo $store_name; ?></a>	
								
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
								<!-- price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
									<div class="item-price">
										<span>
											<?php echo $price_html; ?>
										</span>
									</div>
								<?php } ?>
								<?php 
										$forginal_price = get_post_meta( get_the_ID(), '_regular_price', true );	
										$fsale_price 		= get_post_meta( get_the_ID(), '_sale_price', true );
								?>
								<?php if( $fsale_price > 0){ 
									$sale_off = 100 - (($fsale_price/$forginal_price)*100); ?>
									<div class="sale-off">
										<?php echo '-'.round($sale_off).'% '.esc_html__('Off', 'sw_woocommerce');?>
									</div>
								<?php } ?>	
							</div>									
						</div>
					</div>
					<?php if( ( $i+1 ) % 4 == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
					<?php $i++; endwhile; wp_reset_postdata();?>
				</div>
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
