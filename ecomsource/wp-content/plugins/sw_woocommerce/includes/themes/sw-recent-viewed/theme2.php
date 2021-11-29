<?php 
	$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();
	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
	$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
	if ( empty( $viewed_products ) ) {
		return;
	}

	$query_args = array(
		'posts_per_page' => $numberposts,
		'no_found_rows'  => 1,
		'post_status'    => 'publish',
		'post_type'      => 'product',
		'post__in'       => $viewed_products,
		'orderby'        => 'post__in',
	);

	if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'outofstock',
				'operator' => 'NOT IN',
			),
		); // WPCS: slow query ok.
	}
	$nav_id = 'nav_tabs_res'.rand().time();
	$list = new WP_Query( apply_filters( 'woocommerce_recently_viewed_products_widget_query_args', $query_args ) );

	if ( $list->have_posts() ) {
?>
	<div id="<?php echo esc_attr( 'slider_' . $widget_id ); ?>" class="sw-tab-recent-viewed-slider clearfix" style="margin-bottom: 40px;">
		<div class="resp-tab" style="position:relative;">
		<?php if( $title1 != '' ){?>
			<div class="box-title">
				<h3><?php echo ( $title1 != '' ) ? $title1 : $term_name; ?></h3>
			</div>
		<?php } ?>   
		<div class="top-tab-slider clearfix">
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr($nav_id); ?>"  aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="fa fa-bar"></span>
				<span class="fa fa-bar"></span>
				<span class="fa fa-bar"></span>
			</button>
			<ul class="nav nav-tabs" id="<?php echo esc_attr( $nav_id ); ?>">
				<?php 
				$i = 1;
				while($list->have_posts()): $list->the_post();global $product, $post;
					global $product, $post;
					$ids = $product->get_id();
						?>
						<li class="<?php if( $i == 1 ){ echo 'active'; }?>">
							<a href="#<?php echo esc_attr( $ids. '_' .$widget_id ); ?>" data-toggle="tab">
									<?php 
									$id = get_the_ID();
									if ( has_post_thumbnail() ){
										echo get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'thumbnail'.'.png" alt="No thumb">';		
									}else{
										echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'thumbnail'.'.png" alt="No thumb">';
									}
									?>
									<span class="item-content">
										<span class="title"><?php sw_trim_words( get_the_title(), $title_length ); ?></span>
									<?php if ( $price_html = $product->get_price_html() ){?>
										<span class="price">
											<?php echo $price_html; ?>
										</span>
									<?php } ?>
									</span>
							</a>
						</li>	
					<?php $i ++; ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
		</div>
		<div class="tab-content">
			<?php
				$i    =  0;
				while($list->have_posts()): $list->the_post();	
				global $product, $post;
				$ids = $product->get_id();
				
				$related = array();
				if( function_exists( 'wc_get_related_products' ) ){
					$related = wc_get_related_products( $product->get_id(), $numberposts );
				}else{
					$related = $product->get_related($numberposts);
				}
				
				if ( sizeof( $related ) == 0 ) return;
				$args = apply_filters( 'woocommerce_related_products_args', array(
					'post_type'            => 'product',
					'ignore_sticky_posts'  => 1,
					'no_found_rows'        => 1,
					'posts_per_page'       => $numberposts,
					'post__in'             => $related,
					'post__not_in'         => array( $product->get_id() )
				) );
				
				$args = sw_check_product_visiblity( $args );
				$list2 = new WP_Query( $args );
			?>
			<div class="tab-pane <?php if( $i == 0 ){echo 'active'; }?>" id="<?php echo  esc_attr( $ids.'_'.$widget_id ); ?>">
					<?php if ( $list2->have_posts() ) { ?>
					<div id="<?php echo esc_attr( 'tab_cat_'.$ids.'_' .$widget_id ); ?>" class="woo-tab-container-slider responsive-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
						<div class="resp-slider-container">
								<div class="slider responsive">	
									<?php
										
										$count_items 	= 0;
										$numb 			= ( $list2->found_posts > 0 ) ? $list2->found_posts : count( $list2->posts );
										$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
										$j 				= 0;
										while($list2->have_posts()): $list2->the_post();global $product, $post;
										$class = ( $product->get_price_html() ) ? '' : 'item-nonprice';
										if( $j % $item_row == 0 ){
									?>
										<div class="item product <?php echo esc_attr( $class )?>">
									<?php } ?>
										<?php include( WCTHEME . '/default-item8.php' ); ?>
										<?php if( ( $j+1 ) % $item_row == 0 || ( $j+1 ) == $count_items ){?> </div><?php } ?>
									<?php $j++; endwhile; wp_reset_postdata();?>
								</div>
							</div>
						</div>
					<?php } ?>
					</div>
				<?php $i++; endwhile; wp_reset_postdata();?>
			</div>
		</div>
	</div>
	<?php
}