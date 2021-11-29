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
global $post;
$i = 0;
if ( count($list) > 0 ){
	?>
	<div class="clear"></div>
	<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider35 clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-dots="false">
		<div class="resp-slider-container">
			<?php if( $title2 != '' ){?>
			<div class="box-title clearfix">
				<h3><span><?php echo ( $title2 != '' ) ? $title2 : $term_name; ?></span></h3>
				<a class="see-all pull-right" href="<?php echo esc_url( get_category_link( $category ) );?>"><?php echo esc_html__('View all','sw_core'); ?></a>
			</div>
			<?php } ?> 
			<div class="slider">
				<?php foreach ($list as $post){ 
					?>
					<?php if($post->post_content != Null) { ?>
						<?php if( $i == 0 ) : ?>
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
											<div class="entry-meta clearfix">
												<span class="entry-cat"><?php the_category(); ?></span>
												<span class="entry-date"><?php echo get_the_date('j M Y');?></span>
											</div>
											<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
											<div class="description">
												<?php 										
													$content = self::ya_trim_words($post->post_content, $length, ' ');							
													echo $content;
												?>
											</div>
											<a class="read-more" href="<?php echo get_permalink($post->ID)?>"><?php echo esc_html__('Read More','sw_core');?><i class="fa fa-angle-double-right"></i></a>
										</div>
									</div>
								</div>
							</div>
						<?php else: ?>
							<div class="item widget-pformat-detail">
								<div class="item-inner">								
									<div class="item-detail">
										<div class="img_over">
											<a href="<?php echo get_permalink($post->ID)?>" >
												<?php 
												if ( has_post_thumbnail( $post->ID ) ){
													echo get_the_post_thumbnail( $post->ID, array(303,234), array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, array(303,234), array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
												}else{
													echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'medium'.'.png" alt="No thumb">';
												}
												?>
											</a>
										</div>
										<div class="entry-content">
												<div class="entry-meta clearfix">
													<span class="entry-cat"><?php the_category(); ?></span>
													<span class="entry-date"><?php echo get_the_date('j M Y');?></span>
												</div>
												<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php } ?>
					<?php $i++; }?>
				</div>
			</div>
		</div>
		<?php } ?>