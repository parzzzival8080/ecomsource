<?php
/**
 * Account Details
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

$meta_keys_for_profile_section = array( '_weight', '_length', '_width', '_height' );
update_option( 'CedUmbProfileSelectedMetaKeys', $meta_keys_for_profile_section );
?>
<div class="ced_shopee_account_configuration_wrapper">	
 <div class="ced_shopee_account_configuration_fields">		
		<table class="wp-list-table widefat fixed striped ced_shopee_account_configuration_fields_table">
			<tbody>				
				<tr>
					<th>
						<label><?php esc_html_e( 'Store Id', 'woocommerce-shopee-integration' ); ?></label>
					</th>
					<td>
						<label><?php echo esc_attr( $shop_details['shop_id'] ); ?></label>
					</td>
				</tr>
				<tr>
					<th>
						<label><?php esc_html_e( 'Store Name', 'woocommerce-shopee-integration' ); ?></label>
					</th>
					<td>
						<label><?php echo esc_attr( urldecode( $shop_details['name'] ) ); ?></label>
					</td>
				</tr>
				<tr>
					<th>
						<label><?php esc_html_e( 'Store Location', 'woocommerce-shopee-integration' ); ?></label>
					</th>
					<td>
						<label><?php echo esc_attr( $countries[ $shop_details['location'] ] ); ?></label>
					</td>
				</tr>
				<tr>
					<th>
						<label><?php esc_html_e( 'Account Status', 'woocommerce-shopee-integration' ); ?></label>
					</th>
					<td>
						<?php
						if ( isset( $shop_details['account_status'] ) && 'inactive' == $shop_details['account_status'] ) {
							$inactive = 'selected';
							$active   = '';
						} else {
							$active   = 'selected';
							$inactive = '';
						}
						?>
						<select class="ced_shopee_select select_boxes" id="ced_shopee_account_status">
							<option><?php esc_html_e( '--Select Status--', 'woocommerce-shopee-integration' ); ?></option>
							<option value="active" <?php echo esc_attr( $active ); ?>><?php esc_html_e( 'Active', 'woocommerce-shopee-integration' ); ?></option>
							<option value="inactive" <?php echo esc_attr( $inactive ); ?>><?php esc_html_e( 'Inactive', 'woocommerce-shopee-integration' ); ?></option>
						</select>
						<a class="ced_shopee_update_status_message" data-id="<?php echo esc_attr( $shop_details['id'] ); ?>" id="ced_shopee_update_account_status" href="javascript:void(0);"><?php esc_html_e( 'Update Account Status', 'woocommerce-shopee-integration' ); ?></a>
					</td>
				</tr>			
			</tbody>
		</table>
	</div>

</div>
