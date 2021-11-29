<?php 
/***** Active Plugin ********/
require_once( get_template_directory().'/lib/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'emarket_register_required_plugins' );
function emarket_register_required_plugins() {
  $plugins = array(
    array(
      'name'               => esc_html__( 'WooCommerce', 'emarket' ), 
      'slug'               => 'woocommerce', 
      'required'           => true, 
      'version'			   => '5.4.1'
      ),
    
    array(
     'name'               => esc_html__( 'Revslider', 'emarket' ), 
     'slug'               => 'revslider', 
     'source'             => get_template_directory() . '/lib/plugins/revslider.zip',   
     'required'           => true, 
     'version'            => '6.5.4'
     ),
	
	array(
		'name'               => esc_html__( 'JetSmartFilters', 'emarket' ), 
		'slug'               => 'jet-smart-filters', 
		'source'             => get_template_directory() . '/lib/plugins/jet-smart-filters.zip',  
		'required'           => true, 
		'version'            => '2.2.0'
	),
		
    array(
     'name'               => esc_html__( 'Visual Composer', 'emarket' ), 
     'slug'               => 'js_composer', 
     'source'             => esc_url('https://demo.wpthemego.com/modules/js_composer.zip'),
     'required'           => true, 
     'version'            => '6.7'
     ), 
	 
	 array(
     'name'               => esc_html__( 'Elementor', 'emarket' ), 
     'slug'               => 'elementor',
     'required'           => true, 
     ), 
	 
	 array(
     'name'               => esc_html__( 'Elementor Pro', 'emarket' ), 
     'slug'               => 'elementor-pro', 
    'source'             => get_template_directory() . '/lib/plugins/elementor-pro.zip',
     'required'           => true, 
     'version'            => '3.3.1'
     ), 

    array(
      'name'     		 => esc_html__( 'SW Core', 'emarket' ),
      'slug'      		 => 'sw_core',
      'source'        	 => get_template_directory() . '/lib/plugins/sw_core.zip', 
      'required'  		 => true,   
      'version'			 => '1.2.10'
      ),

    array(
      'name'     		 => esc_html__( 'SW WooCommerce', 'emarket' ),
      'slug'      		 => 'sw_woocommerce',
      'source'         	 => get_template_directory() . '/lib/plugins/sw_woocommerce.zip', 
      'required'  		 => true,
      'version'			 => '1.4.9'
      ),
	
	 array(
      'name'     		 => esc_html__( 'Sw Vendor Slider', 'emarket' ),
      'slug'      		 => 'sw_vendor_slider',
      'source'         	 => get_template_directory() . '/lib/plugins/sw_vendor_slider.zip', 
      'required'  		 => true,
      'version'			 => '1.1.7'
      ),
	  
	 array(
      'name'     		 => esc_html__( 'Sw Product Brand', 'emarket' ),
      'slug'      		 => 'sw_product_brand',
      'source'         	 => get_template_directory() . '/lib/plugins/sw_product_brand.zip', 
      'required'  		 => true,
      'version'			 => '1.0.10'
      ),
	  
	 array(
      'name'     		 => esc_html__( 'Sw List Store', 'emarket' ),
      'slug'      		 => 'sw_liststore',
      'source'         	 => get_template_directory() . '/lib/plugins/sw_liststore.zip', 
      'required'  		 => true,
      'version'			 => '1.0.1'
      ),
	  
    array(
      'name'     		 => esc_html__( 'SW Woocommerce Swatches', 'emarket' ),
      'slug'      		 => 'sw_wooswatches',
      'source'         	 => get_template_directory() . '/lib/plugins/sw_wooswatches.zip', 
      'required'  		 => true,
      'version'			 => '1.1.5'
      ),

    array(
      'name'     		 => esc_html__( 'SW Ajax Woocommerce Search', 'emarket' ),
      'slug'      		 => 'sw_ajax_woocommerce_search',
      'source'         	 => get_template_directory() . '/lib/plugins/sw_ajax_woocommerce_search.zip', 
      'required'  		 => true,
      'version'			 => '1.2.13'
      ),

    array(
      'name'     		 => esc_html__( 'Sw Product Bundles', 'emarket' ),
      'slug'      		 => 'sw-product-bundles',
      'source'         	 => get_template_directory() . '/lib/plugins/sw-product-bundles.zip', 
      'required'  		 => true,
      'version'			 => '2.1.7'
      ),
	
	array(
      'name'     		 => esc_html__( 'Sw Tools plugin', 'emarket' ),
      'slug'      		 => 'sw-tools-plugin',
      'source'         	 => get_template_directory() . '/lib/plugins/sw-tools-plugin.zip', 
      'required'  		 => true,
      'version'			 => '1.0.1'
     ),
	 
	 array(
      'name'     		 => esc_html__( 'Sw Woocommerce Catalog Mode', 'emarket' ),
      'slug'      		 => 'sw-woocatalog',
      'source'         	 => get_template_directory() . '/lib/plugins/sw-woocatalog.zip', 
      'required'  		 => true,
      'version'			 => '1.0.4'
     ),
	 
	array(
      'name'     		 => esc_html__( 'Sw Author', 'emarket' ),
      'slug'      		 => 'sw-author',
      'source'         	 => get_template_directory() . '/lib/plugins/sw-author.zip', 
      'required'  		 => true,
      'version'			 => '1.0.2'
      ),
	
	array(
      'name'     		 => esc_html__( 'Sw Video Box', 'emarket' ),
      'slug'      		 => 'sw-video-box',
      'source'         	 => get_template_directory() . '/lib/plugins/sw-video-box.zip', 
      'required'  		 => true
      ),
	  
    array(
      'name'               => esc_html__( 'One Click Demo Import', 'emarket' ), 
      'slug'               => 'one-click-demo-import', 
      'source'             => get_template_directory() . '/lib/plugins/one-click-demo-import.zip', 
      'required'           => true, 
      ),
	
	array(
      'name'     			 => esc_html__( 'Product Designer', 'emarket' ),
      'slug'      			 => 'product-designer',
	  'source'            	 => esc_url('https://demo.wpthemego.com/modules/product-designer.zip'),
      'required' 			 => false,
      ),  
	  
    array(
      'name'      			 => esc_html__( 'MailChimp for WordPress Lite', 'emarket' ),
      'slug'     			 => 'mailchimp-for-wp',
      'required' 			 => false,
      ),
    array(
      'name'      			 => esc_html__( 'Contact Form 7', 'emarket' ),
      'slug'     			 => 'contact-form-7',
      'required' 			 => false,
      ),
    array(
      'name'      			 => esc_html__( 'YITH Woocommerce Compare', 'emarket' ),
      'slug'      			 => 'yith-woocommerce-compare',
      'required'			 => false
      ),
    array(
      'name'     			 => esc_html__( 'YITH Woocommerce Wishlist', 'emarket' ),
      'slug'      			 => 'yith-woocommerce-wishlist',
      'required' 			 => false
      ),
	
	array(
	'name'     				 => esc_html__( 'Smash Balloon Instagram Feed', 'emarket' ),
	'slug'      			 => 'instagram-feed',
	'required' 				 => false
	), 
	
	array(
	'name'     				 => esc_html__( 'WTI Like Post', 'emarket' ),
	'slug'      			 => 'wti-like-post',
	'required' 				 => false
	), 

    );

	$config = array();

	tgmpa( $plugins, $config );

	}
	add_action( 'vc_before_init', 'emarket_vcSetAsTheme' );
	function emarket_vcSetAsTheme() {
	  vc_set_as_theme();
}