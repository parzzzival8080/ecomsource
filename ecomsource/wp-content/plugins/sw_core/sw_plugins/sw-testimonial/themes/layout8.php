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
	$j = 0;
	$k = 0;
?>
	<div id="<?php echo esc_attr( $widget_id ) ?>" class="testimonial-slider8 carousel carousel-fade slide <?php echo esc_attr( isset( $widget_template ) ? $widget_template : $layout ) . ' ' . esc_attr( $el_class ) ?>" data-ride="carousel" data-interval="<?php echo esc_attr( $interval ); ?>">
	<?php if($title !=''){ ?>
		<div class="box-title">
			<h3><?php echo $title ?></h3>
			<?php	if( $desciption != '' ){ ?>
			<div class="description"><?php echo $desciption; ?></div>
		<?php } ?>
		</div>
	<?php } ?>
		<div class="carousel-inner">
		<?php
			$count_items 	= 0;
			$numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );
			$count_items 	= ( $numberposts >= $numb ) ? $numb : $numberposts;
			while($list->have_posts()): $list->the_post();
			global $post;
			$au_name = get_post_meta( $post->ID, 'au_name', true );
			$au_url  = get_post_meta( $post->ID, 'au_url', true );
			$au_info = get_post_meta( $post->ID, 'au_info', true );
			$active = ($i== 0) ? 'active' :'';
			if( $i % 3 == 0 ){	
			?>
			<div class="item <?php echo esc_attr( $active ) ?>">
			<?php } ?>
				<div class="item-inner">
					<div class="item-detail">
						<div class="client-comment">
						<?php
							$text = get_the_content($post->ID);	
							$content = wp_trim_words($text, $length);
							echo esc_html($content);
						?>
						</div>
						<div class="client-say-info">
							<div class="image-client">
								<a href="#" title="<?php echo esc_attr( $post->post_title ) ?> "><?php the_post_thumbnail( 'thumbnail' ) ?></a>
							</div>
							<div class="name-client">
								<h2><a href="<?php echo esc_url( $au_url ) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html($au_name) ?></a></h2>
								<?php if($au_info !=''){ ?>
									<div class="info-client"><?php echo esc_html($au_info) ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			<?php if( ( $i+1 ) % 3 == 0 || ( $i+1 ) == $count_items ){?> </div><?php } ?>
			<?php $i++; endwhile; wp_reset_postdata();  ?>
		</div>
		<ul class="carousel-indicators">
		<?php 
			while ( $list->have_posts() ) : $list->the_post();
			if( $j % 3 == 0 ) {  $k++;
			$active = ($j== 0)? 'active' :'';
		?>
			<li class="<?php echo esc_attr( $active ); ?>" data-slide-to="<?php echo ($k-1) ?>" data-target="#<?php echo esc_attr( $widget_id ) ?>">
		<?php } if( ( $j+1 ) % 3 == 0 || ( $j+1 ) == $numberposts ){ ?>
			</li>
		<?php 
				}					
			$j++; 
			endwhile; 
			wp_reset_postdata(); 
		?>
		</ul>
	</div>	
<?php	
}
?>