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
	$i = 0;
?>
<div class="clear"></div>
<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider18 clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="resp-slider-container">
		<?php if( $title2 != '' ) { ?>
			<div class="box-title">
			<?php echo ( $title2 != '' ) ? '<h3><span>'. esc_html( $title2 ) .'</span></h3>' : ''; ?>
			</div>
		<?php } ?>
		<div class="slider">
			<div class="wrap-content">
				<?php foreach ($list as $post){ ?>
					<?php if($post->post_content != Null) { ?>
						<div class="item widget-pformat-detail">
							<div class="item-inner">								
								<div class="item-detail">
									<?php 
									$feat_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
									if ( $feat_image_url ){ 
									?>
									<div class="img_over">
										<a href="<?php echo get_permalink($post->ID)?>" >
											<?php 								
												$width  = ( isset( $img_w ) ) ? $img_w : 370;
												$height = ( isset( $img_h ) ) ? $img_h : 240;
												$crop   = ( isset( $crop ) ) ? $crop : false;
												$image = sw_image_resize( $feat_image_url, $width, $height, $crop );
												echo '<img src="'. esc_url( $image['url'] ) .'" alt="'. esc_attr( $post->post_title ) .'">';
											?>
										</a>
										<?php 
											$gameplay   = get_post_meta( $post->ID, '_gameplay', true );
											$gamestory   = get_post_meta( $post->ID, '_gamestory', true );
											$graphic   = get_post_meta( $post->ID, '_grapphic', true );
											$performance   = get_post_meta( $post->ID, '_performance', true );
											$point = (( intval($gameplay) + intval($gamestory) + intval($graphic) + intval($performance))/40)*10;
											if( $point > 0 ){
										?>
										
										<span class="reivew-score"><?php echo esc_attr( $point ); ?></span>
										
										<?php } ?>
									</div>
									<?php } ?>
									<div class="entry-content">
										<div class="item-title">
											<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
										</div>
										<div class="entry-meta">
											<span class="entry-author"><?php the_author_posts_link(); ?></span>
											<span class="entry-date"><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_date( '', $post->ID );?></a></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php $i++; } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>