<?php  		
	if( !class_exists( 'WeDevs_Dokan' ) ){
		return;
	}
	
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_dokan_vendor_'.$this->generateID();
	
	if( $category ){
		if( !is_array( $category ) ){
		$category = explode( ',', $category );
	}
?>
	<div id="<?php echo esc_attr( $widget_id ) ?>" class="responsive-slider sw-vendor-container-slider <?php echo esc_attr( $style );?> loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title1 != '') { ?>
			<div class="title-home"><h2><?php echo esc_html( $title1 ); ?></h2></div>
		<?php } ?>
		<div class="resp-slider-container">
			<div class="slider responsive">
				<?php 
					foreach( $category as $j => $userid ){ 
						$user = get_userdata( $userid );
						if( $user ) {
							$store_info = dokan_get_store_info( $userid );
							$count = count_user_posts( $userid, 'product');
							$store_name = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'sw_vendor_slider' );
							$store_url  = dokan_get_store_url( $userid );
							$seller_rating  = dokan_get_seller_rating( $userid );
				?>
				<?php	if( ( $j % $item_row ) == 0 ) { ?>
					<div class="item item-vendor">
				<?php } ?>
					<?php 
						$default = array(
							'post_type' 			=> 'product',		
							'post_status' 			=> 'publish',
							'ignore_sticky_posts'   => 1,
							'showposts'				=> 3,
							'meta_key' 		 		=> 'total_sales',
							'orderby' 		 		=> 'meta_value_num',
							'author' => $user->ID,
						);
						$list = new WP_Query( $default );
						if( $list->have_posts() ) {
					?>
						<div class="item-product"> 
							<?php 
								$key = 0;
								$count_items = 0;
								$count_items = ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
								while( $list->have_posts() ) : $list->the_post();
								global $product, $post;
								$class = ( $key % 3 == 0 ) ? 'item-large' : '';
								if( $key % 3 == 0 ){
							?>
								<div class="item-product-content <?php echo esc_attr( $class ); ?>">
							<?php } ?>
								<?php echo ( $key % 3 == 1 ) ? '<div class="wrap-small-item">' : ''; ?>
									<div class="item-img">
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo ( $class ) ? get_the_post_thumbnail( $product->get_id(), 'shop_catalog', array( 'alt' => $post->post_title ) ) : get_the_post_thumbnail( $product->get_id(), 'shop_thumbnail', array( 'alt' => $post->post_title ) ); ?></a>
									</div>
								<?php echo ( ( $key % 3 == 2 || ( $key+1 ) == $count_items ) && $key >= 1 )? '</div>' : ''; ?>
								<?php if( ( $key+1 ) % 3 == 0 || ( $key+1 ) == $count_items ){?> </div><?php } ?>								
							<?php $key++; endwhile; wp_reset_postdata(); ?>
						</div>
					<?php } ?>
						<div class="item-bottom">
							<div class="item-user">
								<div class="item-user-img clearfix">
									<a href="<?php echo esc_url( $store_url ); ?>"><?php echo get_avatar($user->ID, 80); ?></a>
								</div>
								<h4>
									<a href="<?php echo esc_url( $store_url ); ?>"><?php echo $user->display_name; ?></a>
									<span><?php echo $count. esc_html__(' products','sw_vendor_slider'); ?></span>
								</h4>
							</div>
						</div>
				<?php if( ( $j+1 ) % $item_row == 0 || ( $j+1 ) == count( $category ) ){?> </div><?php  } ?>
				<?php }} ?>
			</div>
		</div>
	</div>
<?php }else{
	echo '<div class="alert alert-warning alert-dismissible" role="alert">
	<a class="close" data-dismiss="alert">&times;</a>
	<p>'. esc_html__( 'There is not vendor on this component', 'sw_vendor_slider' ) .'</p>
	</div>';
}
