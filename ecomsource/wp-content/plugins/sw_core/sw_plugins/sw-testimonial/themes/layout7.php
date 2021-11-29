<?php 
	$widget_id = isset( $widget_id ) ? $widget_id : 'sw_testimonial'.rand().time();
	$default = array(
		'post_type' => 'testimonial',
		'orderby' => $orderby,
		'order' => $order,
		'post_status' => 'publish',
		'showposts' => $numberposts
	);
	$list = new WP_Query( $default );
	if ( $list->have_posts() ){
	$i = 0;
?>
	<div id="<?php echo esc_attr( $widget_id ) ?>" class="testimonial-post-slider style1 responsive-slider clearfix loading <?php echo esc_attr( $el_class ) ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="resp-slider-container">
			<div id="thumb_<?php echo esc_attr( $widget_id ); ?>" class="slider responsive-thumbnail">
				<?php 
					while($list->have_posts()): $list->the_post();					
				?>
				<div class="item">
					<div class="item-inner">
					<?php if( has_post_thumbnail() ){ ?>
						<div class="image-client">
							<?php the_post_thumbnail( 'thumbnail' ) ?>
						</div>		
					<?php } ?>
					</div>
				</div> 
				<?php $i++; endwhile; wp_reset_postdata(); ?>
			</div>
			<div id="content_<?php echo esc_attr( $widget_id ); ?>" class="slider responsive-content">
				<?php 
					while($list->have_posts()): $list->the_post();				
					global $post;
					$au_name = get_post_meta( $post->ID, 'au_name', true );
					$au_url  = get_post_meta( $post->ID, 'au_url', true );
					$au_info  = get_post_meta( $post->ID, 'au_info', true );
				?>
					<div class="item">
						<div class="item-inner">
							<div class="client-say-info">
								<div class="client-comment">
								<?php 
									$text = get_the_content($post->ID);
									echo esc_html($text);
								?>
								</div>
								<div class="name-client">
									<h2 class="custom-font"><?php echo esc_html($au_name) ?></h2>
									<h4 class="custom-font"><?php echo esc_html($au_info) ?></h4>
								</div>
							</div>
						</div>
					</div> 
				<?php $i++; endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
<?php	
}
?>