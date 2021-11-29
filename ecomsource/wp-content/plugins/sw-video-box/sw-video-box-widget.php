<?php
/**
 * Plugin Name: SW Video Box
 * Plugin URI: http://wpthemego.com
 * Description: A widget that serves as an slider for developing more advanced widgets.
 * Version: 1.0.2
 * Author: wpthemego.com
 * Author URI: http://wpthemego.com
 *
 * This Widget help you to show images of product as a beauty reponsive slider
 */
add_action( 'widgets_init', 'sw_video_box' );

/**
 * Register our widget.
 * 'Slideshow_Widget' is the widget class used below.
 */
function sw_video_box() {
	register_widget( 'sw_video_box_widget' );
}

/**
 * Load script (css, js).
 * 
 */
 
function load_videobox_script(){
	wp_register_style( 'videobox-css', plugins_url('css/videobox.css', __FILE__));
	if (!wp_style_is('videobox-css')) {
		wp_enqueue_style('videobox-css');
	}
	
	wp_register_style( 'magnific-popup-css', plugins_url('css/magnific-popup.css', __FILE__));
	if (!wp_style_is('magnific-popup-css')) {
		wp_enqueue_style('magnific-popup-css');
	}
	
	wp_register_script( 'magnific_popup', plugins_url( 'js/magnific-popup.min.js', __FILE__ ),array(), null, true );
	if (!wp_script_is('magnific_popup')) {
		wp_enqueue_script('magnific_popup');
	}
}
add_action('wp_enqueue_scripts', 'load_videobox_script', 11);
function _is_youtube($url){
		return (preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url));
	}
function _is_vimeo($url){
	return (preg_match('/vimeo\.com/i', $url));
}

function ya_trim_words( $text, $num_words = 30, $more = null ) {
	$text = strip_shortcodes( $text);
	$text = apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	return wp_trim_words($text, $num_words, $more);
}
/**
 * ya slideshow Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, display, and update.  Nice!
 */
class sw_video_box_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct(){
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sw_video_box', 'description' => __('Sw Video Box', 'sw_video') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_video_box' );

		/* Create the widget. */
		parent::__construct( 'sw_video_box', __('Sw Video Box', 'sw_video'), $widget_ops, $control_ops );

		/* Create Shortcode */
		add_shortcode( 'video_box', array( $this, 'WS_Video_Box_Shortcode' ) );
		
		/* Create Vc_map */
		 if ( class_exists('Vc_Manager') ) {
			  vc_add_shortcode_param( 'dropdown_category_post', array( $this, 'ya_category_post_vc_setting' ) );
			 add_action( 'vc_before_init', array( $this, 'WS_video_box_integrateWithVC' ), 20 );
		 }
	}	
	/**
	* Add Vc Params
	**/
	function ya_category_post_vc_setting( $settings, $value ) {
		$terms = get_categories();
		if( count( $terms ) == 0 ){
			return ;
		}
		$r = array();
		$r['pad_counts'] 	= 1;
		$r['hierarchical'] 	= 1;
		$r['hide_empty'] 	= 1;
		$r['value_field']			= 'slug';
		$r['selected'] 		= esc_attr( $value );
		$r['show_count'] 	= false;
	   return '<select name="'.esc_attr($settings['param_name']).'" class="widefat wpb_vc_param_value wpb-input wpb-select '.esc_attr( $settings['param_name']).' dropdown name"> <option value="">' . __( 'All Categories', 'sw_video' ) . '</option>'.walk_category_dropdown_tree( $terms, 0, $r ).'</select>';
	}
	function WS_video_box_integrateWithVC(){
		$link_category = array( __( 'All Category', 'sw_video' ) => '' );
		$link_cats     = get_categories();
		if ( is_array( $link_cats ) ) {
			foreach ( $link_cats as $link_cat ) {
				$link_category[ $link_cat->name ] = $link_cat->slug;
			}
		}
		vc_map( array(
		  "name" => __( "SW Video Box", 'sw_video' ),
		  "base" => "video_box",
		  "icon" => "icon-wpb-ytc",
		  "class" => "",
		  "category" => __( "SW Shortcodes", 'sw_video'),
		  "params" => array(
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Title", 'sw_video' ),
				"param_name" => "title",
				"value" => '',
				"description" => __( "Title", 'sw_video' )
			 ),
			array(
				"type" => "",
				"holder" => "div",
				"class" => "",
				"heading" => __( "---*---General Options---*---", 'sw_video' ),
				"param_name" => "label1",
			 ),
			  array(
				"type" => "dropdown_category_post",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Category", 'sw_video' ),
				"param_name" => "category",
				"value" => $link_category,
				"admin_label" => true,
				"description" => __( "Select a Category", 'sw_video' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order By", 'sw_video' ),
				"param_name" => "orderby",
				"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
				"description" => __( "Order By", 'sw_video' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order", 'sw_video' ),
				"param_name" => "order",
				"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
				"description" => __( "Order", 'sw_video' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number Of Post", 'sw_video' ),
				"param_name" => "numberposts",
				"value" => 5,
				"description" => __( "Number Of Post", 'sw_video' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Display Video Category", 'sw_video' ),
				"param_name" => "display_category",
				"value" => array( 'Yes' => 1, 'No' => 0 ),
				"description" => __( "Display Video Category", 'sw_video' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Display Video Time", 'sw_video' ),
				"param_name" => "display_time",
				"value" => array( 'Yes' => 1, 'No' => 0 ),
				"description" => __( "Display Video Time", 'sw_video' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Display Video Title", 'sw_video' ),
				"param_name" => "display_title",
				"value" => array( 'Yes' => 1, 'No' => 0 ),
				"description" => __( "Display Video Title", 'sw_video' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Video Title Maxlength", 'sw_video' ),
				"param_name" => "length",
				"value" => 5,
				"admin_label" => true,
				"description" => __( "Length Number Video Title", 'sw_video' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Display Video Description", 'sw_video' ),
				"param_name" => "display_desc",
				"value" => array( 'Yes' => 1, 'No' => 0 ),
				"description" => __( "Display Video Description", 'sw_video' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Description Video Maxlength", 'sw_video' ),
				"param_name" => "length_desc",
				"value" => 10,
				"admin_label" => true,
				"description" => __( "Length Number Video Description", 'sw_video' )
			 ),
		  )
	   ) );
	}
	
	/**
		** Add Shortcode
	**/
	function WS_Video_Box_Shortcode( $atts, $content = null ){
		extract( shortcode_atts(
			array(
				'title' 			=> '',
				'category' 			=> '',
				'orderby' 			=> 'name',
				'order'				=> 'DESC',
				'numberposts' 		=> 5,	
				
				'display_category'	=> 1,
				'display_time'		=> 1,
				
				'display_title'		=> 1,
				'length'			=> 5,
				
				'display_desc'		=> 1,
				'length_desc'		=> 10,				
			), $atts )
		);
		ob_start();
		include( plugin_dir_path(dirname(__FILE__)).'sw-video-box/themes/default.php' );
		$content = ob_get_clean();
		return $content;		
	}
	
	/**
	 * Display the widget on the screen.
	 */
	public function widget( $args, $instance ) {
		extract($args);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
		
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
		$instance['title'] = strip_tags( $new_instance['title'] );

		if ( array_key_exists('category', $new_instance) ){
			if ( is_array($new_instance['category']) ){
				$instance['category'] = $new_instance['category'];
			} else {
				$instance['category'] = $new_instance['category'];
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
		
		if (array_key_exists('display_category', $new_instance)){
			$instance['display_category'] = intval( $new_instance['display_category'] );
		}
		
		if (array_key_exists('display_time', $new_instance)){
			$instance['display_time'] = intval( $new_instance['display_time'] );
		}
		
		if (array_key_exists('display_title', $new_instance)){
			$instance['display_title'] = intval( $new_instance['display_title'] );
		}
		
		if (array_key_exists('length', $new_instance)){
			$instance['length'] = intval( $new_instance['length'] );
		}
		
		if (array_key_exists('display_desc', $new_instance)){
			$instance['display_desc'] = intval( $new_instance['display_desc'] );
		}
		
		if (array_key_exists('length_desc', $new_instance)){
			$instance['length_desc'] = intval( $new_instance['length_desc'] );
		}
		return $instance;
	}

	function category_select( $field_name, $opts = array(), $field_value = null ){
		$terms = get_categories();
		if( count( $terms ) == 0 ){
			return ;
		}
		$r = array();
		$r['pad_counts'] 	= 1;
		$r['hierarchical'] 	= 1;
		$r['hide_empty'] 	= 1;
		$r['value_field']	= 'slug';
		$r['selected'] 		= $field_value;
		$r['show_count'] 	= false;
		return '<select class="widefat" id="'.$this->get_field_id($field_name).'" name="'.$this->get_field_name($field_name).'"> <option value="">' . __( 'All Categories', 'sw_video' ) . '</option>'.walk_category_dropdown_tree( $terms, 0, $r ).'</select>';

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
		$title    = isset( $instance['title'] )     ? strip_tags($instance['title']) : '';      
		$category 	= isset( $instance['category'] )    ? $instance['category'] : 0;
		
		
		$orderby    = isset( $instance['orderby'] )     ? strip_tags($instance['orderby']) : 'ID';
		$order      = isset( $instance['order'] )       ? strip_tags($instance['order']) : 'ASC';
		
		$number     = isset( $instance['numberposts'] ) ? intval($instance['numberposts']) : 5;
		
		$display_category   = isset( $instance['display_category'] ) 	? 	$instance['display_category'] 				: 1;
		$display_time     	= isset( $instance['display_time'] ) 	? 	$instance['display_time'] 				: 1;
        $display_title     	= isset( $instance['display_title'] ) 	? 	$instance['display_title'] 				: 1;
		$length		     	= isset( $instance['length'] )      	? 	intval($instance['length']) 			: 5;
		$display_desc     	= isset( $instance['display_desc'] ) 	? 	$instance['display_desc'] 				: 1;
		$length_desc     	= isset( $instance['length_desc'] )     ? 	intval($instance['length_desc']) 		: 10;         
		?>
        
		</p> 
          <div style="background: Blue; color: white; font-weight: bold; text-align:center; padding: 3px"> * Data Config * </div>
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sw_video')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
				type="text"	value="<?php echo esc_attr($title); ?>" />
		</p>
		
		<p id="wgd-<?php echo $this->get_field_id('category'); ?>">
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category', 'sw_video')?></label>
			<br />
			<?php echo $this->category_select('category', array('allow_select_all' => true), $category); ?>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby', 'sw_video')?></label>
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
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'sw_video')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
				<option value="DESC" <?php if ($order=='DESC'){?> selected="selected"
				<?php } ?>>
					<?php _e('Descending', 'sw_video')?>
				</option>
				<option value="ASC" <?php if ($order=='ASC'){?> selected="selected"	<?php } ?>>
					<?php _e('Ascending', 'sw_video')?>
				</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of Posts', 'sw_video')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>"
				type="text"	value="<?php echo esc_attr($number); ?>" />
		</p> 
		
		<p>
			<label for="<?php echo $this->get_field_id('display_category'); ?>"><?php _e('Display Video Category', 'sw_video')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id('display_category'); ?>" name="<?php echo $this->get_field_name('display_category'); ?>">
				<option value="1" <?php if ($display_category=='1'){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'sw_video')?>
				</option>
				<option value="0" <?php if ($display_category=='0'){?> selected="selected"	<?php } ?>>
					<?php _e('No', 'sw_video')?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('display_time'); ?>"><?php _e('Display Video Time', 'sw_video')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id('display_time'); ?>" name="<?php echo $this->get_field_name('display_time'); ?>">
				<option value="1" <?php if ($display_time=='1'){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'sw_video')?>
				</option>
				<option value="0" <?php if ($display_time=='0'){?> selected="selected"	<?php } ?>>
					<?php _e('No', 'sw_video')?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('display_title'); ?>"><?php _e('Display Post Title', 'sw_video')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id('display_title'); ?>" name="<?php echo $this->get_field_name('display_title'); ?>">
				<option value="1" <?php if ($display_title=='1'){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'sw_video')?>
				</option>
				<option value="0" <?php if ($display_title=='0'){?> selected="selected"	<?php } ?>>
					<?php _e('No', 'sw_video')?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('length'); ?>"><?php _e('Post Title Maxlength ', 'sw_video')?></label>
			<br />
			<input class="widefat"
				id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text"
				value="<?php echo esc_attr($length); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('display_desc'); ?>"><?php _e('Display Post Description', 'sw_video')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id('display_desc'); ?>" name="<?php echo $this->get_field_name('display_desc'); ?>">
				<option value="1" <?php if ($display_desc=='1'){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'sw_video')?>
				</option>
				<option value="0" <?php if ($display_desc=='0'){?> selected="selected"	<?php } ?>>
					<?php _e('No', 'sw_video')?>
				</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('length_desc'); ?>"><?php _e('Post Description Maxlength ', 'sw_video')?></label>
			<br />
			<input class="widefat"
				id="<?php echo $this->get_field_id('length_desc'); ?>" name="<?php echo $this->get_field_name('length_desc'); ?>" type="text"
				value="<?php echo esc_attr($length_desc); ?>" />
		</p>
	<?php
	}	
}
?>