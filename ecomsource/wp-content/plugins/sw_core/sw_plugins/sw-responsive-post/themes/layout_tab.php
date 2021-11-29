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
	<div class="sw-tab-reponsive-post clearfix">
		<?php if( $title2 != '' ){?>
			<div class="box-title clearfix">
				<h3><span><?php echo ( $title2 != '' ) ? $title2 : $term_name; ?></span></h3>
				<a class="see-all pull-right" href="<?php echo esc_url( get_category_link( $category ) );?>"><?php echo esc_html__('View all','sw_core'); ?></a>
			</div>
		<?php } ?> 
		<div  class="tab-res-slide clearfix">
			<div class="tab-content clearfix">
				<?php foreach ($list as $post){ 
				?>
				<div class="tab-pane <?php echo ( $i == 0 ) ? 'active' : ''; ?>" id="<?php echo 'post_tab_'.$post->ID; ?>" >
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
										<div class="entry-meta clearfix">
											<div class="user-avatar">
												<?php echo get_avatar( $post->post_author , 50 ); ?>
											</div>
											<div class="item-right">
												<span class="entry-author"><?php the_author_posts_link(); ?></span>
												<?php
													$posted = get_post_time();
													$date = human_time_diff($posted);
												?>
												<span class="entry-date"><?php echo $date; esc_html_e(' ago', 'sw_core')?></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				<?php $i++; } ?>
			</div>
			
			<div class="tab-thumb-slider clearfix">
				<ul class="nav nav-tabs">
					<?php
						$i = 0;
						foreach ($list as $post){ 
						?>
						<li <?php echo ( $i == 0 )? 'class="item active"' : 'class="item"'; ?>>
							<a href="#<?php  echo 'post_tab_'.$post->ID; ?>" data-toggle="tab">
								<?php 
								if ( has_post_thumbnail( $post->ID ) ){
									echo get_the_post_thumbnail( $post->ID, array(210,140), array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, array(210,140), array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
								}else{
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'medium'.'.png" alt="No thumb">';
								}
								?>
							</a>
						</li>
						<?php
							$i++; }
						?>
					</ul>
			</div>	
		</div>
	</div>
		<?php } ?>