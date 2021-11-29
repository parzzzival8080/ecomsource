<?php 

/**
* Layout Tab Category Default
* @version     1.0.0
**/

$widget_id = isset( $widget_id ) ? $widget_id : $this->generateID();
if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
	<a class="close" data-dismiss="alert">&times;</a>
	<p>'. esc_html__( 'Please select a category for SW Tab Category Slider. Layout ', 'sw_core' ) . $layout .'</p>
</div>';
}
if(  $category ){
	if( defined( 'ELEMENTOR_VERSION' ) ){
		$categories = $category;
	}
	elseif ( class_exists('Vc_Manager') ) {
		$categories = explode( ',', $category );
	}
}
$nav_id = 'nav_'.$widget_id.rand().time();
?>
<div class="sw-tab-cat-listing-post sw-ajax" id="<?php echo esc_attr( 'category_' . $widget_id ); ?>">
	<div class="resp-tab" style="position:relative;">
		<div class="top-tab-slider clearfix">
			<?php if( $title1 != '' ) { ?>
			<div class="box-title">
				<?php echo ( $title1 != '' ) ? '<h3><span>'. esc_html( $title1 ) .'</span></h3>' : ''; ?>
				<a class="overall pull-right" href="#<?php echo esc_attr($nav_id); ?>" data-toggle="collapse" data-target="#<?php echo esc_attr($nav_id); ?>"  aria-expanded="false"><span><?php echo esc_html__('Overall','sw_core'); ?></span></a>
			</div>
			<?php } ?>
			<ul class="nav nav-tabs" id="<?php echo esc_attr( $nav_id ); ?>">
				<?php 
				$i = 1;
				foreach( $categories as $cat ){
					$terms = get_term_by('id', $cat, 'category');
					if( $terms ){			
						?>
						<li class="<?php if( $i == $tab_active ){echo 'active loaded'; }?>">
							<a href="#<?php echo esc_attr( $cat. '_' .$widget_id ) ?>" data-type="tab_ajax" data-layout="<?php echo esc_attr( isset( $widget_template ) ? $widget_template : $layout );?>" data-category="<?php echo esc_attr( $cat ) ?>" data-toggle="tab" data-catload="ajax_res" data-number="<?php echo esc_attr( $numberposts ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
								<?php echo $terms->name; ?>
							</a>
						</li>	
						<?php $i ++; ?>
						<?php } } ?>
			</ul>
			</div>
			<div class="tab-content">
				<?php 
				$active = ( $tab_active - 1 >= 0 ) ? $tab_active - 1 : 0;
				$default = array(
					'category' => $categories[$active], 
					'orderby' => $orderby,
					'order' => $order, 
					'numberposts' => $numberposts,
					);
				$list = get_posts($default);
				?>
				<div class="tab-pane active" id="<?php echo esc_attr( $categories[$active]. '_' .$widget_id ) ?>">
					<?php if(  $list  ) : ?>
						<div id="<?php echo esc_attr( $categories[$active]. '_' .$widget_id ); ?>" class="responsive-post-slider clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-ajaxurl="<?php echo esc_url( sw_ajax_url() ) ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
							<div class="resp-slider-container">						
								<div class="slider">
									<div class="wrap-content">
										<?php foreach ($list as $post){ ?>
											<?php if($post->post_content != Null) { ?>
												<div class="item widget-pformat-detail">
													<div class="item-inner">								
														<div class="item-detail">
															<?php $image_game   = get_post_meta( $post->ID, 'second_featured_image', true); if ( $image_game ){ ?>
															<div class="img_over">
																<a href="<?php echo get_permalink($post->ID)?>" >
																<?php
																	if ( $image_game ) {
																		$image = wp_get_attachment_image_url( $image_game, array(120,180) );
																	} else {
																		$image = wc_placeholder_img_src();
																	}
																?>
																	<img src="<?php echo esc_url( $image ); ?>" alt="Featured Second">
																<?php
															?></a>
															</div>
															<?php } ?>
															<div class="entry-content">
																<div class="item-title">
																	<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
																</div>
																<div class="entry-cat">
																		<?php 
																			$categories = wp_get_post_categories( $post->ID );
																			$category_color = '';													
																			foreach($categories as $category){
																				$category_color =  get_term_meta( $category, 'ets_cat_color2', true );
																			  echo '<a data-color="'.esc_attr( $category_color ).'"href="' . get_category_link($category) . '">' . get_cat_name($category) . '</a>';
																			}
																		?>
																</div>
																<div class="entry-date">
																	<span class="entry-day"><?php echo get_the_date( 'j', $post->ID );?></span>
																	<span class="entry-month"><?php echo get_the_date( 'M', $post->ID );?></span>
																</div>
															</div>
														</div>
													</div>
												</div>
											<?php } ?>
										<?php $i++; } ?>
										<a class="more-cat" href="<?php echo esc_url( get_category_link( $category ) );?>"><span><?php echo esc_html__('More games','sw_core'); ?></span></a>
									</div>
								</div>
								</div>
							</div>
							<?php 
							else :
								echo '<div class="alert alert-warning alert-dismissible" role="alert">
							<a class="close" data-dismiss="alert">&times;</a>
							<p>'. esc_html__( 'There is not post on this tab', 'sw_core' ) .'</p>
						</div>';
						endif;
						?>
					</div>
				</div>
		</div>
	</div>