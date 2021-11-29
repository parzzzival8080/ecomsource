<?php
/**
 * Global settings section
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
	require_once $file;
}

if ( isset( $_POST['global_settings'] ) ) {


	if ( ! isset( $_POST['global_settings_submit'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['global_settings_submit'] ) ), 'global_settings' ) ) {
		return;
	}

	$sanitized_array      = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
	$settings             = array();
	$settings             = get_option( 'ced_shopee_global_settings', array() );
	$settings[ $shop_id ] = isset( $sanitized_array['ced_shopee_global_settings'] ) ? ( $sanitized_array['ced_shopee_global_settings'] ) : array();
	update_option( 'ced_shopee_global_settings', $settings );

	if ( isset( $_POST['ced_shopee_global_settings']['ced_shopee_scheduler_info'] ) ) {
		update_option( 'shopee_auto_syncing' . $shop_id, 'on' );
		wp_clear_scheduled_hook( 'ced_shopee_inventory_scheduler_job_' . $shop_id );
		wp_clear_scheduled_hook( 'ced_shopee_order_scheduler_job_' . $shop_id );
		wp_clear_scheduled_hook( 'ced_shopee_sync_existing_products_' . $shop_id );
		


		$inventory_schedule = isset( $_POST['ced_shopee_global_settings']['ced_shopee_inventory_schedule_info'] ) ? sanitize_text_field( wp_unslash( $_POST['ced_shopee_global_settings']['ced_shopee_inventory_schedule_info'] ) ) : '';
		$order_schedule     = isset( $_POST['ced_shopee_global_settings']['ced_shopee_order_schedule_info'] ) ? sanitize_text_field( wp_unslash( $_POST['ced_shopee_global_settings']['ced_shopee_order_schedule_info'] ) ) : '';
		$sync_schedule      = isset( $_POST['ced_shopee_global_settings']['ced_shopee_sync_existing_products'] ) ? sanitize_text_field( wp_unslash( $_POST['ced_shopee_global_settings']['ced_shopee_sync_existing_products'] ) ) : '';
		
		if ( ! empty( $inventory_schedule ) ) {
			wp_schedule_event( time(), $inventory_schedule, 'ced_shopee_inventory_scheduler_job_' . $shop_id );
			update_option( 'ced_shopee_inventory_scheduler_job_' . $shop_id, $shop_id );
		}

		if ( ! empty( $order_schedule ) ) {
			wp_schedule_event( time(), $order_schedule, 'ced_shopee_order_scheduler_job_' . $shop_id );
			update_option( 'ced_shopee_order_scheduler_job_' . $shop_id, $shop_id );
		}

		if ( ! empty( $sync_schedule ) ) {
			wp_schedule_event( time(), $sync_schedule, 'ced_shopee_sync_existing_products_' . $shop_id );
			//do_action( 'ced_shopee_sync_existing_products_' . $shop_id );
			update_option( 'ced_shopee_sync_existing_products_' . $shop_id, $shop_id );
		}
	} else {
		wp_clear_scheduled_hook( 'ced_shopee_sync_existing_products_' . $shop_id );
		wp_clear_scheduled_hook( 'ced_shopee_inventory_scheduler_job_' . $shop_id );
		wp_clear_scheduled_hook( 'ced_shopee_order_scheduler_job_' . $shop_id );
		update_option( 'shopee_auto_syncing' . $shop_id, 'off' );
	}
	if ( isset( $_POST['ced_shopee_global_settings']['ced_shopee_auto_import'] ) ) {
		update_option( 'ced_shopee_enable_product_import', 'on' );
		wp_clear_scheduled_hook( 'ced_shopee_auto_import_cron_' . $shop_id );
		wp_schedule_event( time(), 'ced_shopee_10min', 'ced_shopee_auto_import_cron_' . $shop_id );
	} else {
		wp_clear_scheduled_hook( 'ced_shopee_auto_import_cron_' . $shop_id );
		update_option( 'ced_shopee_enable_product_import', 'off' );
	}

	echo '<div class="notice notice-success" ><p>' . esc_html( __( 'Settings Saved Successfully', 'woocommerce-shopee-integration' ) ) . '</p></div>';
}
$render_data_on_global_settings = get_option( 'ced_shopee_global_settings', false );
?>
<form method="post" action="">
	<?php wp_nonce_field( 'global_settings', 'global_settings_submit' ); ?>
	<div class="navigation-wrapper">
		<div>
			
			<table class="wp-list-table widefat fixed  ced_shopee_global_settings_fields_table">
				<thead>
					<tr>
						<th class="ced_shopee_settings_heading">
							<label class="basic_heading">
								<?php esc_html_e( 'GENERAL DETAILS', 'woocommerce-shopee-integration' ); ?>
							</label>
						</th>
					</tr>
				</thead>
				<tbody>						
					<tr>
						<?php
						$weight = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_weight'] ) ? sanitize_text_field( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_weight'] ) : '';
						?>
						<th>
							<label><?php esc_html_e( 'Package Weight', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<input placeholder="<?php esc_html_e( 'Enter Package Weight', 'woocommerce-shopee-integration' ); ?>" class="ced_shopee_disabled_text_field ced_shopee_inputs" type="text" value="<?php echo esc_attr( $weight ); ?>" id="ced_shopee_package_weight" name="ced_shopee_global_settings[ced_shopee_package_weight]"></input>
						</td>
					</tr>
					<tr>
						<?php
						$weight_unit = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_weight_unit'] ) ? sanitize_text_field( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_weight_unit'] ) : '';
						?>
						<th>
							<label><?php esc_html_e( 'Package Weight Unit', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<select name="ced_shopee_global_settings[ced_shopee_package_weight_unit]" class="ced_shopee_select ced_shopee_global_select_box select_boxes">
								<option value="null"><?php esc_html_e( '--Select--', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'kg' == $weight_unit ) ? 'selected' : ''; ?> value="kg"><?php esc_html_e( 'Kg', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'gm' == $weight_unit ) ? 'selected' : ''; ?> value="gm"><?php esc_html_e( 'Gm', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'lbs' == $weight_unit ) ? 'selected' : ''; ?> value="lbs"><?php esc_html_e( 'Lbs', 'woocommerce-shopee-integration' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<?php
						$length = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_length'] ) ? sanitize_text_field( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_length'] ) : '';
						?>
						<th>
							<label><?php esc_html_e( 'Package Length', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<input placeholder="<?php esc_html_e( 'Enter Package Length', 'woocommerce-shopee-integration' ); ?>" class="ced_shopee_disabled_text_field ced_shopee_inputs" type="text" value="<?php echo esc_attr( $length ); ?>" d="ced_shopee_package_length" name="ced_shopee_global_settings[ced_shopee_package_length]"></input>
						</td>
					</tr>
					<tr>
						<?php
						$height = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_height'] ) ? sanitize_text_field( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_height'] ) : '';
						?>
						<th>
							<label><?php esc_html_e( 'Package Height', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<input placeholder="<?php esc_html_e( 'Enter Package Height', 'woocommerce-shopee-integration' ); ?>" class="ced_shopee_disabled_text_field ced_shopee_inputs" type="text" value="<?php echo esc_attr( $height ); ?>" id="ced_shopee_package_height" name="ced_shopee_global_settings[ced_shopee_package_height]"></input>
						</td>
					</tr>
					<tr>
						<?php
						$width = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_width'] ) ? sanitize_text_field( $render_data_on_global_settings[ $shop_id ]['ced_shopee_package_width'] ) : '';
						?>
						<th>
							<label><?php esc_html_e( 'Package Width', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<input placeholder="<?php esc_html_e( 'Enter Package Width', 'woocommerce-shopee-integration' ); ?>" class="ced_shopee_disabled_text_field ced_shopee_inputs" type="text" value="<?php echo esc_attr( $width ); ?>" id="ced_shopee_package_width" name="ced_shopee_global_settings[ced_shopee_package_width]">
						</td>
					</tr>

					<tr>
						<?php
						$pre_order = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_type'] ) ? sanitize_text_field( $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_type'] ) : '';
						?>
						<th>
							<label><?php esc_html_e( 'Pre Order', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<select name="ced_shopee_global_settings[ced_shopee_product_type]" class="ced_shopee_select ced_shopee_global_select_box select_boxes" data-fieldId="ced_shopee_product_type">
								<option value="null"><?php esc_html_e( '--Select--', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'Yes' == $pre_order ) ? 'selected' : ''; ?> value="Yes"><?php esc_html_e( 'Yes', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'No' == $pre_order ) ? 'selected' : ''; ?> value="No"><?php esc_html_e( 'No', 'woocommerce-shopee-integration' ); ?></option>
							</select> 
						</td>
					</tr>
					<tr>
						<?php
						$days = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_days_to_ship'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_days_to_ship'] : '';
						?>
						<th>
							<label><?php esc_html_e( 'Days to Ship', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<input placeholder="<?php esc_html_e( 'Enter Days to Ship', 'woocommerce-shopee-integration' ); ?>" class="ced_shopee_disabled_text_field ced_shopee_inputs" type="text" value="<?php echo esc_attr( $days ); ?>" id="ced_shopee_days_to_ship" name="ced_shopee_global_settings[ced_shopee_days_to_ship]">
						</td>
					</tr>						
					<tr>
						<?php
						$condition = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_condition'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_condition'] : '';
						?>
						<th>
							<label><?php esc_html_e( 'Product Condition', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<select name="ced_shopee_global_settings[ced_shopee_product_condition]" class="ced_shopee_select ced_shopee_global_select_box select_boxes" data-fieldId="ced_shopee_product_condition">
								<option value="null"><?php esc_html_e( '--Select--', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'New' == $condition ) ? 'selected' : ''; ?> value="New"><?php esc_html_e( 'New Product', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'Used' == $condition ) ? 'selected' : ''; ?> value="Used"><?php esc_html_e( 'Used Product', 'woocommerce-shopee-integration' ); ?></option>
							</select> 
						</td>
					</tr>
					<tr>
						<?php
						$markup_type = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_markup_type'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_markup_type'] : '';
						?>
						<th>
							<label><?php esc_html_e( 'Markup Type', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<select name="ced_shopee_global_settings[ced_shopee_product_markup_type]" class="ced_shopee_select ced_shopee_global_select_box select_boxes"  data-fieldId="ced_shopee_product_markup">
								<option value=""><?php esc_html_e( '--Select--', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'Fixed_Increased' == $markup_type ) ? 'selected' : ''; ?> value="Fixed_Increased"><?php esc_html_e( 'Fixed Increased', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'Fixed_Decreased' == $markup_type ) ? 'selected' : ''; ?> value="Fixed_Decreased"><?php esc_html_e( 'Fixed Decreased', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'Percentage_Increased' == $markup_type ) ? 'selected' : ''; ?> value="Percentage_Increased"><?php esc_html_e( 'Percentage Increased', 'woocommerce-shopee-integration' ); ?></option>
								<option <?php echo ( 'Percentage_Decreased' == $markup_type ) ? 'selected' : ''; ?> value="Percentage_Decreased"><?php esc_html_e( 'Percentage Decreased', 'woocommerce-shopee-integration' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<?php
							$markup_price = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_markup'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_product_markup'] : '';
							?>
							<label><?php esc_html_e( 'Markup Price', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<input placeholder="<?php esc_html_e( 'Enter Markup Price', 'woocommerce-shopee-integration' ); ?>" class="ced_shopee_disabled_text_field ced_shopee_inputs" type="text" value="<?php echo esc_attr( $markup_price ); ?>" id="ced_shopee_product_markup" name="ced_shopee_global_settings[ced_shopee_product_markup]"></input>
						</td>
					</tr>
					<tr>
						<th>
							<?php
							$conversion_rate = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_conversion_rate'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_conversion_rate'] : '';
							?>
							<label><?php esc_html_e( 'Conversion Rate', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<input placeholder="<?php esc_html_e( 'Enter Conversion Rate', 'woocommerce-shopee-integration' ); ?>" class="ced_shopee_disabled_text_field ced_shopee_inputs" type="text" value="<?php echo esc_attr( $conversion_rate ); ?>" id="ced_shopee_conversion_rate" name="ced_shopee_global_settings[ced_shopee_conversion_rate]"></input>
						</td>
					</tr>
					<tr>
						<?php
						$logistics_to_render = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_logistics'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_logistics'] : array();
						?>
						<th>
							<label><?php esc_html_e( 'Logistics', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<?php
						$logistics = get_option( 'ced_shopee_logistic_data', array() );
						if ( ! isset( $logistics[ $shop_id ] ) ) {
							?>
							<td id="ced_shopee_logistics_column">
								<?php $shop_id = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : ''; ?>
								<label><a href="javascript:void(0)" data-id="<?php echo esc_attr( $shop_id ); ?>"  id="ced_shopee_fetch_logistics">Fetch Logistics</a></label>
							</td>
							<?php
						} elseif ( isset( $logistics[ $shop_id ] ) && ! empty( $logistics[ $shop_id ] ) ) {
							?>
							<td>
								<select id="ced_shopee_logistics" class="select_boxes" name="ced_shopee_global_settings[ced_shopee_logistics][]" multiple="">
									<option value=""><?php esc_html_e( '--Select--', 'woocommerce-shopee-integration' ); ?></option>
									<?php
									foreach ( $logistics[ $shop_id ]['logistics'] as $key => $value ) {
										if ( ! $value['enabled'] ) {
											continue;
										}
										if ( in_array( $value['logistic_id'], $logistics_to_render ) ) {
											$selected = 'selected';
										} else {
											$selected = '';
										}
										?>
										<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $value['logistic_id'] ); ?>"><?php echo esc_attr( $value['logistic_name'] ); ?></option>
										<?php
									}
									?>
								</select>
								<?php $shop_id = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : ''; ?>
								<label><a href="javascript:void(0)" data-id="<?php echo esc_attr( $shop_id ); ?>"  id="ced_shopee_fetch_logistics">Refresh Logistics</a></label>
							</td>

							<?php
						}
						?>
					</tr>



				</tbody>
				<thead>
					<tr>
						<th class="ced_shopee_settings_heading">
							<label class="basic_heading">
								<?php esc_html_e( 'SCHEDULER INFORMATION', 'woocommerce-shopee-integration' ); ?>
							</label>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>
							<label><?php esc_html_e( 'AUTO IMPORT PRODUCTS', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<?php
							$checked = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_auto_import'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_auto_import'] : '';
							if ( 'on' == $checked ) {
								$checked = 'checked';
								$style   = 'display: block';
							} else {
								$checked = '';
								$style   = 'display: none';
							}
							?>
							<input class="ced_shopee_disabled_text_field" type="checkbox"  id="ced_shopee_auto_import" name="ced_shopee_global_settings[ced_shopee_auto_import]" <?php echo esc_attr( $checked ); ?>>(check this to auto import products)
						</td>
					</tr>
					<tr>
						<th>
							<label><?php esc_html_e( 'SCHEDULER', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<?php
							$checked = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_scheduler_info'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_scheduler_info'] : '';
							if ( 'on' == $checked ) {
								$checked = 'checked';
								$style   = 'display: contents';
							} else {
								$checked = '';
								$style   = 'display: none';
							}
							?>
							<input class="ced_shopee_disabled_text_field" type="checkbox"  id="ced_shopee_scheduler_info" name="ced_shopee_global_settings[ced_shopee_scheduler_info]" <?php echo esc_attr( $checked ); ?>>(check this to schedule events)
						</td>
					</tr>
				</tbody>
				<tbody class="ced_shopee_scheduler_info" style="<?php echo esc_attr( $style ); ?>" >
					<tr>
						<?php
						$sync_schedule = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_sync_existing_products'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_sync_existing_products'] : '';
						?>
						<th>
							<label><?php esc_html_e( 'Sync Existing Products on the basis ok SKU', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<select name="ced_shopee_global_settings[ced_shopee_sync_existing_products]" class="ced_shopee_select ced_shopee_global_select_box select_boxes_scheduler" data-fieldId="ced_shopee_sync_existing_products">
								<option <?php echo ( '0' == $sync_schedule ) ? 'selected' : ''; ?>  value="0"><?php esc_html_e( 'Disabled', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'daily' == $sync_schedule ) ? 'selected' : ''; ?>  value="daily"><?php esc_html_e( 'Daily', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'twicedaily' == $sync_schedule ) ? 'selected' : ''; ?>  value="twicedaily"><?php esc_html_e( 'Twice Daily', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_6min' == $sync_schedule ) ? 'selected' : ''; ?> value="ced_shopee_6min"><?php esc_html_e( 'Every 6 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_10min' == $sync_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_10min"><?php esc_html_e( 'Every 10 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_15min' == $sync_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_15min"><?php esc_html_e( 'Every 15 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_30min' == $sync_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_30min"><?php esc_html_e( 'Every 30 Minutes', 'ced-umb-shopee' ); ?></option>

							</select>
						</td>
					</tr>
					<tr>
						<?php
						$order_schedule = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_order_schedule_info'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_order_schedule_info'] : '';
						?>
						<th>
							<label><?php esc_html_e( 'Order Sync Scheduler', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<select name="ced_shopee_global_settings[ced_shopee_order_schedule_info]" class="ced_shopee_select ced_shopee_global_select_box select_boxes_scheduler" data-fieldId="ced_shopee_order_schedule_info">

								<option <?php echo ( '0' == $order_schedule ) ? 'selected' : ''; ?>  value="0"><?php esc_html_e( 'Disabled', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'daily' == $order_schedule ) ? 'selected' : ''; ?>  value="daily"><?php esc_html_e( 'Daily', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'twicedaily' == $order_schedule ) ? 'selected' : ''; ?>  value="twicedaily"><?php esc_html_e( 'Twice Daily', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_6min' == $order_schedule ) ? 'selected' : ''; ?> value="ced_shopee_6min"><?php esc_html_e( 'Every 6 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_10min' == $order_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_10min"><?php esc_html_e( 'Every 10 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_15min' == $order_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_15min"><?php esc_html_e( 'Every 15 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_30min' == $order_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_30min"><?php esc_html_e( 'Every 30 Minutes', 'ced-umb-shopee' ); ?></option>

							</select>
						</td>
					</tr>

					<tr>
						<?php
						$inventory_schedule = isset( $render_data_on_global_settings[ $shop_id ]['ced_shopee_inventory_schedule_info'] ) ? $render_data_on_global_settings[ $shop_id ]['ced_shopee_inventory_schedule_info'] : '';
						?>
						<th>
							<label><?php esc_html_e( 'Inventory Sync Scheduler', 'woocommerce-shopee-integration' ); ?></label>
						</th>
						<td>
							<select name="ced_shopee_global_settings[ced_shopee_inventory_schedule_info]" class="ced_shopee_select ced_shopee_global_select_box select_boxes_scheduler" data-fieldId="ced_shopee_inventory_schedule_info">
								<option <?php echo ( '0' == $inventory_schedule ) ? 'selected' : ''; ?>  value="0"><?php esc_html_e( 'Disabled', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'daily' == $inventory_schedule ) ? 'selected' : ''; ?>  value="daily"><?php esc_html_e( 'Daily', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'twicedaily' == $inventory_schedule ) ? 'selected' : ''; ?>  value="twicedaily"><?php esc_html_e( 'Twice Daily', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_6min' == $inventory_schedule ) ? 'selected' : ''; ?> value="ced_shopee_6min"><?php esc_html_e( 'Every 6 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_10min' == $inventory_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_10min"><?php esc_html_e( 'Every 10 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_15min' == $inventory_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_15min"><?php esc_html_e( 'Every 15 Minutes', 'ced-umb-shopee' ); ?></option>
								<option <?php echo ( 'ced_shopee_30min' == $inventory_schedule ) ? 'selected' : ''; ?>  value="ced_shopee_30min"><?php esc_html_e( 'Every 30 Minutes', 'ced-umb-shopee' ); ?></option>

							</select>
						</td>

					</tr>
				</tbody>

			</table>
		</div>

	</div>

	<div align="right">
		<button id="save_global_settings"  name="global_settings" class="ced_shopee_custom_button profile_button" ><?php esc_html_e( 'Save', 'woocommerce-shopee-integration' ); ?></button>
	</div>
</form>
