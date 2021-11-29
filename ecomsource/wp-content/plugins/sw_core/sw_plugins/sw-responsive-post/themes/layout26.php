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
if ( count($list) > 0 ){
	$i = 0;
	?>
	<div class="clear"></div>
	<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-style26 center-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-dots="false">
		<div class="resp-slider-container">
			<?php if( $title2 != '' ){?>
			<div class="box-title">
				<h3><span><?php echo ( $title2 != '' ) ? $title2 : $term_name; ?></span></h3>
				<?php echo ( $description != '' ) ? '<p>'. esc_html( $description ) .'</p>' : ''; ?>
			</div>
			<?php } ?> 
			<div class="slider responsive">
				<?php  foreach ($list as $post){ ?>
					<?php if($post->post_content != Null) {
							if( $i % 2 == 0 ){
					?>
					<div class="item widget-pformat-detail">
							<?php } ?>
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
									<span class="entry-cat"><a href="<?php echo esc_url( get_category_link( $category ) );?>"><?php echo get_cat_name( $category );?></a></span>
									<div class="item-title">
										<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
									</div>
									<div class="description">
										<?php 										
											$content = self::ya_trim_words($post->post_content, $length, ' ');							
											echo $content;
										?>
									</div>
									<a class="read-more" href="<?php echo get_permalink($post->ID)?>"><?php echo esc_html__('Read More','sw_core');?></a>
								</div>
							</div>
						</div>
					<?php if( ( $i+1 ) % 2 == 0 || ( $i+1 ) == $numberposts ){?> </div><?php } ?>
					<?php } ?>
					<?php $i++; }?>
				</div>
			</div>
		</div>
		<?php } ?>