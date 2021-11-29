<?php
/**
 * Category Mapping
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$file = CED_SHOPEE_DIRPATH . 'admin/partials/header.php';
if ( file_exists( $file ) ) {
	include_once $file;
}

$woo_store_categories      = get_terms( 'product_cat' );
$shop_id                   = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
$base_dir                  = wp_upload_dir()['basedir'];
$folder_name               = $base_dir . '/cedcommerce/Shopee/Categories/';
$get_location              = ced_shopee_account_location( $shop_id );
$get_filenames             = ced_shopee_category_files();
$category_first_level_file = $folder_name . $get_filenames[ $get_location ]['level1'];
$shopee_categories         = array();

if ( file_exists( $category_first_level_file ) ) {
	$shopee_categories = file_get_contents( $category_first_level_file );
	$shopee_categories = json_decode( $shopee_categories, true );
} else {
	$category_first_level_file = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/json/MY_categoryLevel1.json';
	$shopee_categories         = file_get_contents( $category_first_level_file );
	$shopee_categories         = json_decode( $shopee_categories, true );
}
?>
<div id="profile_create_message"></div>
<div class="ced_shopee_category_mapping_wrapper" id="ced_shopee_category_mapping_wrapper">
	<div class="ced_shopee_store_categories_listing" id="ced_shopee_store_categories_listing">
		<table class="wp-list-table widefat fixed striped posts ced_shopee_store_categories_listing_table" id="ced_shopee_store_categories_listing_table">
			<thead>
				<th><b><?php esc_html_e( 'Select Categories to be Mapped', 'woocommerce-shopee-integration' ); ?></b></th>
				<th><b><?php esc_html_e( 'WooCommerce Store Categories', 'woocommerce-shopee-integration' ); ?></b></th>
				<th colspan="3"><b><?php esc_html_e( 'Mapped to Shopee Category', 'woocommerce-shopee-integration' ); ?></b></th>
				<td><button class="ced_shopee_custom_button"  name="ced_shopee_refresh_categories" id="ced_shopee_category_refresh_button" data-shop_id=<?php echo esc_attr( $shop_id ); ?>><?php esc_html_e( 'Refresh Categories', 'woocommerce-shopee-integration' ); ?></button></td>
			</thead>
			<tbody>
				<?php
				foreach ( $woo_store_categories as $key => $value ) {
					?>
					<tr class="ced_shopee_store_category" id="<?php echo esc_attr( 'ced_shopee_store_category_' . $value->term_id ); ?>">
						<td>
							<input type="checkbox" class="ced_shopee_select_store_category_checkbox" name="ced_shopee_select_store_category_checkbox[]" data-categoryID="<?php echo esc_attr( $value->term_id ); ?>"></input>
						</td>
						<td>
							<span class="ced_shopee_store_category_name"><?php echo esc_attr( $value->name ); ?></span>
						</td>
						<?php
						$category_mapped_to             = get_term_meta( $value->term_id, 'ced_shopee_mapped_category_' . $shop_id, true );
						$already_mapped_categories_name = get_option( 'ced_woo_shopee_mapped_categories_name' . $shop_id, array() );
						
						$category_mapped_name_to        = isset( $already_mapped_categories_name[ $shop_id ][ $category_mapped_to ] ) ? $already_mapped_categories_name[ $shop_id ][ $category_mapped_to ] : '';

						if ( ! empty( $category_mapped_to ) && ! empty( $category_mapped_name_to ) ) {
							?>
							<td colspan="4">
								<span>
									<b><?php echo esc_attr( $category_mapped_name_to ); ?></b>
								</span>
							</td>
							<?php
						} else {
							?>
							<td colspan="4">
								<span class="ced_shopee_category_not_mapped">
									<b><?php esc_html_e( 'Category Not Mapped', 'woocommerce-shopee-integration' ); ?></b>
								</span>
							</td>
							<?php
						}
						?>
					</tr>
					<tr class="ced_shopee_categories" id="<?php echo esc_attr( 'ced_shopee_categories_' . $value->term_id ); ?>">
						<td></td>
						<td data-catlevel="1">
							<select class="ced_shopee_level1_category ced_shopee_select_category select2 ced_shopee_select2 select_boxes_cat_map" name="ced_shopee_level1_category[]" data-level=1 data-storeCategoryID="<?php echo esc_attr( $value->term_id ); ?>" data-shopeeStoreId="<?php echo esc_attr( $shop_id ); ?>">
								<option value="">--<?php esc_html_e( 'Select', 'woocommerce-shopee-integration' ); ?>--</option>
								<?php
								foreach ( $shopee_categories as $key1 => $value1 ) {
									if ( isset( $value1['category_name'] ) && ! empty( $value1['category_name'] ) ) {
										?>
										<option value="<?php echo esc_attr( $value1['category_id'] ); ?>"><?php echo esc_attr( $value1['category_name'] ); ?></option>	
										<?php
									}
								}
								?>
							</select>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<div class="ced_shopee_category_mapping_header ced_shopee_hidden" id="ced_shopee_category_mapping_header">
		<a class="ced_shopee_add_button" href="" data-shopeeStoreID="<?php echo esc_attr( $shop_id ); ?>" id="ced_shopee_cancel_category_button">
			<?php esc_html_e( 'Cancel', 'woocommerce-shopee-integration' ); ?>
		</a>
		<button class="ced_shopee_add_button" data-shopeeStoreID="<?php echo esc_attr( $shop_id ); ?>" id="ced_shopee_save_category_button">
			<?php esc_html_e( 'Save', 'woocommerce-shopee-integration' ); ?>
		</button>
	</div>

</div>
