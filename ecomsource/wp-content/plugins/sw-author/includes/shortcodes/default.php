<?php 

/**
* Layout Default
* @version     1.0.0
**/


$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();

if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'Please select a Author. Layout ', 'sw_author' ) .'</p>
	</div>';
}
?>
<div id="<?php echo 'author_' . $widget_id; ?>" class="responsive-slider sw-category-author2 loading"  data-append=".resp-slider-container" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">	
	<div class="resp-slider-container">
		<?php	if( $title != '' ){ ?>
			<div class="box-title">
				<h3 class="custom-font"><?php echo esc_html( $title ); ?></h3>
				<div class="description"><?php echo esc_html( $description ); ?> </div>
			</div>
		<?php } ?>
		<div class="slider responsive">
		<?php 
			wp_reset_postdata();
			if( !is_array( $category ) ){
				$category = explode( ',', $category );
			}
			foreach( $category as $cat ){
				$query_args = array(
					'posts_per_page' => $numberposts,
					'post_status'    => 'publish',
					'post_type'      => 'product',
					'orderby'        => $orderby,
					'order'          => $order,
					'meta_query' => array(
						array(
							'key' => 'author_product',
							'value' => $cat,
							'compare' => 'LIKE'
						)
					)
				);
				$list = new WP_Query( $query_args );
				$i = 0;
		?>
			<div class="item item-author">		
				<div class="item-inner">			
					<div class="item-img">
						<a href="<?php echo get_permalink( $cat ); ?>">
							<?php echo get_the_post_thumbnail( $cat, 'large' ); ?>
						</a>
					</div>
					<div class="item-content">
						<h4><a class="custom-font" href="<?php echo get_permalink($cat); ?>">
						<?php echo get_the_title( $cat ); ?></a></h4>
						<?php $numb 			= ( $list->found_posts > 0 ) ? $list->found_posts : count( $list->posts );?>
						<div class="number-books"><?php echo esc_attr( $numb ); ?><?php echo esc_html__(' books','sw_author'); ?></div>
					</div>					
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</div>	