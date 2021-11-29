<?php 	
	$widget_id = isset( $widget_id ) ? $widget_id : 'category_slide_'.$this->generateID();
	$viewall = get_permalink( wc_get_page_id( 'shop' ) );
	if( $category == '' ){
		return '<div class="alert alert-warning alert-dismissible" role="alert">
			<a class="close" data-dismiss="alert">&times;</a>
			<p>'. esc_html__( 'Please select a category for SW Woocommerce Category Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
		</div>';
	}

?>
<div id="<?php echo 'slider_' . $widget_id; ?>" class="responsive-slider sw-category-slider15 loading"  data-append=".resp-slider-container" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<?php if( $title1 != '' ){ ?>
	<div class="box-title">
		<h3><span><?php echo $title1; ?></span></h3>
	</div>
	<?php } ?>
	<div class="resp-slider-container">
		<div class="slider responsive">
		<?php
			if( !is_array( $category ) ){
				$category = explode( ',', $category );
			}
			foreach( $category as $cat ){
				$term = get_term_by('slug', $cat, 'product_cat');	
				if( $term ) :
				$thumbnail_id 	= get_term_meta( $term->term_id, 'thumbnail_id', true );
				$thumb = wp_get_attachment_image( $thumbnail_id,'full', "", array( 'alt' => $term->name ) );
				$category_color =  get_term_meta( $term->term_id, 'ets_cat_color', true );
				$termchild 		= get_terms( 'product_cat', array( 'parent' => $term->term_id, 'hide_empty' => 0, 'number' => $number_child ) );
		?>
			<div class="item item-product-cat">
				<div class="item-wrap">
					<div class="item-content">
						<h3><a class="category" data-color="<?php echo esc_attr( $category_color );?>" href="<?php echo get_term_link( $term->term_id, 'product_cat' ); ?>"><?php sw_trim_words( $term->name, $title_length ); ?></a></h3>
							<?php if( count( $termchild ) > 0 ){ ?>
								<div class="childcat-content"  id="<?php echo 'child_' . $widget_id; ?>">
									<?php 					
										echo '<ul>';
										foreach ( $termchild as $key => $child ) {
											echo '<li><a href="' . get_term_link( $child->term_id, 'product_cat' ) . '">' . $child->name . '</a></li>';
										}
										echo '<li><a href="' . get_term_link( $term->term_id, 'product_cat' ) . '">' . esc_html__('View more', 'sw_woocommerce') . '<i class="fa fa-angle-double-right"></i></a></li>';
										echo '</ul>';
									?>
								</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	(function($){
		$(window).on( 'load',function(){
			$( '.item-content h3 a' ).each(function(){
				var data = $(this).data( 'color' );
				if( data != '' ){
					$(this).css( 'border-top-width', '2px' );
					$(this).css( 'border-top-style', 'solid' );
					$(this).css( 'border-top-color', data );
				}
			});
		});
	})(jQuery);
</script>