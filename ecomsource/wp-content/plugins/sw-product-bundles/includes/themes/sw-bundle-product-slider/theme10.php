<?php 
/**
	* Layout Bunled product Default
	* @version     1.0.0
**/
$term_name = esc_html__( 'All Categories', 'sw_product_bundles' );
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
	<div id="<?php echo $category.'_'.$id; ?>" class="sw-woo-container-slider responsive-slider bundle-slider-style10 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-circle="false" data-dots="false">
		<div class="resp-slider-container">
			<?php if( $title1 != '' || $title2 != '' ){?>
				<div class="box-title">
					<h4><span class="custom-font"><?php echo ( $title2 != '' ) ? $title2 : ''; ?></span></h4>
					<h3><span class="custom-font"><?php echo ( $title1 != '' ) ? $title1 : ''; ?></span></h3>
				</div>
			<?php } ?>
			<div class="slider responsive">	
			<?php 
				$count_items = 0;
				$count_items = ( $numberposts >= $list->found_posts ) ? $list->found_posts : $numberposts;
				$i = 0;
				while($list->have_posts()): $list->the_post();					
				global $product, $post;
					$terms_id = get_the_terms( $post->ID, 'product_cat' );
					$term_str = '';
					
					foreach( $terms_id as $key => $value ) :
						$term_str .= '<a href="'. get_term_link( $value->term_id, 'product_cat' ) .'">'. esc_html( $value->name ) .'</a>';
					endforeach;
				//var_dump($list);
				$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
				if( $i % $item_row == 0 ){
			?>
				<div class="item-countdown item product product-images <?php echo esc_attr( $class )?>" id="<?php echo 'product_'.$id.$post->ID; ?>" data-vertical="false">
				<?php } ?>
					<div class="item-wrap">
						<div class="item-detail">							
							<div class="item-image-bundle products-thumb-big item-img products-thumb">
							    <?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
								<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</div>
							<!-- end pack thumnail -->	
							<div class="item-content">
								<div class="categories-name">
									<?php echo  $term_str; ?>
								</div>
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
								<?php if ( $price_html = $product->get_price_html() ){ ?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>	
							</div>
						</div>
						<!--- pack thumail -->
						<div class="item-pack">
							<div class="slider" id="product_thumbnail_<?php echo esc_attr( $post->ID ); ?>">											 
								<?php	
									$bundles = apply_filters( 'swpb/load/bundle', $product->get_id() );
									foreach ( $bundles as $key => $value )  { 
									$bundle = wc_get_product( $key ); 

									 $product_url = "";
									if ( get_post_type( $key ) == 'product_variation' ) {
										$product_url = get_the_permalink( wp_get_post_parent_id( $key ) );
									} else {
										$product_url = get_the_permalink( $key );
										$name = get_the_title($key);
									}
								?>
								<div class="item-thumbnail-product">
									<div class="thumbnail-wrapper">
										 <a href="<?php echo esc_url( $product_url ); ?>"><?php echo $bundle->get_image( 'thumbnail', array( 'alt' => $name ) ); ?></a>
									</div>
								</div>
								<?php } ?> 
							</div>	
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