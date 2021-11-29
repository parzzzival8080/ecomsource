<?php
/**
 * Accounts Table
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Ced_Shopee_Account_Table
 *
 * @since 1.0.0
 */
class Ced_Shopee_Account_Table extends WP_List_Table {

	/**
	 * Ced_Shopee_Account_Table construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Shopee Account', 'woocommerce-shopee-integration' ),
				'plural'   => __( 'Shopee Accounts', 'woocommerce-shopee-integration' ),
				'ajax'     => false,
			)
		);

	}

	/**
	 * Function to prepare account data to be displayed
	 *
	 * @since 1.0.0
	 */
	public function prepareItems() {
		global $wpdb;

		$per_page = apply_filters( 'ced_shopee_account_list_per_page', 10 );
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->getSortableColumns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		$this->items = self::getAccounts( $per_page, $current_page );

		$count = self::getCount();

		if ( ! $this->current_action() ) {
			$this->set_pagination_args(
				array(
					'total_items' => $count,
					'per_page'    => $per_page,
					'total_pages' => ceil( $count / $per_page ),
				)
			);
			$this->items = self::getAccounts( $per_page, $current_page );
			$this->renderHTML();
		} else {
			$this->process_bulk_action();
		}
	}

	/**
	 * Function to get all the accounts
	 *
	 * @since 1.0.0
	 * @param      int $per_page    Results per page.
	 * @param      int $page_number   Page number.
	 */
	public function getAccounts( $per_page = 10, $page_number = 1 ) {
		global $wpdb;
		$offset = ( $page_number - 1 ) * $per_page;
		$result = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_accounts LIMIT %d OFFSET %d', $per_page, $offset ), 'ARRAY_A' );
		return $result;
	}

	/**
	 * Function to count number of responses in result
	 *
	 * @since 1.0.0
	 */
	public function getCount() {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM wp_ced_shopee_accounts WHERE %d', 1 ), 'ARRAY_A' );
		return count( $result );
	}

	/**
	 * Text displayed when no customer data is available
	 *
	 * @since 1.0.0
	 */
	public function no_items() {
		esc_html_e( 'No Accounts Linked.', 'woocommerce-shopee-integration' );
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @since 1.0.0
	 * @param array $item Account Data.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="shopee_account_id" value="%s" />',
			$item['id']
		);
	}

	/**
	 * Function for name column
	 *
	 * @since 1.0.0
	 * @param array $item Account Data.
	 */
	public function column_name( $item ) {
		$title = '<strong>' . urldecode( $item['name'] ) . '</strong>';
		return $title;
	}

	/**
	 * Function for Shop Id column
	 *
	 * @since 1.0.0
	 * @param array $item Account Data.
	 */
	public function column_shop_id( $item ) {
		return $item['shop_id'];
	}

	/**
	 * Function for Acoount Status column
	 *
	 * @since 1.0.0
	 * @param array $item Account Data.
	 */
	public function column_account_status( $item ) {
		if ( 'inactive' == $item['account_status'] ) {
			return 'InActive';
		} else {
			return 'Active';
		}
	}

	/**
	 * Function for Location column
	 *
	 * @since 1.0.0
	 * @param array $item Account Data.
	 */
	public function column_location( $item ) {
		$countries = ced_shopee_countries();
		return $countries[ $item['location'] ];
	}

	/**
	 * Function for Configure column
	 *
	 * @since 1.0.0
	 * @param array $item Account Data.
	 */
	public function column_configure( $item ) {
		$button_html = "<a class='button' href='" . admin_url( 'admin.php?page=ced_shopee&section=accounts-view&shop_id=' . $item['shop_id'] ) . "'>" . __( 'Configure', 'woocommerce-shopee-integration' ) . '</a>';
		return $button_html;
	}

	/**
	 *  Associative array of columns
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'name'           => __( 'Account Name', 'woocommerce-shopee-integration' ),
			'shop_id'        => __( 'Shopee Store Id', 'woocommerce-shopee-integration' ),
			'location'       => __( 'Shopee Location', 'woocommerce-shopee-integration' ),
			'account_status' => __( 'Account Status', 'woocommerce-shopee-integration' ),
			'configure'      => __( 'Configure', 'woocommerce-shopee-integration' ),
		);

		$columns = apply_filters( 'ced_shopee_alter_feed_table_columns', $columns );
		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @since 1.0.0
	 */
	public function getSortableColumns() {
		$sortable_columns = array();
		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @since 1.0.0
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-enable'  => __( 'Enable', 'woocommerce-shopee-integration' ),
			'bulk-disable' => __( 'Disable', 'woocommerce-shopee-integration' ),
			'bulk-delete'  => __( 'Delete', 'woocommerce-shopee-integration' ),
		);

		return $actions;
	}

	/**
	 * Function to get changes in html
	 *
	 * @since 1.0.0
	 */
	public function renderHTML() {
		?>
		<div class="ced_shopee_wrap ced_shopee_wrap_extn">
			<div class="ced_shopee_setting_header">
				<label class="manage_labels"><b><?php esc_html_e( 'SHOPEE ACCOUNT', 'woocommerce-shopee-integration' ); ?></b></label>
				<?php
				$acc_count = $this->getCount();
				if ( $acc_count < 1 ) {
					echo '<a href="javascript:void(0);" class="ced_shopee_add_account_button ced_shopee_add_button">' . esc_html( __( 'Add Account', 'woocommerce-shopee-integration' ) ) . '</a>';
				}
				?>
			</div>
			<?php esc_attr( display_shopee_support_html() ); ?>
			<div>
				<div id="post-body" class="metabox-holder columns-2">
					<div id="ced_shopee_list_products_table">
						<div class="meta-box-sortables ui-sortable">
							<form method="post" action="">
								<?php
								wp_nonce_field( 'shopee_accounts', 'shopee_accounts_actions' );
								$this->display();
								?>
							</form>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<br class="clear">
			</div>
		</div>
		<div class="ced_shopee_add_account_popup_main_wrapper">
			<div class="ced_shopee_add_account_popup_content">
				<div class="ced_shopee_add_account_popup_header">
					<h5><?php esc_html_e( 'Authorise your Shopee Account', 'woocommerce-shopee-integration' ); ?></h5>
					<span class="ced_shopee_add_account_popup_close">X</span>
				</div>
				<div class="ced_shopee_add_account_popup_body">
					<h6><?php esc_html_e( 'Steps to authorise your account:', 'woocommerce-shopee-integration' ); ?></h6>
					<ul>
						<li>
							<?php
							esc_html_e(
								'Click on the "Authorize" button which will redirect you to "open.shopee.com".
								',
								'woocommerce-shopee-integration'
							)
							?>

						</li>
						<li>
							<?php esc_html_e( 'On the Shopee authorization page you have to select the Country and then Login with your seller panel details.', 'woocommerce-shopee-integration' ); ?>

						</li>
						<li>
							<?php esc_html_e( 'You have to then click on "Yes" button to enable access to API.', 'woocommerce-shopee-integration' ); ?>
						</li>
						<li>
							<?php esc_html_e( 'Finally you will be redirected to your site indicating successful authorization.', 'woocommerce-shopee-integration' ); ?>
						</li>
					</ul>
					<div class="ced_shopee_add_account_button_wrapper">
						<a href="javascript:void(0);" id="ced_shopee_authorise_account_button" class="ced_shopee_add_button"><?php esc_html_e( 'Authorize', 'woocommerce-shopee-integration' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Function to get current action
	 *
	 * @since 1.0.0
	 */
	public function current_action() {

		if ( isset( $_GET['section'] ) ) {
			$action = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';
			return $action;
		} elseif ( isset( $_POST['action'] ) ) {

			if ( ! isset( $_POST['shopee_accounts_actions'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shopee_accounts_actions'] ) ), 'shopee_accounts' ) ) {
				return;
			}

			$action1 = isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';
			$action2 = isset( $_POST['action2'] ) ? sanitize_text_field( wp_unslash( $_POST['action2'] ) ) : '';
			if ( -1 != $action1 ) {
				$action = $action1;
			}
			if ( -1 != $action2 ) {
				$action = $action2;
			}
			return $action;
		}
	}

	/**
	 * Function to perform bulk actions for name column
	 *
	 * @since 1.0.0
	 */
	public function process_bulk_action() {
		if ( 'bulk-delete' === $this->current_action() || ( isset( $_GET['action'] ) && 'bulk-delete' === $_GET['action'] ) || ( isset( $_GET['action2'] ) && 'bulk-delete' === $_GET['action2'] ) ) {

			if ( ! isset( $_POST['shopee_accounts_actions'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shopee_accounts_actions'] ) ), 'shopee_accounts' ) ) {
				return;
			}
			$account_id = isset( $_POST['shopee_account_id'] ) ? sanitize_text_field( wp_unslash( $_POST['shopee_account_id'] ) ) : '';

			global $wpdb;

			$delete_status = $wpdb->query( $wpdb->prepare( 'DELETE FROM wp_ced_shopee_accounts WHERE `id` = %d ', $account_id ) );

			$redirect_url = get_admin_url() . 'admin.php?page=ced_shopee';
			wp_redirect( $redirect_url );
			exit;
		} elseif ( 'bulk-enable' === $this->current_action() || ( isset( $_GET['action'] ) && 'bulk-enable' === $_GET['action'] ) || ( isset( $_GET['action2'] ) && 'bulk-enable' === $_GET['action2'] ) ) {

			if ( ! isset( $_POST['shopee_accounts_actions'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shopee_accounts_actions'] ) ), 'shopee_accounts' ) ) {
				return;
			}

			$account_id = isset( $_POST['shopee_account_id'] ) ? sanitize_text_field( wp_unslash( $_POST['shopee_account_id'] ) ) : '';

			global $wpdb;
			$table_name = 'wp_ced_shopee_accounts';
			$wpdb->update( $table_name, array( 'account_status' => 'active' ), array( 'id' => $account_id ) );
			$redirect_url = get_admin_url() . 'admin.php?page=ced_shopee';
			wp_redirect( $redirect_url );
			exit;
		} elseif ( 'bulk-disable' === $this->current_action() || ( isset( $_GET['action'] ) && 'bulk-disable' === $_GET['action'] ) || ( isset( $_GET['action2'] ) && 'bulk-disable' === $_GET['action2'] ) ) {

			if ( ! isset( $_POST['shopee_accounts_actions'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shopee_accounts_actions'] ) ), 'shopee_accounts' ) ) {
				return;
			}

			$account_id = isset( $_POST['shopee_account_id'] ) ? sanitize_text_field( wp_unslash( $_POST['shopee_account_id'] ) ) : '';

			global $wpdb;
			$table_name = 'wp_ced_shopee_accounts';
			$wpdb->update( $table_name, array( 'account_status' => 'inactive' ), array( 'id' => $account_id ) );
			$redirect_url = get_admin_url() . 'admin.php?page=ced_shopee';
			wp_redirect( $redirect_url );
			exit;
		} elseif ( isset( $_GET['section'] ) ) {
			$file = CED_SHOPEE_DIRPATH . 'admin/partials/' . $this->current_action() . '.php';
			if ( file_exists( $file ) ) {
				include_once $file;
			}
		}
	}
}

$ced_shopee_account_obj = new Ced_Shopee_Account_Table();
$ced_shopee_account_obj->prepareItems();
