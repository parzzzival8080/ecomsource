<?php
/**
 * functions.php
 *
 * The theme's functions and definitions.
 */
/**
 * 1.0 - Define constants. Current Version number & Theme Name.
 */
define('MARKETO_THEME', 'Marketo WordPress Theme');
define('MARKETO_VERSION', '4.2');

define('MARKETO_THEMEROOT', get_template_directory_uri());
define('MARKETO_THEMEROOT_DIR', get_template_directory());
define('MARKETO_IMAGES', MARKETO_THEMEROOT . '/assets/images');
define('MARKETO_IMAGES_DIR', MARKETO_THEMEROOT_DIR . '/assets/images');
define('MARKETO_IMAGES_URI', MARKETO_THEMEROOT . '/assets/images');
define('MARKETO_CSS', MARKETO_THEMEROOT . '/assets/css');
define('MARKETO_CSS_DIR', MARKETO_THEMEROOT_DIR . '/assets/css');
define('MARKETO_SCRIPTS', MARKETO_THEMEROOT . '/assets/js');
define('MARKETO_SCRIPTS_DIR', MARKETO_THEMEROOT_DIR . '/assets/js');
define('MARKETO_PHPSCRIPTS', MARKETO_THEMEROOT . '/assets/php');
define('MARKETO_PHPSCRIPTS_DIR', MARKETO_THEMEROOT_DIR . '/assets/php');
define('MARKETO_INC', MARKETO_THEMEROOT_DIR . '/inc');
define('MARKETO_CUSTOMIZER_DIR', MARKETO_INC . '/customizer/');
define('MARKETO_SHORTCODE_DIR', MARKETO_INC . '/shortcode/');
define('MARKETO_SHORTCODE_DIR_STYLE', MARKETO_INC . '/shortcode/style');
define('MARKETO_REMOTE_CONTENT', esc_url('http://content.xpeedstudio.com/demo-content/marketo'));
define('MARKETO_PLUGINS_DIR', MARKETO_INC . '/includes/plugins');
define('MARKETO_REMOTE_URL', MARKETO_REMOTE_CONTENT);
define( 'MARKETO_GLOBAL_UNYSON', esc_url( 'https://demo.xpeedstudio.com/global-plugin' ) );


/**
 * ----------------------------------------------------------------------------------------
 * 3.0 - Set up the content width value based on the theme's design.
 * ----------------------------------------------------------------------------------------
 */
if (!isset($content_width)) {
    $content_width = 800;
}


/**
 * ----------------------------------------------------------------------------------------
 * 4.0 - Set up theme default and register various supported features.
 * ----------------------------------------------------------------------------------------
 */
if (!function_exists('marketo_setup')) {

    function marketo_setup()
    {
        /**
         * Make the theme available for translation.
         */
        $lang_dir = MARKETO_THEMEROOT . '/languages';
        load_theme_textdomain('marketo', $lang_dir);

        /**
         * Add support for post formats.
         */
        add_theme_support('post-formats', array()
        );

        /**
         * Add support for automatic feed links.
         */
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /**
         * Add support for post thumbnails.
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(750, 465, array('center', 'center')); // Hard crop center center

        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        /**
         * Register nav menus.
         */
        register_nav_menus(
            array(
                'primary' => esc_html__('Primary Menu', 'marketo'),
                'mobile_nav' => esc_html__('Mobile Menu', 'marketo'),
                'vertical_nav' => esc_html__('Vertical Menu', 'marketo'),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ));

        /*
        * Enable support for wide alignment class for Gutenberg blocks.
        */
        add_theme_support( 'align-wide' );
    }

    add_action('after_setup_theme', 'marketo_setup');
}

/**
 * ----------------------------------------------------------------------------------------
 * 7.0 - theme INC.
 * ----------------------------------------------------------------------------------------
 */
include_once get_template_directory() . '/inc/init.php';


/*show default placeholder image*/
function default_wc_placeholder_thumbnail() {
    add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');
    function custom_woocommerce_placeholder_img_src( $src ) {
        $src = WC()->plugin_url() . '/assets/images/placeholder.png';
        return $src;
    }
}
add_action( 'init', 'default_wc_placeholder_thumbnail' );


include_once get_template_directory() . '/inc/mav-menu-custom-fields.php';
$footer_style = marketo_option( 'footer_style',marketo_defaults('footer_style') );


add_filter('marketo_footer_widget_1_width', 'marketo_footer_1_width');
function marketo_footer_1_width(){
    return marketo_option('footer_widget_1_grid');
}

add_filter('marketo_footer_widget_2_width', 'marketo_footer_2_width');
function marketo_footer_2_width(){
    return marketo_option('footer_widget_2_grid');
}

add_filter('marketo_footer_widget_3_width', 'marketo_footer_3_width');
function marketo_footer_3_width(){
    return marketo_option('footer_widget_3_grid');
}

add_filter('marketo_footer_widget_4_width', 'marketo_footer_4_width');
function marketo_footer_4_width(){
    return marketo_option('footer_widget_4_grid');
}

add_filter('marketo_footer_widget_5_width', 'marketo_footer_5_width');
function marketo_footer_5_width(){
    return marketo_option('footer_widget_5_grid');
}

add_filter('marketo_footer_widget_6_width', 'marketo_footer_6_width');
function marketo_footer_6_width(){
    return marketo_option('footer_widget_6_grid');
}

add_filter('marketo_footer_widget_7_width', 'marketo_footer_7_width');
function marketo_footer_7_width(){
    return marketo_option('footer_widget_7_grid');
}

add_filter('marketo_footer_widget_8_width', 'marketo_footer_8_width');
function marketo_footer_8_width(){
    return marketo_option('footer_widget_8_grid');
}

add_filter('marketo_footer_widget_9_width', 'marketo_footer_9_width');
function marketo_footer_9_width(){
    return marketo_option('footer_widget_9_grid');
}

add_filter('marketo_footer_widget_10_width', 'marketo_footer_10_width');
function marketo_footer_10_width(){
    return marketo_option('footer_widget_10_grid');
}

add_filter('marketo_footer_widget_11_width', 'marketo_footer_11_width');
function marketo_footer_11_width(){
    return marketo_option('footer_widget_11_grid');
}

add_filter('marketo_footer_widget_12_width', 'marketo_footer_12_width');
function marketo_footer_12_width(){
    return marketo_option('footer_widget_12_grid');
}

add_filter('woocommerce_add_to_cart_fragments', 'marketo_cart_button_item_count', 30);
function marketo_cart_button_item_count($array_s)
{
    $xs_product_count = WC()->cart->cart_contents_count;
    ob_start();
    ?>
    <span class="xs-item-count highlight xscart"><?php echo esc_html($xs_product_count); ?></span>
    <?php
    $array_s['span.xscart'] = ob_get_clean();
    return $array_s;

}
add_action( 'admin_menu', 'marketo_remove_theme_settings', 999 );
function marketo_remove_theme_settings() {
    remove_submenu_page( 'themes.php', 'fw-settings' );
}


add_action('enqueue_block_editor_assets', 'marketo_action_enqueue_block_editor_assets' );
function marketo_action_enqueue_block_editor_assets() {
    wp_enqueue_style( 'marketo-fonts', marketo_google_fonts_url(['Rubik:400,500,600,700,800,900']), null, MARKETO_VERSION );
    wp_enqueue_style( 'marketo-gutenberg-editor-font-awesome-styles', MARKETO_CSS . '/font-awesome.min.css', null, MARKETO_VERSION );
    wp_enqueue_style( 'marketo-gutenberg-editor-customizer-styles', MARKETO_CSS . '/gutenberg-editor-custom.css', null, MARKETO_VERSION );
    wp_enqueue_style( 'marketo-gutenberg-editor-styles', MARKETO_CSS . '/gutenberg-custom.css', null, MARKETO_VERSION );
    wp_enqueue_style( 'marketo-gutenberg-blog-styles', MARKETO_CSS . '/blog-style.css', null, MARKETO_VERSION );
}



// Add this to your theme functions.php file. Change sidebar id to your primary sidebar id.
function marketo_body_classes( $classes ) {

    if ( is_active_sidebar( 'sidebar-1' ) || ( class_exists( 'Woocommerce' ) && ! is_woocommerce() ) || class_exists( 'Woocommerce' ) && is_woocommerce() && is_active_sidebar( 'shop-sidebar' ) ) {
        $classes[] = 'sidebar-active';
    }else{
        $classes[] = 'sidebar-inactive';
    }
    return $classes;
}
add_filter( 'body_class','marketo_body_classes' );

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

// for optimization dequeue styles
add_action( 'wp_enqueue_scripts', 'marketo_remove_unused_css_files', 9999 );
function marketo_remove_unused_css_files() {
    $fontawesome = marketo_option('optimization_fontawesome_enable', 'yes');
    $blocklibrary = marketo_option('optimization_blocklibrary_enable', 'yes');
    $elementoricons = marketo_option('optimization_elementoricons_enable', 'yes');
    $elementkitsicons = marketo_option('optimization_elementkitsicons_enable', 'yes');
    $socialicons = marketo_option('optimization_socialicons_enable', 'yes');
    $dashicons = marketo_option('optimization_dashicons_enable', 'yes');
    $googlemapapi = marketo_option('optimization_google_api_enable', 'yes');

    // dequeue wp-review styles file
    wp_dequeue_style( 'wur_content_css' );
    wp_deregister_style( 'wur_content_css' );

    //dequeue wp social style file
    wp_dequeue_style( 'xs-front-style' );
    wp_deregister_style( 'xs-front-style' );
    wp_dequeue_style( 'xs_login_font_login_css' );
    wp_deregister_style( 'xs_login_font_login_css' );
    wp_dequeue_script( 'xs_social_custom' );

    // dequeue fontawesome icons file
    if($fontawesome == 'no'){
        wp_dequeue_style( 'font-awesome' );
	    wp_deregister_style( 'font-awesome' );
        wp_dequeue_style( 'font-awesome-5-all' );
        wp_deregister_style( 'font-awesome-5-all' );
        wp_dequeue_style( 'font-awesome-4-shim' );
        wp_deregister_style( 'font-awesome-4-shim' );
        wp_dequeue_style( 'fontawesome-five-css' );
        wp_dequeue_style( 'yith-wcwl-font-awesome' );
        wp_deregister_style( 'yith-wcwl-font-awesome' );
        wp_dequeue_script( 'font-awesome-4-shim' );
    }

    // dequeue block-library file
    if($blocklibrary == 'no'){
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' );
		wp_deregister_style( 'wc-blocks-style' );
        wp_dequeue_script( 'jquery-blockui' );
        wp_deregister_script( 'jquery-blockui' );
    }
    // google map api
    if($googlemapapi == 'no'){
        wp_dequeue_script( 'map-googleapis' );
        wp_deregister_script( 'map-googleapis' );
    }

    if($elementkitsicons == 'no'){		
		wp_dequeue_style( 'elementor-icons-ekiticons' );
		wp_deregister_style( 'elementor-icons-ekiticons' );
    }

    if($socialicons == 'no'){		
        wp_dequeue_style( 'apsc-frontend-css' );
    }

    if($elementoricons == 'no'){
        // Don't remove it in the backend
        if ( is_admin() || current_user_can( 'manage_options' ) ) {
            return;
        }
        wp_dequeue_style( 'elementor-animations' );
        wp_dequeue_style( 'elementor-icons' );
        wp_deregister_style( 'elementor-icons' );        
    }

    if($dashicons == 'no'){
        // Don't remove it in the backend
        if ( is_admin() || current_user_can( 'manage_options' ) ) {
            return;
        }
        wp_dequeue_style( 'dashicons' );
    }
	
}

/* disable option for elementskit icons */
add_action('elementskit_lite/after_loaded', function(){
    add_filter('elementor/icons_manager/additional_tabs', function($icons){
        $elementkitsicons = marketo_option('optimization_elementkitsicons_enable', 'yes');
    
        if($elementkitsicons == 'no'){
            unset($icons['ekiticons']);      
        }
    
        return $icons;
    });
});

/* disable option for font awesome icons from elementor editor */
add_action( 'elementor/frontend/after_register_styles',function() {
    $fontawesome = marketo_option('optimization_fontawesome_enable', 'yes');
    if($fontawesome == 'no'){
        foreach( [ 'solid', 'regular', 'brands' ] as $style ) {
            wp_deregister_style( 'elementor-icons-fa-' . $style );
        }
    }
    
}, 20 );

/* disable option for font awesome icons from elementor editor */
add_filter('elementor/icons_manager/native', function($icons){
    $fontawesome = marketo_option('optimization_fontawesome_enable', 'yes');
    if($fontawesome == 'no'){
        unset($icons['fa-regular']);
        unset($icons['fa-solid']);
        unset($icons['fa-brands']);        
    }

    return $icons;
});


//meta description
function marketo_meta_description() {
    global $post;
    if ( is_singular() ) {
        $des_post = $post->post_title;
        echo '<meta name="description" content="' . $des_post . '" />' . "\n";
    }
    if ( is_home() ) {
        echo '<meta name="description" content="' . get_bloginfo( "description" ) . '" />' . "\n";
    }
    if ( is_archive() ) {
        echo '<meta name="description" content="' . get_bloginfo( "description" ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'marketo_meta_description');
