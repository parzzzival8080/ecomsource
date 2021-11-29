<?php
/**
 * Product Feilds file for profile section
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}


if ( ! class_exists( 'CedShopeeProductsFields' ) ) {

	/**
	 * CedShopeeProductsFields.
	 *
	 * @since    1.0.0
	 */
	class CedShopeeProductsFields {

		/**
		 * CedShopeeProductsFields Instance Variable.
		 *
		 * @var $_instance
		 */
		private static $_instance;

		/**
		 * CedShopeeProductsFields Instance.
		 *
		 * @since    1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Get product custom fields for preparing
		 * product data information to send on different
		 * marketplaces accoding to there requirement.
		 *
		 * @since 1.0.0
		 */
		public static function ced_shopee_get_custom_products_fields() {
			global $post;
			$shop_id   = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
			$logistics = array();
			$logistics = get_option( 'ced_shopee_logistic_data', array() );
			if ( isset( $logistics ) && ! empty( $logistics ) && isset( $logistics[ $shop_id ] ) ) {
				foreach ( $logistics[ $shop_id ]['logistics'] as $key => $value ) {
					if ( ! $value['enabled'] ) {
						continue;
					}
					$options[ $value['logistic_id'] ] = $value['logistic_name'];
				}
			} else {
				$file = CED_SHOPEE_DIRPATH . 'admin/shopee/class-class-ced-shopee-manager.php';
				if ( file_exists( $file ) ) {
					include_once $file;
				}
				$store_id                                      = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
				$get_logistics                                 = array();
				$get_logistics                                 = get_option( 'ced_shopee_logistic_data', array() );
				$woocommerce_shopee_integration_admin_instance = new Class_Ced_Shopee_Manager();
				$get_logistics[ $store_id ]                    = $woocommerce_shopee_integration_admin_instance->ced_shopee_fetch_logistics( $store_id );
				update_option( 'ced_shopee_logistic_data', $get_logistics );
				$logistics = get_option( 'ced_shopee_logistic_data', array() );
				if ( ! empty( $logistics ) ) {
					foreach ( $logistics[ $store_id ]['logistics'] as $key => $value ) {
						if ( ! $value['enabled'] ) {
							continue;
						}
						$options[ $value['logistic_id'] ] = $value['logistic_name'];
					}
				}
			}

			$required_fields = array(
				array(
					'type'     => '_hidden',
					'id'       => '_umb_shopee_category',
					'fields'   => array(
						'id'          => '_umb_shopee_category',
						'label'       => __( 'Category Name', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specify the category name.', 'woocommerce-shopee-integration' ),
						'type'        => 'hidden',
						'class'       => 'wc_input_price',
					),
					'required' => true,
				),
				array(
					'type'     => '_text_input',
					'id'       => '_ced_shopee_weight',
					'fields'   => array(
						'id'          => '_ced_shopee_weight',
						'label'       => __( 'Item Weight', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specifies the item weight.', 'woocommerce-shopee-integration' ),
						'type'        => 'text',
						'class'       => 'wc_input_price',
					),
					'required' => true,
				),
				array(
					'type'     => '_text_input',
					'id'       => '_ced_shopee_package_length',
					'fields'   => array(
						'id'          => '_ced_shopee_package_length',
						'label'       => __( 'Package Length', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specifies the Package Length.', 'woocommerce-shopee-integration' ),
						'type'        => 'text',
						'class'       => 'wc_input_price',
					),
					'required' => true,
				),
				array(
					'type'     => '_text_input',
					'id'       => '_ced_shopee_package_width',
					'fields'   => array(
						'id'          => '_ced_shopee_package_width',
						'label'       => __( 'Package Width', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specifies the Package Width.', 'woocommerce-shopee-integration' ),
						'type'        => 'text',
						'class'       => 'wc_input_price',
					),
					'required' => true,
				),
				array(
					'type'     => '_text_input',
					'id'       => '_ced_shopee_package_height',
					'fields'   => array(
						'id'          => '_ced_shopee_package_height',
						'label'       => __( 'Package Height', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specifies the Package Height.', 'woocommerce-shopee-integration' ),
						'type'        => 'text',
						'class'       => 'wc_input_price',
					),
					'required' => true,
				),
				array(
					'type'     => '_multi_select',
					'id'       => '_ced_shopee_logistics',
					'fields'   => array(
						'id'          => '_ced_shopee_logistics',
						'label'       => __( 'Logistics', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specify the Logistics.', 'woocommerce-shopee-integration' ),
						'type'        => '_multi_select',
						'options'     => ! empty( $options ) ? $options : array(),
						'class'       => 'wc_input_price',
					),
					'required' => true,
				),
				array(
					'type'     => '_select',
					'id'       => '_ced_shopee_product_type',
					'fields'   => array(
						'id'          => '_ced_shopee_product_type',
						'label'       => __( 'Pre Order Product', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specify the Product is pre order or not.', 'woocommerce-shopee-integration' ),
						'type'        => 'select',
						'options'     => array(
							'Yes' => __( 'Yes', 'woocommerce-shopee-integration' ),
							'No'  => __( 'No', 'woocommerce-shopee-integration' ),
						),
						'class'       => 'wc_input_price',
					),
					'required' => false,
				),
				array(
					'type'     => '_text_input',
					'id'       => '_ced_shopee_days_to_ship',
					'fields'   => array(
						'id'          => '_ced_shopee_days_to_ship',
						'label'       => __( 'Days to Ship', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specifies the Days to Ship.', 'woocommerce-shopee-integration' ),
						'type'        => 'text',
						'class'       => 'wc_input_price',
					),
					'required' => false,
				),
				array(
					'type'     => '_select',
					'id'       => '_ced_shopee_condition',
					'fields'   => array(
						'id'          => '_ced_shopee_condition',
						'label'       => __( 'Condition', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specify the Condition.', 'woocommerce-shopee-integration' ),
						'type'        => 'select',
						'options'     => array(
							'New'  => __( 'New', 'woocommerce-shopee-integration' ),
							'Used' => __( 'Used', 'woocommerce-shopee-integration' ),
						),
						'class'       => 'wc_input_price',
					),
					'required' => false,
				),
				array(
					'type'     => '_select',
					'id'       => '_ced_shopee_markup_type',
					'fields'   => array(
						'id'          => '_ced_shopee_markup_type',
						'label'       => __( 'Markup Type', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specify the Markup Price.', 'woocommerce-shopee-integration' ),
						'type'        => 'select',
						'options'     => array(
							'Fixed_Increased'      => __( 'Fixed_Increased' ),
							'Fixed_Decreased'      => __( 'Fixed_Decreased' ),
							'Percentage_Increased' => __( 'Percentage_Increased' ),
							'Percentage_Decreased' => __( 'Percentage_Decreased' ),
						),
						'class'       => 'wc_input_price',
					),
					'required' => false,
				),
				array(
					'type'     => '_text_input',
					'id'       => '_ced_shopee_markup_price',
					'fields'   => array(
						'id'          => '_ced_shopee_markup_price',
						'label'       => __( 'Markup Price', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specifies the Markup Price.', 'woocommerce-shopee-integration' ),
						'type'        => 'text',
						'class'       => 'wc_input_price',
					),
					'required' => false,
				),
				array(
					'type'     => '_text_input',
					'id'       => '_ced_shopee_conversion_rate',
					'fields'   => array(
						'id'          => '_ced_shopee_conversion_rate',
						'label'       => __( 'Conversion Rate', 'woocommerce-shopee-integration' ),
						'desc_tip'    => true,
						'description' => __( 'Specifies the Conversion Rate.', 'woocommerce-shopee-integration' ),
						'type'        => 'text',
						'class'       => 'wc_input_price',
					),
					'required' => false,
				),
			);

			return $required_fields;
		}

		/**
		 * Render dropdown html in the profile edit section
		 *
		 * @since 1.0.0
		 * @param int    $attribute_id Attribute Id.
		 * @param string $attribute_name Attribute name.
		 * @param array  $values Option values.
		 * @param int    $category_id Category Id.
		 * @param int    $product_id Product Id.
		 * @param string $market_place Marketplace.
		 * @param string $attribute_description Attribute Description.
		 * @param int    $index_to_use Index to be used.
		 * @param array  $additional_info Additional data.
		 * @param bool   $is_required Whether required or not.
		 */
		public function render_dropdown_html( $attribute_id, $attribute_name, $values, $category_id, $product_id, $market_place, $attribute_description = null, $index_to_use, $additional_info = array( 'case' => 'product' ), $is_required = '' ) {
			$field_name = $category_id . '_' . $attribute_id;

			if ( 'product' == $additional_info['case'] ) {
				$previous_value = get_post_meta( $product_id, $field_name, true );
			} else {
				$previous_value = $additional_info['value'];
			} ?><input type="hidden" name="<?php echo esc_attr( $market_place . '[]' ); ?>" value="<?php echo esc_attr( $field_name ); ?>" />

			<td>
				<label for=""><?php echo esc_attr( $attribute_name ); ?>
				<?php
				if ( 'required' == $is_required ) {
					?>
					<span class="ced_shopee_wal_required"><?php esc_html_e( '[Required]', 'woocommerce-shopee-integration' ); ?></span>
					<?php
				}
				?>
			</label>
		</td>
		<td>
			<select id="" name="<?php echo esc_attr( $field_name . '[' . $index_to_use . ']' ); ?>" class="select short" style="">
				<?php
				echo '<option value="">' . esc_html( __( '-- Select --', 'woocommerce-shopee-integration' ) ) . '</option>';
				foreach ( $values as $key => $value ) {
					if ( $previous_value == $key ) {
						echo '<option value="' . esc_attr( $key ) . '" selected>' . esc_attr( $value ) . '</option>';
					} else {
						echo '<option value="' . esc_attr( $key ) . '">' . esc_attr( $value ) . '</option>';
					}
				}
				?>
			</select>
		</td>

			<?php
		}

		/**
		 * Render text html in the profile edit section
		 *
		 * @since 1.0.0
		 * @param int    $attribute_id Attribute Id.
		 * @param string $attribute_name Attribute name.
		 * @param int    $category_id Category Id.
		 * @param int    $product_id Product Id.
		 * @param string $market_place Marketplace.
		 * @param string $attribute_description Attribute Description.
		 * @param int    $index_to_use Index to be used.
		 * @param array  $additional_info Additional data.
		 * @param bool   $conditionally_required Whether required or not.
		 * @param string $conditionally_required_text Conditionally required data.
		 */
		public function render_input_text_html( $attribute_id, $attribute_name, $category_id, $product_id, $market_place, $attribute_description = null, $index_to_use, $additional_info = array( 'case' => 'product' ), $conditionally_required = false, $conditionally_required_text = '' ) {
			global $post,$product,$loop;
			$field_name = $category_id . '_' . $attribute_id;
			if ( 'product' == $additional_info['case'] ) {
				$previous_value = get_post_meta( $product_id, $field_name, true );
			} else {
				$previous_value = $additional_info['value'];
			}
			?>

			<input type="hidden" name="<?php echo esc_attr( $market_place . '[]' ); ?>" value="<?php echo esc_attr( $field_name ); ?>" />
			<td>
				<label for=""><?php echo esc_attr( $attribute_name ); ?>
				<?php
				if ( 'required' == $conditionally_required ) {
					?>
					<span class="ced_shopee_wal_required"><?php esc_html_e( '[Required]', 'woocommerce-shopee-integration' ); ?></span>
					<?php
				}
				?>
			</label>
		</td>
					<td>
			<input class="short" style="" name="<?php echo esc_attr( $field_name . '[' . $index_to_use . ']' ); ?>" id="" value="<?php echo esc_attr( $previous_value ); ?>" placeholder="" type="text" /> 
		</td>
			<?php
		}

		/**
		 * Render text html for hidden fields in the profile edit section
		 *
		 * @since 1.0.0
		 * @param int    $attribute_id Attribute Id.
		 * @param string $attribute_name Attribute name.
		 * @param int    $category_id Category Id.
		 * @param int    $product_id Product Id.
		 * @param string $market_place Marketplace.
		 * @param string $attribute_description Attribute Description.
		 * @param int    $index_to_use Index to be used.
		 * @param array  $additional_info Additional data.
		 * @param bool   $conditionally_required Whether required or not.
		 * @param string $conditionally_required_text Conditionally required data.
		 */
		public function render_input_text_html_hidden( $attribute_id, $attribute_name, $category_id, $product_id, $market_place, $attribute_description = null, $index_to_use, $additional_info = array( 'case' => 'product' ), $conditionally_required = false, $conditionally_required_text = '' ) {
			global $post,$product,$loop;
			$field_name = $category_id . '_' . $attribute_id;
			if ( 'product' == $additional_info['case'] ) {
				$previous_value = get_post_meta( $product_id, $field_name, true );
			} else {
				$previous_value = $additional_info['value'];
			}
			?>

			<input type="hidden" name="<?php echo esc_attr( $market_place . '[]' ); ?>" value="<?php echo esc_attr( $field_name ); ?>" />
			<td>
			</label>
		</td>
					<td>
			<label></label>
			<input class="short" style="" name="<?php echo esc_attr( $field_name . '[' . $index_to_use . ']' ); ?>" id="" value="<?php echo esc_attr( $previous_value ); ?>" placeholder="" type="hidden" /> 
		</td>
			<?php
		}
	}
}
