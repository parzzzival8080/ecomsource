<?php 
/**
** Theme: Responsive Slider
** Author: Smartaddons
** Version: 1.0
**/
$id = 'sw_reponsive_post_slider_'.rand().time();
wp_reset_postdata();
?>
<div id="<?php echo esc_attr($id); ?>" class="sw-video-gallery loading">
	<!-- Title and Description -->
	<?php if( $title2 != '' ) { ?>
			<div class="box-title">
			<?php echo ( $title2 != '' ) ? '<h3><span>'. esc_html( $title2 ) .'</span></h3>' : ''; ?>
			</div>
		<?php } ?>
	<?php
	$default = array(
		'post_type'	=> 'post',
		'cat' => $category, 
		'orderby' => $orderby,
		'order' => $order,
		'posts_per_page' => $numberposts,
		'tax_query' => array( 
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-video',
				)				 
			)
		);
	$list = new WP_Query($default);
	if ($list->have_posts()){
		?>
		<div class="sw-video-wrapper clearfix">
			<div class="box-video-left">
				<?php foreach( $list->posts as $item ) : ?>
					<div class="item item-video">
						<?php echo '<div class="video-wrapper">'. emarket_get_entry_content_asset( $item->ID ) . '</div>'; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="box-video-right">
				<?php foreach( $list->posts as $item ) : ?>
					<div class="item item-video-thumb">
						<div class="item-detail">
							<div class="item-thumb">
								<?php 
								if ( has_post_thumbnail( $item->ID ) ){

									echo get_the_post_thumbnail( $item->ID, array(108,60), array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $item->ID, array(108,60), array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'thumbnail2'.'.png" alt="No thumb">';		
								}else{
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'thumbnail2'.'.png" alt="No thumbnail2">';
								}
								?>
							</div>
							<div class="item-content">
								<h3><?php echo wp_trim_words( $item->post_title, $length ); ?></h3>
								<p><?php echo get_the_time( 'G:i', $item->ID ); ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; wp_reset_postdata(); ?>
			</div>
		</div>
		<?php 
	}else{
		echo '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Has no video on this category', 'sw_core' ) .'</p>
	</div>';
} 
?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {              
		$( '.sw-video-gallery' ).each(function(){
			var $rtl 					= $('body').hasClass( 'rtl' );
			var $vertical			= true;
			var $img_slider 	= $(this).find('.box-video-left');
			var $thumb_slider = $(this).find('.box-video-right' );

			$img_slider.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				arrows: false,
				rtl: $rtl,
				asNavFor: $thumb_slider
			});
			$thumb_slider.slick({
				slidesToShow: 6,
				slidesToScroll: 1,
				asNavFor: $img_slider,
				arrows: false,
				rtl: $rtl,
				vertical: $vertical,
				verticalSwiping: $vertical,
				focusOnSelect: true,
				responsive: [
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 4    
					}
				},
				{
					breakpoint: 360,
					settings: {
						slidesToShow: 2    
					}
				}
				]
			});
			
			//remove active class from all thumbnail slides
			 $('.box-video-right .slick-slide').removeClass('slick-active');

			 //set active class to first thumbnail slides
			 $('.box-video-right .slick-slide').eq(0).addClass('slick-active');
			var el = $(this);
			setTimeout(function(){
				el.removeClass("loading");					
			}, 1000);
		});
	});
    //]]>
</script>
