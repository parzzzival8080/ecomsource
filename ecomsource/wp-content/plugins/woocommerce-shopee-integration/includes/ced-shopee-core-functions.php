<?php
/**
 * Core Functions
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * CoreFunctions ced_shopee_countries.
 *
 * @since 1.0.0
 */
function ced_shopee_countries() {
	$countries = array(
		'MY' => 'Malaysia',
		'TH' => 'Thailand',
		'VN' => 'Vietnam',
		'PH' => 'Philippines',
		'SG' => 'Singapore',
		'ID' => 'Indonesia',
		'TW' => 'Taiwan',
		'BR' => 'Brazil',
		'MX' => 'Mexico',
	);
	return $countries;
}

/**
 * CoreFunctions display_shopee_support_html.
 *
 * @since 1.0.0
 */
function display_shopee_support_html() {
	?>

	<div class="ced_contact_menu_wrap" style="display:none;">
		<input type="checkbox" href="#" class="ced_menu_open" name="menu-open" id="menu-open" />
		<label class="ced_menu_button" for="menu-open">
			<img src="<?php echo esc_url( CED_SHOPEE_URL . 'admin/images/icon.png' ); ?>" alt="" title="Click to Chat">
		</label>
		<a href="https://join.skype.com/UHRP45eJN8qQ" class="ced_menu_content ced_skype" target="_blank"> <i class="fa fa-skype" aria-hidden="true"></i> </a>
		<a href="https://chat.whatsapp.com/BcJ2QnysUVmB1S2wmwBSnE" class="ced_menu_content ced_whatsapp" target="_blank"> <i class="fa fa-whatsapp" aria-hidden="true"></i> </a>
	</div>

	<?php
}

/**
 * CoreFunctions ced_shopee_category_files.
 *
 * @since 1.0.0
 */
function ced_shopee_category_files() {
	$category_files = array(
		'MY' => array(
			'all'    => 'MY_categoryList.json',
			'level1' => 'MY_categoryLevel1.json',
		),
		'TH' => array(
			'all'    => 'TH_categoryList.json',
			'level1' => 'TH_categoryLevel1.json',
		),
		'VN' => array(
			'all'    => 'VN_categoryList.json',
			'level1' => 'VN_categoryLevel1.json',
		),
		'PH' => array(
			'all'    => 'PH_categoryList.json',
			'level1' => 'PH_categoryLevel1.json',
		),
		'SG' => array(
			'all'    => 'SG_categoryList.json',
			'level1' => 'SG_categoryLevel1.json',
		),
		'ID' => array(
			'all'    => 'ID_categoryList.json',
			'level1' => 'ID_categoryLevel1.json',
		),
		'TW' => array(
			'all'    => 'TW_categoryList.json',
			'level1' => 'TW_categoryLevel1.json',
		),
		'BR' => array(
			'all'    => 'BR_categoryList.json',
			'level1' => 'BR_categoryLevel1.json',
		),
		'MX' => array(
			'all'    => 'MX_categoryList.json',
			'level1' => 'MX_categoryLevel1.json',
		),
	);
	return $category_files;
}

/**
 * CoreFunctions ced_shopee_account_location.
 *
 * @since 1.0.0
 * @param int $shop_id Shopee Shop Id.
 */
function ced_shopee_account_location( $shop_id = '' ) {
	global $wpdb;
	$shop_details = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_accounts WHERE `shop_id`= %d', $shop_id ), 'ARRAY_A' );
	return $shop_details['location'];
}

/**
 * CoreFunctions ced_shopee_inactive_shops.
 *
 * @since 1.0.0
 * @param int $shop_id Shopee Shop Id.
 */
function ced_shopee_inactive_shops( $shop_id = '' ) {
	global $wpdb;
	$in_active_shops = $wpdb->get_results( $wpdb->prepare( 'SELECT `shop_id` FROM wp_ced_shopee_accounts WHERE `account_status` = %s ', 'inactive' ), 'ARRAY_A' );

	foreach ( $in_active_shops as $key => $value ) {
		if ( $value['shop_id'] === $shop_id ) {
			return true;
		}
	}
}

/**
 * CoreFunctions shopee_domains.
 *
 * @since 1.0.0
 */
function shopee_domains() {
	$domains = array(
		'MY' => 'https://shopee.com.my/',
		'TH' => 'https://shopee.co.th/',
		'VN' => 'https://shopee.vn/',
		'PH' => 'https://shopee.ph/',
		'SG' => 'https://shopee.sg/',
		'ID' => 'https://shopee.co.id/',
		'TW' => 'https://shopee.tw/',
		'BR' => 'https://shopee.com.br/',
		'MX' => 'https://shopee.com.mx/',
	);
	return $domains;
}

/**
 * Check WooCommmerce active or not.
 *
 * @since 1.0.0
 */
function ced_shopee_check_woocommerce_active() {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	}
	return false;
}
/**
 * This code runs when WooCommerce is not activated,
 *
 * @since 1.0.0
 */
function deactivate_ced_shopee_woo_missing() {

	deactivate_plugins( CED_SHOPEE_PLUGIN_BASENAME );
	add_action( 'admin_notices', 'ced_shopee_woo_missing_notice' );
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}
/**
 * Callback function for sending notice if woocommerce is not activated.
 *
 * @since 1.0.0
 */
function ced_shopee_woo_missing_notice() {

	// translators: %s: search term !!
	echo '<div class="notice notice-error is-dismissible"><p>' . sprintf( esc_html( __( 'Woocommerce Shopee Integration requires WooCommerce to be installed and active. You can download %s from here.', 'woocommerce-shopee-integration' ) ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';
}

/**
 * Callback function for display html.
 *
 * @since 1.0.0
 */
function ced_shopee_render_html( $meta_keys_to_be_displayed = array(), $added_meta_keys = array() ) {

	$html  = '';
	$html .= '<table class="wp-list-table widefat fixed striped">';

	if ( isset( $meta_keys_to_be_displayed ) && is_array( $meta_keys_to_be_displayed ) && ! empty( $meta_keys_to_be_displayed ) ) {
		$total_items  = count( $meta_keys_to_be_displayed );
		$pages        = ceil( $total_items / 10 );
		$current_page = 1;
		$counter      = 0;
		$break_point  = 1;

		foreach ( $meta_keys_to_be_displayed as $meta_key => $meta_data ) {
			$display = 'display : none';
			if ( 0 == $counter ) {
				if ( 1 == $break_point ) {
					$display = 'display : contents';
				}
				$html .= '<tbody style="' . esc_attr( $display ) . '" class="ced_shopee_metakey_list_' . $break_point . '  			ced_shopee_metakey_body">';
				$html .= '<tr><td colspan="3"><label>CHECK THE METAKEYS OR ATTRIBUTES</label></td>';
				$html .= '<td class="ced_shopee_pagination"><span>' . $total_items . ' items</span>';
				$html .= '<button class="button ced_shopee_navigation" data-page="1" ' . ( ( 1 == $break_point ) ? 'disabled' : '' ) . ' ><b><<</b></button>';
				$html .= '<button class="button ced_shopee_navigation" data-page="' . esc_attr( $break_point - 1 ) . '" ' . ( ( 1 == $break_point ) ? 'disabled' : '' ) . ' ><b><</b></button><span>' . $break_point . ' of ' . $pages;
				$html .= '</span><button class="button ced_shopee_navigation" data-page="' . esc_attr( $break_point + 1 ) . '" ' . ( ( $pages == $break_point ) ? 'disabled' : '' ) . ' ><b>></b></button>';
				$html .= '<button class="button ced_shopee_navigation" data-page="' . esc_attr( $pages ) . '" ' . ( ( $pages == $break_point ) ? 'disabled' : '' ) . ' ><b>>></b></button>';
				$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr><td><label>Select</label></td><td><label>Metakey / Attributes</label></td><td colspan="2"><label>Value</label></td>';

			}
			$checked    = ( in_array( $meta_key, $added_meta_keys ) ) ? 'checked=checked' : '';
			$html      .= '<tr>';
			$html      .= "<td><input type='checkbox' class='ced_shopee_meta_key' value='" . esc_attr( $meta_key ) . "' " . $checked . '></input></td>';
			$html      .= '<td>' . esc_attr( $meta_key ) . '</td>';
			$meta_value = ! empty( $meta_data[0] ) ? $meta_data[0] : '';
			$html      .= '<td colspan="2">' . esc_attr( $meta_value ) . '</td>';
			$html      .= '</tr>';
			++$counter;
			if ( 10 == $counter ) {
				
				$counter = 0;
				++$break_point;
				$html .= '<tr><td colsapn="4"><a href="" class="ced_shopee_custom_button button button-primary">Save</a></td></tr>';
				$html .= '</tbody>';
			}
		}
	} else {
		$html .= '<tr><td colspan="4" class="shopee-error">No data found. Please search the metakeys.</td></tr>';
	}
	$html .= '</table>';
	return $html;
}
