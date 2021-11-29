<?php
/**
 * Header of the extensiom
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$shop_id = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
update_option( 'ced_shopee_shop_id', $shop_id );
global $wpdb;
$shop_details = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_accounts WHERE `shop_id` = %d ', $shop_id ), 'ARRAY_A' );
$shop_details = $shop_details[0];
$countries    = ced_shopee_countries();

if ( isset( $_GET['section'] ) ) {
	$section = sanitize_text_field( wp_unslash( $_GET['section'] ) );
}

?>
<div class="ced_shopee_loader">
	<img src="<?php echo esc_url( CED_SHOPEE_URL . 'admin/images/loading.gif' ); ?>" width="50px" height="50px" class="ced_shopee_loading_img" >
</div>
<div class="success-admin-notices is-dismissible"></div>
<div class="navigation-wrapper">
	<ul class="navigation">
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ced_shopee&section=accounts-view&shop_id=' . $shop_id ) ); ?>" class="
								<?php
								if ( 'accounts-view' == $section ) {
									echo 'active';
								}
								?>
			"><?php esc_html_e( 'Account Details', 'woocommerce-shopee-integration' ); ?></a>
		</li>
		<li>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ced_shopee&section=settings-view&shop_id=' . $shop_id ) ); ?>" class="
								<?php
								if ( 'settings-view' == $section ) {
									echo 'active';
								}
								?>
			"><?php esc_html_e( 'Settings', 'woocommerce-shopee-integration' ); ?></a>
		</li>
		<li>
			<a class="
			<?php
			if ( 'category-mapping-view' == $section ) {
				echo 'active';
			}
			?>
			" href="<?php echo esc_url( admin_url( 'admin.php?page=ced_shopee&section=category-mapping-view&shop_id=' . $shop_id ) ); ?>"><?php esc_html_e( 'Category Mapping', 'woocommerce-shopee-integration' ); ?></a>
		</li>
		<li>
			<a class="
			<?php
			if ( 'class-ced-shopee-profile-table' == $section ) {
				echo 'active';
			}
			?>
			" href="<?php echo esc_url( admin_url( 'admin.php?page=ced_shopee&section=class-ced-shopee-profile-table&shop_id=' . $shop_id ) ); ?>"><?php esc_html_e( 'Profile', 'woocommerce-shopee-integration' ); ?></a>
		</li>
		<li>
			<a class="
			<?php
			if ( 'class-shopeelistproducts' == $section ) {
				echo 'active';
			}
			?>
			" href="<?php echo esc_url( admin_url( 'admin.php?page=ced_shopee&section=class-shopeelistproducts&shop_id=' . $shop_id ) ); ?>"><?php esc_html_e( 'Products', 'woocommerce-shopee-integration' ); ?></a>
		</li>
		<li>
			<a class="
			<?php
			if ( 'class-ced-shopee-list-orders' == $section ) {
				echo 'active';
			}
			?>
			" href="<?php echo esc_url( admin_url( 'admin.php?page=ced_shopee&section=class-ced-shopee-list-orders&shop_id=' . $shop_id ) ); ?>"><?php esc_html_e( 'Orders', 'woocommerce-shopee-integration' ); ?></a>
		</li>
	</ul>
	<?php
	if ( isset( $shop_details['name'] ) ) {
		?>
		<span class="ced_shopee_current_account_name"><?php echo '<b>Account Name</b> - <label><b>' . esc_attr( urldecode( $shop_details['name'] ) ) . '</b></label>'; ?></span>
		<?php
	}
	?>
</div>
<?php esc_attr( display_shopee_support_html() ); ?>
