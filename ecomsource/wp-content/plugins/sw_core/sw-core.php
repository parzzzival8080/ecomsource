<?php
/**
 * Plugin Name: SW Core
 * Plugin URI: http://www.smartaddons.com
 * Description: A plugin developed for many shortcode in theme
 * Version: 1.2.10
 * Author: Smartaddons
 * Author URI: http://www.smartaddons.com
 * This Widget help you to show images of product as a beauty reponsive slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if( !function_exists( 'is_plugin_active' ) ){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
/* define plugin path */
if ( ! defined( 'SWPATH' ) ) {
	define( 'SWPATH', plugin_dir_path( __FILE__ ) );
}
/* define plugin URL */
if ( ! defined( 'SWURL' ) ) {
	define( 'SWURL', plugins_url(). '/sw_core' );
}

function sw_core_construct(){
	/*
	** Require file
	*/
	if( class_exists( 'Vc_Manager' ) ){
		require_once ( SWPATH . '/visual-map.php' );
	}
	require_once( SWPATH . 'sw_plugins/sw-plugins.php' );
	
	/*
	** Load text domain
	*/
	load_plugin_textdomain( 'sw_core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	
	/*
	** Call action and filter
	*/
	add_filter('widget_text', 'do_shortcode');
	add_action('init', 'sw_head_cleanup');
	add_action( 'wp_enqueue_scripts', 'Sw_AddScript', 20 );
}

add_action( 'plugins_loaded', 'sw_core_construct', 20 );


function sw_head_cleanup() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action('init', 'sw_head_cleanup');

function Sw_AddScript(){
	wp_register_style('ya_photobox_css', SWURL . '/css/photobox.css', array(), null);	
	wp_register_style('fancybox_css', SWURL . '/css/jquery.fancybox.css', array(), null);
	wp_register_style('shortcode_css', SWURL . '/css/shortcodes.css', array(), null);
	wp_register_script('photobox_js', SWURL . '/js/photobox.js', array(), null, true);
	wp_register_script('fancybox', SWURL . '/js/jquery.fancybox.pack.js', array(), null, true);
	wp_enqueue_style( 'fancybox_css' );
	wp_enqueue_style( 'shortcode_css' );
	wp_enqueue_script( 'fancybox' );
}

/**
 * Add meta box custom
 */
function prfx_review_meta() {
    add_meta_box( 'prfx_meta', __( 'MetaBox Custom', 'sw_core' ), 'prfx_meta_callback', 'post', 'side', 'high' );
	add_meta_box( 'sw_review_meta', esc_html__( 'Review Meta', 'sw_core' ), 'sw_review_meta', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'prfx_review_meta' );
/**
 * Outputs the content of the meta box
 */
function prfx_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    global $post;
	$prfx_stored_meta = get_post_meta( $post->ID );
    ?>

 <p>
    <span class="prfx-row-title">-&nbsp;<?php _e( 'Check if this is a Hot post: ', 'sw_core' )?></span>
    <div class="prfx-row-content">
        <label for="hot_checkbox">
            <input type="checkbox" name="hot_checkbox" id="hot_checkbox" value="yes" <?php if ( isset ( $prfx_stored_meta['hot_checkbox'] ) ) checked( $prfx_stored_meta['hot_checkbox'][0], 'yes' ); ?> />
            <?php _e( 'Hot Post', 'sw_core' )?>
        </label>

    </div>
</p>
 <p>
    <span class="prfx-row-title">-&nbsp;<?php _e( 'Check if this is a Highlight post: ', 'sw_core' )?></span>
    <div class="prfx-row-content">
        <label for="highlight_checkbox">
            <input type="checkbox" name="highlight_checkbox" id="highlight_checkbox" value="yes" <?php if ( isset ( $prfx_stored_meta['highlight_checkbox'] ) ) checked( $prfx_stored_meta['highlight_checkbox'][0], 'yes' ); ?> />
            <?php _e( 'Highlight Post', 'sw_core' )?>
        </label>

    </div>
</p>

 <p>
    <span class="prfx-row-title">-&nbsp;<?php _e( 'Check if this is a Recommend post: ', 'sw_core' )?></span>
    <div class="prfx-row-content">
        <label for="recommend_checkbox">
            <input type="checkbox" name="recommend_checkbox" id="recommend_checkbox" value="yes" <?php if ( isset ( $prfx_stored_meta['recommend_checkbox'] ) ) checked( $prfx_stored_meta['recommend_checkbox'][0], 'yes' ); ?> />
            <?php _e( 'Recommend Post', 'sw_core' )?>
        </label>

    </div>
</p>
    <?php
}

/* Metabox content */
function sw_review_meta(){
	 wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
	 global $post;
	// Add an nonce field so we can check for it later.
	$pros   = get_post_meta( $post->ID, '_review_pros', true );
	$cons   = get_post_meta( $post->ID, '_review_cons', true );
	$gameplay   = get_post_meta( $post->ID, '_gameplay', true );
	$gamestory   = get_post_meta( $post->ID, '_gamestory', true );
	$graphic   = get_post_meta( $post->ID, '_grapphic', true );
	$performance   = get_post_meta( $post->ID, '_performance', true );

	$name_game   = get_post_meta( $post->ID, '_name_game', true );
	$deverloper   = get_post_meta( $post->ID, '_deverloper', true );
	$publisher   = get_post_meta( $post->ID, '_publisher', true );
	$release   = get_post_meta( $post->ID, '_release', true );
	$platforms   = get_post_meta( $post->ID, '_platforms', true );
?>
	<div class="sw-meta">
		<h3><?php echo esc_html__( 'Game Review', 'sw_core' ); ?></h3>
		<div class="meta-item">
			<p>
				<h4>Pros: </h4><br />
				<?php wp_editor( $pros, '_review_pros', $settings = array('textarea_name' => '_review_pros', 'textarea_rows' => 5) ); ?>
			</p>
		</div>
		
		<div class="meta-item">
			<p>
				<h4>Cons: </h4><br />
				<?php wp_editor( $cons, '_review_cons', $settings = array('textarea_name' => '_review_cons', 'textarea_rows' => 5) ); ?>
			</p>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Game Play', 'sw_core' ); ?>: </p>
			<input type="text" name="_gameplay" id="_gameplay" size="70" value="<?php echo esc_attr( $gameplay ); ?>"/>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Games Story', 'sw_core' ); ?>: </p>
			<input type="text" name="_gamestory" id="_gamestory" size="70" value="<?php echo esc_attr( $gamestory ); ?>"/>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Graphic', 'sw_core' ); ?>: </p>
			<input type="text" name="_grapphic" id="_grapphic" size="70" value="<?php echo esc_attr( $graphic ); ?>"/>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Performance', 'sw_core' ); ?>: </p>
			<input type="text" name="_performance" id="_performance" size="70" value="<?php echo esc_attr( $performance ); ?>"/>
		</div>
		
		<h3><?php echo esc_html__( 'Game Info', 'sw_core' ); ?></h3>
		<div class="meta-item">
			<p><?php echo esc_html__( 'Name Game', 'sw_core' ); ?>: </p>
			<input  type="text" name="_name_game" id="_name_game" size="70" value="<?php echo esc_attr( $name_game ); ?>"/>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Deverloper', 'sw_core' ); ?>: </p>
			<input type="text" name="_deverloper" id="_deverloper" size="70" value="<?php echo esc_attr( $deverloper ); ?>"/>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Publisher', 'sw_core' ); ?>: </p>
			<input type="text" name="_publisher" id="_publisher" size="70" value="<?php echo esc_attr( $publisher ); ?>"/>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Release Date', 'sw_core' ); ?>: </p>
			<input type="text" name="_release" id="_release" size="70" value="<?php echo esc_attr( $release ); ?>"/>
		</div>
		
		<div class="meta-item">
			<p><?php echo esc_html__( 'Platforms', 'sw_core' ); ?>: </p>
			<input type="text" name="_platforms" id="_platforms" size="70" value="<?php echo esc_attr( $platforms ); ?>"/>
		</div>
	</div>
<?php 
}

/**
 * Saves the custom meta input
 */
function prfx_meta_save( $post_id ) {
    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
	
	// Checks for input hot and saves - save checked as yes and unchecked at no
	if( isset( $_POST[ 'hot_checkbox' ] ) ) {
		update_post_meta( $post_id, 'hot_checkbox', 'yes' );
	} else {
		update_post_meta( $post_id, 'hot_checkbox', 'no' );
	}
	// Checks for input highlight and saves - save checked as yes and unchecked at no
	if( isset( $_POST[ 'highlight_checkbox' ] ) ) {
		update_post_meta( $post_id, 'highlight_checkbox', 'yes' );
	} else {
		update_post_meta( $post_id, 'highlight_checkbox', 'no' );
	}
	
	// Checks for input highlight and saves - save checked as yes and unchecked at no
	if( isset( $_POST[ 'recommend_checkbox' ] ) ) {
		update_post_meta( $post_id, 'recommend_checkbox', 'yes' );
	} else {
		update_post_meta( $post_id, 'recommend_checkbox', 'no' );
	}
	
	if( isset( $_POST['_review_pros'] ) ){
		update_post_meta( $post_id, '_review_pros',  $_POST['_review_pros'] );
	}
	if( isset( $_POST['_review_cons'] ) ){
		update_post_meta( $post_id, '_review_cons', $_POST['_review_cons'] );
	}
	if( isset( $_POST['_gameplay'] ) ){
		update_post_meta( $post_id, '_gameplay', $_POST['_gameplay'] );
	}
	if( isset( $_POST['_gamestory'] ) ){
		update_post_meta( $post_id, '_gamestory', $_POST['_gamestory'] );
	}
	if( isset( $_POST['_grapphic'] ) ){
		update_post_meta( $post_id, '_grapphic', $_POST['_grapphic'] );
	}
	if( isset( $_POST['_performance'] ) ){
		update_post_meta( $post_id, '_performance', $_POST['_performance'] );
	}
	
	if( isset( $_POST['_name_game'] ) ){
		update_post_meta( $post_id, '_name_game', $_POST['_name_game'] );
	}
	if( isset( $_POST['_deverloper'] ) ){
		update_post_meta( $post_id, '_deverloper', $_POST['_deverloper'] );
	}
	if( isset( $_POST['_publisher'] ) ){
		update_post_meta( $post_id, '_publisher', $_POST['_publisher'] );
	}
	if( isset( $_POST['_release'] ) ){
		update_post_meta( $post_id, '_release', $_POST['_release'] );
	}
	if( isset( $_POST['_platforms'] ) ){
		update_post_meta( $post_id, '_platforms', $_POST['_platforms'] );
	}
	
}
add_action( 'save_post', 'prfx_meta_save' );

//init the meta box
add_action( 'after_setup_theme', 'custom_postimage_setup' );
function custom_postimage_setup(){
    add_action( 'add_meta_boxes', 'custom_postimage_meta_box' );
    add_action( 'save_post', 'custom_postimage_meta_box_save' );
}

function custom_postimage_meta_box(){

    //on which post types should the box appear?
       add_meta_box('custom_postimage_meta_box',__( 'More Featured Images', 'sw_core'),'custom_postimage_meta_box_func','post','side','low');
}

function custom_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in custom_postimage_meta_box_save($post_id) as well.

        $image_meta_val=get_post_meta( $post->ID, 'second_featured_image', true);
		if ( $image_meta_val ) {
				$image = wp_get_attachment_thumb_url( $image_meta_val );
			} else {
				$image = wc_placeholder_img_src();
			}
        ?>
        <div class="custom_postimage_wrapper" id="second_featured_image_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo esc_url( $image ); ?>" style="width:100%;margin:0 0 20px; display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt="">
            <a class="addimage button" onclick="custom_postimage_add_image('second_featured_image');"style="margin:0 0 20px;"><?php _e('add image','sw_core'); ?></a><br>
            <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="custom_postimage_remove_image('second_featured_image');"><?php _e('remove image','sw_core'); ?></a>
            <input type="hidden" name="second_featured_image" id="second_featured_image" value="<?php echo $image_meta_val; ?>" />
        </div>
    <script>
    function custom_postimage_add_image(key){

        var $wrapper = jQuery('#'+key+'_wrapper');

        custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
            title: '<?php _e('select image','sw_core'); ?>',
            button: {
                text: '<?php _e('select image','sw_core'); ?>'
            },
            multiple: false
        });
        custom_postimage_uploader.on('select', function() {

            var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
            var img_url = attachment['url'];
            var img_id = attachment['id'];
            $wrapper.find('input#'+key).val(img_id);
            $wrapper.find('img').attr('src',img_url);
            $wrapper.find('img').show();
            $wrapper.find('a.removeimage').show();
        });
        custom_postimage_uploader.on('open', function(){
            var selection = custom_postimage_uploader.state().get('selection');
            var selected = $wrapper.find('input#'+key).val();
            if(selected){
                selection.add(wp.media.attachment(selected));
            }
        });
        custom_postimage_uploader.open();
        return false;
    }

    function custom_postimage_remove_image(key){
        var $wrapper = jQuery('#'+key+'_wrapper');
        $wrapper.find('input#'+key).val('');
        $wrapper.find('img').hide();
        $wrapper.find('a.removeimage').hide();
        return false;
    }
    </script>
    <?php
    wp_nonce_field( 'custom_postimage_meta_box', 'custom_postimage_meta_box_nonce' );
}

function custom_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['custom_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['custom_postimage_meta_box_nonce'],'custom_postimage_meta_box' )){

        //same array as in custom_postimage_meta_box_func($post)
		if(isset($_POST['second_featured_image']) && intval($_POST['second_featured_image'])!=''){
			update_post_meta( $post_id,'second_featured_image', intval($_POST['second_featured_image']));
		}else{
			update_post_meta( $post_id, 'second_featured_image', '');
		}
    }
}

class YA_Shortcodes{
	protected $supports = array();

	protected $tags = array( 'bloginfo', 'get_url' );

	public function __construct(){
		$this->add_shortcodes();
	}
	
	public function add_shortcodes(){
		if ( is_array($this->tags) && count($this->tags) ){
			foreach ( $this->tags as $tag ){
				add_shortcode($tag, array($this, $tag));
			}
		}
	}
	

	/**
	 * Bloginfo
	 * */
	function bloginfo( $atts){
		extract( shortcode_atts(array(
			'show' => 'wpurl',
			'filter' => 'raw'
			), $atts)
		);
		$html = '';
		$html .= get_bloginfo($show, $filter);

		return $html;
	}
	
	
	/*
	* Get URL shortcode
	*/
	function get_url($atts) {
		if(is_front_page()){
			$frontpage_ID = get_option('page_on_front');
			$link =  get_site_url().'/?page_id='.$frontpage_ID ;
			return $link;
		}
		elseif(is_page()){
			$pageid = get_the_ID();
			$link = get_site_url().'/?page_id='.$pageid ;
			return $link;
		}
		else{
			$link = $_SERVER['REQUEST_URI'];
			return $link;
		}
	}
}
new YA_Shortcodes();

/*
 * Vertical mega menu
 *
 */
function yt_vertical_megamenu_shortcode($atts){
	extract( shortcode_atts( array(
		'menu_locate' =>'',
		'title'  =>'',
		'el_class' => '',
		'menu_item' => 10,
		'more_text' => esc_html__( 'See More', 'sw_core' ),
		'less_text' => esc_html__( 'See Less', 'sw_core' ),
		), $atts ) );
	$output = '<div class="vc_wp_custommenu wp_verticle_emarket wpb_content_element ' . $el_class . '">';
	if($title != ''){
		$output.='<div class="mega-left-title">
		<strong>'.$title.'</strong>
	</div>';
}
$output.='<div class="wrapper_vertical_menu vertical_megamenu"  data-number="' .esc_attr( $menu_item ).'" data-moretext="'.esc_attr( $more_text ).'" data-lesstext="'.esc_attr( $less_text ).'">';
ob_start();
$output .= wp_nav_menu( array( 'theme_location' => $menu_locate , 'menu_class' => 'nav vertical-megamenu' ) );
$output .= ob_get_clean();
$output .= '</div></div>';
return $output;
}
add_shortcode('ya_mega_menu','yt_vertical_megamenu_shortcode');



/**
 * Clean up gallery_shortcode()
 *
 * Re-create the [gallery] shortcode and use thumbnails styling from Bootstrap
 *
 * @link http://twitter.github.com/bootstrap/components.html#thumbnails
 */
function ya_gallery($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if (!empty($attr['ids'])) {
		if (empty($attr['orderby'])) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$output = apply_filters('post_gallery', '', $attr);

	if ($output != '') {
		return $output;
	}

	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby']) {
			unset($attr['orderby']);
		}
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'size'       => 'medium',
		'include'    => '',
		'exclude'    => ''
		), $attr)
	);

	$id = intval($id);

	if ($order === 'RAND') {
		$orderby = 'none';
	}

	if (!empty($include)) {
		$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

		$attachments = array();
		foreach ($_attachments as $key => $val) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif (!empty($exclude)) {
		$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	} else {
		$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	}

	if (empty($attachments)) {
		return '';
	}

	if (is_feed()) {
		$output = "\n";
		foreach ($attachments as $att_id => $attachment) {
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}
	
	if (!wp_style_is('ya_photobox_css')){
		wp_enqueue_style('ya_photobox_css');
	}
	
	if (!wp_enqueue_script('photobox_js')){
		wp_enqueue_script('photobox_js');
	}
	
	$output = '<ul id="photobox-gallery-' . esc_attr( $instance ). '" class="thumbnails photobox-gallery gallery gallery-columns-'.esc_attr( $columns ).'">';

	$i = 0;
	$width = 100/$columns - 1;
	foreach ($attachments as $id => $attachment) {
		//$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
		$link = '<a class="thumbnail" href="' .esc_url( wp_get_attachment_url($id) ) . '">';
		$link .= wp_get_attachment_image($id,'large');
		$link .= '</a>';
		
		$output .= '<li style="width: '.esc_attr( $width ).'%;">' . $link;
		$output .= '</li>';
	}

	$output .= '</ul>';
	
	add_action('wp_footer', 'ya_add_script_gallery', 50);
	
	return $output;
}
add_action( 'after_setup_theme', 'ya_setup_gallery', 20 );
function ya_setup_gallery(){
	if ( current_theme_supports('bootstrap-gallery') ) {
		remove_shortcode('gallery');
		add_shortcode('gallery', 'ya_gallery');
	}
}

function ya_add_script_gallery() {
	$script = '';
	$script .= '<script type="text/javascript">
	jQuery(document).ready(function($) {
		try{
						// photobox
			$(".photobox-gallery").each(function(){
				$("#" + this.id).photobox("li a");
							// or with a fancier selector and some settings, and a callback:
				$("#" + this.id).photobox("li:first a", { thumbs:false, time:0 }, imageLoaded);
				function imageLoaded(){
					console.log("image has been loaded...");
				}
			})
} catch(e){
	console.log( e );
}
});
</script>';

echo $script;
}
/*
   * Shortcode change logo footer 
   *
  */
function emarket_change_logo( $atts ){
	extract(shortcode_atts(array(
		'title' => '',
		'colorset' => '',
		'post_status' => 'publish',
		'el_class' => ''
		), $atts));
	if( function_exists( 'emarket_options' ) ) {
		$emarket_logo = emarket_options()->getCpanelValue('sitelogo');
		$emarket_colorset = emarket_options()->getCpanelValue('scheme');
		$site_logo = emarket_options()->getCpanelValue('sitelogo');
		$output   ='';
		$output  .='<div class="ya-logo">';
		$output	 .='<a  href="'.esc_url( home_url( '/' ) ).'">';
		if(emarket_options()->getCpanelValue('sitelogo')){
			$output	 .='<img src="'.esc_url( $emarket_logo ).'" alt="logo"/>';
		}else{
			$logo = get_template_directory_uri().'/assets/img/logo-footer.png';
			$output	 .='<img src="'.esc_url( $logo ).'" alt="logo"/>';
		}
		$output	 .='</a>';
		$output  .='</div>';
		return $output; 
	}
}
add_shortcode('logo_footer','emarket_change_logo');

/***********************
 * Emarket IMG SLIDER
 *
 ***************************/
 function emarket_img_slide($atts){
	extract( shortcode_atts( array(
		'title' => '',
		'ids' => '',
		'fade' =>'true',
		'dots' => 'true',
		'autoplaySpeed' =>1000,
		'autoplay' =>'true', 
		'interval' => 5000
	), $atts ) );

//$ids = array();
$ids = explode( ',', $ids );
$emarket_direction = emarket_options()->getCpanelValue( 'direction' );
if ( is_rtl() || $emarket_direction == 'rtl' ){
	$rtl = 'true';
}else {$rtl = 'false';}
$html ='<div class="fade-slide loading" data-fade="'.esc_attr( $fade).'" data-dots="'.esc_attr( $dots).'" data-autoplaySpeed="'.esc_attr( $autoplaySpeed).'" data-autoplay="'.esc_attr( $autoplay).'" data-rtl="'.$rtl.'" >';
foreach ( $ids as $attach_id ) :  
	$linkimg = wp_get_attachment_image_url($attach_id,'full');
    $html .='<div class="image"><img src="'.esc_url( $linkimg ).'" alt="'.esc_html__('slide show','emarket').'"></div>';
endforeach ;
$html .='</div>';
return $html;
}
 add_shortcode('img_slide','emarket_img_slide');
 function load_img_slider_script(){
        if (!is_admin()){
			wp_register_script( 'slick_img_js', plugins_url( '/js/img.min.js', __FILE__ ),array(), null, true );		
			if (!wp_script_is('slick_img_js')) {
				wp_enqueue_script('slick_img_js');
			} 				
        }
    }
add_action('wp_enqueue_scripts', 'load_img_slider_script', 11);

function getPostViews($postID){
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0 View";
	}
	return $count.' Views';
}
function setPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if($count==''){
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	}else{
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}
add_action( 'wp_head', 'populaPost', 0 );
function populaPost(){
	if(is_single()){
		setPostViews(get_the_ID());
	}
}