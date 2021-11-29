<?php
/**
 * Plugin Name: SW List Store
 * Plugin URI: http://www.wpthemego.com/
 * Description: This plugin list store layout WordPress
 * Version: 1.0.1
 * Author: WpThemeGo
 * Author URI: http://www.wpthemego.com/
 * This plugin list store layout WordPress
 * Text Domain: sw_liststore
 * Domain Path: /languages/
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}
/* define plugin URL */
if ( ! defined( 'MBURL' ) ) {
	define( 'MBURL', plugins_url(). '/sw_liststore' );
}

function  sw_placeholder_img_src2(){
	return MBURL . '/images/placehold.png' ;
}

class Sw_liststore{
	function __construct(){
		global  $sw_detect;
		add_action( "admin_menu", array( $this, "sw_add_theme_menu_item" ) );
		add_action( "admin_init", array( $this, "sw_display_theme_panel_fields" ) );
		add_action( "admin_enqueue_scripts", array( $this, "sw_liststore_script" ) );
		add_action('wp_enqueue_scripts', array( $this, 'sw_scripts_liststore' ), 1100);
		
		/* Create Shortcode */
		add_shortcode( 'liststore_shortcode', array( $this, 'SWLS_Shortcode' ) );
	}
	
	function sw_liststore_script(){
		wp_enqueue_style( 'liststore-option', plugins_url( '/css/admin/style.css', __FILE__ ),array(), null );
	}
	
	function sw_scripts_liststore(){
		global $sw_detect;
		wp_enqueue_style('liststore_style', MBURL . '/css/style.css', array(), null);
	}
	
	function sw_add_theme_menu_item() {
		add_menu_page(
			esc_html__( "SW List Store", 'sw_liststore' ),
					esc_html__( "SW List Store", 'sw_liststore' ),
			'manage_options',
			'sw-liststore',
					array( $this, "sw_theme_settings_page2" ),
			'dashicons-admin-generic',
			1001
		);
	}
	
	function get_value( $option_id ){
		$options = get_option( 'sw_liststore' );		
		return isset( $options[$option_id] ) ? $options[$option_id] : array();
	}
	
	function sw_theme_settings_page2() {
		$thumb_vals  	= $this->get_value( 'thumbnail_id' );
		$map_vals  		= $this->get_value( 'liststore_map' );
		$address_vals  	= $this->get_value('liststore_address' );
		$time_vals  	= $this->get_value('liststore_time' );
		$phone_vals  	= $this->get_value('liststore_phone' );
		$title_vals  	= $this->get_value( 'liststore_title' );
		$link_vals 	 = $this->get_value( 'liststore_link' );
		
?>
		<div class="setting-liststore-panel">
			<h1><?php esc_html_e( 'List Store Panel', 'sw_liststore' ) ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields("section"); ?>
				<div id="setting_liststore">
					<?php 
							if( count( $link_vals ) > 0 ) {
							foreach( $link_vals as $key => $val ){
								$class = ( $key % 2 == 0 ) ? 'even' : '';
					?>
					<div class="setting-wrapper <?php echo esc_attr( $class ) ?>" id="<?php echo esc_attr( 'setting_id_' . $key ); ?>">
					
						<ul class="clearfix">
							<li class="label"><?php esc_html_e( 'Title', 'sw_liststore' ) ?></li>
							<li class="setting-content"><input type="text" name="sw_liststore[liststore_title][]" value="<?php echo esc_attr( $title_vals[$key] ); ?>" size="60"/> </li>
						</ul>
						
						<ul class="clearfix">
							<li class="label"><?php esc_html_e( 'Address', 'sw_liststore' ) ?></li>
							<li class="setting-content"><input type="text" name="sw_liststore[liststore_address][]" value="<?php echo esc_attr( $address_vals[$key] ); ?>" size="60"/> </li>
						</ul>
						
						<ul class="clearfix">
							<li class="label"><?php esc_html_e( 'Work time', 'sw_liststore' ) ?></li>
							<li class="setting-content"><input type="text" name="sw_liststore[liststore_time][]" value="<?php echo esc_attr( $time_vals[$key] ); ?>" size="60"/> </li>
						</ul>
						
						<ul class="clearfix">
							<li class="label"><?php esc_html_e( 'Phone', 'sw_liststore' ) ?></li>
							<li class="setting-content"><input type="text" name="sw_liststore[liststore_phone][]" value="<?php echo esc_attr( $phone_vals[$key] ); ?>" size="60"/> </li>
						</ul>
						
						<ul class="clearfix">
								<li class="label"><?php esc_html_e( 'Website', 'sw_liststore' ) ?></li>
								<li class="setting-content"><input type="text" name="sw_liststore[liststore_link][]" value="<?php echo esc_attr( $val ); ?>" size="60"/> </li>
						</ul>
							
						<ul class="clearfix">
							<li class="label"><?php esc_html_e( 'Map', 'sw_liststore' ) ?></li>
							<li class="setting-content"><input type="text" name="sw_liststore[liststore_map][]" value="<?php echo esc_attr( $map_vals[$key] ); ?>" size="60"/> </li>
						</ul>
						
						<ul class="clearfix">
							<li class="label"><?php esc_html_e( 'Upload Image Thumbnail', 'sw_liststore' ) ?></li>
							<li class="setting-content">
								<?php
									$thumb_image = sw_placeholder_img_src2();
									$thumb_key = isset( $thumb_vals[$key] ) ? $thumb_vals[$key] : 0;
									if ( $thumb_key ) {
										$thumb_image = wp_get_attachment_thumb_url( $thumb_vals[$key] );
									} 
								?>
								<div class="form-upload">
									<div class="product-thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $thumb_image ); ?>" width="60px" height="60px" /></div>
									<div style="line-height: 60px;">
										<input type="hidden" class="thumbnail" name="sw_liststore[thumbnail_id][]" value="<?php echo esc_attr( $thumb_key ) ?>"/>
										<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'sw_liststore' ); ?></button>
										<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'sw_liststore' ); ?></button>
									</div>									
								</div>
							</li>
						</ul>
						<button type="button" class="setting-remove" data-id="<?php echo esc_attr( '#setting_id_' . $key ); ?>"><?php esc_html_e( 'Remove This Block', 'sw_liststore' ); ?></button>
					</div>
					<?php } ?>
					<?php } ?>
				</div>
				<div id="setting_append"></div>				
				
				<div class="setting-liststore-bottom">
					<p class="submit"><button type="button" id="setting_more" class="setting_more">Add More</button></p>
					<?php submit_button(); ?>          
				</div>
			</form>
		</div>
		<script type="text/javascript"> 
			(function($) {
				"use strict";
				var key = <?php echo count( $link_vals ); ?>;
				$('.setting_more').on( 'click', function(){
					var item_class = ( key % 2 == 0 ) ?  'even' : '';
					var content = '<div class="setting-wrapper ' + item_class +'" id="setting_id_'+ key + '">' +
						'<ul class="clearfix">' +
							'<li class="label"><?php esc_html_e( 'Title', 'sw_liststore' ) ?></li>' +
							'<li class="setting-content"><input type="text" name="sw_liststore[liststore_title][]" value="" size="60"/> </li>'+
						'</ul>' +
						'<ul class="clearfix">' +
							'<li class="label"><?php esc_html_e( 'Address', 'sw_liststore' ) ?></li>' +
							'<li class="setting-content"><input type="text" name="sw_liststore[liststore_address][]" value="" size="60"/> </li>'+
						'</ul>' +
						'<ul class="clearfix">' +
							'<li class="label"><?php esc_html_e( 'Work time', 'sw_liststore' ) ?></li>' +
							'<li class="setting-content"><input type="text" name="sw_liststore[liststore_time][]" value="" size="60"/> </li>'+
						'</ul>' +
						'<ul class="clearfix">' +
							'<li class="label"><?php esc_html_e( 'Phone', 'sw_liststore' ) ?></li>' +
							'<li class="setting-content"><input type="text" name="sw_liststore[liststore_phone][]" value="" size="60"/> </li>'+
						'</ul>' +
						'<ul class="clearfix">' +
								'<li class="label"><?php esc_html_e( 'Website', 'sw_liststore' ) ?></li>' + 
								'<li class="setting-content"><input type="text" name="sw_liststore[liststore_link][]" value="" size="60"/> </li>' +
						'</ul>'+ 	
						'<ul class="clearfix">' +
							'<li class="label"><?php esc_html_e( 'Map', 'sw_liststore' ) ?></li>' + 
							'<li class="setting-content"><input type="text" name="sw_liststore[liststore_map][]" value="" size="60"/> </li>' +
						'</ul>'+ 
						'<ul class="clearfix">'+ 
							'<li class="label"><?php esc_html_e( 'Upload Image Thumbnail', 'sw_liststore' ) ?></li>'+ 
							'<li class="setting-content">'+ 
								'<div class="form-upload">'+ 
									'<div class="product-thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url(  sw_placeholder_img_src2() ); ?>" width="60px" height="60px" /></div>'+ 
									'<div style="line-height: 60px;">'+ 
										'<input type="hidden" class="thumbnail" name="sw_liststore[thumbnail_id][]" />'+ 
										'<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'sw_woocommerce' ); ?></button>'+ 
										'<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'sw_woocommerce' ); ?></button>'+ 
									'</div>'+ 									
								'</div>'+ 
							'</li>'+ 
						'</ul>' +
						'<button type="button" class="setting-remove" data-id="#setting_id_'+ key + '"><?php esc_html_e( 'Remove This Block', 'sw_liststore' ); ?></button>';
						$('#setting_append').append( content );
						$( '.setting-remove' ).on( 'click', function(){
							var target = $(this).data('id');
							$(target).remove();
						});
						$( '.form-upload' ).each( function(){
							sw_upload_image( $(this) );
						});
						$( '.checkbox-mobile' ).each( function(){
							var textbox = $(this).find( 'input[type="hidden"]' );
							$(this).find( 'input[type="checkbox"]' ).on( 'click', function(){
								var value = ( $(this).is(':checked') ) ? 1 : 0;
								 textbox.val( value ); 
							});
						});
						key ++;
				});					
				function sw_upload_image( tar_parent ){
					// Only show the "remove image" button when needed
					if ( ! tar_parent.find( '.thumbnail' ).val() ) {
						tar_parent.find( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					tar_parent.find( '.upload_image_button' ).on( 'click', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( "Choose an image", 'sw_woocommerce' ); ?>',
							button: {
								text: '<?php _e( "Use image", 'sw_woocommerce' ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment = file_frame.state().get( 'selection' ).first().toJSON();
							console.log( attachment );
							tar_parent.find( '.thumbnail' ).val( attachment.id );
							tar_parent.find( '.product-thumbnail > img' ).attr( 'src', attachment.sizes.thumbnail.url );
							tar_parent.find( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					tar_parent.find( '.remove_image_button' ).on( 'click', function() {
						tar_parent.find( '.product-thumbnail > img' ).attr( 'src', '<?php echo esc_js(  sw_placeholder_img_src2() ); ?>' );
						tar_parent.find( '.thumbnail' ).val( '' );
						tar_parent.find( '.remove_image_button' ).hide();
						return false;
					});
				}
				$( '.setting-remove' ).on( 'click', function(){
					var target = $(this).data('id');
					$(target).remove();
				});
				$( '.form-upload' ).each( function(){
					sw_upload_image( $(this) );
				});
				$( '.checkbox-mobile' ).each( function(){
					var textbox = $(this).find( 'input[type="hidden"]' );
					$(this).find( 'input[type="checkbox"]' ).on( 'click', function(){
						var value = ( $(this).is(':checked') ) ? 1 : 0;
						 textbox.val( value ); 
					});
				});
			})(jQuery);
		</script>
<?php
	}

	function sw_display_theme_panel_fields() {
		add_settings_section("section", "SW Liststore", null, "theme-options");	
		register_setting( "section", "sw_liststore", array() );
	}		
	
		// Liststore Shortcode
	public function SWLS_Shortcode( $atts, $content = '' ){
		extract( shortcode_atts(
			array(
				'title' => '',	
				'description' => '',
			), $atts )
		);
		ob_start();
		$thumb_vals  = $this->get_value( 'thumbnail_id' );
		$map_vals  = $this->get_value( 'liststore_map' );
		$address_vals  = $this->get_value('liststore_address' );
		$time_vals  = $this->get_value('liststore_time' );
		$phone_vals  = $this->get_value('liststore_phone' );
		$title_vals  = $this->get_value( 'liststore_title' );
		$link_vals 	 = $this->get_value( 'liststore_link' );
	?>
		<div class="sw-list-store">
				<div class="title"><?php echo esc_html( $title ); ?></div>
				<div class="description"><?php echo esc_html( $description ); ?></div>
				<div class="resp-tab" style="position:relative;">
					<div class="nav-tabs-select">
						<ul class="nav nav-tabs">
							<?php
								$i = 0;
								foreach( $link_vals as $key => $item ) { 
									
								?>
								<li class="<?php if( $i == 0 ){echo 'active'; }?>">
									<a data-toggle="tab" href="#<?php echo esc_attr( 'map' . $key ); ?>">
										<div class="item-content">
											<h3><?php echo esc_html( $title_vals[$key] ); ?></h3>
											<ul>
												<li><span><?php echo esc_html( $address_vals[$key] ); ?></span></li>
												<li><i class="fa fa-phone"></i><span><?php echo esc_html( $phone_vals[$key] ); ?></span></li>
												<li><i class="fa  fa-clock-o"></i><span><?php echo esc_html( $time_vals[$key] ); ?></span></li>
											</ul>
										</div>
									</a>
								</li>
							<?php $i++; } ?>	
						</ul>
					</div>
					<div class="tab-content clearfix">
						<?php $i = 0; foreach( $link_vals as $key => $item ) { ?> 
						
							<div class="tab-pane fade <?php echo ( ($i == 0)?'active': ''); ?>"  id="<?php echo esc_attr( 'map' . $key ); ?>"><?php echo  $map_vals[$key] ; ?></div>
							
						<?php $i++; } ?>
					</div>
				</div>
		</div>
		<script type="text/javascript">
			(function($) {
				"use strict";
				
			})(jQuery);
		</script>
	<?php 
		$content = ob_get_clean();
		return $content;
	}
	
}
new Sw_liststore();
