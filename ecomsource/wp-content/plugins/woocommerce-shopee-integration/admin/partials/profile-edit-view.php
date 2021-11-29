<?php
/**
 * Profile section to be rendered
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$file_header   = CED_SHOPEE_DIRPATH . 'admin/partials/header.php';
$file_category = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-category.php';
$file_fields   = CED_SHOPEE_DIRPATH . 'admin/partials/class-cedshopeeproductsfields.php';

if ( file_exists( $file_header ) ) {
	include_once $file_header;
}
if ( file_exists( $file_category ) ) {
	include_once $file_category;
}
if ( file_exists( $file_fields ) ) {
	include_once $file_fields;
}

$shop_id    = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
$profile_id = isset( $_GET['profileID'] ) ? sanitize_text_field( wp_unslash( $_GET['profileID'] ) ) : '';

if ( isset( $_POST['add_meta_keys'] ) || isset( $_POST['ced_shopee_profile_save_button'] ) ) {

	if ( ! isset( $_POST['profile_creation_submit'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['profile_creation_submit'] ) ), 'profile_creation' ) ) {
		return;
	}

	$is_active        = isset( $_POST['profile_status'] ) ? 'Active' : 'Inactive';
	$marketplace_name = isset( $_POST['marketplaceName'] ) ? sanitize_text_field( wp_unslash( $_POST['marketplaceName'] ) ) : 'shopee';

	$updateinfo = array();

	if ( isset( $_POST['ced_shopee_required_common'] ) ) {
		$post_array = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
		foreach ( ( $post_array['ced_shopee_required_common'] ) as $key ) {
			$array_to_save = array();
			isset( $post_array[ $key ][0] ) ? $array_to_save['default'] = $post_array[ $key ][0] : $array_to_save['default'] = '';
			if ( '_umb_' . $marketplace_name . '_subcategory' == $key ) {
				isset( $post_array[ $key ] ) ? $array_to_save['default'] = $post_array[ $key ] : $array_to_save['default'] = '';
			}
			if ( '_ced_shopee_logistics' == $key ) {
				isset( $post_array[ $key ][0] ) ? $array_to_save['default'] = $post_array[ $key ] : $array_to_save['default'] = '';
			}
			if ( '_umb_shopee_category' == $key && empty( $profile_id ) ) {
				$category_id = isset( $post_array['ced_shopee_level3_category'] ) ? $post_array['ced_shopee_level3_category'] : '';
				$category_id = isset( $category_id[0] ) ? $category_id[0] : '';
				isset( $post_array[ $key ][0] ) ? $array_to_save['default'] = $category_id : $array_to_save['default'] = '';
			}
			isset( $post_array[ $key . '_attibuteMeta' ] ) ? $array_to_save['metakey'] = $post_array[ $key . '_attibuteMeta' ] : $array_to_save['metakey'] = 'null';
			$updateinfo[ $key ] = $array_to_save;
		}
	}

	$updateinfo = json_encode( $updateinfo );
	if ( empty( $profile_id ) ) {

		$shopee_categories_name = get_option( 'ced_shopee_categories' . $shop_id, array() );
		$category_ids           = array();
		for ( $i = 1; $i <= 6; $i++ ) {
			$post_data      = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
			$category_ids[] = isset( $post_data[ 'ced_shopee_level' . $i . '_category' ] ) ? $post_data[ 'ced_shopee_level' . $i . '_category' ] : '';
		}
		$category_name = '';
		foreach ( $category_ids as $index => $category_id ) {
			foreach ( $shopee_categories_name['categories'] as $key => $value ) {
				if ( isset( $category_id[0] ) && ! empty( $category_id[0] ) && $category_id[0] == $value['category_id'] ) {
					$category_name     .= $value['category_name'] . ' --> ';
					$shopee_category_id = $value['category_id'];
				}
			}
		}

		$profile_name    = substr( $category_name, 0, -5 );
		$profile_details = array(
			'profile_name'   => urldecode( $profile_name ),
			'profile_status' => 'active',
			'profile_data'   => $updateinfo,
			'shop_id'        => $shop_id,
		);
		global $wpdb;
		$profile_table_name = 'wp_ced_shopee_profiles';

		$wpdb->insert( $profile_table_name, $profile_details );
		$profile_id               = $wpdb->insert_id;
		$shopee_category_instance = Class_Ced_Shopee_Category::get_instance();
		$category_attributes      = $shopee_category_instance->ced_shopee_get_category_attributes( $shop_id, $shopee_category_id );
		$profile_edit_url         = admin_url( 'admin.php?page=ced_shopee&section=class-ced-shopee-profile-table&shop_id=' . $shop_id . '&profileID=' . $profile_id . '&panel=edit' );
		header( 'location:' . $profile_edit_url . '' );
	} elseif ( $profile_id ) {
		global $wpdb;
		$table_name = 'wp_ced_shopee_profiles';
		$wpdb->update(
			$table_name,
			array(
				'profile_status' => $is_active,
				'profile_data'   => $updateinfo,
			),
			array( 'id' => $profile_id )
		);
	}
}


global $wpdb;
$profile_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_profiles WHERE `id` = %d ', $profile_id ), 'ARRAY_A' );

if ( ! empty( $profile_data ) ) {
	$profile_category_data = json_decode( $profile_data[0]['profile_data'], true );
}
$profile_category_data = isset( $profile_category_data ) ? $profile_category_data : '';
$profile_category_id   = isset( $profile_category_data['_umb_shopee_category']['default'] ) ? $profile_category_data['_umb_shopee_category']['default'] : '';
$profile_data          = isset( $profile_data[0] ) ? $profile_data[0] : $profile_data;

$attributes           = wc_get_attribute_taxonomies();
$attr_options         = array();
$added_meta_keys      = get_option( 'ced_shopee_selected_metakeys', false );
$select_dropdown_html = '';

if ( $added_meta_keys && count( $added_meta_keys ) > 0 ) {
	foreach ( $added_meta_keys as $meta_key ) {
		$attr_options[ $meta_key ] = $meta_key;
	}
}
if ( ! empty( $attributes ) ) {
	foreach ( $attributes as $attributes_object ) {
		$attr_options[ 'umb_pattr_' . $attributes_object->attribute_name ] = $attributes_object->attribute_label;
	}
}


$shopee_category_instance = Class_Ced_Shopee_Category::get_instance();
$category_attributes      = $shopee_category_instance->ced_shopee_get_category_attributes( $shop_id, $profile_category_id );
$attribute_data           = array();
if ( ! empty( $category_attributes ) ) {
	foreach ( $category_attributes['attributes'] as $key => $value ) {
		$attribute_data[] = array(
			'attribute_id'   => $value['attribute_id'],
			'attribute_name' => $value['attribute_name'],
		);
	}
	update_option( 'ced_shopee_category_attributes_' . $profile_category_id, $attribute_data );
}


$product_field_instance = CedShopeeProductsFields::get_instance();
$fields                 = $product_field_instance->ced_shopee_get_custom_products_fields();


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
}


?>
<?php require_once CED_SHOPEE_DIRPATH . 'admin/pages/ced-shopee-metakeys-template.php'; ?>
<form action="" method="post">
	<?php wp_nonce_field( 'profile_creation', 'profile_creation_submit' ); ?>
	<div class="ced_shopee_profile_details_wrapper">
	  <div class="ced_shopee_profile_details_fields">
	   <table>
		<thead>
		 <tr>
		  <th class="ced_shopee_profile_heading ced_shopee_settings_heading">
		   <label class="basic_heading"><?php esc_html_e( 'BASIC DETAILS', 'woocommerce-shopee-integration' ); ?></label>
	   </th>
   </tr>
</thead>
<tbody>		
 <tr>
  <td>
   <label><?php esc_html_e( 'Profile Name', 'woocommerce-shopee-integration' ); ?></label>
</td>
<?php

if ( isset( $profile_data['profile_name'] ) ) {
	?>
	<td>
		<label><b><?php echo esc_attr( urldecode($profile_data['profile_name']) ); ?></b></label>
	</td>
</tr>
	<?php
} else {
	?>

	<td data-catlevel="1" id="ced_shopee_categories_in_profile">
	 <select class="ced_shopee_select_category_on_add_profile select2 ced_shopee_select2 ced_shopee_level1_category"  name="ced_shopee_level1_category[]" data-level=1 data-shopeeStoreId="<?php echo esc_attr( $shop_id ); ?>">
		<option value="">--<?php esc_html_e( '--Select--', 'woocommerce-shopee-integration' ); ?>--</option>
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
<td><a><?php esc_html_e( 'Please Select Profile Name ', 'woocommerce-shopee-integration' ); ?></a></td>
	<?php
}
?>
<tr>
	<?php
	$required_in_any_case = array( '_umb_id_type', '_umb_id_val', '_umb_brand' );
	global $global_ced_shopee_render_attributes;
	$market_place = 'ced_shopee_required_common';
	$product_id   = 0;
	$category_id  = '';
	$index_to_use = 0;
	?>
   <tr>
	  <th  class="ced_shopee_profile_heading basic_heading ced_shopee_settings_heading">
		 <label class="basic_heading"><?php esc_html_e( 'GENERAL DETAILS', 'woocommerce-shopee-integration' ); ?></label>
	 </th>
	 </tr>
		<?php
		if ( ! empty( $profile_data ) ) {
			$data = json_decode( $profile_data['profile_data'], true );
		}
		$logistics_selected = ! empty( $data['_ced_shopee_logistics']['default'] ) ? $data['_ced_shopee_logistics']['default'] : array();

		foreach ( $fields as $value ) {
			$required = $value['required'];
			$is_text  = true;
			$field_id = trim( $value['fields']['id'], '_' );
			if ( in_array( $value['fields']['id'], $required_in_any_case ) ) {
				$attribute_name_to_render  = ucfirst( $value['fields']['label'] );
				$attribute_name_to_render .= '<span class="ced_shopee_wal_required">' . __( '[ Required ]', 'woocommerce-shopee-integration' ) . '</span>';
			} else {
				$attribute_name_to_render = ucfirst( $value['fields']['label'] );
			}
			$default = isset( $data[ $value['fields']['id'] ]['default'] ) ? $data[ $value['fields']['id'] ]['default'] : '';
			echo '<tr class="form-field _umb_id_type_field ">';

			if ( '_select' == $value['type'] ) {
				$value_for_dropdown = $value['fields']['options'];
				if ( '_umb_id_type' == $value['fields']['id'] ) {
					unset( $value_for_dropdown['null'] );
				}
				$value_for_dropdown = apply_filters( 'ced_shopee_alter_data_to_render_on_profile', $value_for_dropdown, $field_id );
				$product_field_instance->render_dropdown_html(
					$field_id,
					$attribute_name_to_render,
					$value_for_dropdown,
					$category_id,
					$product_id,
					$market_place,
					$value['fields']['description'],
					$index_to_use,
					array(
						'case'  => 'profile',
						'value' => $default,
					),
					$required
				);
				$is_text = false;
			} elseif ( '_text_input' == $value['type'] ) {
				$product_field_instance->render_input_text_html(
					$field_id,
					$attribute_name_to_render,
					$category_id,
					$product_id,
					$market_place,
					$value['fields']['description'],
					$index_to_use,
					array(
						'case'  => 'profile',
						'value' => $default,
					),
					$required
				);
			} elseif ( '_multi_select' == $value['type'] ) {
				$is_text = false;
				?>
			<tr class="form-field _umb_id_type_field ">
			   <input type="hidden" name="ced_shopee_required_common[]" value="_ced_shopee_logistics">
			   <td>
				  <label for=""><?php esc_html_e( 'Logistics', 'woocommerce-shopee-integration' ); ?><span class="ced_shopee_wal_required"> [ Required ]</span></label>
			  </td>
			  <td>
				  <select id="ced_shopee_logistics" name="_ced_shopee_logistics[]" class="select short" style="" multiple="">
					 <option value=""><?php esc_html_e( '-- Select --', 'woocommerce-shopee-integration' ); ?></option>
					 <?php
						foreach ( $value['fields']['options'] as $logistics_id => $logistics_value ) {
							if ( in_array( $logistics_id, $logistics_selected ) ) {
								$selected = 'selected';
							} else {
								$selected = '';
							}
							echo '<option value="' . esc_attr( $logistics_id ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $logistics_value ) . '</option>';
						}
						?>
				 </select>
			 </td>
		 </tr>

				<?php
			} elseif ( '_hidden' == $value['type'] ) {
				$product_field_instance->render_input_text_html_hidden(
					$field_id,
					$attribute_name_to_render,
					$category_id,
					$product_id,
					$market_place,
					$value['fields']['description'],
					$index_to_use,
					array(
						'case'  => 'profile',
						'value' => $profile_category_id,
					),
					$required
				);
				$is_text = false;
			} else {
				$is_text = false;
			}

			echo '<td>';
			if ( $is_text ) {
				$previous_selected_value = 'null';
				if ( isset( $data[ $value['fields']['id'] ]['metakey'] ) && 'null' != $data[ $value['fields']['id'] ]['metakey'] ) {
					$previous_selected_value = $data[ $value['fields']['id'] ]['metakey'];
				}
				$select_id = $value['fields']['id'] . '_attibuteMeta';
				?>
				<select id="<?php echo esc_attr( $select_id ); ?>" name="<?php echo esc_attr( $select_id ); ?>">
	<option value="null" selected> -- select -- </option>
				<?php
				if ( is_array( $attr_options ) ) {
					foreach ( $attr_options as $attr_key => $attr_name ) :
						if ( trim( $previous_selected_value ) == $attr_key ) {
							$selected = 'selected';
						} else {
							$selected = '';
						}
						?>
		<option value="<?php echo esc_attr( $attr_key ); ?>  "<?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $attr_name ); ?></option>
						<?php
				endforeach;
				}
				?>
	 </select>
				<?php
			}
			echo '</td>';
			echo '</tr>';
		}
		?>
		<tr>
	<?php
	if ( ! empty( $profile_id ) ) {
		?>
	 <th  class="ced_shopee_profile_heading ced_shopee_settings_heading">
	   <label class="basic_heading"><?php esc_html_e( 'CATEGORY SPECIFIC', 'woocommerce-shopee-integration' ); ?></label>
   </th>
		<?php
	}
	?>

</tr>
<?php
if ( ! empty( $category_attributes ) ) {
	foreach ( $category_attributes['attributes'] as $key1 => $value ) {
		$is_text  = true;
		$field_id = trim( $value['attribute_id'], '_' );
		$default  = isset( $data[ $profile_category_id . '_' . $value['attribute_id'] ] ) ? $data[ $profile_category_id . '_' . $value['attribute_id'] ] : '';
		$default  = isset( $default['default'] ) ? $default['default'] : '';
		$required = '';
		echo '<tr class="form-field _umb_brand_field ">';

		if ( 'DROP_DOWN' == $value['input_type'] ) {
			$value_for_dropdown      = $value['options'];
			$temp_value_for_dropdown = array();
			foreach ( $value_for_dropdown as $key => $_value ) {
				$temp_value_for_dropdown[ $_value ] = $_value;
			}
			$value_for_dropdown = $temp_value_for_dropdown;

			if ( true === $value['is_mandatory'] ) {
				$required = 'required';
			}

			$product_field_instance->render_dropdown_html(
				$field_id,
				ucfirst( $value['attribute_name'] ),
				$value_for_dropdown,
				$profile_category_id,
				$product_id,
				$market_place,
				$value['attribute_name'],
				$index_to_use,
				array(
					'case'  => 'profile',
					'value' => $default,
				),
				$required
			);
			$is_text = false;
		} elseif ( 'COMBO_BOX' === $value['input_type'] ) {
			$is_text = true;
			if ( true === $value['is_mandatory'] ) {
				$required = 'required';
			}
			$product_field_instance->render_input_text_html(
				$field_id,
				ucfirst( $value['attribute_name'] ),
				$profile_category_id,
				$product_id,
				$market_place,
				$value['attribute_name'],
				$index_to_use,
				array(
					'case'  => 'profile',
					'value' => $default,
				),
				$required
			);
		} elseif ( 'TEXT_FILED' === $value['input_type'] ) {
			$is_text = true;
			if ( true === $value['is_mandatory'] ) {
				$required = 'required';
			}
			$product_field_instance->render_input_text_html(
				$field_id,
				ucfirst( $value['attribute_name'] ),
				$profile_category_id,
				$product_id,
				$market_place,
				$value['attribute_name'],
				$index_to_use,
				array(
					'case'  => 'profile',
					'value' => $default,
				),
				$required
			);
		} else {
			if ( true === $value['is_mandatory'] ) {
				$required = 'required';
			}
			$global_ced_shopee_render_attributes->render_input_text_html(
				$field_id,
				ucfirst( $value['attribute_name'] ),
				$profile_category_id,
				$product_id,
				$market_place,
				$value['attribute_name'],
				$index_to_use,
				array(
					'case'  => 'profile',
					'value' => $default,
				),
				$required
			);
		}

		echo '<td>';
		if ( $is_text ) {
			$previous_selected_value = 'null';
			if ( isset( $data[ $profile_category_id . '_' . $value['attribute_id'] ] ) && ! empty( $data[ $profile_category_id . '_' . $value['attribute_id'] ] ) ) {
				$previous_selected_value = $data[ $profile_category_id . '_' . $value['attribute_id'] ]['metakey'];
			}
			$select_id = $profile_category_id . '_' . $value['attribute_id'] . '_attibuteMeta';
			?>
			<select id="<?php echo esc_attr( $select_id ); ?>" name="<?php echo esc_attr( $select_id ); ?>">
	<option value="null" selected> -- select -- </option>
			<?php
			if ( is_array( $attr_options ) ) {
				foreach ( $attr_options as $attr_key => $attr_name ) :
					if ( trim( $previous_selected_value == $attr_key ) ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>
		<option value="<?php echo esc_attr( $attr_key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $attr_name ); ?></option>
					<?php
			endforeach;
			}
			?>
	 </select>
			<?php
		}
		echo '</td>';
		echo '</tr>';
	}
}
?>
</tr>
</tbody>
</table>
</div>
</div>
<div>
  <button class="ced_shopee_custom_button save_profile_button" name="ced_shopee_profile_save_button" ><?php esc_html_e( 'Save Profile', 'woocommerce-shopee-integration' ); ?></button>
  
</div>
</form>

