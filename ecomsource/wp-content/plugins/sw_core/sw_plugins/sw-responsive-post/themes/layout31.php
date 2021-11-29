<?php 
/**
** Theme: Responsive Slider
** Author: Smartaddons
** Version: 1.0
**/
$default = array(
	'category' => $category, 
	'meta_key'	=> 'rmp_avg_rating',
	'orderby'   => 'meta_value',
	'order' => $order,
	'numberposts' => $numberposts,
	);

$list = get_posts($default);
do_action( 'before' ); 
$id = 'sw_reponsive_post_slider_'.rand().time();
global $post;
$i = 1; 
if ( count($list) > 0 ){
	?>
	<div class="clear"></div>
	<div id="<?php echo esc_attr( $id ) ?>" class="responsive-post-slider31 clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-dots="false">
		<div class="resp-slider-container">
			<?php if( $title2 != '' ){?>
			<div class="box-title clearfix">
				<h3><span><?php echo ( $title2 != '' ) ? $title2 : $term_name; ?></span></h3>
				<a class="see-all pull-right" href="<?php echo esc_url( get_category_link( $category ) );?>"><?php echo esc_html__('View all','sw_core'); ?></a>
			</div>
			<?php } ?> 
			<div class="slider">
				<?php foreach ($list as $post){ 
						$view_count = get_post_meta( $post->ID, 'post_views_count', true );
					?>
					<?php if($post->post_content != Null) { ?>
					<div class="item widget-pformat-detail">
						<div class="item-inner">								
							<div class="item-detail">
								<div class="img_over">
									<span class="count-order<?php echo $i; ?>"><?php echo $i; ?></span>
								</div>
								<div class="entry-content">
									<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
									<div class="entry-meta">
										<span class="entry-author"><?php esc_html_e('by', 'sw_core'); ?> <?php the_author_posts_link(); ?></span>
										<span class="entry-date"><?php echo get_the_date('j M Y');?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					<?php $i++; }?>
				</div>
			</div>
		</div>
		<?php } ?>