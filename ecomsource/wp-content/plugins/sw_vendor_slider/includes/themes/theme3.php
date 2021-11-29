<?php 
	if( !class_exists( 'WC_Vendors' ) ){
		return;
	}
	
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_vendor_'.$this->generateID();
	
	
	if( $category ){
		if( !is_array( $category ) ){
			$category = explode( ',', $category );
		}
?>
	<div id="<?php echo esc_attr( $widget_id.rand() ) ?>" class="responsive-slider sw-vendor-container-slider4 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title1 != '') { ?>
			<div class="box-title"><h3><?php echo esc_html( $title1 ); ?></h3></div>
		<?php } ?>
		<div class="resp-slider-container">		
			<div class="slider responsive">
				<?php 
					foreach( $category as $j => $userid ){ 
						$user = get_userdata( $userid );
						$count = count_user_posts( $userid, 'product');
						if( $user ) {
				?>
				<?php	if( ( $j % $item_row ) == 0 ) { ?>
					<div class="item item-vendor">
				<?php } ?>
					<div class="item-wrap">
						<div class="item-bottom clearfix">
							<div class="item-user">
								<div class="item-user-img clearfix">
									<a href="<?php echo esc_url( WCV_Vendors::get_vendor_shop_page( $user->ID ) ); ?>"><?php echo get_avatar($user->ID, 50); ?></a>
								</div>
								<h4>
									<a href="<?php echo esc_url( WCV_Vendors::get_vendor_shop_page( $user->ID ) ); ?>"><?php echo ( get_user_meta( $user->ID, 'pv_shop_name', true ) ) ? get_user_meta( $user->ID, 'pv_shop_name', true ) : $user->display_name; ?></a>
									<span><?php echo $count. esc_html__(' products','sw_vendor_slider'); ?></span>
								</h4>
							</div>
						</div>
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
						<div class="item-product clearfix">
							<?php 
								$key = 0;
								$count_items = 0;
								$count_items = ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
								while( $list->have_posts() ) : $list->the_post();
								global $product, $post;
								if( $key % 3 == 0 ){
							?>
								<div class="item-product-content">
							<?php } ?>
									<div class="item-img">
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_post_thumbnail( $product->get_id(), 'shop_thumbnail', array( 'alt' => $post->post_title ) ); ?></a>
									</div>
								<?php if( ( $key+1 ) % 3 == 0 || ( $key+1 ) == $count_items ){?> </div><?php } ?>								
							<?php $key++; endwhile; wp_reset_postdata(); ?>
						</div>
					<?php } ?>
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
