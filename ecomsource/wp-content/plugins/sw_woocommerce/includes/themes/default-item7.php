<?php 

/**
* Layout Theme Default
* @version     1.0.0
**/
?>
<div class="item-wrap7">
	<div class="item-detail">										
		<div class="item-img products-thumb">		
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<?php 
				$id = get_the_ID();
				if ( has_post_thumbnail() ){
					echo get_the_post_thumbnail( $post->ID, 'shop_catalog', array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'shop_catalog', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
				}else{
					echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
				}
				?>
			</a>
		<?php sw_label_sales() ?>
		<?php echo emarket_quickview(); ?>
		</div>										
		<div class="item-content">																			
			<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>								
			<!-- price -->
			<?php if ( $price_html = $product->get_price_html() ){?>
				<div class="item-price">
					<span>
						<?php echo $price_html; ?>
					</span>
				</div>
			<?php } ?>
			<div class="wrap">
				<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
			</div>
		</div>								
	</div>
</div>