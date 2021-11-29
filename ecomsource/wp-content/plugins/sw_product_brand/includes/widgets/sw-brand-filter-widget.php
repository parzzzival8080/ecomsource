<?php
/**
 * Name: SW Brand Filter
 * Description: A widget that serves as an slider for developing more advanced widgets.
 */
 
class sw_brand_filter_widget extends WC_Widget {
		
	/**
	 * Widget setup.
	 */
	function __construct(){
		$this->widget_cssclass    = 'sw_brand_filter';
		$this->widget_description = __( 'Sw Brand Filter', 'sw_product_brand' );
		$this->widget_id          = 'sw_brand_filter';
		$this->widget_name        = __( 'Sw brand filter widget', 'sw_product_brand' );
		parent::__construct();		
		add_filter( 'posts_join', array( $this, 'add_join_filter_brand' ) );
		add_filter( 'posts_where', array( $this, 'add_where_filter_brand' ) );
	}
	
	protected function get_current_term_slug() {
		return absint( is_tax() ? get_queried_object()->slug : 0 );
	}
	
	/**
	 * Display the widget on the screen.
	 */
	public function widget( $args, $instance ) {
		extract($args);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$numberposts = isset( $instance['numberposts'] ) ? $instance['numberposts'] : 5;
		//$number =  $instance['numberposts'] ? $instance['numberposts'] : 5;
		echo $before_widget;
		echo $before_title . $title . $after_title;
		extract($instance);
		$terms = get_terms( 'product_brand', array( 'parent' => '', 'hide_empty' => 0, 'number'=> $numberposts ) );		
		$current_values = isset( $_GET['filter_brand'] ) ? explode( ',', $_GET['filter_brand'] ) : array();
	?>
		<ul class="sw-filter-product-brand">
			<?php 
				foreach( $terms as $key => $term ) : 
					$current_filter = isset( $_GET[ 'filter_brand' ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ 'filter_brand' ] ) ) ) : array(); 
					$current_filter = array_map( 'sanitize_title', $current_filter );
					$link = remove_query_arg( 'filter_brand', $this->get_current_page_url() );
					$option_is_set  = in_array( $term->slug, $current_values, true );
					$count = isset( $term->count ) ? $term->count : 0;
					
					if ( ! in_array( $term->slug, $current_filter, true ) ) {
						$current_filter[] = $term->slug;
					}
					
					foreach ( $current_filter as $key => $value ) {
						if ( $value === $this->get_current_term_slug() ) {
							unset( $current_filter[ $key ] );
						}

						if ( $option_is_set && $value === $term->slug ) {
							unset( $current_filter[ $key ] );
						}
					}
					if ( ! empty( $current_filter ) ) {
						asort( $current_filter );
						$link = add_query_arg( 'filter_brand', implode( ',', $current_filter ), $link );

						$link = str_replace( '%2C', ',', $link );
					}		

					

					if ( $count || $option_is_set ) {
						$term_html = '<a rel="nofollow" href="' . $link . '">' . esc_html( $term->name ) . '</a>';
					} else {
						$link      = false;
						$term_html = '<span>' . esc_html( $term->name ) . '</span>';
					}
					
					echo '<li class="item-filter-brand ' . ( $option_is_set ? 'active' : '' ) . '">';
					echo wp_kses_post( apply_filters( 'sw_filter_brand_html', $term_html, $term, $link ) );
					echo '</li>';
				endforeach; 
			?>
		</ul>
	
	<?php 				
		/* After widget (defined by themes). */
		echo $after_widget;
	}  

	function add_join_filter_brand( $joins ){
		$filter_brand = isset( $_GET['filter_brand'] ) ? explode( ',', $_GET['filter_brand'] ) : array();
		if( count( $filter_brand ) ) :
			global $wpdb;
			$joins .= " inner join  {$wpdb->term_relationships}  as wrel on {$wpdb->posts}.ID = wrel.object_id";
			$joins .= " inner join {$wpdb->term_taxonomy} as wtax on wtax.term_taxonomy_id = wrel.term_taxonomy_id";
			$joins .= " inner join {$wpdb->terms} as wter on wter.term_id = wtax.term_id";
		endif;
		return $joins;
    }
	
    function add_where_filter_brand( $where ) {
		$filter_brand = isset( $_GET['filter_brand'] ) ? explode( ',', $_GET['filter_brand'] ) : array();
		if( count( $filter_brand ) ) :
			$where = $where. ' AND ( wter.slug in ("' . implode( '","', $filter_brand ) . '"))';
		endif;
		return $where;
    }
   
	
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// strip tag on text field
		$instance['title'] = strip_tags( $new_instance['title'] );
        
		if ( array_key_exists('numberposts', $new_instance) ){
				$instance['numberposts'] = intval( $new_instance['numberposts'] );
			}
			
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	public function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults 		= array();
		$instance 		= wp_parse_args( (array) $instance, $defaults ); 		
		$title    		= isset( $instance['title'] )     	? strip_tags($instance['title']) : '';    
		$number     	= isset( $instance['numberposts'] ) 	? intval($instance['numberposts']) : 5;   
		$widget_template   = isset( $instance['widget_template'] ) ? strip_tags($instance['widget_template']) : 'default';
                   
                 
		?>
        </p> 
          <div style="background: Blue; color: white; font-weight: bold; text-align:center; padding: 3px"> * Data Config * </div>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sw_product_brand')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
				type="text"	value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numberposts'); ?>"><?php _e('Number of Posts', 'sw_woocommerce')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>"
			type="text"	value="<?php echo esc_attr($number); ?>" />
		</p>		
	<?php
	}	
}
?>