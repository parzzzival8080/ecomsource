<?php 
/*
** Name: Class VC Brand Element
*/

Class SW_Product_Brand_Element{
	private $snumber = 1;
	
	function __construct(){
		add_shortcode( 'sw_brand', array( $this, 'PB_Shortcode' ) );
		
		/* Create Vc_map */
		if (class_exists('Vc_Manager')) {
			add_action( 'vc_before_init', array( $this, 'PB_integrateWithVC' ), 10 );
		}
	}
	
	/*
	** Generate ID
	*/
	public function generateID() {
		return 'sw-brand-' .(int) $this->snumber++;
	}
	
	/**
	* Add Vc Params
	**/
	function PB_integrateWithVC(){
		$terms = get_terms( 'product_brand', array( 'parent' => '', 'hide_empty' => 0 ) );
		$term = array( __( 'All Categories', 'sw_product_brand' ) => 0 );
		if( count( $terms ) > 0 ){			
			foreach( $terms as $cat ){
				$term[$cat->name] = $cat -> slug;
			}
		}
		vc_map( array(
		  "name" => __( "Sw Brand", 'sw_product_brand' ),
		  "base" => "sw_brand",
		  "icon" => "icon-wpb-ytc",
		  "class" => "",
		  "category" => __( "SW Shortcodes", 'sw_product_brand' ),
		  "params" => array(
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Title", 'sw_product_brand' ),
				"param_name" => "title1",
				"admin_label" => true,
				"value" => '',
				"description" => __( "Title", 'sw_product_brand' )
			 ),						
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Description", 'sw_woocommerce' ),
				"param_name" => "description",
				"admin_label" => true,
				"value" => '',
				"description" => __( "Description", 'sw_woocommerce' )
			 ),
			 array(
				"type" => "multiselect",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Category", 'sw_product_brand' ),
				"param_name" => "category",
				"admin_label" => true,
				"value" => $term,
				"description" => __( "Select Categories", 'sw_product_brand' )
			 ),
				 
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Style", 'sw_product_brand' ),
				"param_name" => "style",
				"admin_label" => true,
				"value" => array( 'Default' => '', 'Style 1' => 'style1' ),
				"description" => __( "Select Style", 'sw_product_brand' )
			 ),
			 
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order By", 'sw_product_brand' ),
				"param_name" => "orderby",
				"admin_label" => true,
				"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
				"description" => __( "Order By", 'sw_product_brand' )
			 ),
			 
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order", 'sw_product_brand' ),
				"param_name" => "order",
				"admin_label" => true,
				"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
				"description" => __( "Order", 'sw_product_brand' )
			 ),
			 
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number Of Post", 'sw_product_brand' ),
				"param_name" => "numberposts",
				"admin_label" => true,
				"value" => 5,
				"description" => __( "Number Of Post", 'sw_product_brand' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number row per column", 'sw_product_brand' ),
				"param_name" => "item_row",
				"admin_label" => true,
				"value" =>array(1,2,3),
				"description" => __( "Number row per column", 'sw_product_brand' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns >1200px: ", 'sw_product_brand' ),
				"param_name" => "columns",
				"admin_label" => true,
				"value" => array(1,2,3,4,5,6,7,8,9,10),
				"description" => __( "Number of Columns >1200px:", 'sw_product_brand' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns on 992px to 1199px:", 'sw_product_brand' ),
				"param_name" => "columns1",
				"admin_label" => true,
				"value" => array(1,2,3,4,5,6,7,8),
				"description" => __( "Number of Columns on 992px to 1199px:", 'sw_product_brand' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns on 768px to 991px:", 'sw_product_brand' ),
				"param_name" => "columns2",
				"admin_label" => true,
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns on 768px to 991px:", 'sw_product_brand' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns on 480px to 767px:", 'sw_product_brand' ),
				"param_name" => "columns3",
				"admin_label" => true,
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns on 480px to 767px:", 'sw_product_brand' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns in 480px or less than:", 'sw_product_brand' ),
				"param_name" => "columns4",
				"admin_label" => true,
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns in 480px or less than:", 'sw_product_brand' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Speed", 'sw_product_brand' ),
				"param_name" => "speed",
				"admin_label" => true,
				"value" => 1000,
				"description" => __( "Speed Of Slide", 'sw_product_brand' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Auto Play", 'sw_product_brand' ),
				"param_name" => "autoplay",
				"admin_label" => true,
				"value" => array( 'False' => 'false', 'True' => 'true' ),
				"description" => __( "Auto Play", 'sw_product_brand' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Interval", 'sw_product_brand' ),
				"param_name" => "interval",
				"admin_label" => true,
				"value" => 5000,
				"description" => __( "Interval", 'sw_product_brand' )
			 ),
			  array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Layout", 'sw_product_brand' ),
				"param_name" => "layout",
				"admin_label" => true,
				"value" => array( 'Layout Default' => 'default' , 'Layout 1' => 'layout1', 'Layout 2' => 'layout2', 'Layout Mobile' => 'mobile'),
				"description" => __( "Layout", 'sw_product_brand' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Total Items Slided", 'sw_product_brand' ),
				"param_name" => "scroll",
				"admin_label" => true,
				"value" => 1,
				"description" => __( "Total Items Slided", 'sw_product_brand' )
			 ),
		  )
	   ) );
	}
	/**
		** Add Shortcode
	**/
	function PB_Shortcode( $atts, $content = null ){
		extract( shortcode_atts(
			array(
				'title1' => '',
				'description'=> '',
				'category' => 0,
				'style' => '',
				'orderby' => 'name',
				'order'	=> 'DESC',
				'numberposts' => 5,
				'length' => 25,
				'item_row'=> 1,
				'columns' => 4,
				'columns1' => 4,
				'columns2' => 3,
				'columns3' => 2,
				'columns4' => 1,
				'speed' => 1000,
				'autoplay' => 'false',
				'interval' => 5000,
				'scroll' => 1,			
				'layout'  => 'default'
			), $atts )
		);
		ob_start();		
		if( $layout == 'default' ){
			include( PBRPATH . 'includes/themes/sw-brands/default.php' );
		}
		if( $layout == 'layout1' ){
			include( PBRPATH . 'includes/themes/sw-brands/layout1.php' );
		}
		if( $layout == 'layout2' ){
			include( PBRPATH . 'includes/themes/sw-brands/layout2.php' );
		}
		if( $layout == 'mobile' ){
			include( PBRPATH . 'includes/themes/sw-brands/theme-mobile.php' );
		}			
		$content = ob_get_clean();
		
		return $content;
	}
}

new SW_Product_Brand_Element();
?>