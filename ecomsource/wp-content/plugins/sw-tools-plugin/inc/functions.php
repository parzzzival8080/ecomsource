<?php 

function swquicktools_plugin_tool_override_check( $path, $file ){
	$paths = '';

	if( locate_template( 'sw-tools-plugin/'.$path . '/' . $file . '.php' ) ){
		$paths = locate_template( 'sw-tools-plugin/'.$path . '/' . $file . '.php' );		
	}else{
		$paths = SWTOOLPLUGINTHEME . '/' . $path . '/' . $file . '.php';
	}
	
	return $paths;
}

function swquicktools_prefix_admin_setting_tools_plugin(){ 
	
		
		$show_status = '1';
		if(isset($_POST['show_status'])) {
			$show_status = sanitize_text_field($_POST['show_status']);
		}
		$show_position = '1';
		if(isset($_POST['show_position'])) {
			$show_position = sanitize_text_field($_POST['show_position']);
		}
		$show_top = '196';
		if(isset($_POST['show_top'])) {
			$show_top = sanitize_text_field($_POST['show_top']);
		}
		$show_number = '10';
		if(isset($_POST['show_number'])) {
			$show_number = sanitize_text_field($_POST['show_number']);
		}			
		$show_categories = 'no';
		if(isset($_POST['show_categories'])) {
			$show_categories = sanitize_text_field( $_POST['show_categories']);
		}
		$show_categories_empty = 'no';
		if(isset($_POST['show_categories_empty'])) {
			$show_categories_empty = sanitize_text_field( $_POST['show_categories_empty']);
		}		
		$show_cart = 'no';
		if(isset($_POST['show_cart'])) {
			$show_cart = sanitize_text_field( $_POST['show_cart']);
		}
		$show_account = 'no';
		if(isset($_POST['show_account'])) {
			$show_account = sanitize_text_field( $_POST['show_account']);
		}
		$show_search = 'no';
		if(isset($_POST['show_search'])) {
			$show_search = sanitize_text_field( $_POST['show_search']);
		}
		$show_recent_view = 'no';
		if(isset($_POST['show_recent_view'])) {
			$show_recent_view = sanitize_text_field( $_POST['show_recent_view']);
		}
		$show_backtop = 'no';
		if(isset($_POST['show_backtop'])) {
			$show_backtop = sanitize_text_field( $_POST['show_backtop']);
		}
	   
	    update_option( 'swquicktools_tools_plugin_show_status', $show_status );
		update_option( 'swquicktools_tools_plugin_show_position', $show_position );
		update_option( 'swquicktools_tools_plugin_show_top', $show_top );
		update_option( 'swquicktools_tools_plugin_show_number', $show_number );
		update_option( 'swquicktools_tools_plugin_show_categories', $show_categories );
		update_option( 'swquicktools_tools_plugin_show_categories_empty', $show_categories_empty );
		update_option( 'swquicktools_tools_plugin_show_cart', $show_cart );		
		update_option( 'swquicktools_tools_plugin_show_account', $show_account );
		update_option( 'swquicktools_tools_plugin_show_search', $show_search );
		update_option( 'swquicktools_tools_plugin_show_recent_view', $show_recent_view );
		update_option( 'swquicktools_tools_plugin_show_backtop', $show_backtop );	
   
    header("Location:../wp-admin/admin.php?page=wp_setting_tools_plugin_class");
}
add_action( 'admin_post_setting_tools_plugin', 'swquicktools_prefix_admin_setting_tools_plugin' );
add_action( 'admin_post_nopriv_setting_tools_plugin', 'swquicktools_prefix_admin_setting_tools_plugin' ); // this is for non logged users


function swquicktools_tools_plugin(){
   global $woocommerce;
		
	$show_status = get_option( 'swquicktools_tools_plugin_show_status' );
	$show_position = get_option( 'swquicktools_tools_plugin_show_position' );
	$show_categories = get_option( 'swquicktools_tools_plugin_show_categories' );
	$show_categories_empty = get_option( 'swquicktools_tools_plugin_show_categories_empty' );
	$show_top = get_option( 'swquicktools_tools_plugin_show_top' );
	$show_cart = get_option( 'swquicktools_tools_plugin_show_cart' );
	$show_backtop = get_option( 'swquicktools_tools_plugin_show_backtop' );
	$show_recent_view = get_option( 'swquicktools_tools_plugin_show_recent_view' );
	$show_search = get_option( 'swquicktools_tools_plugin_show_search' );
	$show_account = get_option( 'swquicktools_tools_plugin_show_account' );
	$show_number = get_option( 'swquicktools_tools_plugin_show_number' );

	if(isset($show_status) && $show_status == 1) {
	
		if($show_position == 1):
			$position  = 'right';
		else:
			$position  = 'left';
		endif;
	
	   if ($show_categories == 'on') {
		    if ($show_categories_empty == 'on') {
				$categories = get_terms( array(
					'taxonomy' => 'product_cat',
					'parent'  => 0,
					'hide_empty' => false
				));					
			}
			else {
				$categories = get_terms( array(
					'taxonomy' => 'product_cat',
					'parent'  => 0,
				));				
			}

			foreach ($categories as $key => $item) {
				$termchildren = get_term_children( $item->term_id , 'product_cat' );
				
				
				$term = array();
				foreach ($termchildren as $count => $child) {		
					$term[] = get_term_by( 'id', $child, 'product_cat' );
					foreach ($term as $childchild) {
						$termchildrenchild = get_term_children( $childchild->term_id , 'product_cat' );
						$termchildchild = array();
						foreach ($termchildrenchild as $itemtermchildrenchild) {
							$termchildchild[] = get_term_by( 'id', $itemtermchildrenchild, 'product_cat' );
						}
					}
					$term[$count]->childrens = 	$termchildchild;				
				}
				$categories[$key]->childrens = 	$term;		
			}	
		}
		
		?>
			<div id="sw-groups" class="<?php echo esc_attr($position); ?> sw-groups-sticky hidden-xs" style="top:<?php echo esc_attr($show_top.'px'); ?>">
				<?php if ($show_categories == 'on'): ?>
				      <a class="sticky-categories" data-target="popup" data-popup="#popup-categories"><span><?php echo esc_html__( 'Categories'); ?></span><i class="fa fa-align-justify"></i></a>
				<?php endif; ?>	
				<?php if ($show_cart == 'on'): ?>
					<a class="sticky-mycart" data-target="popup" data-popup="#popup-mycart"><span><?php echo esc_html__( 'Cart'); ?></span><i class="fa fa-shopping-cart"></i></a>
				<?php endif; ?>	
				<?php if ($show_account == 'on'): ?>
					<a class="sticky-myaccount" data-target="popup" data-popup="#popup-myaccount"><span><?php echo esc_html__( 'Account'); ?></span><i class="fa fa-user"></i></a>
				<?php endif; ?>	
				<?php if ($show_search == 'on'): ?>
					<a class="sticky-mysearch" data-target="popup" data-popup="#popup-mysearch"><span><?php echo esc_html__( 'Search'); ?></span><i class="fa fa-search"></i></a>
				<?php endif; ?>	
				<?php if ($show_recent_view == 'on'): ?>
					<a class="sticky-recent" data-target="popup" data-popup="#popup-recent"><span><?php echo esc_html__( 'Recent Viewed Products'); ?></span><i class="fa fa-recent"></i></a>
				<?php endif; ?>	
				<?php if ($show_backtop == 'on'): ?>
					<a class="sticky-backtop" data-target="scroll" data-scroll="html"><span><?php echo esc_html__( 'Back To Top'); ?></span><i class="fa fa-angle-double-up"></i></a>
				<?php endif; ?>

				<?php if ($show_account == 'on'): ?>
					<?php include( swquicktools_plugin_tool_override_check( '', 'account' ) ); ?>
                <?php endif; ?>	
				
				<?php if ($show_categories == 'on'): ?>
					<?php include( swquicktools_plugin_tool_override_check( '', 'category' ) ); ?>
                <?php endif; ?>	
				
				<?php if ($show_cart == 'on'): ?>
				<div class="popup popup-mycart popup-hidden" id="popup-mycart">
					<div class="popup-screen">
						<div class="popup-position">
							<div class="popup-container popup-small">
								<div class="popup-html">
									<div class="popup-header">
										<span><i class="fa fa-shopping-cart"></i><?php echo esc_html__( 'Shopping cart','sw-tools-plugin'); ?></span>
										<a class="popup-close" data-target="popup-close" data-popup-close="#popup-mycart">&times;</a>
									</div>
									<div class="popup-content">
										<div class="cart-header">
										    <?php include( swquicktools_plugin_tool_override_check( '', 'mini-cart-ajax' ) ); ?>
										</div>
									</div>			
								</div>
							</div>
						</div>
					</div>					
				</div>
				<?php endif; ?>	
				
				<?php if ($show_search == 'on'): ?>
			    	<?php include( swquicktools_plugin_tool_override_check( '', 'search' ) ); ?>
				<?php endif; ?>
				
				<?php if ($show_recent_view == 'on'): ?>
         			<?php include( swquicktools_plugin_tool_override_check( '', 'recent_view' ) ); ?>	
				<?php endif; ?>
			</div>		
		<?php	
	    }
}
add_action( 'wp_footer', 'swquicktools_tools_plugin' );



add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>
 
	<?php global $woocommerce; ?>
	<?php include( swquicktools_plugin_tool_override_check( '', 'mini-cart-ajax' ) ); ?>
	<?php
	$fragments['.sw-mini-cart-ajax'] = ob_get_clean();
	return $fragments;
}

function swquicktools_woocommerce_recently_viewed_products( $atts, $content = null ) {
    // Get shortcode parameters
    extract(shortcode_atts(array(
        "per_page" => '5'
    ), $atts));
    // Get WooCommerce Global
    global $woocommerce;

    // Get recently viewed product cookies data
    $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
    $viewed_products = array_filter( array_map( 'absint', $viewed_products ) );
    // If no data, quit
    if ( empty( $viewed_products ) )
        return __( 'Has no content to show !','sw-tools-plugin' );

    // Create the object
    ob_start();

    // Get products per page
    if( !isset( $per_page ) ? $number = 5 : $number = $per_page )

    // Create query arguments array
    $query_args = array(
                    'posts_per_page' => $number, 
                    'no_found_rows'  => 1, 
                    'post_status'    => 'publish', 
                    'post_type'      => 'product', 
                    'post__in'       => $viewed_products, 
                    'orderby'        => 'rand'
                    );

    // Add meta_query to query args
    $query_args['meta_query'] = array();

    // Check products stock status
    $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();

    // Create a new query
    $r = new WP_Query($query_args);

    // If query return results
    if ( $r->have_posts() ) {

        // Start the loop
        while ( $r->have_posts()) {
            $r->the_post();
            global $product;
			
			$maximumper           = 0;
			$percentage           = 0;
            $regular_price = $product->get_regular_price();
            $sale_price    = $product->get_sale_price();

			if(isset($sale_price) && $sale_price != 0 && floatval( $regular_price ) != 0) {
				$percentage    = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
			}

            if ( $percentage > $maximumper ) {
                $maximumper = $percentage;
            }
			$salehtml = '';
			if($maximumper !== 0):
			   $salehtml = '<span class="bt-sale">-'.$maximumper.'%</span>';
			endif;
            $content .= '
				<div class="sw sw-sm-4 sw-xs-6">
					<div class="form-box">
						<div class="item">
							<div class="product-thumb transition">
								<div class="image">' . $salehtml . '
									<a href="' . get_permalink() . '">
										' . ( has_post_thumbnail() ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) : woocommerce_placeholder_img( 'shop_thumbnail' ) ) . '
									</a>
								</div>
								<div class="caption">
									<h4 class="font-ct"><a href="' . get_permalink() . '" title=" ' . get_the_title() . '" > ' . get_the_title() . '</a></h4>
									<p class="price">' . $product->get_price_html() . '</p>
								</div>
								<div class="button-group">
									<a href="'.do_shortcode("[add_to_cart_url id=".$product->get_id()."]").'" class="add_to_cart_button ajax_add_to_cart" data-product_id='.$product->get_id().' >
											<span class="">'.esc_html__( 'Add to cart','sw-tools-plugin').'</span>
									</a>
								</div>								
							</div>
						</div>
					</div>	
			    </div>';
        }
    }

    // Get clean object
    $content .= ob_get_clean();

    // Return whole content
    return $content;
}

// Register the shortcode
add_shortcode("woocommerce_recently_viewed_products", "swquicktools_woocommerce_recently_viewed_products");







