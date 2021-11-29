<?php 
/**
* Sw Responsive Category Listing
* Athor: SmartAddons
**/

if( !class_exists('sw_resp_category_slider') ) :
	add_action( 'widgets_init', 'sw_resp_category_register' );
	function sw_resp_category_register(){
			register_widget( 'sw_resp_category_slider' );
	}
	class sw_resp_category_slider extends WP_Widget {
			/**
			 * Widget setup.
			 */
			function __construct() {

				/* Widget settings. */
				$widget_ops = array( 'classname' => 'sw_resp_category_slider', 'description' => __('Sw Responsive Categories Slider Widget', 'sw_core') );

				/* Widget control settings. */
				$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_resp_category_slider' );

				/* Create the widget. */
				parent::__construct( 'sw_resp_category_slider', __('Sw Responsive Categories Slider Widget', 'sw_core'), $widget_ops, $control_ops );
				
				/* Create Shortcode */
				add_shortcode( 'resp_category', array( $this, 'resp_category_shortcode_callback' ) );
				
				/* Create Vc_map */
				if (class_exists('Vc_Manager')) {
					add_action( 'vc_before_init', array( $this, 'SC_integrateWithVC' ) );
				}
				add_action( 'wp_enqueue_scripts',  array( $this, 'sw_category_listing_script' ) );
				
				add_action( 'wp_ajax_sw_ajax_tab_listing', array( $this, 'sw_ajax_tab_listing_callback' ) );
				add_action( 'wp_ajax_nopriv_sw_ajax_tab_listing', array( $this, 'sw_ajax_tab_listing_callback' ) );
			}
			
			/**
			* Enqueue js for attribute color
			**/
			 function sw_category_listing_script(){
				$args = array(
					'ajaxurl'	=> admin_url('admin-ajax.php'),
					);		
				
				wp_register_script( 'sw-resp-category-ajax', SWURL . '/js/sw-resp-category-ajax.js', array(), null, true );
				wp_localize_script( 'sw-resp-category-ajax', 'frontend_val', $args );
				wp_enqueue_script( 'sw-resp-category-ajax' );
			}
			
		/**
		* Add Vc Params
		**/
		function SC_integrateWithVC(){
				$terms = get_categories();
				$term = array( __( 'Select Category', 'sw_core' ) => '' );
				if( count( $terms )  > 0 ){
					foreach( $terms as $cat ){
						$term[$cat->name] = $cat -> slug;
					}
				}
				vc_map( array(
					"name" => __( "SW Resp Categories Listing", 'sw_core' ),
					"base" => "resp_category",
					"icon" => "icon-wpb-ytc",
					"class" => "",
					"category" => __( "SW Core", 'sw_core'),
					"params" => array(
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Title", 'sw_core' ),
							"param_name" => "title1",
							"admin_label" => true,
							"value" => '',
							"description" => __( "Title", 'sw_core' )
							),			 
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Description", 'sw_core' ),
							"param_name" => "description",
							"admin_label" => true,
							"value" => '',
							"description" => __( "Description", 'sw_core' )
							),
						array(
							"type" => "multiselect",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Category", 'sw_core' ),
							"param_name" => "category",
							"admin_label" => true,
							"value" => $term,
							"description" => __( "Select Categories", 'sw_core' )
							),			 
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Order By", 'sw_core' ),
							"param_name" => "orderby",
							"admin_label" => true,
							"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
							"description" => __( "Order By", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Order", 'sw_core' ),
							"param_name" => "order",
							"admin_label" => true,
							"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
							"description" => __( "Order", 'sw_core' )
							),
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Number Of Post", 'sw_core' ),
							"param_name" => "numberposts",
							"admin_label" => true,
							"value" => 5,
							"description" => __( "Number Of Post", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Number row per column", 'sw_core' ),
							"param_name" => "item_row",
							"admin_label" => true,
							"value" =>array(1,2,3),
							"description" => __( "Number row per column", 'sw_core' )
							),				 
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Tab Active", 'sw_core' ),
							"param_name" => "tab_active",
							"admin_label" => true,
							"value" => 1,
							"description" => __( "Select tab active", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Number of Columns >1200px: ", 'sw_core' ),
							"param_name" => "columns",
							"admin_label" => true,
							"value" => array(1,2,3,4,5,6),
							"description" => __( "Number of Columns >1200px:", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Number of Columns on 992px to 1199px:", 'sw_core' ),
							"param_name" => "columns1",
							"admin_label" => true,
							"value" => array(1,2,3,4,5,6),
							"description" => __( "Number of Columns on 992px to 1199px:", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Number of Columns on 768px to 991px:", 'sw_core' ),
							"param_name" => "columns2",
							"admin_label" => true,
							"value" => array(1,2,3,4,5,6),
							"description" => __( "Number of Columns on 768px to 991px:", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Number of Columns on 480px to 767px:", 'sw_core' ),
							"param_name" => "columns3",
							"admin_label" => true,
							"value" => array(1,2,3,4,5,6),
							"description" => __( "Number of Columns on 480px to 767px:", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Number of Columns in 480px or less than:", 'sw_core' ),
							"param_name" => "columns4",
							"admin_label" => true,
							"value" => array(1,2,3,4,5,6),
							"description" => __( "Number of Columns in 480px or less than:", 'sw_core' )
							),
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Speed", 'sw_core' ),
							"param_name" => "speed",
							"admin_label" => true,
							"value" => 1000,
							"description" => __( "Speed Of Slide", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Auto Play", 'sw_core' ),
							"param_name" => "autoplay",
							"admin_label" => true,
							"value" => array( 'False' => 'false', 'True' => 'true' ),
							"description" => __( "Auto Play", 'sw_core' )
							),
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Interval", 'sw_core' ),
							"param_name" => "interval",
							"admin_label" => true,
							"value" => 5000,
							"description" => __( "Interval", 'sw_core' )
							),
						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Layout", 'sw_core' ),
							"param_name" => "layout",
							"admin_label" => true,
							"value" => array( 'Layout Default' => 'default', 'Layout Style 1' => 'layout1', 'Layout Style 2' => 'layout2' ),
							"description" => __( "Layout", 'sw_core' )
							),
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __( "Total Items Slided", 'sw_core' ),
							"param_name" => "scroll",
							"admin_label" => true,
							"value" => 1,
							"description" => __( "Total Items Slided", 'sw_core' )
							),
						)
		) 
		);
	}

	function resp_category_shortcode_callback( $atts, $content = null ){
			extract( shortcode_atts(
				array(
					'title1' => '',
					'category'  => '',
					'orderby' => '',
					'order'	=> '',
					'numberposts' => 5,
					'tab_active' => 1,
					'length' => 25,
					'item_row'=> 1,
					'columns' => 4,
					'columns1' => 4,
					'columns2' => 3,
					'columns3' => 2,
					'columns4' => 1,
					'speed' => 1000,
					'autoplay' => 'false',
					'interval' => 2000,
					'layout'  => 'default',
					'scroll' => 1,
					'el_class' => 'el class',
					), $atts )
			);
			ob_start();		
			if( $layout == 1 ){
				include( 'themes/default.php' );
			}elseif( $layout == 2 ){
				include( 'themes/layout1.php' );
			}elseif( $layout == 3 ){
				include( 'themes/layout2.php' );
			}
			
			$content = ob_get_clean();
			return $content;
		}
		
		function sw_ajax_tab_listing_callback(){
			$cat 			 	 = ( isset( $_POST["catid"] )   	&& $_POST["catid"] != '' ) ? $_POST["catid"] : '';
			$target      = ( isset( $_POST["target"] )  	&& $_POST["target"] != '' ) ? str_replace( '#', '', $_POST["target"] ) : '';
			$layout      	= ( isset( $_POST["layout"] )  	&& $_POST["layout"] != '' ) ? $_POST["layout"] : 'default';
			$numberposts = ( isset( $_POST["number"] )  	&& $_POST["number"] > 0 ) ? $_POST["number"] : 0;
			$item_row    = ( isset( $_POST["item_row"] )  && $_POST["item_row"] > 0 ) ? $_POST["item_row"] : 1;
			$columns		 = ( isset( $_POST["columns"] )   && $_POST["columns"] > 0 ) ? $_POST["columns"] : 1;
			$columns1		 = ( isset( $_POST["columns1"] )  && $_POST["columns1"] > 0 ) ? $_POST["columns1"] : 1;
			$columns2		 = ( isset( $_POST["columns2"] )  && $_POST["columns2"] > 0 ) ? $_POST["columns2"] : 1;
			$columns3		 = ( isset( $_POST["columns3"] )  && $_POST["columns3"] > 0 ) ? $_POST["columns3"] : 1;
			$columns4		 = ( isset( $_POST["columns4"] )  && $_POST["columns4"] > 0 ) ? $_POST["columns4"] : 1;
			$interval		 = ( isset( $_POST["interval"] )  && $_POST["interval"] > 0 ) ? $_POST["interval"] : 1000;
			$speed			 = ( isset( $_POST["speed"] )  	  && $_POST["speed"] > 0 ) ? $_POST["speed"] : 1000;
			$scroll			 = ( isset( $_POST["scroll"] )  	&& $_POST["scroll"] > 0 ) ? $_POST["scroll"] : 1;
			$orderby 		 = ( isset( $_POST["orderby"] ) 	&& $_POST["orderby"] != '' ) ? $_POST["orderby"] : 'ID';
			$order 		   = ( isset( $_POST["order"] ) 	&& $_POST["order"] != '' ) ? $_POST["order"] : 'DESC';
			$autoplay		 = ( isset( $_POST["autoplay"] )  && $_POST["autoplay"] != '' ) ? $_POST["autoplay"] : 'false';

			$default = array(
				'category' => $cat, 
				'orderby' => $orderby,
				'order' => $order, 
				'numberposts' => $numberposts,
				);
			$list = get_posts($default);
			?>
			<div class="tab-pane active" id="<?php echo esc_attr( $target ) ?>">
				<?php if(  $list ) : ?>
					<div id="<?php echo esc_attr( $target ); ?>" class="responsive-post-slider clearfix" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
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
						<p>'. esc_html__( 'There is not product on this tab', 'sw_core' ) .'</p>
					</div>';
					endif;
					?>
				</div>
				<?php 
				exit;
			}
		/**
		 * Display the widget on the screen.
		 */
		/**
		 * Display the widget on the screen.
		 */
		public function widget( $args, $instance ) {
			wp_reset_postdata();
			extract($args);
			$title = apply_filters( 'widget_title', empty( $instance['title1'] ) ? '' : $instance['title1'], $instance, $this->id_base );
			$description1 = apply_filters( 'widget_description', empty( $instance['description1'] ) ? '' : $instance['description1'], $instance, $this->id_base );
			echo $before_widget;
			if ( !empty( $title ) && !empty( $description1 ) ) { echo $before_title . $title . $after_title . '<h5 class="category_description clearfix">' . $description1 . '</h5>'; }
			else if (!empty( $title ) && $description1==NULL ){ echo $before_title . $title . $after_title; }
			
			if (!isset($instance['category'])){
				$instance['category'] = 0;
			}
			extract($instance);

			if ( !array_key_exists('widget_template', $instance) ){
				$instance['widget_template'] = 'default';
			}
			
			if ( $tpl = $this->getTemplatePath( $instance['widget_template'] ) ){ 
				$link_img = plugins_url('images/', __FILE__);
				$widget_id = $args['widget_id'];		
				include $tpl;
			}
					
			/* After widget (defined by themes). */
			echo $after_widget;
		}    
		
		protected function getTemplatePath($tpl='default', $type=''){
			$file = '/'.$tpl.$type.'.php';
			$dir =realpath(dirname(__FILE__)).'/themes';
			
			if ( file_exists( $dir.$file ) ){
				return $dir.$file;
			}
			
			return $tpl=='default' ? false : $this->getTemplatePath('default', $type);
		}
		
		/**
		 * Update the widget settings.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			// strip tag on text field
			$instance['title1'] = strip_tags( $new_instance['title1'] );
			$instance['description'] = strip_tags( $new_instance['description'] );
			// int or array
			if ( array_key_exists('category', $new_instance) ){
				if ( is_array($new_instance['category']) ){
					$instance['category'] = array_map( 'intval', $new_instance['category'] );
				} else {
					$instance['category'] = intval($new_instance['category']);
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
			
			if ( array_key_exists('length', $new_instance) ){
				$instance['length'] = intval( $new_instance['length'] );
			}
			
			if ( array_key_exists('tab_active', $new_instance) ){
				$instance['tab_active'] = intval( $new_instance['tab_active'] );
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
			if ( array_key_exists('columns4', $new_instance) ){
				$instance['columns4'] = intval( $new_instance['columns4'] );
			}		
			if ( array_key_exists('interval', $new_instance) ){
				$instance['interval'] = intval( $new_instance['interval'] );
			}
			if ( array_key_exists('speed', $new_instance) ){
				$instance['speed'] = intval( $new_instance['speed'] );
			}
			if ( array_key_exists('start', $new_instance) ){
				$instance['start'] = intval( $new_instance['start'] );
			}
			if ( array_key_exists('scroll', $new_instance) ){
				$instance['scroll'] = intval( $new_instance['scroll'] );
			}	
			if ( array_key_exists('autoplay', $new_instance) ){
				$instance['autoplay'] = strip_tags( $new_instance['autoplay'] );
			}
					$instance['widget_template'] = strip_tags( $new_instance['widget_template'] );
					
								
			return $instance;
		}
		function category_select( $field_name, $opts = array(), $field_value = null ){
			$default_options = array(
					'multiple' => true,
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
					$allow_select_all = '<option value="0">All Categories</option>';
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
			
			$categories = get_categories();
			//print '<pre>'; var_dump($categories);
			// if (!$templates) return '';
			$all_category_ids = array();
			foreach ($categories as $cat) $all_category_ids[] = (int)$cat->term_id;
			
			$is_valid_field_value = is_numeric($field_value) && in_array($field_value, $all_category_ids);
			if (!$is_valid_field_value && is_array($field_value)){
				$intersect_values = array_intersect($field_value, $all_category_ids);
				$is_valid_field_value = count($intersect_values) > 0;
			}
			if (!$is_valid_field_value){
				$field_value = '0';
			}
		
			$select_html = '<select ' . $select_attributes . '>';
			if (isset($allow_select_all)) $select_html .= $allow_select_all;
			foreach ($categories as $cat){
				$select_html .= '<option value="' . $cat->term_id . '"';
				if ($cat->term_id == $field_value || (is_array($field_value)&&in_array($cat->term_id, $field_value))){ $select_html .= ' selected="selected"';}
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
			$defaults = array();
			$instance = wp_parse_args( (array) $instance, $defaults ); 		
							 
			$title1 = isset( $instance['title1'] )    ? 	strip_tags($instance['title1']) : '';
			$description = isset( $instance['description'] )    ? 	strip_tags($instance['description']) : '';
			$categoryid = isset( $instance['category'] )    ? $instance['category'] : 0;
			$style      	= isset( $instance['style'] )       	? strip_tags($instance['style']) : '';
			$orderby    = isset( $instance['orderby'] )     ? strip_tags($instance['orderby']) : 'ID';
			$order      = isset( $instance['order'] )       ? strip_tags($instance['order']) : 'ASC';
			$number     = isset( $instance['numberposts'] ) ? intval($instance['numberposts']) : 5;
			$length     = isset( $instance['length'] )      ? intval($instance['length']) : 25;
			$tab_active    	= isset( $instance['tab_active'] )    ? intval($instance['tab_active']) : 1;
			$columns     = isset( $instance['columns'] )      ? intval($instance['columns']) : '';
			$columns1     = isset( $instance['columns1'] )      ? intval($instance['columns1']) : '';
			$columns2     = isset( $instance['columns2'] )      ? intval($instance['columns2']) : '';
			$columns3     = isset( $instance['columns3'] )      ? intval($instance['columns3']) : '';
			$columns4     = isset( $instance['columns4'] )      ? intval($instance['columns4']) : '';
			$autoplay     = isset( $instance['autoplay'] )      ? strip_tags($instance['autoplay']) : 'true';
			$interval     = isset( $instance['interval'] )      ? intval($instance['interval']) : 5000;
			$speed     = isset( $instance['speed'] )      ? intval($instance['speed']) : 1000;
			$scroll     = isset( $instance['scroll'] )      ? intval($instance['scroll']) : 1;
			$widget_template   = isset( $instance['widget_template'] ) ? strip_tags($instance['widget_template']) : 'default';
										 
									 
			?>		
					</p> 
						<div style="background: Blue; color: white; font-weight: bold; text-align:center; padding: 3px"> * Data Config * </div>
					</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Title', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>"
					type="text"	value="<?php echo esc_attr($title1); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"
					type="text"	value="<?php echo esc_attr($description); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category ID', 'sw_core')?></label>
				<br />
				<?php echo $this->category_select('category', array('allow_select_all' => true), $categoryid); ?>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby', 'sw_core')?></label>
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
				<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'sw_core')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
					<option value="DESC" <?php if ($order=='DESC'){?> selected="selected"
					<?php } ?>>
						<?php _e('Descending', 'sw_core')?>
					</option>
					<option value="ASC" <?php if ($order=='ASC'){?> selected="selected"	<?php } ?>>
						<?php _e('Ascending', 'sw_core')?>
					</option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of Posts', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>"
					type="text"	value="<?php echo esc_attr($number); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('length'); ?>"><?php _e('Excerpt length (in words): ', 'sw_core')?></label>
				<br />
				<input class="widefat"
					id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" 
					value="<?php echo esc_attr($length); ?>" />
			</p>  
			
			<p>
				<label for="<?php echo $this->get_field_id('tab_active'); ?>"><?php _e('Tab active: ', 'sw_woocommerce')?></label>
				<br />
				<input class="widefat"
					id="<?php echo $this->get_field_id('tab_active'); ?>" name="<?php echo $this->get_field_name('tab_active'); ?>" type="text" 
					value="<?php echo esc_attr($tab_active); ?>" />
			</p> 
			
			<?php $number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6); ?>
			<p>
				<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns >1200px: ', 'sw_core')?></label>
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
				<label for="<?php echo $this->get_field_id('columns1'); ?>"><?php _e('Number of Columns on 992px to 1199px: ', 'sw_core')?></label>
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
				<label for="<?php echo $this->get_field_id('columns2'); ?>"><?php _e('Number of Columns on 768px to 991px: ', 'sw_core')?></label>
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
				<label for="<?php echo $this->get_field_id('columns3'); ?>"><?php _e('Number of Columns on 480px to 767px: ', 'sw_core')?></label>
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
				<label for="<?php echo $this->get_field_id('columns4'); ?>"><?php _e('Number of Columns in 480px or less than: ', 'sw_core')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns4'); ?>"
					name="<?php echo $this->get_field_name('columns4'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns4){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p> 
			
			<p>
				<label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', 'sw_core')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>">
					<option value="false" <?php if ($autoplay=='false'){?> selected="selected"
					<?php } ?>>
						<?php _e('False', 'sw_core')?>
					</option>
					<option value="true" <?php if ($autoplay=='true'){?> selected="selected"	<?php } ?>>
						<?php _e('True', 'sw_core')?>
					</option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('interval'); ?>"><?php _e('Interval', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>"
					type="text"	value="<?php echo esc_attr($interval); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('speed'); ?>"><?php _e('Speed', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('speed'); ?>" name="<?php echo $this->get_field_name('speed'); ?>"
					type="text"	value="<?php echo esc_attr($speed); ?>" />
			</p>
			
			
			<p>
				<label for="<?php echo $this->get_field_id('scroll'); ?>"><?php _e('Total Items Slided', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('scroll'); ?>" name="<?php echo $this->get_field_name('scroll'); ?>"
					type="text"	value="<?php echo esc_attr($scroll); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('widget_template'); ?>"><?php _e("Template", 'sw_core')?></label>
				<br/>
				
				<select class="widefat"
					id="<?php echo $this->get_field_id('widget_template'); ?>"	name="<?php echo $this->get_field_name('widget_template'); ?>">
					<option value="default" <?php if ($widget_template=='default'){?> selected="selected"
					<?php } ?>>
						<?php _e('Default', 'sw_core')?>
					</option>
					<option value="layout1" <?php if ($widget_template=='layout1'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout1', 'sw_core')?>
					</option>
					<option value="layout2" <?php if ($widget_template=='layout2'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout2', 'sw_core')?>
					</option>
					</option>
				</select>
			</p>           
		<?php
		}		
	}
endif;
?>