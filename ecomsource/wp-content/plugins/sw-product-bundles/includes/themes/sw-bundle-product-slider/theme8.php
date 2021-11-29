<?php 
/**
	* Layout Bunled product Default
	* @version     1.0.0
**/
$term_name = esc_html__( 'All Categories', 'sw_product_bundles' );
$viewall = get_permalink( wc_get_page_id( 'shop' ) );
$default = array(
	'post_type' => 'product',	
	'orderby' => $orderby,
	'order' => $order,
	'post_status' => 'publish',
	'showposts' => $numberposts	
);
$default['tax_query'][] = array(						
		'taxonomy' => 'product_type',
		'field'    => 'name',
		'terms'    => 'bundle',
		'operator' => 'IN',	
	);
//var_dump($category);
if( $category != '' ){
	$term = get_term_by( 'slug', $category, 'product_cat' );
	if( $term ) :
		$term_name = $term->name;
		$viewall = get_term_link( $term->term_id, 'product_cat' );
	endif; 
	
	$default['tax_query'][] = array(
		array(
			'taxonomy'  => 'product_cat',
			'field'     => 'slug',
			'terms'     => $category ));
}
$id = 'sw_countdown_'.$this->generateID();
$list = new WP_Query( $default );
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $category.'_'.$id; ?>" class="sw-woo-container-slider responsive-slider bundle-slider-style8 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false" data-dots="false">
		<?php if( $title1 != '' ){?>
		<div class="box-title">
			<h3><span><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></span></h3>
			<a class="view-all" href="<?php echo esc_url( $viewall ); ?>"><?php echo esc_html__('View all','sw_product_bundles'); ?></a>
		</div>
		<?php } ?>
		<div class="resp-slider-container">
			<div class="slider responsive">	
			<?php 
				$count_items = 0;
				$count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts;
				$i = 0;
				while($list->have_posts()): $list->the_post();					
				global $product, $post;
				//var_dump($list);
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
				if( $i % $item_row == 0 ){
			?>
				<div class="item product product-images <?php echo esc_attr( $class )?>" id="<?php echo 'product_'.$id.$post->ID; ?>" data-vertical="false">
				<?php } ?>
					<div class="item-wrap">
						<div class="item-detail">							
							<div class="item-image-bundle item-img products-thumb">
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
								<!-- add to cart, wishlist, compare -->
								<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</div>
							<div class="item-content">
								<!-- end rating  -->
								<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>																
								<!-- price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>
								<?php
										$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_type() : $product->product_type;
										echo sw_label_new();
										if( $product_type != 'variable' ) {
											$forginal_price 	= get_post_meta( $post->ID, '_regular_price', true );	
											$fsale_price 		= get_post_meta( $post->ID, '_sale_price', true );
											if( $fsale_price > 0 && $product->is_on_sale() ){ 
												$sale_off = 100 - ( ( $fsale_price/$forginal_price ) * 100 ); 
												$html = '<div class="sale-off2 ' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
												$html .= ''.round( $sale_off ).'% '. esc_html__('off','sw_product_bundles');
												$html .= '</div>';
												echo apply_filters( 'sw_label_sales', $html );
											} 
										}else{
											echo '<div class="' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
											wc_get_template( 'single-product/sale-flash.php' );
											echo '</div>';
										}
								?>
								<div class="text-bundle"><?php echo esc_html__('Bundle Includes:','sw_product_bundles'); ?></div>
							</div>
							<!--- pack thumail -->
						   <div class="product-thumbnail responsive-slider" id="product_thumbnail_<?php echo esc_attr( $post->ID ); ?>" data-lg="3" data-md="3" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="3" data-mobile="2" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="false" data-circle="false" data-dots="false" data-vertical="false">	
							<?php  $bundles = apply_filters( 'swpb/load/bundle', $product->get_id() );?>
							<div class="slider responsive">	
								<?php	
									$bundles = apply_filters( 'swpb/load/bundle', $product->get_id() );
									foreach ( $bundles as $key => $value )  { 
									$bundle = wc_get_product( $key ); 
									$product_url = "";
									if ( get_post_type( $key ) == 'product_variation' ) {
										$product_url = get_the_permalink( wp_get_post_parent_id( $key ) );
									} else {
										$product_url = get_the_permalink( $key );
									}

								?>
								<div class="item-thumbnail-product">
									<div class="thumbnail-wrapper">
										<a href="<?php echo esc_url( $product_url ); ?>"><?php echo $bundle->get_image( 'thumbnail', array( 'alt' => get_the_title() ) );	?></a>
									</div>
								</div>
								<?php } ?>
							</div>		
						</div>	
							<!-- end pack thumnail -->	
						</div>
					</div>
				<?php if( ( $i+1 ) % $item_row == 0 || ( $i+1 ) == $count_items ){?> </div><?php }?>
			<?php $i ++; endwhile; wp_reset_postdata();?>
			</div>
		</div>            
	</div>
<?php
	} 
?>