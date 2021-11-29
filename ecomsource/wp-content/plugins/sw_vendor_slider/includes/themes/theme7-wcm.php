<?php 
	if( !class_exists('WCMp') ){
		return;
	}
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_wcmp_vendor_'.$this->generateID();
	
	
	if( $category ){
		if( !is_array( $category ) ){
			$category = explode( ',', $category );
		}
?>
	<div id="<?php echo esc_attr( $widget_id.rand() ) ?>" class="responsive-slider sw-vendor-container-slider8 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title1 != '' ){
					
					$titles = strrpos($title1, ' ');
					$title2 = substr( $title1, 0, $titles );
				?>
				<div class="block-title clearfix">
					<h3><p><?php echo str_replace( $title2,'', $title1 ) ?></p><span><?php echo ( $title1 != '' ) ? $title1 : ''; ?></span></h3>
				</div>
				<?php } ?>
		<div class="resp-slider-container">
			<div class="slider responsive">
				<?php 
					foreach( $category as $j => $userid ){ 
						$user = get_userdata( $userid );
						$count = count_user_posts( $userid, 'product');
						$store_icon_src = wp_get_attachment_image_src( get_user_meta( $user->ID, '_vendor_image', true ), array( 60, 60 ) );
						$vendor_banner = get_user_meta( $user->ID,'_vendor_banner', true );
						$image = $vendor_banner ? wp_get_attachment_image( $vendor_banner, 'large' ) : '<img src="https://place-hold.it/270x180" />';
						if( $user ) {
							global $WCMp;
							$vendor = get_wcmp_vendor($userid);
				?>
				<?php	if( ( $j % $item_row ) == 0 ) { ?>
					<div class="item item-vendor">
				<?php } ?>
					<div class="item-wrap">
						<div class="item-bottom clearfix">
							<div class="item-user">
								<div class="item-user-img clearfix">
									<div class="shop-image"><a href="<?php echo $vendor->get_permalink(); ?>"><?php echo $image; ?></a></div>
									<a href="<?php echo $vendor->get_permalink(); ?>"><img src="<?php echo esc_url( $store_icon_src ) ?>" /></a>
								</div>
								<div class="item-content">
									<a href="<?php echo $vendor->get_permalink(); ?>"><?php echo $vendor->page_title ?></a>
									<span><?php echo $count. esc_html__(' products','sw_vendor_slider'); ?></span>
								</div>
							</div>
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
