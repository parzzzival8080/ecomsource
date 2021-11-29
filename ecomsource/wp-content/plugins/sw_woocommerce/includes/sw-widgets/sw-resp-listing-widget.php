<?php
/**
 * Sw Woocommerce Responsive Listing
 * Plugin URI: http://www.magentech.com
 * Version: 1.0
 * This Widget help you to show images of product as a beauty tab reponsive slideshow
 */
if ( !class_exists('sw_resp_listing_widget') ) {
	class sw_resp_listing_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'sw_resp_listing_widget', 'description' => __('Sw Woocommerce Responsive Listing', 'sw_woocommerce') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_resp_listing_widget' );

			/* Create the widget. */
			parent::__construct( 'woo_resp_listing', __('Sw Woocommerce Responsive Listing', 'sw_woocommerce'), $widget_ops, $control_ops );
					
			add_shortcode( 'woo_resp_listing', array( $this, 'SC_WooResp' ) );
			
			/* Create Vc_map */
			if ( class_exists('Vc_Manager') && class_exists( 'WooCommerce' ) ) {
				add_action( 'vc_before_init', array( $this, 'SCRL_integrateWithVC' ) );
			}
			
			/* Add ajax */
			if( version_compare( WC()->version, '2.4', '>=' ) ){
					add_action( 'wc_ajax_sw_resp_listing_ajax', array( $this, 'sw_resp_listing_ajax' ) );
			}else{
				add_action( 'wp_ajax_sw_resp_listing_ajax', array( $this, 'sw_resp_listing_ajax') );
				add_action( 'wp_ajax_nopriv_sw_resp_listing_ajax', array( $this, 'sw_resp_listing_ajax') );
			}
		}

		/**
			* Add Vc Params
		**/
		function SCRL_integrateWithVC(){
			$terms = get_terms( 'product_cat', array( 'parent' => '', 'hide_empty' => false ) );
			if( count( $terms ) == 0 ){
				return ;
			}
			$term = array( __( 'All Category Product', 'sw_woocommerce' ) => '' );
			foreach( $terms as $cat ){
				$term[$cat->name] = $cat -> slug;
			}		
			vc_map( array(
				"name" => __( "SW Woocommerce Responsive Listing", 'sw_woocommerce' ),
				"base" => "woo_resp_listing",
				"icon" => "icon-wpb-ytc",
				"class" => "",
				"category" => __( "SW Shortcodes", 'sw_woocommerce'),
				"params" => array(
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Title", 'sw_woocommerce' ),
					"param_name" => "title1",
					"value" => '',
					"description" => __( "Title", 'sw_woocommerce' )
				),				
				  array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Category", 'sw_woocommerce' ),
					"param_name" => "category",
					"value" => $term,
					"description" => __( "Select Categories", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Select Order Product", 'sw_woocommerce' ),
					"param_name" => "select_order",
					"value" => array('Latest Products' => 'latest', 'Best Sellers' => 'bestsales', 'Top Rating Products' => 'rating', 'Featured Products' => 'featured'),
					"description" => __( "Select Order Product", 'sw_woocommerce' )
				 ),			 
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number Of Post", 'sw_woocommerce' ),
					"param_name" => "numberposts",
					"value" => 5,
					"description" => __( "Number Of Post", 'sw_woocommerce' )
				 ),			
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns desktop: ", 'sw_woocommerce' ),
					"param_name" => "columns",
					"value" => array(1,2,3,4),
					"description" => __( "Number of Columns desktop:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on small desktop:", 'sw_woocommerce' ),
					"param_name" => "columns1",
					"value" => array(1,2,3,4),
					"description" => __( "Number of Columns on small desktop:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on tablet:", 'sw_woocommerce' ),
					"param_name" => "columns2",
					"value" => array(1,2,3,4),
					"description" => __( "Number of Columns on tablet:", 'sw_woocommerce' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on smartphone:", 'sw_woocommerce' ),
					"param_name" => "columns3",
					"value" => array(1,2,3,4),
					"description" => __( "Number of Columns on smartphone:", 'sw_woocommerce' )
				 ),			 		 
				array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Layout", 'sw_woocommerce' ),
					"param_name" => "layout",
					"value" => array( 'Layout Default' => 'default' ),
					"description" => __( "Layout", 'sw_woocommerce' )
				 ),
			  )
		   ) );
		}
		/**
			** Add Shortcode
		**/
		function SC_WooResp( $atts, $content = null ){
			extract( shortcode_atts(
				array(
					'title1' => '',
					'category' => '',
					'select_order' => 'latest',				
					'numberposts' => 5,					
					'columns' => 4,
					'columns1' => 4,
					'columns2' => 3,
					'columns3' => 2,					
					'layout'  => 'default',
				), $atts )
			);
			ob_start();	
			if( $layout == 'default' ){
				include( plugin_dir_path(dirname(__FILE__)).'/themes/sw-woo-resp-listing/default.php' );
			}
			
			$content = ob_get_clean();
			
			return $content;
		}
		
		/*Call to order clause*/
		public static function order_by_rating_post_clauses( $args ) {
			global $wpdb;

			$args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";

			$args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";

			$args['join'] .= "
				LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
				LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
			";

			$args['orderby'] = "average_rating DESC, $wpdb->posts.post_date DESC";

			$args['groupby'] = "$wpdb->posts.ID";

			return $args;
		}
		
		public function ya_trim_words( $text, $num_words = 30, $more = null ) {
			$text = strip_shortcodes( $text);
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			return wp_trim_words($text, $num_words, $more);
		}
		/**
		 * Display the widget on the screen.
		 */
		public function widget( $args, $instance ) {
			extract($args);
			
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			echo $before_widget;
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

			if ( !array_key_exists('widget_template', $instance) ){
				$instance['widget_template'] = 'default';
			}
			extract($instance);			
			if ( $tpl = $this->getTemplatePath( $instance['widget_template'] ) ){ 
				$link_img = plugins_url('images/', __FILE__);
				include $tpl;
			}
					
			/* After widget (defined by themes). */
			echo $after_widget;
		}    

		protected function getTemplatePath($tpl='default', $type=''){
			$file = '/'.$tpl.$type.'.php';
			$dir = plugin_dir_path(dirname(__FILE__)).'/themes/sw-woo-resp-listing';
			
			if ( file_exists( $dir.$file ) ){
				return $dir.$file;
			}
			
			return $tpl=='default' ? false : $this->getTemplatePath('default', $type);
		}
		
		/*
		** Ajax Callback
		*/
		public function sw_resp_listing_ajax(){
			
			$category			= ( isset( $_POST["catid"] ) && $_POST["catid"] != '' ) ? $_POST["catid"] : '';
			$layout      	  = ( isset( $_POST["layout"] )  	&& $_POST["layout"] != '' ) ? $_POST["layout"] : 'default';
			$page 				= ( isset( $_POST["page"]) ) ? $_POST["page"] : 1;
			$attributes 	= ( isset( $_POST["attributes"] )  && $_POST["attributes"] != '' ) ? $_POST["attributes"] : '';
			$orderby 		= ( isset( $_POST["orderby"] ) 	&& $_POST["orderby"] != '' ) ? $_POST["orderby"] : 'date';
			$order 		   	= ( isset( $_POST["order"] ) 	&& $_POST["order"] != '' ) ? $_POST["order"] : 'DESC';
			$number 			= ( isset( $_POST["numb"] ) ) ? $_POST["numb"] : 0;
			$title_length 	= ( isset( $_POST["title_length"] )  	&& $_POST["title_length"] > 0 ) ? $_POST["title_length"] : 0;
			$paged 				= ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			
			if( $layout == 'theme2' ):
			
				$default = array(
					'post_type' => 'product',		
					'orderby' => $orderby,
					'order' => $order,
					'meta_key'		=> 'recommend_product',
					'meta_value'	=> '1',
					'post_status' => 'publish',
					'showposts' => $number,
					'offset' => $number*$page,
				);
			elseif( $layout == 'theme3' ):
			
				$default = array(
					'post_type' => 'product',		
					'post_status' => 'publish',
					'showposts' => $number,
					'ignore_sticky_posts'   => 1,
					'meta_key' 		 		=> 'total_sales',
					'orderby' 		 		=> 'meta_value_num '. $orderby ,
					'order' => $order,
					'offset' => $number*$page,
					);
			else:
				
				$default = array(
					'post_type'	=> 'product',
					'post_status' => 'publish',
					'orderby' => $orderby,
					'order' => $order,
					'showposts' => $number,
					'offset' => $number*$page,
				);
				
			endif;
			
			if( $category != '' ){
				$term = get_term_by( 'slug', $category, 'product_cat' );	
				if( $term ) :
					$term_name = $term->name;
				endif;

				$default['tax_query'] = array(
					array(
						'taxonomy'  => 'product_cat',
						'field'     => 'slug',
						'terms'     => $category ),
						'operator' => 'IN'
					);	
			}
			$list = new WP_Query( $default );
			while( $list->have_posts() ) : $list->the_post();
			global $product;			
		?>
		<?php if( $layout == 'theme1' || $layout == 'theme2' || $layout == 'theme3'): ?>
			<div class="<?php echo esc_attr( $attributes ); ?>">
				<div class="item-wrap">
					<div class="item-detail">										
						<div class="item-img products-thumb">			
							<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
							<!-- add to cart, wishlist, compare -->
							<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
						</div>										
						<div class="item-content">
							<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>								
							<!-- rating  -->
							<?php 
							$rating_count = $product->get_rating_count();
							$review_count = $product->get_review_count();
							$average      = $product->get_average_rating();
							?>
							<?php if (  wc_review_ratings_enabled() ) { ?>
							<div class="reviews-content">
								<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*13 ).'px"></span>' : ''; ?></div>
							</div>	
							<?php } ?>
							<!-- end rating  -->
							<!-- price -->
							<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
							<?php } ?>	
						</div>								
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="<?php echo esc_attr( $attributes ); ?>">
				<div class="item-wrap">
					<div class="item-detail">										
						<div class="item-img products-thumb">			
							<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
						</div>										
						<div class="item-content">
							<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(), $title_length ); ?></a></h4>								
							<!-- price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
									<div class="item-price clearfix">
										<span class="title-price"><?php echo esc_html__( 'Staring from','sw_woocommerce' );?></span>
										<span>
											<?php echo $price_html; ?>
										</span>
									</div>
								<?php } ?>
							<?php do_action( 'woocommerce_after_add_to_cart_form'); ?>
						</div>								
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php 
			endwhile; wp_reset_postdata();
			exit();
		}
		
		/**
		 * Update the widget settings.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			// strip tag on text field
			$instance['title1'] = strip_tags( $new_instance['title1'] );
			$instance['title_length'] = intval( $new_instance['title_length'] );
			// int or array
			if ( array_key_exists('category', $new_instance) ){
				if ( is_array($new_instance['category']) ){
					$instance['category'] = $new_instance['category'] ;
				} else {
				$instance['category'] = strip_tags( $new_instance['category'] );
				}
			}	
			
			if ( array_key_exists('orderby', $new_instance) ){
				$instance['orderby'] = strip_tags( $new_instance['orderby'] );
			}

			if ( array_key_exists('order', $new_instance) ){
				$instance['order'] = strip_tags( $new_instance['order'] );
			}
			
			if ( array_key_exists('numberposts', $new_instance) ){
				$instance['numberposts'] = intval( $new_instance['numberposts'] );
			}			
			if ( array_key_exists('columns', $new_instance) ){
				$instance['columns'] = intval( $new_instance['columns'] );
			}
			if ( array_key_exists('columns1', $new_instance) ){
				$instance['columns1'] = intval( $new_instance['columns1'] );
			}
			if ( array_key_exists('columns2', $new_instance) ){
				$instance['columns2'] = intval( $new_instance['columns2'] );
			}
			if ( array_key_exists('columns3', $new_instance) ){
				$instance['columns3'] = intval( $new_instance['columns3'] );
			}			
			$instance['widget_template'] = strip_tags( $new_instance['widget_template'] );
			
						
			
			return $instance;
		}

		function category_select( $field_name, $opts = array(), $field_value = null ){
			$default_options = array(
					'multiple' => false,
					'disabled' => false,
					'size' => 5,
					'class' => 'widefat',
					'required' => false,
					'autofocus' => false,
					'form' => false,
			);
			$opts = wp_parse_args($opts, $default_options);
		
			if ( (is_string($opts['multiple']) && strtolower($opts['multiple'])=='multiple') || (is_bool($opts['multiple']) && $opts['multiple']) ){
				$opts['multiple'] = 'multiple';
				if ( !is_numeric($opts['size']) ){
					if ( intval($opts['size']) ){
						$opts['size'] = intval($opts['size']);
					} else {
						$opts['size'] = 5;
					}
				}
			} else {
				// is not multiple
				unset($opts['multiple']);
				unset($opts['size']);
				if (is_array($field_value)){
					$field_value = array_shift($field_value);
				}
				if (array_key_exists('allow_select_all', $opts) && $opts['allow_select_all']){
					unset($opts['allow_select_all']);
					$allow_select_all = '<option value="">All Categories</option>';
				}
			}
		
			if ( (is_string($opts['disabled']) && strtolower($opts['disabled'])=='disabled') || is_bool($opts['disabled']) && $opts['disabled'] ){
				$opts['disabled'] = 'disabled';
			} else {
				unset($opts['disabled']);
			}
		
			if ( (is_string($opts['required']) && strtolower($opts['required'])=='required') || (is_bool($opts['required']) && $opts['required']) ){
				$opts['required'] = 'required';
			} else {
				unset($opts['required']);
			}
		
			if ( !is_string($opts['form']) ) unset($opts['form']);
		
			if ( !isset($opts['autofocus']) || !$opts['autofocus'] ) unset($opts['autofocus']);
		
			$opts['id'] = $this->get_field_id($field_name);
		
			$opts['name'] = $this->get_field_name($field_name);
			if ( isset($opts['multiple']) ){
				$opts['name'] .= '[]';
			}
			$select_attributes = '';
			foreach ( $opts as $an => $av){
				$select_attributes .= "{$an}=\"{$av}\" ";
			}
			
			$categories = get_terms('product_cat');
			$all_category_ids = array();
			foreach ($categories as $cat) $all_category_ids[] = $cat->slug;
			
			$is_valid_field_value = in_array($field_value, $all_category_ids);
			if (!$is_valid_field_value && is_array($field_value)){
				$intersect_values = array_intersect($field_value, $all_category_ids);
				$is_valid_field_value = count($intersect_values) > 0;
			}
			if (!$is_valid_field_value){
				$field_value = '';
			}

			$select_html = '<select ' . $select_attributes . '>';
			if (isset($allow_select_all)) $select_html .= $allow_select_all;
			foreach ($categories as $cat){			
				$select_html .= '<option value="' . $cat->slug . '"';
				if ($cat->slug == $field_value || (is_array($field_value)&&in_array($cat->slug, $field_value))){ $select_html .= ' selected="selected"';}
				$select_html .=  '>'.$cat->name.'</option>';
			}
			$select_html .= '</select>';
			return $select_html;
		}
		

		/**
		 * Displays the widget settings controls on the widget panel.
		 * Make use of the get_field_id() and get_field_name() function
		 * when creating your form elements. This handles the confusing stuff.
		 */
		public function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults 			= array();
			$instance 			= wp_parse_args( (array) $instance, $defaults ); 		
			$title1				= isset( $instance['title1'] )      ? strip_tags($instance['title1']) : ''; 
			$title_length	    = isset( $instance['title_length'] )  	? intval($instance['title_length']) : 0;				
			$categoryid 		= isset( $instance['category'] )    ? $instance['category'] : null;
			$orderby    	= isset( $instance['orderby'] )     	? strip_tags($instance['orderby']) : 'ID';
			$order      	= isset( $instance['order'] )       	? strip_tags($instance['order']) : 'ASC';		
			$number     		= isset( $instance['numberposts'] ) ? intval($instance['numberposts']) : 5;
			$columns     		= isset( $instance['columns'] )      ? intval($instance['columns']) : 1;
			$columns1     		= isset( $instance['columns1'] )      ? intval($instance['columns1']) : 1;
			$columns2     		= isset( $instance['columns2'] )      ? intval($instance['columns2']) : 1;
			$columns3     		= isset( $instance['columns3'] )      ? intval($instance['columns3']) : 1;
			$widget_template    = isset( $instance['widget_template'] ) ? strip_tags($instance['widget_template']) : 'default';
					   
					 
			?>

			</p> 
			  <div style="background: Blue; color: white; font-weight: bold; text-align:center; padding: 3px"> * Data Config * </div>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Title', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>"
					type="text"	value="<?php echo esc_attr($title1); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('title_length'); ?>"><?php _e('Product Title Length', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('title_length'); ?>" name="<?php echo $this->get_field_name('title_length'); ?>"
				type="text"	value="<?php echo esc_attr($title_length); ?>" />
			</p>
	
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category', 'sw_woocommerce')?></label>
				<br />
				<?php echo $this->category_select('category', array('allow_select_all' => true), $categoryid); ?>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby', 'sw_woocommerce')?></label>
				<br />
				<?php $allowed_keys = array('name' => 'Name', 'author' => 'Author', 'date' => 'Date', 'title' => 'Title', 'modified' => 'Modified', 'parent' => 'Parent', 'ID' => 'ID', 'rand' =>'Rand', 'comment_count' => 'Comment Count'); ?>
				<select class="widefat"
				id="<?php echo $this->get_field_id('orderby'); ?>"
				name="<?php echo $this->get_field_name('orderby'); ?>">
				<?php
				$option ='';
				foreach ($allowed_keys as $value => $key) :
					$option .= '<option value="' . $value . '" ';
				if ($value == $orderby){
					$option .= 'selected="selected"';
				}
				$option .=  '>'.$key.'</option>';
				endforeach;
				echo $option;
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'sw_woocommerce')?></label>
			<br />
			<select class="widefat"
			id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
			<option value="DESC" <?php if ($order=='DESC'){?> selected="selected"
				<?php } ?>>
				<?php _e('Descending', 'sw_woocommerce')?>
			</option>
			<option value="ASC" <?php if ($order=='ASC'){?> selected="selected"	<?php } ?>>
				<?php _e('Ascending', 'sw_woocommerce')?>
			</option>
		</select>
		</p>

			<p>
				<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of Posts', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>"
					type="text"	value="<?php echo esc_attr($number); ?>" />
			</p>
			
			
			<?php $number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4); ?>
			<p>
				<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns >1200px: ', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns'); ?>"
					name="<?php echo $this->get_field_name('columns'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p> 
			
			<p>
				<label for="<?php echo $this->get_field_id('columns1'); ?>"><?php _e('Number of Columns on 992px to 1199px: ', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns1'); ?>"
					name="<?php echo $this->get_field_name('columns1'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns1){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p> 
			
			<p>
				<label for="<?php echo $this->get_field_id('columns2'); ?>"><?php _e('Number of Columns on 768px to 991px: ', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns2'); ?>"
					name="<?php echo $this->get_field_name('columns2'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns2){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p> 
			
			<p>
				<label for="<?php echo $this->get_field_id('columns3'); ?>"><?php _e('Number of Columns on 480px to 767px: ', 'sw_woocommerce')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns3'); ?>"
					name="<?php echo $this->get_field_name('columns3'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns3){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p> 		
			
						
			<p>
				<label for="<?php echo $this->get_field_id('widget_template'); ?>"><?php _e("Template", 'sw_woocommerce')?></label>
				<br/>
				
				<select class="widefat"
					id="<?php echo $this->get_field_id('widget_template'); ?>"	name="<?php echo $this->get_field_name('widget_template'); ?>">
					<option value="default" <?php if ($widget_template=='default'){?> selected="selected"
					<?php } ?>>
						<?php _e('Default', 'sw_woocommerce')?>
					</option>	
					<option value="theme1" <?php if ($widget_template=='theme1'){?> selected="selected"
					<?php } ?>>
						<?php _e('Theme1', 'sw_woocommerce')?>
					</option>
					<option value="theme2" <?php if ($widget_template=='theme2'){?> selected="selected"
					<?php } ?>>
						<?php _e('Theme2', 'sw_woocommerce')?>
					</option>	
					<option value="theme3" <?php if ($widget_template=='theme3'){?> selected="selected"
					<?php } ?>>
						<?php _e('Theme3', 'sw_woocommerce')?>
					</option>	
				</select>
			</p>               
		<?php
		}		
	}
}
?>