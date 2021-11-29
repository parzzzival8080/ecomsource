<?php 
	if(!class_exists('WCFMmp')) {
		return;
	}
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_wcfm_vendor_'.$this->generateID();
	
	
	if( $category ){
		if( !is_array( $category ) ){
			$category = explode( ',', $category );
		}
?>
	<div id="<?php echo esc_attr( $widget_id.rand() ) ?>" class="responsive-slider sw-vendor-container-slider5 loading clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
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
						if( $user ) {
							global $WCFMmp;
							$store_user  = wcfmmp_get_store( $userid );
							$store_info  = $store_user->get_shop_info();
							
							$gravatar        = $store_user->get_avatar();
							$store_url       = wcfmmp_get_store_url( $userid );
							$store_name      = wcfm_get_vendor_store_name( $userid );
							$banner          = $store_user->get_banner();
							$banner_image    = $banner ? $banner : 'https://place-hold.it/270x180';

				?>
				<?php	if( ( $j % $item_row ) == 0 ) { ?>
					<div class="item item-vendor">
				<?php } ?>
					<div class="item-wrap">
						<div class="item-bottom clearfix">
							<div class="item-user">
								<div class="item-user-img clearfix">
									<div class="shop-image"><a href="<?php echo esc_url( $store_url ); ?>"><img src="<?php echo $banner_image; ?>"/></a></div>
									<a href="<?php echo esc_url( $store_url ); ?>"><img width="70" src="<?php echo esc_url( $gravatar ); ?>" /></a>
								</div>
								<div class="item-content">
									<span><?php echo $count. esc_html__(' products','sw_vendor_slider'); ?></span>
									<a href="<?php echo esc_url( $store_url ); ?>"><?php echo $store_name; ?></a>
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
