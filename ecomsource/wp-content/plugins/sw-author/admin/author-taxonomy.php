<?php 
/**
* Register post type author and taxonomy
* Author: Smartaddons
**/

// create function placeholder img
if( !function_exists( 'sw_placeholder_img_src' ) ) {
	function sw_placeholder_img_src(){
		return SAURL . '/images/placehold.png' ;
	}
}

/**
* Function Register
**/
add_action( 'init', 'sw_author_register', 0 );
function sw_author_register(){
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => __( 'Authors', 'sw_author' ),
		'singular_name'     => __( 'Author', 'sw_author' ),
		'search_items'      => __( 'Search Authors', 'sw_author' ),
		'all_items'         => __( 'All Authors', 'sw_author' ),
		'parent_item'       => __( 'Parent Author', 'sw_author' ),
		'parent_item_colon' => __( 'Parent Author:', 'sw_author' ),
		'edit_item'         => __( 'Edit Author', 'sw_author' ),
		'update_item'       => __( 'Update Author', 'sw_author' ),
		'add_new_item'      => __( 'Add New Author', 'sw_author' ),
		'new_item_name'     => __( 'New Author Name', 'sw_author' ),
		'menu_name'         => __( 'Authors', 'sw_author' ),
	);

	$args = array(
		'labels' 			=> $labels,
		'public' 			=> true,
		'has_archive' 		=> true,
		'show_ui' 			=> true,
		'query_var' 		=> true,
		'menu_icon' 		=> 'dashicons-admin-users',
		'rewrite'         	=> array( 'slug' => 'book_author' ),
		'capability_type' 	=> 'post',
		'hierarchical' 		=> true,			
		'menu_position' 	=> 4,
		'supports' 				=> array( 'title','thumbnail', 'editor', 'book_author', 'comments' ),
		'publicly_queryable' => true
	);
	register_post_type( 'book_author' , $args );

	register_taxonomy( 'author_cat', array( 'book_author' ), array( 'hierarchical' => true, 'label' => esc_html__( 'Categories Authors', 'sw_author' ), 'singular_label' => esc_html__( 'Category Author', 'sw_author' ), 'rewrite' => true ) );
	register_taxonomy( 'author_tag', 'book_author', array(
		'hierarchical'  => false, 
		'label' 				=> esc_html__( 'Tags', 'sw_author' ), 
		'singular_name' => esc_html__( 'Tags', 'sw_author' ), 
		'rewrite' 			=> true, 
		'query_var' 		=> true
		)
	);
}

/**
* Register Metabox
**/
add_action( 'admin_init', 'sw_author_init' );
function sw_author_init(){
	add_meta_box( 'sw_author_meta', esc_html__( 'Awards & Honorable Mentions', 'sw_author' ), 'sw_author_meta', 'book_author', 'normal', 'high' );
	add_meta_box( 'sw_author_social_meta', esc_html__( 'Infor Author', 'sw_author' ), 'sw_author_social_meta', 'book_author', 'side', 'high' );
	add_meta_box( 'sw_author_product_meta', esc_html__( 'Book Authors', 'sw_author' ), 'sw_author_product_meta', 'product', 'side', 'high' );
	//add_meta_box( 'sw_author_product_preview_meta', esc_html__( 'Book Preview', 'sw_author' ), 'sw_author_product_preview_meta', 'product', 'side', 'high' );
}

add_action( 'admin_enqueue_scripts', 'sw_author_admin_script' );
function sw_author_admin_script(){
	$text = array(
		'award_title' => esc_attr__( 'Enter award title', 'sw_author' ),
		'award_year' => esc_attr__( 'Enter award year', 'sw_author' ),
		'award_book' => esc_attr__( 'Enter name of book', 'sw_author' ),
		'award_delete' => esc_html__( 'Delete', 'sw_author' ),
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	);
	$current_screen =  isset( get_current_screen()->id ) ? get_current_screen()->id : '';
	if( in_array( $current_screen, array( 'book_author', 'product' ) ) ){
		wp_register_script( 'sw-author-admin', SAURL . '/js/admin/admin.min.js', array( 'jquery', 'selectWoo' ), null, true );
		wp_localize_script( 'sw-author-admin', 'sw_author', $text );
		wp_enqueue_script( 'sw-author-admin' );
		wp_enqueue_script('autocomplete-jquery');		wp_enqueue_style( 'sw-author', SAURL . '/css/admin/style.css', null, true );
	}
}


/* Metabox content */
function sw_author_meta(){
	global $post;
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'sw_author_save_meta', 'sw_author_meta_nonce' );
	$award_title  = get_post_meta( $post->ID, 'sw_awards_title', true );
	$award_year  = get_post_meta( $post->ID, 'sw_awards_year', true );
	$award_book  = get_post_meta( $post->ID, 'sw_awards_book', true );
?>
	<div class="sw-author-awards">
	<?php 
		if( is_array( $award_title ) && is_array( $award_year ) && is_array( $award_book ) ) { 
			foreach( $award_title as $key => $value ) {
	?>
			<div class="author-awards-item">
				<input type="text" name="sw_awards_title[]" value="<?php echo esc_attr( $value ); ?>" class="awards-title" placeholder="<?php echo esc_attr__( 'Enter award title', 'sw_author' ); ?>"/>
				<input type="number" name="sw_awards_year[]" value="<?php echo esc_attr( isset( $award_year[$key] ) ? $award_year[$key] : '' ); ?>" class="awards-year" min="1000"  placeholder="<?php echo esc_attr__( 'Enter award year', 'sw_author' ); ?>"/>
				<input type="text" name="sw_awards_book[]" value="<?php echo esc_attr( isset( $award_book[$key] ) ? $award_book[$key] : '' ); ?>" class="awards-book"  placeholder="<?php echo esc_attr__( 'Enter name of book', 'sw_author' ); ?>"/>
				<a href="#" class="delete-award-item"><?php esc_html_e( 'Delete', 'sw_author' ); ?></a>
			</div>
			<?php } ?>
	<?php }else{ ?>
		<div class="author-awards-item">
			<input type="text" name="sw_awards_title[]" value="" class="awards-title" placeholder="<?php echo esc_attr__( 'Enter award title', 'sw_author' ); ?>"/>
			<input type="number" name="sw_awards_year[]" value="" class="awards-year" min="1000"  placeholder="<?php echo esc_attr__( 'Enter award year', 'sw_author' ); ?>"/>
			<input type="text" name="sw_awards_book[]" value="" class="awards-book"  placeholder="<?php echo esc_attr__( 'Enter name of book', 'sw_author' ); ?>"/>
		</div>
	<?php } ?>
	</div>
	<div class="author-awards-bottom"><a href="#" class="award-more"><?php esc_html_e( 'Add More', 'sw_author' ); ?></div>
<?php 
}
function sw_author_social_meta(){
		global $post;
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'sw_author_save_meta', 'sw_author_meta_nonce' );
		
		$facebook = get_post_meta( $post->ID, 'facebook', true );	
		$twitter = get_post_meta( $post->ID, 'twitter', true );
		$instagram = get_post_meta( $post->ID, 'instagram', true );
		$linkedin = get_post_meta( $post->ID, 'linkedin', true );
		$age = get_post_meta( $post->ID, 'age', true );
		$languages = get_post_meta( $post->ID, 'languages', true );
		$bookpl = get_post_meta( $post->ID, 'bookpl', true );
		
	?>	
		<div>
		<h3><?php _e( 'Social', 'sw_author' ); ?></h3>
			<p><label><b><?php _e('Facebook', 'sw_author'); ?>:</b></label><br/>
				<input type ="text" name = "facebook" value ="<?php echo esc_attr( $facebook );?>" size="80%" />
			</p>
			<p><label><b><?php _e('Twitter', 'sw_author'); ?>:</b></label><br/>
				<input type ="text" name = "twitter" value ="<?php echo esc_attr( $twitter );?>" size="80%" />
			</p>
			<p><label><b><?php _e('Instagram', 'sw_author'); ?>:</b></label><br/>
				<input type ="text" name = "instagram" value ="<?php echo esc_attr( $instagram );?>" size="80%" />
			</p>
			<p><label><b><?php _e('Linkedin', 'sw_author'); ?>:</b></label><br/>
				<input type ="text" name = "linkedin" value ="<?php echo esc_attr( $linkedin );?>" size="80%" />
			</p>
		<h3><?php _e( 'Infor Author', 'sw_author' ); ?></h3>
			<p><label><b><?php _e('Age', 'sw_author'); ?>:</b></label><br/>
				<input type ="text" name = "age" value ="<?php echo esc_attr( $age );?>" size="80%" />
			</p>	
			<p><label><b><?php _e('Languages', 'sw_author'); ?>:</b></label><br/>
				<input type ="text" name = "languages" value ="<?php echo esc_attr( $languages );?>" size="80%" />
			</p>
			<p><label><b><?php _e('Books published', 'sw_author'); ?>:</b></label><br/>
				<input type ="text" name = "bookpl" value ="<?php echo esc_attr( $bookpl );?>" size="80%" />
			</p>
		</div>
	<?php 
	}
function sw_author_product_meta(){
	global $post;
	wp_nonce_field( 'sw_author_save_meta', 'sw_author_meta_nonce' );
	$sw_author_product  = get_post_meta( $post->ID, 'author_product', true );
?>
	<div class="author-product">
		<p><?php esc_html_e( 'Select Book Author', 'sw_author' ) ?></p>
		<select id="author_product" multiple data-placeholder="<?php esc_attr_e( 'Select Book Author', 'sw_author' ) ?>" name="author_product[]">
			<?php 
				if(  $sw_author_product != '' ){ 
					foreach( $sw_author_product as $key ){
						$check = ( in_array( $key, $sw_author_product ) ) ? 'selected="selected"' : '';
						echo '<option value="'. esc_attr( $key ) .'" '. $check .'>'. get_the_title( $key ) .'</option>';
					}
				
				} 
			?>
		</select>
	</div>
<?php 
}

function sw_author_product_preview_meta(){
	global $post;
	wp_nonce_field( 'sw_author_save_meta', 'sw_author_meta_nonce' );
	$author_product_preview  = get_post_meta( $post->ID, 'author_product_preview', true );
	$attach_url =( wp_get_attachment_url( $author_product_preview ) ) ? wp_get_attachment_url( $author_product_preview ) : '';
?>
	<div class="author-product">
		<p><?php esc_html_e( 'Upload Preview Book', 'sw_author' ) ?></p>
		<div class="form-upload">
			<div class="product-thumbnail"><input type="text" readonly value="<?php echo esc_url( $attach_url ); ?>" style="width: 100%; margin-bottom: 10px;"/></div>
			<div>
				<input type="hidden" class="thumbnail" name="author_product_preview" value="<?php echo esc_attr( $author_product_preview ); ?>"/>
				<button type="button" class="upload_image_button button"><?php _e( 'Upload', 'sw_author' ); ?></button>
				<button type="button" class="remove_image_button button">x</button>
			</div>									
		</div>
	</div>
	<script>
		(function($) {
			"use strict";
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
						title: '<?php _e( "Choose an image", 'sw_author' ); ?>',
						button: {
							text: '<?php _e( "Use image", 'sw_author' ); ?>'
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get( 'selection' ).first().toJSON();
						tar_parent.find( '.thumbnail' ).val( attachment.id );
						tar_parent.find( '.product-thumbnail > input' ).val('<?php echo esc_js( $attach_url ); ?>');
						tar_parent.find( '.remove_image_button' ).show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				tar_parent.find( '.remove_image_button' ).on( 'click', function() {
					tar_parent.find( '.thumbnail' ).val( '' );
					tar_parent.find( '.product-thumbnail > input' ).val('');
					tar_parent.find( '.remove_image_button' ).hide();
					return false;
				});
			}
			$( '.form-upload' ).each( function(){
				sw_upload_image( $(this) );
			});
		})(jQuery);
	</script>
<?php 
}


/* Save Metabox */
add_action( 'save_post', 'sw_author_save_meta', 10, 1 );
function sw_author_save_meta( $post_id ){
	if ( ! isset( $_POST['sw_author_meta_nonce'] ) ) {
		return;
	}
	$sw_awards_title = isset( $_POST['sw_awards_title'] ) ? $_POST['sw_awards_title'] : array();
	$sw_awards_year  = isset( $_POST['sw_awards_year'] ) ? $_POST['sw_awards_year'] : array();
	$sw_awards_book  = isset( $_POST['sw_awards_book'] ) ? $_POST['sw_awards_book'] : array();
	$sw_author_product  = isset( $_POST['author_product'] ) ? $_POST['author_product'] : array();
	$author_product_preview  = isset( $_POST['author_product_preview'] ) ? $_POST['author_product_preview'] : '';
	$facebook  = isset( $_POST['facebook'] ) ? $_POST['facebook'] : '';
	$twitter  = isset( $_POST['twitter'] ) ? $_POST['twitter'] : '';
	$linkedin  = isset( $_POST['linkedin'] ) ? $_POST['linkedin'] : '';
	$instagram  = isset( $_POST['instagram'] ) ? $_POST['instagram'] : '';
	$age  = isset( $_POST['age'] ) ? $_POST['age'] : '';
	$languages  = isset( $_POST['languages'] ) ? $_POST['languages'] : '';
	$bookpl  = isset( $_POST['bookpl'] ) ? $_POST['bookpl'] : '';
	
	update_post_meta( $post_id, 'age', $age );
	update_post_meta( $post_id, 'languages', $languages );
	update_post_meta( $post_id, 'bookpl', $bookpl );
	update_post_meta( $post_id, 'facebook', $facebook );
	update_post_meta( $post_id, 'twitter', $twitter );
	update_post_meta( $post_id, 'linkedin', $linkedin );
	update_post_meta( $post_id, 'instagram', $instagram );
	update_post_meta( $post_id, 'sw_awards_title', $sw_awards_title );
	update_post_meta( $post_id, 'sw_awards_year', $sw_awards_year );	
	update_post_meta( $post_id, 'sw_awards_book', $sw_awards_book );	
	update_post_meta( $post_id, 'author_product', $sw_author_product );	
	update_post_meta( $post_id, 'author_product_preview', $author_product_preview );	
}

add_action( 'wp_ajax_sw_search_author_callback', 'sw_search_author_callback' );
function sw_search_author_callback(){
	$filter_name = ( isset($_GET['q']) && $_GET['q'] ) ? $_GET['q'] : '';
	$filter_name = str_replace( "%20"," ",$filter_name );

	global $wpdb;
	$post_ids =  $post_ids =  $wpdb->get_results( $wpdb->prepare(
	"SELECT SQL_CALC_FOUND_ROWS {$wpdb->posts}.ID, {$wpdb->posts}.post_title FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_title LIKE %s AND {$wpdb->posts}.post_type='book_author'
	AND ({$wpdb->posts}.post_password = '') AND ({$wpdb->posts}.post_status = 'publish') 
	GROUP BY {$wpdb->posts}.ID 
	ORDER BY {$wpdb->posts}.post_title LIKE %s DESC", '%' .$filter_name . '%', '%' .$filter_name . '%' ));
	
	if( count( $post_ids ) ){
		foreach( $post_ids as $post_id ){
			$suggestions[] = array(
				'id' 	=> $post_id->ID,
				'title' => $post_id->post_title,		
			);	
		}
	}else{
		$no_results =  __( 'No products found.', 'sw_ajax_woocommerce_search' );
		$suggestions[] = array(
		  'id' 		=> - 1,
		  'title' => $no_results,
		);
	}
	echo json_encode( array( 'items' => $suggestions ) );
	exit();
}