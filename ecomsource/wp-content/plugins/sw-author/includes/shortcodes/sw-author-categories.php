<?php 
/**
* SW Author Categories Widgets
* Athor: Smartaddons
**/

if( !class_exists('sw_author_categories_widget') ) :

add_action( 'widgets_init', 'sw_author_categories_widget_register' );
function sw_author_categories_widget_register(){
	register_widget( 'sw_author_categories_widget' );
}

class sw_author_categories_widget extends WP_Widget{
	function __construct(){
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sw_author_categories', 'description' => __('Sw Categories By Author', 'sw_author') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_author_categories' );

		/* Create the widget. */
		parent::__construct( 'sw_author_categories', __('Sw Categories By Author Widget', 'sw_author'), $widget_ops, $control_ops );
		
	}
	
	function widget( $args, $instance ){		
		extract($args);		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $before_widget;
		if ($title){
			echo $before_title . $title . $after_title;
		}
		extract($instance);
		
			if( $layout == 'default' ){
				include( sw_author_override_check( 'shortcodes', 'default' ) );	
			}
			if( $layout == 'layout1' ){
				include( sw_author_override_check( 'shortcodes', 'layout1' ) );	
			}			
		
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		// strip tag on text field
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['description'] = strip_tags( $new_instance['description'] );
		
		if ( array_key_exists('orderby', $new_instance) ){
			$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		}

		if ( array_key_exists('order', $new_instance) ){
			$instance['order'] = strip_tags( $new_instance['order'] );
		}
		
		if ( array_key_exists('category', $new_instance) ){
			if ( is_array($new_instance['category']) ){
				$instance['category'] = $new_instance['category'] ;
			} else {
				$instance['category'] = strip_tags( $new_instance['category'] );
			}
		}
		
		if ( array_key_exists('layout', $new_instance) ){
			$instance['layout'] = strip_tags( $new_instance['layout'] );
		}

		if ( array_key_exists('numberposts', $new_instance) ){
			$instance['numberposts'] = intval( $new_instance['numberposts'] );
		}
		
		if ( array_key_exists('item_row', $new_instance) ){
			$instance['item_row'] = intval( $new_instance['item_row'] );
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
			if (array_key_exists('allow_select_all', $opts) && $opts['allow_select_all']){
				unset($opts['allow_select_all']);
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
				$allow_select_all = '<option value="0">'. esc_html__( 'Select Author', 'sw_vendor_slider' ) .'</option>';
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
		
		$categories = get_posts( array( 'post_type' => 'book_author', 'showposts' => -1, 'orderby' => 'name' ) );
		//print '<pre>'; var_dump($categories);
		$all_category_ids = array();
		foreach ($categories as $cat) $all_category_ids[] = (int)$cat->ID;
		
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
			$select_html .= '<option value="' . $cat->ID . '"';
			if ($cat->ID == $field_value || (is_array($field_value)&&in_array($cat->ID, $field_value))){ $select_html .= ' selected="selected"';}
			$select_html .=  '>'.$cat->post_title.'</option>';
		}
		$select_html .= '</select>';
		return $select_html;
		}
	
	/**
		 * Displays the widget settings controls on the widget panel.
		 * Make use of the get_field_id() and get_field_name() function
		 * when creating your form elements. This handles the confusing stuff.
		 */
		 
	public function form( $instance ){
		$title 		= isset( $instance['title'] ) 		? strip_tags( $instance['title'] )   : '';
		$description = isset( $instance['description'] )    ? 	strip_tags($instance['description']) : '';
		$categoryid 		= isset( $instance['category'] )     ? $instance['category'] : null;
		$orderby    = isset( $instance['orderby'] )     ? strip_tags( $instance['orderby'] ) : 'ID';
		$order      = isset( $instance['order'] )       ? strip_tags( $instance['order'] ) 	 : 'DESC';
		$number     = isset( $instance['numberposts'] ) ? intval( $instance['numberposts'] ) : 5;
		$item_row   = isset( $instance['item_row'] )    ? intval($instance['item_row']) : 1;
		$columns    = isset( $instance['columns'] )     ? intval($instance['columns']) : 1;
		$columns1   = isset( $instance['columns1'] )    ? intval($instance['columns1']) : 1;
		$columns2   = isset( $instance['columns2'] )    ? intval($instance['columns2']) : 1;
		$columns3   = isset( $instance['columns3'] )    ? intval($instance['columns3']) : 1;
		$columns4   = isset( $instance['columns'] )     ? intval($instance['columns4']) : 1;
		$autoplay   = isset( $instance['autoplay'] )    ? strip_tags($instance['autoplay']) : 'false';
		$interval   = isset( $instance['interval'] )    ? intval($instance['interval']) : 5000;
		$speed     	= isset( $instance['speed'] )      	? intval($instance['speed']) : 1000;
		$scroll    	= isset( $instance['scroll'] )      ? intval($instance['scroll']) : 1;
		$layout     = isset( $instance['layout'] )      ? strip_tags( $instance['layout'] )	 : 'default';
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'sw_author' ) ?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>"
				type="text"	value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'sw_author')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"
				type="text"	value="<?php echo esc_attr($description); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select Categories', 'sw_author')?></label>
			<br />
			<?php echo $this->category_select('category', array( 'allow_select_all' => true ), $categoryid); ?>
		</p>
			
		<p>
			<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of Items', 'sw_author')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>"
				type="text"	value="<?php echo esc_attr($number); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby', 'sw_author')?></label>
			<br />
			<?php $allowed_keys = array('name' => 'Name', 'book_author' => 'Author', 'date' => 'Date', 'title' => 'Title', 'modified' => 'Modified', 'parent' => 'Parent', 'ID' => 'ID', 'rand' =>'Rand', 'comment_count' => 'Comment Count'); ?>
			<select class="widefat"
				id="<?php echo $this->get_field_id('orderby'); ?>"
				name="<?php echo $this->get_field_name('orderby'); ?>">
				<?php
				$option ='';
				foreach ( $allowed_keys as $value => $key ) :
					$option .= '<option value="' . $value . '" ' . selected( $value, $orderby, false ) . '>'.$key.'</option>';
				endforeach;
				echo $option;
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order', 'sw_author')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name('order'); ?>">
				<option value="DESC" <?php if ( $order=='DESC' ){?> selected="selected"
				<?php } ?>>
					<?php _e( 'Descending', 'sw_author' )?>
				</option>
				<option value="ASC" <?php if ( $order=='ASC' ){?> selected="selected"	<?php } ?>>
					<?php _e( 'Ascending', 'sw_author' )?>
				</option>
			</select>
		</p>
		
		<?php $row_number = array( '1' => 1, '2' => 2, '3' => 3 ); ?>
		<p>
			<label for="<?php echo $this->get_field_id('item_row'); ?>"><?php _e('Number row per column:  ', 'sw_author')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id('item_row'); ?>"
				name="<?php echo $this->get_field_name('item_row'); ?>">
				<?php
				$option ='';
				foreach ($row_number as $key => $value) :
					$option .= '<option value="' . $value . '" ';
					if ($value == $item_row){
						$option .= 'selected="selected"';
					}
					$option .=  '>'.$key.'</option>';
				endforeach;
				echo $option;
				?>
			</select>
		</p> 
		
		<?php $number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6); ?>
		<p>
			<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns >1200px: ', 'sw_author')?></label>
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
			<label for="<?php echo $this->get_field_id('columns1'); ?>"><?php _e('Number of Columns on 992px to 1199px: ', 'sw_author')?></label>
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
			<label for="<?php echo $this->get_field_id('columns2'); ?>"><?php _e('Number of Columns on 768px to 991px: ', 'sw_author')?></label>
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
			<label for="<?php echo $this->get_field_id('columns3'); ?>"><?php _e('Number of Columns on 480px to 767px: ', 'sw_author')?></label>
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
			<label for="<?php echo $this->get_field_id('columns4'); ?>"><?php _e('Number of Columns in 480px or less than: ', 'sw_author')?></label>
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
			<label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', 'sw_author')?></label>
			<br />
			<select class="widefat"
				id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>">
				<option value="false" <?php if ($autoplay=='false'){?> selected="selected"
				<?php } ?>>
					<?php _e('False', 'sw_author')?>
				</option>
				<option value="true" <?php if ($autoplay=='true'){?> selected="selected"	<?php } ?>>
					<?php _e('True', 'sw_author')?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('interval'); ?>"><?php _e('Interval', 'sw_author')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>"
				type="text"	value="<?php echo esc_attr($interval); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('speed'); ?>"><?php _e('Speed', 'sw_author')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('speed'); ?>" name="<?php echo $this->get_field_name('speed'); ?>"
				type="text"	value="<?php echo esc_attr($speed); ?>" />
		</p>
		
		
		<p>
			<label for="<?php echo $this->get_field_id('scroll'); ?>"><?php _e('Total Items Slided', 'sw_author')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('scroll'); ?>" name="<?php echo $this->get_field_name('scroll'); ?>"
				type="text"	value="<?php echo esc_attr($scroll); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( "Layout", 'sw_author' )?></label>
			<br/>
			
			<select class="widefat"
				id="<?php echo $this->get_field_id( 'layout' ); ?>"	name="<?php echo $this->get_field_name('layout'); ?>">
				<option value="default" <?php if ( $layout=='default' ){ ?> selected="selected"
				<?php } ?>>
					<?php _e( 'Default', 'sw_author' )?>		
				</option>				
			</select>
		</p>  
	<?php 
	}
}
endif;
?>