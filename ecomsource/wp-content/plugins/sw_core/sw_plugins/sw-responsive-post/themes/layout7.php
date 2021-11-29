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
			'meta_query' => array(
				array(
					'key' => 'hot_checkbox',
					'value' => 'yes'
					)
				)
	);
	$list = get_posts($default);
	do_action( 'before' ); 
	$id = 'sw_reponsive_post_slider_'.rand().time();
	if ( count($list) > 0 ){
	$i = 0;
?>
<div class="clear"></div>
<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider7 clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<div class="resp-slider-container">
		<?php if( $title2 != '' ) { ?>
			<div class="block-title">
			<?php echo ( $title2 != '' ) ? '<h3>'. esc_html( $title2 ) .'</h3>' : ''; ?>
			</div>
		<?php } ?>
		<div class="slider">
			<div class="wrap-content">
				<?php foreach ($list as $post){ ?>
					<?php if($post->post_content != Null) { ?>
						<?php if( $i == 0 || $i == 1 ) : ?>
						<div class="item item-large widget-pformat-detail">
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
										</div>
									<?php } ?>
									<div class="entry-content">
										<div class="entry-cat">
												<?php 
													$categories = wp_get_post_categories( $post->ID );
													$category_color = '';													
													foreach($categories as $category){
														$category_color =  get_term_meta( $category, 'ets_cat_color2', true );
													  echo '<a data-color="'.esc_attr( $category_color ).'"href="' . get_category_link($category) . '">' . get_cat_name($category) . '</a>';
													}
												?>
										</div>
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
					<?php else : ?>
						<div class="item widget-pformat-detail">
							<div class="item-inner">								
								<div class="item-detail">
									<?php if ( has_post_thumbnail( $post->ID ) ){ ?>
									<div class="img_over">
										<a href="<?php echo get_permalink($post->ID)?>" >
											<?php 
										if ( has_post_thumbnail( $post->ID ) ){
											
												echo get_the_post_thumbnail( $post->ID, array(240,160) ) ? get_the_post_thumbnail( $post->ID, array(240,160) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
										}else{
											echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
										}
									?></a>
										<span class="entry-comment"><?php echo get_post()->comment_count;?></span>
									</div>
									<?php } ?>
									<div class="entry-content">
										<div class="entry-cat">
												<?php 
													$categories = wp_get_post_categories( $post->ID );
													$category_color = '';													
													foreach($categories as $category){
														$category_color =  get_term_meta( $category, 'ets_cat_color2', true );
													  echo '<a data-color="'.esc_attr( $category_color ).'"href="' . get_category_link($category) . '">' . get_cat_name($category) . '</a>';
													}
												?>
										</div>
										<div class="item-title">
											<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
										</div>
									</div>
								</div>
							</div>
						</div>

					<?php endif; ?>
					<?php } ?>
				<?php $i++; } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<script type="text/javascript">
</script>