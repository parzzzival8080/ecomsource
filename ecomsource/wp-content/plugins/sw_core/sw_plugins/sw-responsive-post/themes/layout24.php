<?php 
	/**
		** Theme: Responsive Slider
		** Author: Smartaddons
		** Version: 1.0
	**/
	$default = array(
			'category' => $category, 
			'orderby' => $orderby,
			'order' => $order, 
			'numberposts' => $numberposts,
	);
	$list = get_posts($default);
	do_action( 'before' ); 
	$id = 'sw_reponsive_post_slider_'.rand().time();
	if ( count($list) > 0 ){
?>
<div class="clear"></div>
<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider24 responsive-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-dots="true" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="resp-slider-container">
		<div class="box-title">
		<?php echo ( $title2 != '' ) ? '<h3>'. esc_html( $title2 ) .'</h3>' : ''; ?>
		<?php if( $description != '' ){	echo '<div class="description">'.$description.'</div>'; } ?>
		</div>
		<div class="slider responsive">
			<?php foreach ($list as $post){ ?>
				<?php if($post->post_content != Null) { ?>
				<div class="item widget-pformat-detail">
					<div class="item-inner">								
						<div class="item-detail">
							<?php $image_secound   = get_post_meta( $post->ID, 'second_featured_image', true); if ( $image_secound ){ ?>
							<div class="img_over">
								<a href="<?php echo get_permalink($post->ID)?>" >
								<?php
									if ( $image_secound ) {
										$image = wp_get_attachment_image_url( $image_secound, 'medium' );
									} else {
										$image = wc_placeholder_img_src();
									}
								?>
									<img src="<?php echo esc_url( $image ); ?>" alt="Featured Second">
								<?php
							?></a>
							</div>
							<?php } ?>
							<div class="entry-content">
								
								<div class="item-title">
									<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
								</div>
								
								<div class="description">
									<?php 										
										$content = self::ya_trim_words($post->post_content, $length, ' ');							
										echo $content;
									?>
								</div>
								<a class="read-more" title="<?php echo esc_html__('Read More','sw_core');?>" href="<?php echo get_permalink($post->ID)?>"><?php echo esc_html__('Read More','sw_core');?></a>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php }?>
		</div>
	</div>
</div>
<?php } ?>