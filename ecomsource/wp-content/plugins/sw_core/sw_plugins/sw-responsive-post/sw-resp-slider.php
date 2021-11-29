<?php
/**
 * Name: SW Responsive Slider Widget
 * Version: 1.0
 * Author: smartaddons.com
 * Author URI: http://smartaddons.com
 */

if( !class_exists('sw_resp_slider') ) :
	add_action( 'widgets_init', 'sw_resp_register' );
	function sw_resp_register(){
			register_widget( 'sw_resp_slider' );
	}
	 
	class sw_resp_slider extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {

			/* Widget settings. */
			$widget_ops = array( 'classname' => 'sw_resp_slider', 'description' => __('Sw Responsive Slider Widget', 'sw_core') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_resp_slider' );

			/* Create the widget. */
			parent::__construct( 'sw_resp_slider', __('Sw Responsive Slider Widget', 'sw_core'), $widget_ops, $control_ops );
			
			/* Create Shortcode */
			add_shortcode( 'respost_slide', array( $this, 'RP_Shortcode' ) );
			
			/* Add Custom field to category product */
			add_action( 'category_add_form_fields', array( $this, 'add_category_fields' ), 100 );
			add_action( 'category_edit_form_fields', array( $this, 'edit_category_fields' ), 100 );
			add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
			add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );
			
			/* Create Vc_map */
			if (class_exists('Vc_Manager')) {
				add_action( 'vc_before_init', array( $this, 'RP_integrateWithVC' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'resp_slider_script' ) );
		}
		
		/*
		** Enqueue Script
		*/
		function resp_slider_script(){	
			wp_register_script( 'slick_slider', SWURL . '/js/slick.min.js' ,array(), null, false );		
			if (!wp_script_is('slick_slider')) {
				wp_enqueue_script('slick_slider');
			}                
		}
		
		/**
		* Add Vc Params
		**/
		function RP_integrateWithVC(){
			$link_category = array( __( 'All Categories', 'js_composer' ) => '' );
			$link_cats     = get_categories();
			//var_dump($link_cats);
			//var_dump($link_category);
			if ( is_array( $link_cats ) ) {
				foreach ( $link_cats as $link_cat ) {
					$link_category[ $link_cat->name ] = $link_cat->term_id;
				}
			}
			vc_map( array(
				"name" => __( "SW Responsive Post Slider", 'sw_core' ),
				"base" => "respost_slide",
				"icon" => "icon-wpb-ytc",
				"class" => "",
				"category" => __( "SW Core", 'sw_core'),
				"params" => array(
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Title", 'sw_core' ),
					"param_name" => "title2",
					"value" => "",
					"description" => __( "Title", 'sw_core' )
				 ),
				 
					array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Description", 'sw_core' ),
					"param_name" => "description",
					"value" => "",
					"description" => __( "Description", 'sw_core' )
				 ),
				 
				 array(
				'param_name'    => 'category',
				'type'          => 'dropdown',
				'value'         => $link_category, // here I'm stuck
				'heading'       => __('Category filter:', 'sw_core'),
				'description'   => '',
				'holder'        => 'div',
				'class'         => ''
				 ),
				 array(
					'type' => 'textfield',
					'heading' => __( 'Excerpt length (in words)', 'js_composer' ),
					'param_name' => 'length',
					'description' => __( 'Excerpt length (in words).', 'js_composer' )
				),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Order By", 'sw_core' ),
					"param_name" => "orderby",
					"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Title' => 'title', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
					"description" => __( "Order By", 'sw_core' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Order", 'sw_core' ),
					"param_name" => "order",
					"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
					"description" => __( "Order", 'sw_core' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number Of Post", 'sw_core' ),
					"param_name" => "numberposts",
					"value" => 5,
					"description" => __( "Number Of Post", 'sw_core' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns >1200px: ", 'sw_core' ),
					"param_name" => "columns",
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns >1200px:", 'sw_core' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 992px to 1199px:", 'sw_core' ),
					"param_name" => "columns1",
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 992px to 1199px:", 'sw_core' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 768px to 991px:", 'sw_core' ),
					"param_name" => "columns2",
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 768px to 991px:", 'sw_core' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns on 480px to 767px:", 'sw_core' ),
					"param_name" => "columns3",
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns on 480px to 767px:", 'sw_core' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Number of Columns in 480px or less than:", 'sw_core' ),
					"param_name" => "columns4",
					"value" => array(1,2,3,4,5,6),
					"description" => __( "Number of Columns in 480px or less than:", 'sw_core' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Speed", 'sw_core' ),
					"param_name" => "speed",
					"value" => 1000,
					"description" => __( "Speed Of Slide", 'sw_core' )
				 ),
				 array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Auto Play", 'sw_core' ),
					"param_name" => "autoplay",
					"value" => array( 'True' => 'true', 'False' => 'false' ),
					"description" => __( "Auto Play", 'sw_core' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Interval", 'sw_core' ),
					"param_name" => "interval",
					"value" => 5000,
					"description" => __( "Interval", 'sw_core' )
				 ),
					array(
					"type" => "dropdown",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Layout", 'sw_core' ),
					"param_name" => "layout",
					"value" => array( 'Layout Default' => '1', 'Layout Style 1' => '2','Layout Style 2' => '3' ),
					"description" => __( "Layout", 'sw_core' )
				 ),
				 array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Total Items Slided", 'sw_core' ),
					"param_name" => "scroll",
					"value" => 1,
					"description" => __( "Total Items Slided", 'sw_core' )
				 ),
				)
			 ) );
		}
		/**
			** Add Shortcode
		**/
		function RP_Shortcode( $atts, $content = null ){
			extract( shortcode_atts(
				array(
					'title2' => '',
					'description' =>'',
					'category' => '',
					'orderby' => '',
					'order'	=> '',
					'numberposts' => 5,
					'length' => 25,
					'columns' => 4,
					'columns1' => 4,
					'columns2' => 3,
					'columns3' => 2,
					'columns4' => 1,
					'speed' => 1000,
					'autoplay' => 'true',
					'interval' => 5000,
					'layout'  => 1,
					'scroll' => 1
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
		
		public function ya_trim_words( $text, $num_words = 30, $more = null ) {
			$text = strip_shortcodes( $text);
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			return wp_trim_words($text, $num_words, $more);
		}
		
		/**
		*	Add Custom field on category post
		**/
		public function add_category_fields() { 
	?>
			<div class="form-field">
				<label><?php _e( 'Thumbnail', 'sw_core' ); ?></label>
				<div id="post_cat_thumbnail1" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="post_cat_thumbnail_id" name="post_cat_thumbnail_id" />
					<button type="button" class="upload_image_button1 button"><?php _e( 'Upload/Add image', 'sw_core' ); ?></button>
					<button type="button" class="remove_image_button1 button"><?php _e( 'Remove image', 'sw_core' ); ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( ! jQuery( '#post_cat_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button1' ).hide();
					}

					// Uploading files
					var file_frame1;

					jQuery( document ).on( 'click', '.upload_image_button1', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame1 ) {
							file_frame1.open();
							return;
						}

						// Create the media frame.
						file_frame1 = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( "Choose an image", "sw_core" ); ?>',
							button: {
								text: '<?php _e( "Use image", "sw_core" ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame1.on( 'select', function() {
							var attachment = file_frame1.state().get( 'selection' ).first().toJSON();
							
							jQuery( '#post_cat_thumbnail_id' ).val( attachment.id );
							jQuery( '#post_cat_thumbnail1 > img' ).attr( 'src', attachment.sizes.thumbnail.url );
							jQuery( '.remove_image_button1' ).show();
						});

						// Finally, open the modal.
						file_frame1.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button1', function() {
						jQuery( '#post_cat_thumbnail1 img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#post_cat_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button1' ).hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</div>
			<?php
		}
		
		public function edit_category_fields( $term ) {

			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id1', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = wc_placeholder_img_src();
			}
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php _e( 'Thumbnail 1', 'sw_core' ); ?></label></th>
				<td>
					<div id="post_cat_thumbnail1" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
					<div style="line-height: 60px;">
						<input type="hidden" id="post_cat_thumbnail_id" name="post_cat_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
						<button type="button" class="upload_image_button1 button"><?php _e( 'Upload/Add image', 'sw_core' ); ?></button>
						<button type="button" class="remove_image_button1 button"><?php _e( 'Remove image', 'sw_core' ); ?></button>
					</div>
					<script type="text/javascript">

						// Only show the "remove image" button when needed
						if ( '0' === jQuery( '#post_cat_thumbnail_id' ).val() ) {
							jQuery( '.remove_image_button1' ).hide();
						}

						// Uploading files
						var file_frame1;

						jQuery( document ).on( 'click', '.upload_image_button1', function( event ) {

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( file_frame1 ) {
								file_frame1.open();
								return;
							}

							// Create the media frame.
							file_frame1 = wp.media.frames.downloadable_file = wp.media({
								title: '<?php _e( "Choose an image", "sw_core" ); ?>',
								button: {
									text: '<?php _e( "Use image", "sw_core" ); ?>'
								},
								multiple: false
							});

							// When an image is selected, run a callback.
							file_frame1.on( 'select', function() {
								var attachment = file_frame1.state().get( 'selection' ).first().toJSON();

								jQuery( '#post_cat_thumbnail_id' ).val( attachment.id );
								jQuery( '#post_cat_thumbnail1 img' ).attr( 'src', attachment.sizes.thumbnail.url );
								jQuery( '.remove_image_button1' ).show();
							});

							// Finally, open the modal.
							file_frame1.open();
						});

						jQuery( document ).on( 'click', '.remove_image_button1', function() {
							jQuery( '#post_cat_thumbnail1 img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
							jQuery( '#post_cat_thumbnail_id' ).val( '' );
							jQuery( '.remove_image_button1' ).hide();
							return false;
						});

					</script>
					<div class="clear"></div>
				</td>
			</tr>
			<?php
		}
		public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
			if ( isset( $_POST['post_cat_thumbnail_id'] ) ) {
				update_term_meta( $term_id, 'thumbnail_id1', absint( $_POST['post_cat_thumbnail_id'] ) );
			}
		}
		
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
			$instance['title2'] = strip_tags( $new_instance['title2'] );
			if ( array_key_exists('title1', $new_instance) ){
				$instance['title1'] = strip_tags( $new_instance['title1'] );
			}
			$instance['description'] = strip_tags( $new_instance['description'] );
			// int or array
			if ( array_key_exists('category', $new_instance) ){
				if ( is_array($new_instance['category']) ){
					$instance['category'] = array_map( 'intval', $new_instance['category'] );
				} else {
					$instance['category'] = intval($new_instance['category']);
				}
			}
			
			if ( array_key_exists('style', $new_instance) ){
				$instance['style'] = strip_tags( $new_instance['style'] );
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
			
			if ( array_key_exists('img_h', $new_instance) ){
				$instance['img_h'] = intval( $new_instance['img_h'] );
			}
			if ( array_key_exists('img_w', $new_instance) ){
				$instance['img_w'] = intval( $new_instance['img_w'] );
			}
			if ( array_key_exists('crop', $new_instance) ){
				$instance['crop'] = strip_tags( $new_instance['crop'] );
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
			$title2 = isset( $instance['title2'] )    ? 	strip_tags($instance['title2']) : '';
			$description = isset( $instance['description'] )    ? 	strip_tags($instance['description']) : '';
			$categoryid = isset( $instance['category'] )    ? $instance['category'] : 0;
			$style      	= isset( $instance['style'] )       	? strip_tags($instance['style']) : '';
			$orderby    = isset( $instance['orderby'] )     ? strip_tags($instance['orderby']) : 'ID';
			$order      = isset( $instance['order'] )       ? strip_tags($instance['order']) : 'ASC';
			$number     = isset( $instance['numberposts'] ) ? intval($instance['numberposts']) : 5;
					$length     = isset( $instance['length'] )      ? intval($instance['length']) : 25;
			$columns     = isset( $instance['columns'] )      ? intval($instance['columns']) : '';
			$columns1     = isset( $instance['columns1'] )      ? intval($instance['columns1']) : '';
			$columns2     = isset( $instance['columns2'] )      ? intval($instance['columns2']) : '';
			$columns3     = isset( $instance['columns3'] )      ? intval($instance['columns3']) : '';
			$columns4     = isset( $instance['columns4'] )      ? intval($instance['columns4']) : '';
			$autoplay     = isset( $instance['autoplay'] )      ? strip_tags($instance['autoplay']) : 'true';
			$interval     = isset( $instance['interval'] )      ? intval($instance['interval']) : 5000;
			$speed     = isset( $instance['speed'] )      ? intval($instance['speed']) : 1000;
			$scroll     = isset( $instance['scroll'] )      ? intval($instance['scroll']) : 1;
			$img_w     = isset( $instance['img_w'] )      ? intval($instance['img_w']) : 300;
			$img_h     = isset( $instance['img_h'] )      ? intval($instance['img_h']) : 400;
			$crop     = isset( $instance['crop'] )      ? strip_tags($instance['crop']) : 'false';
			$widget_template   = isset( $instance['widget_template'] ) ? strip_tags($instance['widget_template']) : 'default';
										 
									 
			?>		
					</p> 
						<div style="background: Blue; color: white; font-weight: bold; text-align:center; padding: 3px"> * Data Config * </div>
					</p>
			<?php if ( $widget_template=='layout20' || $widget_template=='layout27' ){ ?>
			<p>
				<label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Title1', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>"
					type="text"	value="<?php echo esc_attr($title1); ?>" />
			</p>
			<?php } ?>
			<p>
				<label for="<?php echo $this->get_field_id('title2'); ?>"><?php _e('Title', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('title2'); ?>" name="<?php echo $this->get_field_name('title2'); ?>"
					type="text"	value="<?php echo esc_attr($title2); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"
					type="text"	value="<?php echo esc_attr($description); ?>" />
			</p>
			
			<?php if ( $widget_template=='layout8' ||  $widget_template=='layout16' ){ ?>
			<p>
				<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style', 'sw_core')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
					<option value="" <?php if ($style=='default'){?> selected="selected"
						<?php } ?>>
						<?php _e('Default', 'sw_core')?>
					</option>
					<option value="style1" <?php if ($style=='style1'){?> selected="selected"
						<?php } ?>>
						<?php _e('Style1', 'sw_core')?>
					</option>
				</select>
			</p>
			
			<?php } ?>
			
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
				<label for="<?php echo $this->get_field_id('img_w'); ?>"><?php _e('Set image width', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('img_w'); ?>" name="<?php echo $this->get_field_name('img_w'); ?>"
					type="text"	value="<?php echo esc_attr($img_w); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('img_h'); ?>"><?php _e('Set image height', 'sw_core')?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('img_h'); ?>" name="<?php echo $this->get_field_name('img_h'); ?>"
					type="text"	value="<?php echo esc_attr($img_h); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('crop'); ?>"><?php _e('Crop Images', 'sw_core')?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('crop'); ?>" name="<?php echo $this->get_field_name('crop'); ?>">
					<option value="false" <?php if ($crop=='false'){?> selected="selected"
					<?php } ?>>
						<?php _e('False', 'sw_core')?>
					</option>
					<option value="true" <?php if ($crop=='true'){?> selected="selected"	<?php } ?>>
						<?php _e('True', 'sw_core')?>
					</option>
				</select>
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
					<option value="layout3" <?php if ($widget_template=='layout3'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout3', 'sw_core')?>
					</option>
					<option value="layout4" <?php if ($widget_template=='layout4'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout4', 'sw_core')?>
					</option>
					<option value="layout5" <?php if ($widget_template=='layout5'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout5', 'sw_core')?>
					</option>
					<option value="layout6" <?php if ($widget_template=='layout6'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout6', 'sw_core')?>
					</option>
					<option value="layout7" <?php if ($widget_template=='layout7'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout7', 'sw_core')?>
					</option>
					<option value="layout8" <?php if ($widget_template=='layout8'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout8', 'sw_core')?>
					</option>
					<option value="layout9" <?php if ($widget_template=='layout9'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout9', 'sw_core')?>
					</option>
					<option value="layout10" <?php if ($widget_template=='layout10'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout10', 'sw_core')?>
					</option>
					<option value="layout11" <?php if ($widget_template=='layout11'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout11', 'sw_core')?>
					</option>
					<option value="layout12" <?php if ($widget_template=='layout12'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout Video', 'sw_core')?>
					</option>
					<option value="layout13" <?php if ($widget_template=='layout13'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout Review', 'sw_core')?>
					</option>
					<option value="layout14" <?php if ($widget_template=='layout14'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout List', 'sw_core')?>
					</option>
					<option value="layout15" <?php if ($widget_template=='layout15'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout Live New', 'sw_core')?>
					</option>
					<option value="layout17" <?php if ($widget_template=='layout17'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout Recommend', 'sw_core')?>
					</option>
					<option value="layout16" <?php if ($widget_template=='layout16'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout16', 'sw_core')?>
					</option>
					<option value="layout18" <?php if ($widget_template=='layout18'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout18', 'sw_core')?>
					</option>
					<option value="layout19" <?php if ($widget_template=='layout19'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout19', 'sw_core')?>
					</option>
					<option value="layout20" <?php if ($widget_template=='layout20'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout20', 'sw_core')?>
					</option>
					<option value="layout21" <?php if ($widget_template=='layout21'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout21', 'sw_core')?>
					</option>
					<option value="layout22" <?php if ($widget_template=='layout22'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout22', 'sw_core')?>
					</option>
					<option value="layout23" <?php if ($widget_template=='layout23'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout23', 'sw_core')?>
					</option>
					<option value="layout24" <?php if ($widget_template=='layout24'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout24', 'sw_core')?>
					</option>
					<option value="layout25" <?php if ($widget_template=='layout25'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout25', 'sw_core')?>
					</option>
					<option value="layout26" <?php if ($widget_template=='layout26'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout26', 'sw_core')?>
					</option>
					<option value="layout27" <?php if ($widget_template=='layout27'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout27', 'sw_core')?>
					</option>
					<option value="layout28" <?php if ($widget_template=='layout28'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout28', 'sw_core')?>
					</option>
					<option value="layout29" <?php if ($widget_template=='layout29'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout29', 'sw_core')?>
					</option>
					<option value="layout30" <?php if ($widget_template=='layout30'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout30', 'sw_core')?>
					</option>
					<option value="layout31" <?php if ($widget_template=='layout31'){?> selected="selected"
					<?php } ?>>
						<?php _e('Popular Post', 'sw_core')?>
					</option>
					<option value="layout32" <?php if ($widget_template=='layout32'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout32', 'sw_core')?>
					</option>
					<option value="layout33" <?php if ($widget_template=='layout33'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout33', 'sw_core')?>
					</option>
					<option value="layout34" <?php if ($widget_template=='layout34'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout34', 'sw_core')?>
					</option>
					<option value="layout35" <?php if ($widget_template=='layout35'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout35', 'sw_core')?>
					</option>
					<option value="layout_tab" <?php if ($widget_template=='layout_tab'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout Tab', 'sw_core')?>
					</option>
					<option value="layout36" <?php if ($widget_template=='layout36'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout36', 'sw_core')?>
					</option>
					<option value="layout37" <?php if ($widget_template=='layout37'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout37', 'sw_core')?>
					</option>
					<option value="layout38" <?php if ($widget_template=='layout38'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout38', 'sw_core')?>
					</option>
					<option value="layout39" <?php if ($widget_template=='layout39'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout39', 'sw_core')?>
					</option>
					<option value="layout40" <?php if ($widget_template=='layout40'){?> selected="selected"
					<?php } ?>>
						<?php _e('Layout40', 'sw_core')?>
					</option>
				</select>
			</p>           
		<?php
		}	
	}
endif;
?>