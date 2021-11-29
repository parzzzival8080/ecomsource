<?php
/**
 * Name: SW Brand Taxonomy
 * Description: Register taxonomy brand
 */
 
class sw_brand_taxonomy {
	
	/**
	 * Setup.
	 */
	function __construct(){
		/* Add Taxonomy and Post type */
		add_action( 'init', array( $this, 'brand_register' ), 5 );	
		
		/* Add Custom field to category product */
		add_action( 'product_brand_add_form_fields', array( $this, 'add_category_fields' ), 100 );
		add_action( 'product_brand_edit_form_fields', array( $this, 'edit_category_fields' ), 100 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );
		
		// Add columns
		add_filter( 'manage_edit-product_brand_columns', array( $this, 'product_brand_columns' ) );
		add_filter( 'manage_product_brand_custom_column', array( $this, 'product_brand_column' ), 10, 3 );
	}
	
	/*
	** Register Taxonomy
	*/
	function brand_register() {
		$args = array( 
			'hierarchical' => true, 
			'label' =>  __( 'Categories Brand', 'sw_product_brand' ), 
			'singular_label' => __( 'Category Brand', 'sw_product_brand' ), 
			'rewrite' => true,
			'capabilities'          => array(
				'manage_terms' => 'manage_product_terms',
				'edit_terms'   => 'edit_product_terms',
				'delete_terms' => 'delete_product_terms',
				'assign_terms' => 'assign_product_terms',
			),
		);
		register_taxonomy( 'product_brand', array( 'product' ), $args );
	}
	
	/**
		*	Add Custom field on category product
		**/
		public function add_category_fields() { 
			wp_enqueue_media();
	?>
			<div class="form-field">
				<label><?php _e( 'Thumbnail', 'sw_product_brand' ); ?></label>
				<div id="product_brand_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="product_brand_thumbnail_id" name="product_brand_thumbnail_id" />
					<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'sw_product_brand' ); ?></button>
					<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'sw_product_brand' ); ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( ! jQuery( '#product_brand_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( "Choose an image", 'sw_product_brand' ); ?>',
							button: {
								text: '<?php _e( "Use image", 'sw_product_brand' ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment = file_frame.state().get( 'selection' ).first().toJSON();
							
							jQuery( '#product_brand_thumbnail_id' ).val( attachment.id );
							jQuery( '#product_brand_thumbnail > img' ).attr( 'src', attachment.sizes.thumbnail.url );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#product_brand_thumbnail img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#product_brand_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</div>
			<?php
		}
		
		public function edit_category_fields( $term ) {
			wp_enqueue_media();
			$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_bid', true ) );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = wc_placeholder_img_src();
			}
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'sw_product_brand' ); ?></label></th>
				<td>
					<div id="product_brand_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
					<div style="line-height: 60px;">
						<input type="hidden" id="product_brand_thumbnail_id" name="product_brand_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
						<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'sw_product_brand' ); ?></button>
						<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'sw_product_brand' ); ?></button>
					</div>
					<script type="text/javascript">

						// Only show the "remove image" button when needed
						if ( '0' === jQuery( '#product_brand_thumbnail_id' ).val() ) {
							jQuery( '.remove_image_button' ).hide();
						}

						// Uploading files
						var file_frame;

						jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( file_frame ) {
								file_frame.open();
								return;
							}

							// Create the media frame.
							file_frame = wp.media.frames.downloadable_file = wp.media({
								title: '<?php _e( "Choose an image", 'sw_product_brand' ); ?>',
								button: {
									text: '<?php _e( "Use image", 'sw_product_brand' ); ?>'
								},
								multiple: false
							});

							// When an image is selected, run a callback.
							file_frame.on( 'select', function() {
								var attachment = file_frame.state().get( 'selection' ).first().toJSON();

								jQuery( '#product_brand_thumbnail_id' ).val( attachment.id );
								jQuery( '#product_brand_thumbnail img' ).attr( 'src', attachment.sizes.thumbnail.url );
								jQuery( '.remove_image_button' ).show();
							});

							// Finally, open the modal.
							file_frame.open();
						});

						jQuery( document ).on( 'click', '.remove_image_button', function() {
							jQuery( '#product_brand_thumbnail img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
							jQuery( '#product_brand_thumbnail_id' ).val( '' );
							jQuery( '.remove_image_button' ).hide();
							return false;
						});

					</script>
					<div class="clear"></div>
				</td>
			</tr>
			<?php
		}
		public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
			if ( isset( $_POST['product_brand_thumbnail_id'] ) && 'product_brand' === $taxonomy ) {
				update_term_meta( $term_id, 'thumbnail_bid', absint( $_POST['product_brand_thumbnail_id'] ) );
			}
		}
	
	/**
	 * Thumbnail column added to category admin.
	 *
	 * @param mixed $columns
	 * @return array
	 */
	public function product_brand_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', 'sw_product_brand' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @param string $columns
	 * @param string $column
	 * @param int $id
	 * @return array
	 */
	public function product_brand_column( $columns, $column, $id ) {

		if ( 'thumb' == $column ) {

			$thumbnail_id = get_term_meta( $id, 'thumbnail_bid', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id ) ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();
			} else {
				$image = wc_placeholder_img_src();
			}

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: http://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'sw_product_brand' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		return $columns;
	}
}

new sw_brand_taxonomy();
?>