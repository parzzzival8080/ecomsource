<?php
/**
 * Product listing in manage products
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

if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * ShopeeListProducts
 *
 * @since 1.0.0
 */
class ShopeeListProducts extends WP_List_Table {


	/**
	 * ShopeeListProducts construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'Product', 'woocommerce-shopee-integration' ),
				'plural'   => __( 'Products', 'woocommerce-shopee-integration' ),
				'ajax'     => true,
			)
		);

	}

	/**
	 * Function for preparing data to be displayed
	 *
	 * @since 1.0.0
	 */
	public function prepareItems() {
		global $wpdb;

		$per_page  = apply_filters( 'ced_shopee_products_per_page', 10 );
		$post_type = 'product';
		$columns   = $this->get_columns();
		$hidden    = array();
		$sortable  = $this->getSortableColumns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}
		$this->items = self::cedShopeeGetProductDetails( $per_page, $current_page, $post_type );
		$count       = self::get_count( $per_page, $current_page );

		$this->set_pagination_args(
			array(
				'total_items' => $count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $count / $per_page ),
			)
		);

		if ( ! $this->current_action() ) {
			$this->items = self::cedShopeeGetProductDetails( $per_page, $current_page, $post_type );
			$this->renderHTML();
		} else {
			$this->process_bulk_action();
		}
	}

	/**
	 * Function for get product data
	 *
	 * @since 1.0.0
	 * @param      int    $per_page    Results per page.
	 * @param      int    $page_number   Page number.
	 * @param      string $post_type   Post type.
	 */
	public function cedShopeeGetProductDetails( $per_page = '', $page_number = '', $post_type = '' ) {
		$filter_file = CED_SHOPEE_DIRPATH . 'admin/partials/class-filterclass.php';
		if ( file_exists( $filter_file ) ) {
			include_once $filter_file;
		}

		$instance_of_filter_class = new FilterClass();

		$args = $this->GetFilteredData( $per_page, $page_number );
		if ( ! empty( $args ) && isset( $args['tax_query'] ) || isset( $args['meta_query'] ) || isset( $args['s'] ) ) {
			$args = $args;
		} else {
			$args = array(
				'post_type'      => $post_type,
				'posts_per_page' => $per_page,
				'paged'          => $page_number,
			);
		}

		$loop = new WP_Query( $args );

		$product_data   = $loop->posts;
		$woo_categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
		$woo_products   = array();
		foreach ( $product_data as $key => $value ) {
			$get_product_data                    = wc_get_product( $value->ID );
			$get_product_data                    = $get_product_data->get_data();
			$woo_products[ $key ]['category_id'] = isset( $get_product_data['category_ids'][0] ) ? $get_product_data['category_ids'][0] : '';
			$woo_products[ $key ]['id']          = $value->ID;
			$woo_products[ $key ]['name']        = $get_product_data['name'];
			$woo_products[ $key ]['stock']       = $get_product_data['stock_quantity'];
			$woo_products[ $key ]['sku']         = $get_product_data['sku'];
			$woo_products[ $key ]['price']       = $get_product_data['price'];
			$image_url_id                        = $get_product_data['image_id'];
			$woo_products[ $key ]['image']       = wp_get_attachment_url( $image_url_id );
			foreach ( $woo_categories as $key1 => $value1 ) {
				if ( isset( $get_product_data['category_ids'][0] ) ) {
					if ( $value1->term_id == $get_product_data['category_ids'][0] ) {
						$woo_products[ $key ]['category'] = $value1->name;
					}
				}
			}
		}

		if ( isset( $_POST['filter_button'] ) ) {
			if ( ! isset( $_POST['manage_product_filters'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['manage_product_filters'] ) ), 'manage_products' ) ) {
				return;
			}
			$woo_products = $instance_of_filter_class->ced_shopee_filters_on_products();
		} elseif ( isset( $_POST['s'] ) && ! empty( $_POST['s'] ) ) {
			if ( ! isset( $_POST['manage_product_filters'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['manage_product_filters'] ) ), 'manage_products' ) ) {
				return;
			}
			$woo_products = $instance_of_filter_class->product_search_box();
		}

		return $woo_products;
	}

	/**
	 * Text displayed when no data is available
	 *
	 * @since 1.0.0
	 */
	public function no_items() {
		esc_html_e( 'No Products To Show.', 'woocommerce-shopee-integration' );
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
	 * Render the bulk edit checkbox
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="shopee_product_ids[]" class="shopee_products_id" value="%s" />',
			$item['id']
		);
	}

	/**
	 * Function for name column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_name( $item ) {
		$url             = get_edit_post_link( $item['id'], '' );
		$actions['id']   = 'ID:' . $item['id'];
		$shop_id         = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
		$actions['view'] = "<a class='' href='javascript:void(0)' data=" . ( $item['id'] ) . ' data-shopid=' . ( $shop_id ) . " id='ced_shopee_preview'>" . __( 'Preview', 'woocommerce-shopee-integration' ) . '</a>';
		$status          = get_post_meta( $item['id'], '_ced_shopee_listing_id_' . $shop_id );
		if ( isset( $status ) && ! empty( $status ) ) {
			$actions['update'] = "<a class='ced_shopee_preview_product_update_button' data-id='" . $item['id'] . "' href='javascript:void(0)' id='ced_shopee_update_product'>" . __( 'Update', 'woocommerce-shopee-integration' ) . '</a>';
		} else {
			$actions['upload'] = "<a class='ced_shopee_preview_product_upload_button' data-id='" . $item['id'] . "' href='javascript:void(0)' id='ced_shopee_upload_product'>" . __( 'Upload', 'woocommerce-shopee-integration' ) . '</a>';
		}
		echo '<b><a class="ced_shopee_prod_name" href="' . esc_url( $url ) . '">' . esc_attr( $item['name'] ) . '</a></b><br>';
		return $this->row_actions( $actions );
	}

	/**
	 * Function for profile column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_profile( $item ) {
		$shop_id                      = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
		$get_profile_id_of_prod_level = get_post_meta( $item['id'], 'ced_shopee_profile_assigned' . $shop_id, true );
		if ( ! empty( $get_profile_id_of_prod_level ) ) {
			global $wpdb;
			$profile_name = $wpdb->get_results( $wpdb->prepare( 'SELECT `profile_name` FROM wp_ced_shopee_profiles WHERE `id` = %d ', $get_profile_id_of_prod_level ), 'ARRAY_A' );
			$profile_name = isset( $profile_name[0]['profile_name'] ) ? sanitize_text_field( wp_unslash( $profile_name[0]['profile_name'] ) ) : '';
			echo '<b>' . esc_attr( urldecode( $profile_name ) ) . '</b>';
			$profile_id = $get_profile_id_of_prod_level;
		} else {
			$get_shopee_category_id_data = get_term_meta( $item['category_id'] );
			$get_shopee_category_id      = isset( $get_shopee_category_id_data[ 'ced_shopee_mapped_category_' . $shop_id ] ) ? ( $get_shopee_category_id_data[ 'ced_shopee_mapped_category_' . $shop_id ] ) : '';
			if ( ! empty( $get_shopee_category_id ) && isset( $get_shopee_category_id ) ) {
				foreach ( $get_shopee_category_id as $key => $shopee_id ) {
					$get_shopee_profile_assigned = get_option( 'ced_woo_shopee_mapped_categories_name' . $shop_id);
					$get_shopee_profile_assigned = isset( $get_shopee_profile_assigned[ $_GET['shop_id'] ][ $shopee_id ] ) ? $get_shopee_profile_assigned[ $shop_id ][ $shopee_id ] : '';
				}

				if ( isset( $get_shopee_profile_assigned ) && ! empty( $get_shopee_profile_assigned ) ) {
					echo '<b>' . esc_attr( $get_shopee_profile_assigned ) . '</b>';
				}
			} else {
				echo '<b class="not_completed">' . esc_html( __( 'Profile Not Assigned', 'woocommerce-shopee-integration' ) ) . '</b>';
				$actions['edit'] = '<a href="javascript:void(0)" class="ced_shopee_profiles_on_pop_up" data-shopid="' . ( $shop_id ) . '" data-product_id="' . ( $item['id'] ) . '">' . __( 'Assign Profile', 'woocommerce-shopee-integration' ) . '</a>';
				return $this->row_actions( $actions );
			}
			$profile_id = isset( $get_shopee_category_id_data[ 'ced_shopee_profile_id_' . $shop_id ] ) ? ( $get_shopee_category_id_data[ 'ced_shopee_profile_id_' . $shop_id ] ) : '';
			$profile_id = $profile_id[0];
		}

		if ( ! empty( $profile_id ) ) {
			$edit_profile_url  = admin_url( 'admin.php?page=ced_shopee&section=class-ced-shopee-profile-table&shop_id=' . ( $shop_id ) . '&profileID=' . ( $profile_id ) . '&panel=edit' );
			$actions['edit']   = '<a href="' . esc_url( $edit_profile_url ) . '">' . __( 'Edit', 'woocommerce-shopee-integration' ) . '</a>';
			$actions['change'] = '<a href="javascript:void(0)" class="ced_shopee_profiles_on_pop_up" data-shopid="' . ( $shop_id ) . '" data-product_id="' . ( $item['id'] ) . '">' . __( 'Change', 'woocommerce-shopee-integration' ) . '</a>';
			return $this->row_actions( $actions );
		}
	}

	/**
	 * Function for stock column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_stock( $item ) {
		if ( 0 == $item['stock'] || '0' == $item['stock'] ) {
			return '<b class="stock_alert" >' . esc_html( __( 'Out Of Stock', 'woocommerce-shopee-integration' ) ) . '</b>';
		} else {
			return '<b>' . ( $item['stock'] ) . '</b>';
		}
	}

	/**
	 * Function for category column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_category( $item ) {
		if ( isset( $item['category'] ) ) {
			return '<b>' . ( $item['category'] ) . '</b>';
		}
	}

	/**
	 * Function for price column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_price( $item ) {
		$currency_symbol = get_woocommerce_currency_symbol();
		return ( $currency_symbol ) . '&nbsp<b class="success_upload_on_shopee">' . ( $item['price'] ) . '</b>';
	}

	/**
	 * Function for product type column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_type( $item ) {
		$product      = wc_get_product( $item['id'] );
		$product_type = $product->get_type();
		return '<b>' . ( $product_type ) . '</b>';
	}

	/**
	 * Function for sku column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_sku( $item ) {
		return '<b>' . ( $item['sku'] ) . '</b>';
	}

	/**
	 * Function for image column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_image( $item ) {
		return '<img height="50" width="50" src="' . esc_url( $item['image'] ) . '">';
	}

	/**
	 * Function for status column
	 *
	 * @since 1.0.0
	 * @param array $item Product Data.
	 */
	public function column_status( $item ) {
		$shop_id = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
		$status  = get_post_meta( $item['id'], '_ced_shopee_listing_id_' . ( $shop_id ), true );
		if ( isset( $status ) && ! empty( $status ) ) {
			$listing_id          = get_post_meta( $item['id'], '_ced_shopee_listing_id_' . ( $shop_id ), true );
			$location            = ced_shopee_account_location( $shop_id );
			$get_location_domain = shopee_domains();
			$view_url            = $get_location_domain[ $location ] . 'product/' . ( $shop_id ) . '/' . ( $listing_id );
			echo '<b class="success_upload_on_shopee" id="' . esc_attr( $item['id'] ) . '">' . esc_html( __( 'Uploaded', 'woocommerce-shopee-integration' ) ) . '</b>';
			$actions['view'] = '<a href="' . esc_url( $view_url ) . '"  class="' . ( $item['id'] ) . '" target="#">' . esc_html( __( 'View On Shopee', 'woocommerce-shopee-integration' ) ) . '</a>';
			return $this->row_actions( $actions );
		} else {
			echo '<b class="not_completed" id="' . esc_attr( $item['id'] ) . '">' . esc_html( __( 'Not Uploaded', 'woocommerce-shopee-integration' ) ) . '</b>';
		}
	}

	/**
	 * Associative array of columns
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {
		$columns = array(
			'cb'       => '<input type="checkbox" />',
			'image'    => __( 'Product Image', 'woocommerce-shopee-integration' ),
			'name'     => __( 'Product Name', 'woocommerce-shopee-integration' ),
			'type'     => __( 'Product Type', 'woocommerce-shopee-integration' ),
			'price'    => __( 'Product Price', 'woocommerce-shopee-integration' ),
			'profile'  => __( 'Profile Assigned', 'woocommerce-shopee-integration' ),
			'sku'      => __( 'Product Sku', 'woocommerce-shopee-integration' ),
			'stock'    => __( 'Product Stock', 'woocommerce-shopee-integration' ),
			'category' => __( 'Woo Category', 'woocommerce-shopee-integration' ),
			'status'   => __( 'Product Status', 'woocommerce-shopee-integration' ),
		);
		$columns = apply_filters( 'ced_shopee_alter_product_table_columns', $columns );
		return $columns;
	}

	/**
	 * Function to count number of responses in result
	 *
	 * @since 1.0.0
	 * @param      int $per_page    Results per page.
	 * @param      int $page_number   Page number.
	 */
	public function get_count( $per_page, $page_number ) {
		$args = $this->GetFilteredData( $per_page, $page_number );
		if ( ! empty( $args ) && isset( $args['tax_query'] ) || isset( $args['meta_query'] ) ) {
			$args = $args;
		} else {
			$args = array( 'post_type' => 'product' );
		}
		$loop         = new WP_Query( $args );
		$product_data = $loop->posts;
		$product_data = $loop->found_posts;

		return $product_data;
	}

	/**
	 * Function to get the filtered data
	 *
	 * @since 1.0.0
	 * @param      int $per_page    Results per page.
	 * @param      int $page_number   Page number.
	 */
	public function GetFilteredData( $per_page, $page_number ) {
		$shop_id = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
		if ( isset( $_GET['status_sorting'] ) || isset( $_GET['pro_cat_sorting'] ) || isset( $_GET['pro_type_sorting'] ) || isset( $_GET['searchBy'] ) ) {
			if ( ! empty( $_REQUEST['pro_cat_sorting'] ) ) {
				$pro_cat_sorting = isset( $_GET['pro_cat_sorting'] ) ? sanitize_text_field( wp_unslash( $_GET['pro_cat_sorting'] ) ) : '';
				if ( ! empty( $pro_cat_sorting ) ) {
					$selected_cat          = array( $pro_cat_sorting );
					$tax_query             = array();
					$tax_queries           = array();
					$tax_query['taxonomy'] = 'product_cat';
					$tax_query['field']    = 'id';
					$tax_query['terms']    = $selected_cat;
					$args['tax_query'][]   = $tax_query;
				}
			}

			if ( ! empty( $_REQUEST['pro_type_sorting'] ) ) {
				$pro_type_sorting = isset( $_GET['pro_type_sorting'] ) ? sanitize_text_field( wp_unslash( $_GET['pro_type_sorting'] ) ) : '';
				if ( ! empty( $pro_type_sorting ) ) {
					$selected_type         = array( $pro_type_sorting );
					$tax_query             = array();
					$tax_queries           = array();
					$tax_query['taxonomy'] = 'product_type';
					$tax_query['field']    = 'id';
					$tax_query['terms']    = $selected_type;
					$args['tax_query'][]   = $tax_query;
				}
			}

			if ( ! empty( $_REQUEST['status_sorting'] ) ) {
				$status_sorting = isset( $_GET['status_sorting'] ) ? sanitize_text_field( wp_unslash( $_GET['status_sorting'] ) ) : '';
				if ( ! empty( $status_sorting ) ) {
					$meta_query = array();
					if ( 'Uploaded' == $status_sorting ) {
						$args['orderby'] = 'meta_value_num';
						$args['order']   = 'ASC';

						$meta_query[] = array(
							'key'     => '_ced_shopee_listing_id_' . ( $shop_id ),
							'compare' => 'EXISTS',
						);
					} elseif ( 'NotUploaded' == $status_sorting ) {
						$meta_query[] = array(
							'key'     => '_ced_shopee_listing_id_' . ( $shop_id ),
							'compare' => 'NOT EXISTS',
						);
					}
					$args['meta_query'] = $meta_query;
				}
			}
			if ( ! empty( $_REQUEST['searchBy'] ) ) {
				$search_by = isset( $_GET['searchBy'] ) ? sanitize_text_field( wp_unslash( $_GET['searchBy'] ) ) : '';
				if ( ! empty( $search_by ) ) {
					$args['s'] = $search_by;
				}
			}

			$args['post_type']      = 'product';
			$args['posts_per_page'] = $per_page;
			$args['paged']          = $page_number;
			return $args;
		}
	}

	/**
	 * Render bulk actions
	 *
	 * @since 1.0.0
	 * @param      string $which    Where the apply button is placed.
	 */
	protected function bulk_actions( $which = '' ) {
		if ( 'top' == $which ) :
			if ( is_null( $this->_actions ) ) {
				$this->_actions = $this->get_bulk_actions();
				/**
				 * Filters the list table Bulk Actions drop-down.
				 *
				 * The dynamic portion of the hook name, `$this->screen->id`, refers
				 * to the ID of the current screen, usually a string.
				 *
				 * This filter can currently only be used to remove bulk actions.
				 *
				 * @since 3.5.0
				 *
				 * @param array $actions An array of the available bulk actions.
				 */
				$this->_actions = apply_filters( "bulk_actions-{$this->screen->id}", $this->_actions );
				$two            = '';
			} else {
				$two = '2';
			}

			if ( empty( $this->_actions ) ) {
				return;
			}

			echo '<label for="bulk-action-selector-' . esc_attr( $which ) . '" class="screen-reader-text">' . esc_html( __( 'Select bulk action' ) ) . '</label>';
			echo '<select name="action' . esc_attr( $two ) . '" class="bulk-action-selector ">';
			echo '<option value="-1">' . esc_html( __( 'Bulk Actions' ) ) . "</option>\n";

			foreach ( $this->_actions as $name => $title ) {
				$class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

				echo "\t" . '<option value="' . esc_attr( $name ) . '"' . esc_attr( $class ) . '>' . esc_attr( $title ) . "</option>\n";
			}

			echo "</select>\n";

			submit_button( __( 'Apply' ), 'action', '', false, array( 'id' => 'ced_shopee_bulk_operation' ) );
			echo "\n";
		endif;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @since 1.0.0
	 */
	public function get_bulk_actions() {
		$actions = array(
			'upload_product' => __( 'Upload Products', 'woocommerce-shopee-integration' ),
			'update_product' => __( 'Update Products', 'woocommerce-shopee-integration' ),
			'update_image'   => __( 'Update Image', 'woocommerce-shopee-integration' ),
			'update_stock'   => __( 'Update Stock', 'woocommerce-shopee-integration' ),
			'update_price'   => __( 'Update Price', 'woocommerce-shopee-integration' ),
			'remove_product' => __( 'Remove Product', 'woocommerce-shopee-integration' ),
		);
		return $actions;
	}

	/**
	 * Function for rendering html
	 *
	 * @since 1.0.0
	 */
	public function renderHTML() {
		?>
		<div id="post-body" class="metabox-holder columns-2">
		 <div id="post-body-content">
		   <div class="meta-box-sortables ui-sortable">
			<?php
			$status_actions = array(
				'Uploaded'    => __( 'Uploaded', 'woocommerce-shopee-integration' ),
				'NotUploaded' => __( 'Not Uploaded', 'woocommerce-shopee-integration' ),
			);

			$product_types = get_terms( 'product_type', array( 'hide_empty' => false ) );
			$temp_array    = array();
			foreach ( $product_types as $key => $value ) {
				if ( 'simple' == $value->name || 'variable' == $value->name ) {
					$temp_array_type[ $value->term_id ] = ucfirst( $value->name );
				}
			}
			$product_types      = $temp_array_type;
			$product_categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
			$temp_array         = array();
			foreach ( $product_categories as $key => $value ) {
				$temp_array[ $value->term_id ] = $value->name;
			}
			$product_categories = $temp_array;

			$previous_selected_status = isset( $_GET['status_sorting'] ) ? sanitize_text_field( wp_unslash( $_GET['status_sorting'] ) ) : '';
			$previous_selected_cat    = isset( $_GET['pro_cat_sorting'] ) ? sanitize_text_field( wp_unslash( $_GET['pro_cat_sorting'] ) ) : '';
			$previous_selected_type   = isset( $_GET['pro_type_sorting'] ) ? sanitize_text_field( wp_unslash( $_GET['pro_type_sorting'] ) ) : '';
			echo '<div class="ced_shopee_wrap">';
			echo '<form method="post" action="">';
			wp_nonce_field( 'manage_products', 'manage_product_filters' );
			echo '<div class="ced_shopee_top_wrapper">';
			echo '<select name="status_sorting" class="select_boxes_product_page">';
			echo '<option value="">' . esc_html( __( 'Product Status', 'woocommerce-shopee-integration' ) ) . '</option>';
			foreach ( $status_actions as $name => $title ) {
				$selected_status = ( $previous_selected_status == $name ) ? 'selected="selected"' : '';
				$class           = 'edit' === $name ? ' class="hide-if-no-js"' : '';
				echo '<option ' . esc_attr( $selected_status ) . ' value="' . esc_attr( $name ) . '"' . esc_attr( $class ) . '>' . esc_attr( $title ) . '</option>';
			}
			echo '</select>';
			echo '<select name="pro_cat_sorting" class="select_boxes_product_page">';
			echo '<option value="">' . esc_html( __( 'Product Category', 'woocommerce-shopee-integration' ) ) . '</option>';
			foreach ( $product_categories as $name => $title ) {
				$selected_cat = ( $previous_selected_cat == $name ) ? 'selected="selected"' : '';
				$class        = 'edit' === $name ? ' class="hide-if-no-js"' : '';
				echo '<option ' . esc_attr( $selected_cat ) . ' value="' . esc_attr( $name ) . '"' . esc_attr( $class ) . '>' . esc_attr( $title ) . '</option>';
			}
			echo '</select>';
			echo '<select name="pro_type_sorting" class="select_boxes_product_page">';
			echo '<option value="">' . esc_html( __( 'Product Type', 'woocommerce-shopee-integration' ) ) . '</option>';
			foreach ( $product_types as $name => $title ) {
				$selected_type = ( $previous_selected_type == $name ) ? 'selected="selected"' : '';
				$class         = 'edit' === $name ? ' class="hide-if-no-js"' : '';
				echo '<option ' . esc_attr( $selected_type ) . ' value="' . esc_attr( $name ) . '"' . esc_attr( $class ) . '>' . esc_attr( $title ) . '</option>';
			}
			echo '</select>';
			$this->search_box( 'Search Products', 'search_id', 'search_product' );
			submit_button( __( 'Filter', 'woocommerce-shopee-integration' ), 'action', 'filter_button', false, array() );
			echo '</div>';
			echo '</form>';
			echo '</div>';
			?>

		 <form method="post">
			 <?php
				$this->display();
				?>
		 </form>

	 </div>
 </div>
 <div class="clear"></div>
</div>
<div class="ced_shopee_preview_product_popup_main_wrapper"></div>
		<?php
	}
}

$ced_shopee_products_obj = new ShopeeListProducts();
$ced_shopee_products_obj->prepareItems();
