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
	<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider15 responsive-slider clearfix loading" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-dots="false">
		<div class="resp-slider-container">
			<?php if( $title2 != '' ){?>
			<div class="box-title">
				<h3><span><?php echo ( $title2 != '' ) ? $title2 : $term_name; ?></span></h3>
			</div>
			<?php } ?> 
			<div class="slider responsive">
				<?php foreach ($list as $post){ ?>
				<?php if($post->post_content != Null) { ?>
				<div class="item widget-pformat-detail">
					<div class="item-inner">								
						<div class="item-detail">
							<div class="entry-content">
								<div class="item-title">
									<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?>:</a></h4>
								</div>	
								<div class="description">
									<?php 										
										$content = self::ya_trim_words($post->post_content, $length, ' ');							
										echo $content;
									?>
								</div>
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