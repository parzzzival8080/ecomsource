<?php 
/**
* Room Widgets
* Athor: Smartaddons
**/
add_action( 'widgets_init', 'sw_author_widget_register' );
function sw_author_widget_register(){
	register_widget( 'sw_author_product_widget' );
}

// Top Rated
class sw_author_product_widget extends WP_Widget{
	function __construct(){
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sw_author_product', 'description' => __('Sw Products By Author', 'sw_author') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sw_author_product' );

		/* Create the widget. */
		parent::__construct( 'sw_author_product', __('Sw Products By Author widget', 'sw_author'), $widget_ops, $control_ops );
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
				include( sw_author_override_check( 'widgets', 'default' ) );	
			}
			if( $layout == 'layout1' ){
				include( sw_author_override_check( 'widgets', 'layout1' ) );	
			}			
		
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		// strip tag on text field
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		if ( array_key_exists('orderby', $new_instance) ){
			$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		}

		if ( array_key_exists('order', $new_instance) ){
			$instance['order'] = strip_tags( $new_instance['order'] );
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
	
	public function form( $instance ){
		$title 		= isset( $instance['title'] ) 		? strip_tags( $instance['title'] )   : '';    
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
				<option value="layout1" <?php if ( $layout=='layout1' ){?> selected="selected"
				<?php } ?>>
					<?php _e( 'Related Author Product', 'sw_author' )?>		
				</option>				
			</select>
		</p>  
	<?php 
	}
}