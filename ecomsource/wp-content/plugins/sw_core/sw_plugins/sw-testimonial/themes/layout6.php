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
	<div id="<?php echo esc_attr( $widget_id ) ?>" class="testimonial-post-slider6 responsive-slider clearfix loading<?php echo esc_attr( $el_class ) ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<?php if( $title != '' ): ?>
			<div class="block-title">
				<h3><?php echo $title; ?></h3>					
			</div>
		<?php endif; ?>
		<div class="resp-slider-container">
			<div class="slider responsive">
				<?php 
					while($list->have_posts()): $list->the_post();				
					global $post;
					$au_name = get_post_meta( $post->ID, 'au_name', true );
					$au_url  = get_post_meta( $post->ID, 'au_url', true );
					$au_info  = get_post_meta( $post->ID, 'au_info', true );
				?>
					<div class="item">
						<div class="item-inner clearfix">
							<?php if( has_post_thumbnail() ){ ?>
								<div class="image-client">
									<?php the_post_thumbnail( 'large' ) ?>
								</div>		
							<?php } ?>
							<div class="client-say-info">
								<div class="client-comment custom-font">
								<?php 
									$text = get_the_content($post->ID);
									echo esc_html($text);
								?>
								</div>
								<div class="name-client">
									<h2 class="custom-font"><a href="<?php echo esc_url( $au_url ) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html($au_name) ?></a></h2>
								</div>
								<a class="view-more" href="<?php echo esc_url( $au_url ) ?>" title="<?php echo esc_html__('View more','sw_core'); ?>"><?php echo esc_html__('view more','sw_core'); ?></a>
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