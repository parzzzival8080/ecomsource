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
	<!-- No Result -->
	<?php if( have_posts() ) : ?>
	<div class="author-filter">
		<a href="#" data-character="all" class="active"><?php echo esc_html__( 'All', 'sw_author' ); ?></a>
		<?php 
			$alphas = range('A', 'Z');
			foreach( $alphas as $alpha ){
		?>
			<a href="#" data-character="<?php echo esc_attr( $alpha ); ?>"><?php echo esc_html( $alpha ); ?></a>
		<?php } ?>
	</div>
	<div class="author-column" id="author_listing">
	<?php while( have_posts() ) : the_post(); global $post; ?>
		<div id="post-<?php the_ID();?>" <?php post_class(); ?>>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<div class="item-img"><?php the_post_thumbnail( 'medium' ); ?></div>
				<h4><?php the_title(); ?></h4>

			</a>
			<?php do_action('count_author'); ?>
		</div>
		<?php endwhile; wp_reset_postdata();?>
	</div>
	<?php endif; ?>
	<div class="clearfix"></div>
</div>
<?php get_footer(); ?>