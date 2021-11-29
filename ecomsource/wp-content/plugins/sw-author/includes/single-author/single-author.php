<?php get_header(); ?>
<?php if ( !is_front_page() ) { ?>
<div class="emarket_breadcrumbs">
	<div class="container">
		<?php
			if (!is_front_page() ) {
				if (function_exists('emarket_breadcrumb')){
					emarket_breadcrumb('<div class="breadcrumbs theme-clearfix">', '</div>');
				} 
			} 
		?>
	</div>
</div>
<?php } ?>
<div class="container">
	<div class="single-author">
		<div class="single-author-content">
			<div class="container">
				<div class="row">				
					<div class=" item-image col-lg-2 col-md-2 clearfix">
						<?php the_post_thumbnail( 'large' ); ?>					
						<div class="custom-font">
							<?php do_action( 'sw_share_author_sw' ); ?>
						</div>
					</div>
					<div class="item-single-content col-lg-8 col-md-8 clearfix">
						<h4 class="custom-font"><?php the_title(); ?></h4>
						<div class="description">					
							<?php  the_content(); ?>
						</div>
						<div class="">
							<?php do_action( 'social_author_sw' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php do_action( 'sw_author_bottom_detail' ); ?>
		 
	</div>
</div>
<?php get_footer(); ?>