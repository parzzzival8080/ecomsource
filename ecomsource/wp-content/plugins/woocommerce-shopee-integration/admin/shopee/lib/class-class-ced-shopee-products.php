<?php
/**
 * Products related operations fo shopee
 *
 * @package  Woocommerce_Shopee_Integration
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if (! defined('ABSPATH')) {
    die;
}

/**
 * Class_Ced_Shopee_Products.
 *
 * @since 1.0.0
 */
class Class_Ced_Shopee_Products
{

    /**
     * Instance variable
     *
     * @var $_instance
     */
    public static $_instance;

    /**
     * Class_Ced_Shopee_Products Instance.
     *
     * @since 1.0.0
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Class_Ced_Shopee_Products construct
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->load_dependency();
    }

    /**
     * Class_Ced_Shopee_Products load_dependency
     *
     * @since 1.0.0
     */
    public function load_dependency()
    {
        $file = CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        if (file_exists($file)) {
            include_once $file;
        }

        $this->shopee_send_http_request_instance = new Class_Ced_Shopee_Send_Http_Request();
    }

    /**
     * Function for preparing product data to be uploaded
     *
     * @since 1.0.0
     * @param array $pro_ids Product Ids.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function ced_shopee_prepare_data_for_uploading($pro_ids = array(), $shop_id)
    {
        foreach ($pro_ids as $key => $value) {
            $prod_data = wc_get_product($value);
            if (! is_object($prod_data)) {
                continue;
            }

            $profile_data = $this->ced_shopee_get_profile_assigned_data($value, $shop_id);
            if (! $this->is_profile_assigned_to_product) {
                $error['msg'] = 'Profile Not Assigned .';
                return $error;
            }

            $type             = $prod_data->get_type();
            $already_uploaded = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);

            if ('variable' == $type) {
                $prepared_data = $this->get_formatted_data($value, $shop_id);

                $this->data         = $prepared_data;
                $product_variations = $this->get_product_variations($value);

                $this->data = $prepared_data;

                self::doupload($value, $shop_id);

                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                    $variation_prepared_data = $this->get_formatted_data_for_variation($value, $shop_id, $response['item_id']);
                    $variation_response      = $this->doaddvariation($variation_prepared_data, $shop_id);
                    if (isset($variation_response['item_id'])) {
                        $item_details = $this->get_item_detail($item_id, $shop_id);

                        if (isset($item_details['item'])) {
                            update_post_meta($value, 'ced_shopee_item_details', $item_details);
                            if (isset($item_details['item']['has_variation']) && (1 == $item_details['item']['has_variation'] || '1' == $item_details['item']['has_variation'])) {
                                foreach ($item_details['item']['variations'] as $variation_key => $variation) {
                                    $variation_sku = isset($variation['variation_sku']) ? $variation['variation_sku'] : '';
                                    if (in_array($variation_sku, $product_variations)) {
                                        $variation_id = array_search($variation_sku, $product_variations);
                                        update_post_meta($variation_id, '_ced_shopee_listing_id_' . $shop_id, $variation['variation_id']);
                                    }
                                }
                            }
                        }
                    } else {
                        $response         = $variation_response;
                        $removed_response = $this->ced_shopee_prepare_data_for_delete(array( $value ), $shop_id);
                        if (isset($removed_response['item_id'])) {
                            $item_id = $removed_response['item_id'];
                            delete_post_meta($value, '_ced_shopee_listing_id_' . $shop_id);
                        }
                    }
                }
            } else {
                $prepared_data = $this->get_formatted_data($value, $shop_id);
                $this->data    = $prepared_data;
                self::doupload($value, $shop_id);
                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                }
            }
        }

        return $response;
    }


    /**
     * Function for preparing product data to be updated
     *
     * @since 1.0.0
     * @param array $pro_ids Product Ids.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function ced_shopee_prepare_data_for_updating($pro_ids = array(), $shop_id)
    {
        foreach ($pro_ids as $key => $value) {
            $prod_data = wc_get_product($value);
            if (! is_object($prod_data)) {
                continue;
            }
            $type = $prod_data->get_type();

            if ('variable' == $type) {
                $item_id            = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
                $prepared_data      = $this->get_formatted_data($value, $shop_id, $item_id);
                $this->data         = $prepared_data;
                $product_variations = $this->get_product_variations($value);

                $this->data = $prepared_data;

                self::doupdate($value, $shop_id);

                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                    $variation_prepared_data_options = $this->get_formatted_data_for_variation($value, $shop_id, $response['item_id'], 'yes', 'update_list');
                    $variation_response              = $this->do_add_variation_update_list($variation_prepared_data_options, $shop_id);
                    $variation_prepared_data         = $this->get_formatted_data_for_variation($value, $shop_id, $response['item_id'], 'yes');
                    $variation_response              = $this->do_add_variation_update($variation_prepared_data, $shop_id);
                    if (isset($variation_response['item_id'])) {
                        $item_details = $this->get_item_detail($item_id, $shop_id);

                        if (isset($item_details['item'])) {
                            update_post_meta($value, 'ced_shopee_item_details', $item_details);
                            if (isset($item_details['item']['has_variation']) && (1 == $item_details['item']['has_variation'] || '1' == $item_details['item']['has_variation'])) {
                                foreach ($item_details['item']['variations'] as $variation_key => $variation) {
                                    $variation_sku = isset($variation['variation_sku']) ? $variation['variation_sku'] : '';
                                    if (in_array($variation_sku, $product_variations)) {
                                        $variation_id = array_search($variation_sku, $product_variations);
                                        update_post_meta($variation_id, '_ced_shopee_listing_id_' . $shop_id, $variation['variation_id']);
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $item_id       = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
                $prepared_data = $this->get_formatted_data($value, $shop_id, $item_id);
                $this->data    = $prepared_data;
                self::doupdate($value, $shop_id);
                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                }
            }
        }
        return $response;
    }


    public function do_add_variation_update($data, $shop_id)
    {
        $response              = $this->add_variation_to_shopee_update($data, $shop_id);
        $this->upload_response = $response;
        return $this->upload_response;
    }

    public function add_variation_to_shopee_update($parameters, $shop_id)
    {
        require_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action           = 'item/tier_var/add';
        $parameters       = $parameters;
        $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        return $response;
    }

    public function do_add_variation_update_list($parameters, $shop_id)
    {
        require_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action           = 'item/tier_var/update_list';
        $parameters       = $parameters;
        $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);

        return $response;
    }

    /**
     * Function for getting stock of products to be updated
     *
     * @since 1.0.0
     * @param array $pro_ids Product Ids.
     * @param int   $shop_id Shopee Shop Id.
     * @param bool  $not_ajax Whether ajax or not.
     */
    public function ced_shopee_prepare_data_for_updating_stock($pro_ids = array(), $shop_id, $not_ajax = false)
    {
        foreach ($pro_ids as $key => $value) {
            $prod_data = wc_get_product($value);
            if (! is_object($prod_data)) {
                continue;
            }
            $profile_data   = $this->ced_shopee_get_profile_assigned_data($value, $shop_id);
            $type           = $prod_data->get_type();
            $prod_data      = wc_get_product($value);
            $shopee_item_id = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
            if ('variable' == $type) {
                $variations = $prod_data->get_available_variations();
                foreach ($variations as $key => $variation) {
                    $variation_shopee_item_id = (int) get_post_meta($variation['variation_id'], '_ced_shopee_listing_id_' . $shop_id, true);
                    if (empty($variation_shopee_item_id)) {
                        continue;
                    }

                    $stock = (int) $this->fetch_meta_value_of_product($variation['variation_id'], '_ced_shopee_stock');
                    if (''  == $stock) {
                        $stock = (int) get_post_meta($variation['variation_id'], '_stock', true);
                    }
                    $prepared_data_for_uploading                 = array();
                    $prepared_data_for_uploading['item_id']      = (int) $shopee_item_id;
                    $prepared_data_for_uploading['variation_id'] = (int) $variation_shopee_item_id;
                    $prepared_data_for_uploading['stock']        = (int) $stock;
                    $response                                    = $this->do_update_stock($prepared_data_for_uploading, 'variation', $shop_id);

                    if (isset($response['item']['item_id'])) {
                        $item_id = $response['item']['item_id'];
                        update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                        $item_details = $this->get_item_detail($shopee_item_id, $shop_id);
                        if (isset($item_details['item'])) {
                            update_post_meta($value, 'ced_shopee_item_details', $item_details);
                            if (isset($item_details['has_variation']) && $item_details['has_variation']) {
                                foreach ($item_details['variations'] as $variation_key => $variation) {
                                    $variation_name = isset($variation['name']) ? $variation['name'] : '';
                                    if (in_array($variation_name, $product_variations)) {
                                        $variation_id = array_search($variation_name, $product_variations);
                                        update_post_meta($variation_id, '_ced_shopee_listing_id_' . $shop_id, $variation['variation_id']);
                                    }
                                }
                            }
                        }
                    }
                }
                if (false == $not_ajax) {
                    return $response;
                }
            } else {
                $stock = (int) $this->fetch_meta_value_of_product($value, '_ced_shopee_stock');
                if (''  == $stock) {
                    $stock = get_post_meta($value, '_stock', true);
                }

                $prepared_data_for_uploading            = array();
                $prepared_data_for_uploading['item_id'] = (int) $shopee_item_id;
                $prepared_data_for_uploading['stock']   = (int) $stock;
                $response                               = $this->do_update_stock($prepared_data_for_uploading, 'simple', $shop_id);
                if (isset($response['item']['item_id'])) {
                    $item_id = $response['item']['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                    $item_details = $this->get_item_detail($item_id, $shop_id);
                    if (isset($item_details['item'])) {
                        update_post_meta($value, 'ced_shopee_item_details', $item_details);
                    }
                }
            }
            if (false == $not_ajax) {
                return $response;
            }
        }
    }

    /**
     * Function for gettting product image to be updated
     *
     * @since 1.0.0
     * @param array $pro_ids Product Ids.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function ced_shopee_prepare_data_for_updating_image($pro_ids = array(), $shop_id)
    {
        foreach ($pro_ids as $key => $value) {
            $prod_data = wc_get_product($value);
            $type      = $prod_data->get_type();

            if ('variable' == $type) {
                $item_id       = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
                $prepared_data = $this->get_image_for_update($value, $item_id);
                $this->data    = $prepared_data;

                self::do_update_image($value, $shop_id);

                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                }
            } else {
                $item_id       = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
                $prepared_data = $this->get_image_for_update($value, $item_id);
                $this->data    = $prepared_data;
                self::do_update_image($value, $shop_id);
                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                }
            }
        }
        return $response;
    }

    /**
     * Function for getting price for updating
     *
     * @since 1.0.0
     * @param array $pro_ids Product Ids.
     * @param int   $shop_id Shopee Shop Id.
     * @param bool  $not_ajax Whether ajax or not.
     */
    public function ced_shopee_prepare_data_for_updating_price($pro_ids = array(), $shop_id, $not_ajax = false)
    {
        foreach ($pro_ids as $key => $value) {
            $prod_data = wc_get_product($value);
            if (! is_object($prod_data)) {
                continue;
            }
            $profile_data   = $this->ced_shopee_get_profile_assigned_data($value, $shop_id);
            $type           = $prod_data->get_type();
            $prod_data      = wc_get_product($value);
            $shopee_item_id = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
            if ('variable' == $type) {
                $variations = $prod_data->get_available_variations();
                foreach ($variations as $key => $variation) {
                    $variation_shopee_item_id = (int) get_post_meta($variation['variation_id'], '_ced_shopee_listing_id_' . $shop_id, true);
                    if (empty($variation_shopee_item_id)) {
                        continue;
                    }

                    $price = (float) $this->fetch_meta_value_of_product($variation['variation_id'], '_ced_shopee_price');
                    if (empty($price)) {
                        $price = $variation['display_price'];
                    }

                    $conversion_rate = $this->fetch_meta_value_of_product($value, '_ced_shopee_conversion_rate');
                    $markup_type     = $this->fetch_meta_value_of_product($value, '_ced_shopee_markup_type');
                    if (! empty($markup_type)) {
                        $markup_value = (int) $this->fetch_meta_value_of_product($value, '_ced_shopee_markup_price');
                        if (! empty($markup_value)) {
                            if ('Fixed_Increased' == $markup_type) {
                                $price = $price + $markup_value;
                            } elseif ('Fixed_Decreased' == $markup_type) {
                                $price = $price - $markup_value;
                            } elseif ('Percentage_Increased' == $markup_type) {
                                $price = ($price + (($markup_value / 100) * $price));
                            } elseif ('Percentage_Decreased' == $markup_type) {
                                $price = ($price - (($markup_value / 100) * $price));
                            }
                        }
                    }

                    if (! empty($conversion_rate)) {
                        $price = $price * $conversion_rate;
                    }
                    $prepared_data_for_uploading                 = array();
                    $prepared_data_for_uploading['item_id']      = (int) $shopee_item_id;
                    $prepared_data_for_uploading['variation_id'] = (int) $variation_shopee_item_id;
                    $prepared_data_for_uploading['price']        = (float) $price;

                    $response = $this->do_update_price($prepared_data_for_uploading, 'variation', $shop_id);

                    if (isset($response['item']['item_id'])) {
                        $item_id = $response['item']['item_id'];
                        update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                        $item_details = $this->get_item_detail($shopee_item_id, $shop_id);
                        if (isset($item_details['item'])) {
                            update_post_meta($value, 'ced_shopee_item_details', $item_details);
                            if (isset($item_details['has_variation']) && $item_details['has_variation']) {
                                foreach ($item_details['variations'] as $variation_key => $variation) {
                                    $variation_name = isset($variation['name']) ? $variation['name'] : '';
                                    if (in_array($variation_name, $product_variations)) {
                                        $variation_id = array_search($variation_name, $product_variations);
                                        update_post_meta($variation_id, '_ced_shopee_listing_id_' . $shop_id, $variation['variation_id']);
                                    }
                                }
                            }
                        }
                    }
                }

                if (false == $not_ajax) {
                    return $response;
                }
            } else {
                $price = (float) $this->fetch_meta_value_of_product($value, '_ced_shopee_price');
                if (empty($price)) {
                    $price = get_post_meta($value, '_price', true);
                }

                $conversion_rate = $this->fetch_meta_value_of_product($value, '_ced_shopee_conversion_rate');
                $markup_type     = $this->fetch_meta_value_of_product($value, '_ced_shopee_markup_type');
                if (! empty($markup_type)) {
                    $markup_value = (int) $this->fetch_meta_value_of_product($value, '_ced_shopee_markup_price');
                    if (! empty($markup_value)) {
                        if ('Fixed_Increased' == $markup_type) {
                            $price = $price + $markup_value;
                        } elseif ('Fixed_Decreased' == $markup_type) {
                            $price = $price - $markup_value;
                        } elseif ('Percentage_Increased' == $markup_type) {
                            $price = ($price + (($markup_value / 100) * $price));
                        } elseif ('Percentage_Decreased' == $markup_type) {
                            $price = ($price - (($markup_value / 100) * $price));
                        }
                    }
                }

                if (! empty($conversion_rate)) {
                    $price = $price * $conversion_rate;
                }
                $prepared_data_for_uploading            = array();
                $prepared_data_for_uploading['item_id'] = (int) $shopee_item_id;
                $prepared_data_for_uploading['price']   = (float) $price;
                $response                               = $this->do_update_price($prepared_data_for_uploading, 'simple', $shop_id);
                if (isset($response['item']['item_id'])) {
                    $item_id = $response['item']['item_id'];
                    update_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, $item_id);
                    $item_details = $this->get_item_detail($item_id, $shop_id);
                    if (isset($item_details['item'])) {
                        update_post_meta($value, 'ced_shopee_item_details', $item_details);
                    }
                }
            }
            if (false == $not_ajax) {
                return $response;
            }
        }
    }

    /**
     * Function for deleting product
     *
     * @since 1.0.0
     * @param array $pro_ids Product Ids.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function ced_shopee_prepare_data_for_delete($pro_ids = array(), $shop_id)
    {
        foreach ($pro_ids as $key => $value) {
            $prod_data = wc_get_product($value);
            $type      = $prod_data->get_type();

            if ('variable' == $type) {
                $item_id            = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
                $prepared_data      = $this->get_item_id_for_delete($value, $item_id);
                $this->data         = $prepared_data;
                $product_variations = $this->get_product_variations($value);

                $this->data = $prepared_data;

                self::do_remove($value, $shop_id);

                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    delete_post_meta($value, '_ced_shopee_listing_id_' . $shop_id);
                }
            } else {
                $item_id       = get_post_meta($value, '_ced_shopee_listing_id_' . $shop_id, true);
                $prepared_data = $this->get_item_id_for_delete($value, $item_id);
                $this->data    = $prepared_data;
                self::do_remove($value, $shop_id);
                $response = $this->upload_response;
                if (isset($response['item_id'])) {
                    $item_id = $response['item_id'];
                    delete_post_meta($value, '_ced_shopee_listing_id_' . $shop_id);
                }
            }
        }
        return $response;
    }

    /**
     * Function for preparing  product data
     *
     * @since 1.0.0
     * @param int $pro_ids Product Id.
     * @param int $shop_id Shopee Shop Id.
     * @param int $shopee_item_id Shopee Product Listing Id.
     */
    public function get_formatted_data($pro_ids = '', $shop_id = '', $shopee_item_id = '')
    {
        $profile_data = $this->ced_shopee_get_profile_assigned_data($pro_ids, $shop_id);
        $product      = wc_get_product($pro_ids);

        if (WC()->version > '3.0.0') {
            $product_data = $product->get_data();
            $product_type = $product->get_type();
            $quantity     = (int) $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_stock');
            if ('' == $quantity) {
                $quantity = (int) get_post_meta($pro_ids, '_stock', true);
            }
            $title       = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_title');
            $description = $product_data['description'] . ' ' . $product_data['short_description'];
            if (empty($title)) {
                $title = $product_data['name'];
            }

            $price = (float) $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_price');
            if (empty($price)) {
                $price = (float) $product_data['price'];
            }

            if ('variable' == $product_type) {
                $variations = $product->get_available_variations();
                if (isset($variations['0']['display_regular_price'])) {
                    $price    = (float) $variations['0']['display_regular_price'];
                    $quantity = 1;
                }
            }
        }
        $package_length = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_package_length');
        $package_width  = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_package_width');
        $package_height = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_package_height');
        $weight         = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_weight');

        if (empty($weight)) {
            $weight = get_post_meta($pro_ids, '_weight', true);
        }
        if (empty($package_length)) {
            $package_length = get_post_meta($pro_ids, '_length', true);
        }
        if (empty($package_width)) {
            $package_width = get_post_meta($pro_ids, '_width', true);
        }
        if (empty($package_height)) {
            $package_height = get_post_meta($pro_ids, '_height', true);
        }

        $condition      = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_condition');
        $days_to_ship   = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_days_to_ship');
        $category_id    = $this->fetch_meta_value_of_product($pro_ids, '_umb_shopee_category');
        $preorder       = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_product_type');
        $attributes     = get_option('ced_shopee_category_attributes_' . $category_id, true);
        $pro_attributes = array();
        if (isset($attributes) && is_array($attributes) && ! empty($attributes)) {
            foreach ($attributes as $attribute_key => $attribute_value) {
                if ($this->fetch_meta_value_of_product($pro_ids, $category_id . '_' . $attribute_value['attribute_id']) != '') {
                    $pro_attributes[ $attribute_key ]['attributes_id'] = $attribute_value['attribute_id'];
                    $pro_attributes[ $attribute_key ]['value']         = $this->fetch_meta_value_of_product($pro_ids, $category_id . '_' . $attribute_value['attribute_id']);
                }
            }
        }

        $pro_attributes = array_values($pro_attributes);
        $product        = wc_get_product($pro_ids);
        if ($product->get_type() == 'variable') {
            $variations = $product->get_available_variations();
            if ($variations) {
                if (0 == $weight || '' == $weight) {
                    $weight = $variations[0]['weight'];
                }

                if (0 == $package_width || '' == $package_width) {
                    $package_width = $variations[0]['dimensions']['width'];
                }
                if (0 == $package_height || '' == $package_height) {
                    $package_height = $variations[0]['dimensions']['height'];
                }
                if (0 == $package_length || '' == $package_length) {
                    $package_length = $variations[0]['dimensions']['length'];
                }
            }
        }
        $conversion_rate = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_conversion_rate');
        $markup_type     = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_markup_type');

        if (! empty($markup_type)) {
            $markup_value = (int) $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_markup_price');
            if (! empty($markup_value)) {
                if ('Fixed_Increased' == $markup_type) {
                    $price = $price + $markup_value;
                } elseif ('Fixed_Decreased' == $markup_type) {
                    $price = $price - $markup_value;
                } elseif ('Percentage_Increased' == $markup_type) {
                    $price = ($price + (($markup_value / 100) * $price));
                } elseif ('Percentage_Decreased' == $markup_type) {
                    $price = ($price - (($markup_value / 100) * $price));
                }
            }
        }

        if (! empty($conversion_rate)) {
            $price = $price * $conversion_rate;
        }

        $render_data_global_settings = get_option('ced_shopee_global_settings', false);
        $weight_unit = $render_data_global_settings[ $shop_id ]['ced_shopee_package_weight_unit'];

        if (!empty($weight_unit) && !empty($weight)) {
            if ($weight_unit == 'gm') {
                $weight = ($weight/1000);
            } elseif ($weight_unit == 'lbs') {
                $weight = ($weight/2.205);
            }
        }

        $args = array(

            'category_id'    => (int) $category_id,
            'stock'          => (int) $quantity,
            'name'           => $title,
            'description'    => strip_tags($description),
            'price'          => (float) $price,
            'weight'         => (float) $weight,
            'package_height' => (int) $package_height,
            'package_length' => (int) $package_length,
            'package_width'  => (int) $package_width,
            'condition'      => isset($condition) ? strtoupper($condition) : 'NEW',
            'attributes'     => $pro_attributes,
        );

        $item_sku = get_post_meta($pro_ids, '_sku', true);
        if (! empty($item_sku)) {
            $args['item_sku'] = $item_sku;
        }
        if (! empty($shopee_item_id)) {
            $args['item_id'] = (int) $shopee_item_id;
        }
        if (isset($preorder) && 'yes' == strtolower($preorder)) {
            $args['days_to_ship'] = (int) $days_to_ship;
            $args['is_pre_order'] = (bool) true;
        }

        $logistics = get_option('ced_shopee_logistic_data', array());

        $saved_logistic = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_logistics');

        if (! empty($saved_logistic)) {
            if (! is_array($saved_logistic)) {
                $saved_logistic = array( $saved_logistic );
            }
            foreach ($saved_logistic as $key => $value) {
                $args['logistics'][] = array(
                    'logistic_id' => (int) $value,
                    'enabled'     => true,
                    'size_id'     => 1,
                );
            }
        }

        if (empty($shopee_item_id)) {
            $picture_url = wp_get_attachment_image_url(get_post_meta($pro_ids, '_thumbnail_id', true), 'full') ? wp_get_attachment_image_url(get_post_meta($pro_ids, '_thumbnail_id', true), 'full') : '';

            $args['images'][] = array( 'url' => $picture_url );

            $attachment_ids = $product->get_gallery_image_ids();
            if (! empty($attachment_ids)) {
                foreach ($attachment_ids as $attachment_id) {
                    if (empty(wp_get_attachment_url($attachment_id))) {
                        continue;
                    }
                    $args['images'][] = array( 'url' => wp_get_attachment_url($attachment_id) );
                }
            }
        }

        
        return $args;
    }


    /**
     * Function for getting profile data of the product
     *
     * @since 1.0.0
     * @param int $pro_ids Product Id.
     * @param int $shop_id Shopee Shop Id.
     */
    public function ced_shopee_get_profile_assigned_data($pro_ids, $shop_id)
    {
        global $wpdb;
        $product_data = wc_get_product($pro_ids);
        $product      = $product_data->get_data();
        $category_id  = isset($product['category_ids']) ? $product['category_ids'] : array();
        $profile_id   = get_post_meta($pro_ids, 'ced_shopee_profile_assigned' . $shop_id, true);
        if (! empty($profile_id)) {
            $profile_id = $profile_id;
        } else {
            foreach ($category_id as $key => $value) {
                $profile_id = get_term_meta($value, 'ced_shopee_profile_id_' . $shop_id, true);

                if (! empty($profile_id)) {
                    break;
                }
            }
        }

        if (isset($profile_id) && ! empty($profile_id)) {
            $this->is_profile_assigned_to_product = true;
            $profile_data                         = $wpdb->get_results($wpdb->prepare('SELECT * FROM wp_ced_shopee_profiles WHERE `id` = %d ', $profile_id), 'ARRAY_A');
            if (is_array($profile_data)) {
                $profile_data = isset($profile_data[0]) ? $profile_data[0] : $profile_data;
                $profile_data = isset($profile_data['profile_data']) ? json_decode($profile_data['profile_data'], true) : array();
            }
        } else {
            $this->is_profile_assigned_to_product = false;
        }
        $this->profile_data = isset($profile_data) ? $profile_data : '';
    }

    /**
     * Function for getting meta value of the product
     *
     * @since 1.0.0
     * @param int    $pro_ids Product Id.
     * @param string $meta_key Metakey used in profile section.
     */
    public function fetch_meta_value_of_product($pro_ids, $meta_key)
    {
        if (isset($this->is_profile_assigned_to_product) && $this->is_profile_assigned_to_product) {
            $_product = wc_get_product($pro_ids);
            if ($_product->get_type() == 'variation') {
                $parent_id = $_product->get_parent_id();
            } else {
                $parent_id = '0';
            }

            if (! empty($this->profile_data) && isset($this->profile_data[ $meta_key ])) {
                $profile_data      = $this->profile_data[ $meta_key ];
                $temp_profile_data = $profile_data;
                if (isset($temp_profile_data['default']) && ! empty($temp_profile_data['default']) && ! is_null($temp_profile_data['default'])) {
                    $value = $temp_profile_data['default'];
                } elseif (isset($temp_profile_data['metakey']) && ! empty($temp_profile_data['metakey']) && 'null' != $temp_profile_data['metakey']) {
                    if (strpos($temp_profile_data['metakey'], 'umb_pattr_') !== false) {
                        $woo_attribute = explode('umb_pattr_', $temp_profile_data['metakey']);
                        $woo_attribute = end($woo_attribute);

                        if ($_product->get_type() == 'variation') {
                            $var_product = wc_get_product($parent_id);
                            $attributes  = $var_product->get_variation_attributes();
                            if (isset($attributes[ 'attribute_pa_' . $woo_attribute ]) && ! empty($attributes[ 'attribute_pa_' . $woo_attribute ])) {
                                $woo_attribute_value = $attributes[ 'attribute_pa_' . $woo_attribute ];
                                if ('0' != $parent_id) {
                                    $product_terms = get_the_terms($parent_id, 'pa_' . $woo_attribute);
                                } else {
                                    $product_terms = get_the_terms($pro_ids, 'pa_' . $woo_attribute);
                                }
                            } else {
                                $woo_attribute_value = $var_product->get_attribute('pa_' . $woo_attribute);
                                $woo_attribute_value = explode(',', $woo_attribute_value);
                                $woo_attribute_value = $woo_attribute_value[0];

                                if ('0' != $parent_id) {
                                    $product_terms = get_the_terms($parent_id, 'pa_' . $woo_attribute);
                                } else {
                                    $product_terms = get_the_terms($pro_ids, 'pa_' . $woo_attribute);
                                }
                            }
                            if (is_array($product_terms) && ! empty($product_terms)) {
                                foreach ($product_terms as $tempkey => $tempvalue) {
                                    if ($tempvalue->slug == $woo_attribute_value) {
                                        $woo_attribute_value = $tempvalue->name;
                                        break;
                                    }
                                }
                                if (isset($woo_attribute_value) && ! empty($woo_attribute_value)) {
                                    $value = $woo_attribute_value;
                                } else {
                                    $value = get_post_meta($pro_ids, $meta_key, true);
                                }
                            } else {
                                $value = get_post_meta($pro_ids, $meta_key, true);
                            }
                        } else {
                            $woo_attribute_value = $_product->get_attribute('pa_' . $woo_attribute);
                            $product_terms       = get_the_terms($pro_ids, 'pa_' . $woo_attribute);
                            if (is_array($product_terms) && ! empty($product_terms)) {
                                foreach ($product_terms as $tempkey => $tempvalue) {
                                    if ($tempvalue->slug == $woo_attribute_value) {
                                        $woo_attribute_value = $tempvalue->name;
                                        break;
                                    }
                                }
                                if (isset($woo_attribute_value) && ! empty($woo_attribute_value)) {
                                    $value = $woo_attribute_value;
                                } else {
                                    $value = get_post_meta($pro_ids, $meta_key, true);
                                }
                            } else {
                                $value = get_post_meta($pro_ids, $meta_key, true);
                            }
                        }
                    } else {
                        $value = get_post_meta($pro_ids, $temp_profile_data['metakey'], true);
                        if ('_thumbnail_id' == $temp_profile_data['metakey']) {
                            $value = wp_get_attachment_image_url(get_post_meta($pro_ids, '_thumbnail_id', true), 'thumbnail') ? wp_get_attachment_image_url(get_post_meta($pro_ids, '_thumbnail_id', true), 'thumbnail') : '';
                        }
                        if (! isset($value) || empty($value) || is_null($value) || '0' == $value || 'null' == $value) {
                            if ('0' == $parent_id) {
                                $value = get_post_meta($parent_id, $temp_profile_data['metakey'], true);
                                if ('_thumbnail_id' == $temp_profile_data['metakey']) {
                                    $value = wp_get_attachment_image_url(get_post_meta($parent_id, '_thumbnail_id', true), 'thumbnail') ? wp_get_attachment_image_url(get_post_meta($parent_id, '_thumbnail_id', true), 'thumbnail') : '';
                                }

                                if (! isset($value) || empty($value) || is_null($value)) {
                                    $value = get_post_meta($pro_ids, $meta_key, true);
                                }
                            } else {
                                $value = get_post_meta($pro_ids, $meta_key, true);
                            }
                        }
                    }
                } else {
                    $value = get_post_meta($pro_ids, $meta_key, true);
                }
            } else {
                $value = get_post_meta($pro_ids, $meta_key, true);
            }
            return $value;
        }
    }

    /**
     * Get Image to be updated
     *
     * @since 1.0.0
     * @param int $pro_ids Product Id.
     * @param int $shopee_item_id Shopee Product Listing Id.
     */
    public function get_image_for_update($pro_ids = array(), $shopee_item_id = '')
    {
        $prepared_data_for_uploading = array();
        $product_data                = wc_get_product($pro_ids);
        $product_type                = $product_data->get_type();
        $product                     = $product_data->get_data();

        if (! empty($shopee_item_id)) {
            $prepared_data_for_updating['item_id'] = (int) $shopee_item_id;
        }

        $picture_url = wp_get_attachment_image_url(get_post_meta($pro_ids, '_thumbnail_id', true), 'full') ? wp_get_attachment_image_url(get_post_meta($pro_ids, '_thumbnail_id', true), 'full') : '';

        $prepared_data_for_updating['images'][] = $picture_url;
        $attachment_ids                         = $product['gallery_image_ids'];

        if (! empty($attachment_ids)) {
            foreach ($attachment_ids as $attachment_id) {
                $prepared_data_for_updating['images'][] = wp_get_attachment_url($attachment_id);
            }
        }

        return $prepared_data_for_updating;
    }

    /**
     * Get item id of the product to be deleted
     *
     * @since 1.0.0
     * @param int $pro_ids Product Id.
     * @param int $shopee_item_id Shopee Product listing Id.
     */
    public function get_item_id_for_delete($pro_ids = array(), $shopee_item_id = '')
    {
        $prepared_data_for_uploading = array();
        $product_data                = wc_get_product($pro_ids);
        $product_type                = $product_data->get_type();
        $product                     = $product_data->get_data();

        if (! empty($shopee_item_id)) {
            $prepared_data_for_uploading['item_id'] = (int) $shopee_item_id;
        }

        if ('variable' == $product_type) {
            $variations = $product_data->get_available_variations();
        }

        return $prepared_data_for_uploading;
    }

    /**
     * Get Variations of a variable product
     *
     * @since 1.0.0
     * @param int $pro_ids Product Id.
     */
    public function get_product_variations($pro_ids)
    {
        $parent_sku = get_post_meta($pro_ids, '_sku', true);
        $_product   = wc_get_product($pro_ids);

        $variations = $_product->get_available_variations();
        foreach ($variations as $key => $variation) {
            if ($parent_sku == $variation['sku'] || '' == $variation['sku']) {
                $variation_sku = (string) $variation['variation_id'];
            } else {
                $variation_sku = $variation['sku'];
            }

            $pro_variations[ $variation['variation_id'] ] = $variation_sku;
        }

        return $pro_variations;
    }

    /**
     * Get Variation data of a variable product
     *
     * @since 1.0.0
     * @param int    $pro_ids Product ID.
     * @param int    $shop_id Shopee Shop Id.
     * @param int    $shopee_item_id Shopee Product Listing Id.
     * @param string $isupdate Whether the product is updating or not.
     */
    public function get_formatted_data_for_variation($pro_ids, $shop_id = '', $shopee_item_id = '', $isupdate = '', $update_list = '', $update_img = '')
    {
        $parent_sku         = get_post_meta($pro_ids, '_sku', true);
        $_product           = wc_get_product($pro_ids);
        $variations         = $_product->get_available_variations();
        $product_variations = $this->get_product_variations($pro_ids);
        $profile_data       = $this->ced_shopee_get_profile_assigned_data($pro_ids, $shop_id);
        if ('yes' == $isupdate) {
            $pro_variations            = array();
            $pro_variations['item_id'] = (int) $shopee_item_id;
            $product                   = wc_get_product($pro_ids);
            $variations                = $product->get_available_variations();
            $attribute                 = $product->get_variation_attributes();
            $attribule_array           = array();
            foreach ($attribute as $name => $value) {
                $attribule_array['name']    = wc_attribute_label($name);
                $attribule_array['options'] = array_values($value);

                $proVariationstier['tier_variation'][] = $attribule_array;
            }
            foreach ($variations as $key => $variation) {
                if ($parent_sku == $variation['sku'] || '' == $variation['sku']) {
                    $variation_sku = (string) $variation['variation_id'];
                } else {
                    $variation_sku = $variation['sku'];
                }
                $variation_array     = array();
                $tier_index          = array();
                $variation_id        = wc_get_product($variation['variation_id']);
                $variation_attribute = $variation_id->get_variation_attributes();
                $count               = 0;
                foreach ($variation_attribute as $value) {
                    if (0 == $count) {
                        $picture_variations_url    = wp_get_attachment_image_url(get_post_meta($variation['variation_id'], '_thumbnail_id', true), 'full') ? wp_get_attachment_image_url(get_post_meta($variation['variation_id'], '_thumbnail_id', true), 'full') : '';
                        $image_variation[ $value ] = $picture_variations_url;
                    }
                    $count++;
                }
                foreach ($variation['attributes'] as $attri_value) {
                    foreach ($proVariationstier['tier_variation'] as $tier_key => $tier_value) {
                        foreach ($tier_value['options'] as $k => $v) {
                            if ($attri_value == $v) {
                                $tier_index[] = $k;
                            }
                        }
                    }
                }
                $price           = $variation['display_price'];
                $conversion_rate = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_conversion_rate');
                $markup_type     = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_markup_type');
                if ('' != $markup_type && ! empty($markup_type)) {
                    $markup_value = (int) $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_markup_price');
                    if ('' != $markup_value && ! empty($markup_value)) {
                        if ('Fixed_Increased' == $markup_type) {
                            $price = $price + $markup_value;
                        } elseif ('Fixed_Decreased' == $markup_type) {
                            $price = $price - $markup_value;
                        } elseif ('Percentage_Increased' == $markup_type) {
                            $price = ($price + (($markup_value / 100) * $price));
                        } elseif ('Percentage_Decreased' == $markup_type) {
                            $price = ($price - (($markup_value / 100) * $price));
                        }
                    }
                }

                if (! empty($conversion_rate)) {
                    $price = $price * $conversion_rate;
                }
                $variation_array['tier_index']    = $tier_index;
                $variation_array['stock']         = $variation['max_qty'];
                $variation_array['price']         = (float) $price;
                $variation_array['variation_sku'] = $variation_sku;
                $pro_variations['variation'][]    = $variation_array;
            }
            if (! empty($image_variation)) {
                $proVariationstier['item_id'] = (int) $shopee_item_id;
                $options                      = $proVariationstier['tier_variation'][0]['options'];
                $structured_img               = array();
                foreach ($options as $k => $v) {
                    if (isset($image_variation[ $v ])) {
                        $structured_img[] = $image_variation[ $v ];
                    }
                }
                $proVariationstier['tier_variation'][0]['images_url'] = array_values($structured_img);
            }

            foreach ($pro_variations['variation'] as $index => $data) {
                if (in_array($data['variation_sku'], $product_variations)) {
                    $variation_id = array_search($data['variation_sku'], $product_variations);
                    $listingId    = get_post_meta($variation_id, '_ced_shopee_listing_id_' . $shop_id, true);
                    if ($listingId) {
                        unset($pro_variations['variation'][ $index ]);
                    }
                }
            }
            if ('update_list' == $update_list) {
                $proVariationstList['item_id']        = (int) $shopee_item_id;
                $proVariationstList['tier_variation'] = $proVariationstier['tier_variation'];

                return $proVariationstList;
            }

            $pro_variations['variation'] = array_values($pro_variations['variation']);

            if (! empty($update_img)) {
                return $proVariationstier;
            } else {
                return $pro_variations;
            }
        } else {
            $pro_variations            = array();
            $pro_variations['item_id'] = (int) $shopee_item_id;
            $product                   = wc_get_product($pro_ids);
            $variations                = $product->get_available_variations();
            $attribute                 = $product->get_variation_attributes();
            $attribule_array           = array();
            foreach ($attribute as $name => $value) {
                $attribule_array['name']            = wc_attribute_label($name);
                $attribule_array['options']         = array_values($value);
                $pro_variations['tier_variation'][] = $attribule_array;
            }

            foreach ($variations as $index => $variation) {
                $variation_max_qty = (int) $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_stock');
                if ('' == $variation_max_qty) {
                    $variation_max_qty = get_post_meta($variation['variation_id'], '_stock', true);
                }
                if ($variation_max_qty <= 0) {
                    continue;
                }
                if ($parent_sku == $variation['sku'] || '' == $variation['sku']) {
                    $variation_sku = (string) $variation['variation_id'];
                } else {
                    $variation_sku = $variation['sku'];
                }
                $variation_array     = array();
                $tier_index          = array();
                $variation_id        = wc_get_product($variation['variation_id']);
                $variation_attribute = $variation_id->get_variation_attributes();
                $count               = 0;
                foreach ($variation_attribute as $var_value => $value) {
                    if (0 == $count) {
                        $picture_variations_url    = wp_get_attachment_image_url(get_post_meta($variation['variation_id'], '_thumbnail_id', true), 'full') ? wp_get_attachment_image_url(get_post_meta($variation['variation_id'], '_thumbnail_id', true), 'full') : '';
                        $image_variation[ $value ] = $picture_variations_url;
                    }
                    $count++;
                }
                foreach ($variation['attributes'] as $attri_value) {
                    foreach ($pro_variations['tier_variation'] as $tier_key => $tier_value) {
                        foreach ($tier_value['options'] as $k => $v) {
                            if ($attri_value == $v) {
                                $tier_index[] = $k;
                            }
                        }
                    }
                }

                $price = (float) $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_price');
                if (empty($price)) {
                    $price = $variation['display_price'];
                }

                $conversion_rate = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_conversion_rate');
                $markup_type     = $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_markup_type');
                if (! empty($markup_type)) {
                    $markup_value = (int) $this->fetch_meta_value_of_product($pro_ids, '_ced_shopee_markup_price');
                    if (! empty($markup_value)) {
                        if ('Fixed_Increased' == $markup_type) {
                            $price = $price + $markup_value;
                        } elseif ('Fixed_Decreased' == $markup_type) {
                            $price = $price - $markup_value;
                        } elseif ('Percentage_Increased' == $markup_type) {
                            $price = ($price + (($markup_value / 100) * $price));
                        } elseif ('Percentage_Decreased' == $markup_type) {
                            $price = ($price - (($markup_value / 100) * $price));
                        }
                    }
                }

                if (! empty($conversion_rate)) {
                    $price = $price * $conversion_rate;
                }
                $variation_array['tier_index']    = $tier_index;
                $variation_array['stock']         = (int) $variation_max_qty;
                $variation_array['price']         = (float) $price;
                $variation_array['variation_sku'] = $variation_sku;
                $pro_variations['variation'][]    = $variation_array;
            }
            if (! empty($image_variation)) {
                $pro_variations['item_id'] = (int) $shopee_item_id;
                $options                   = $pro_variations['tier_variation'][0]['options'];
                $structured_img            = array();
                foreach ($options as $k => $v) {
                    if (isset($image_variation[ $v ])) {
                        $structured_img[] = $image_variation[ $v ];
                    }
                }
                $pro_variations['tier_variation'][0]['images_url'] = array_values($structured_img);
            }

            return $pro_variations;
        }
    }

    /**
     * Uploading products to shopee
     *
     * @since 1.0.0
     * @param int $product_id Product ID.
     * @param int $shop_id Shopee Shop Id.
     */
    public function doupload($product_id, $shop_id)
    {
        $response              = $this->upload_to_shopee($this->data, $shop_id);
        $this->upload_response = $response;
    }

    /**
     * Updating products on shopee
     *
     * @since 1.0.0
     * @param int $product_id Product ID.
     * @param int $shop_id Shopee Shop Id.
     */
    public function doupdate($product_id, $shop_id)
    {
        $response              = $this->update_on_shopee($this->data, $shop_id);
        $this->upload_response = $response;
    }

    /**
     * Updating product stock on shopee
     *
     * @since 1.0.0
     * @param array  $parameters Parameters required on shopee.
     * @param string $product_type Product Type.
     * @param int    $shop_id Shopee Shop Id.
     */
    public function do_update_stock($parameters, $product_type = 'simple', $shop_id)
    {
        $response = $this->update_stock($parameters, $product_type, $shop_id);
        return $response;
    }

    /**
     * Updating product price on shopee
     *
     * @since 1.0.0
     * @param array  $parameters Parameters required on shopee.
     * @param string $product_type Product Type.
     * @param int    $shop_id Shopee Shop Id.
     */
    public function do_update_price($parameters, $product_type = 'simple', $shop_id)
    {
        $response = $this->update_price($parameters, $product_type, $shop_id);
        return $response;
    }

    /**
     * Updating product images on shopee
     *
     * @since 1.0.0
     * @param int $product_id Product ID.
     * @param int $shop_id Shopee Shop Id.
     */
    public function do_update_image($product_id, $shop_id)
    {
        $response              = $this->update_image($this->data, $shop_id);
        $this->upload_response = $response;
    }

    /**
     * Removing product from shopee
     *
     * @since 1.0.0
     * @param int $product_id Product ID.
     * @param int $shop_id Shopee Shop Id.
     */
    public function do_remove($product_id, $shop_id)
    {
        $response              = $this->delete_from_shopee($this->data, $shop_id);
        $this->upload_response = $response;
    }

    /**
     * Uploading product to shopee
     *
     * @since 1.0.0
     * @param array $parameters Parameters required on Shopee.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function upload_to_shopee($parameters, $shop_id)
    {
        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action           = 'item/add';
        $parameters       = $parameters;
        $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        return $response;
    }

    /**
     * Updating product on shopee
     *
     * @since 1.0.0
     * @param array $parameters Parameters required on Shopee.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function update_on_shopee($parameters, $shop_id)
    {
        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action           = 'item/update';
        $parameters       = $parameters;
        $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        return $response;
    }

    /**
     * Updating product stock on shopee
     *
     * @since 1.0.0
     * @param array  $parameters Parameters required on Shopee.
     * @param string $product_type Product Type.
     * @param int    $shop_id Shopee Shop Id.
     */
    public function update_stock($parameters, $product_type, $shop_id)
    {
        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        if ('simple' == $product_type) {
            $action           = 'items/update_stock';
            $parameters       = $parameters;
            $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();
            $response         = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        } elseif ('variation' == $product_type) {
            $action           = 'items/update_variation_stock';
            $parameters       = $parameters;
            $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();
            $response         = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        }
        return $response;
    }

    /**
     * Updating product price on shopee
     *
     * @since 1.0.0
     * @param array  $parameters Parameters required on Shopee.
     * @param string $product_type Product Type.
     * @param int    $shop_id Shopee Shop Id.
     */
    public function update_price($parameters, $product_type, $shop_id)
    {
        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        if ('simple' == $product_type) {
            $action           = 'items/update_price';
            $parameters       = $parameters;
            $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();
            $response         = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        } elseif ('variation' == $product_type) {
            $action           = 'items/update_variation_price';
            $parameters       = $parameters;
            $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();
            $response         = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        }
        return $response;
    }

    /**
     * Updating product images on shopee
     *
     * @since 1.0.0
     * @param array $parameters Parameters required on Shopee.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function update_image($parameters, $shop_id)
    {
        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action           = 'item/img/update';
        $parameters       = $parameters;
        $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        return $response;
    }


    /**
     * Removing product from  shopee
     *
     * @since 1.0.0
     * @param array $parameters Parameters required on Shopee.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function delete_from_shopee($parameters, $shop_id)
    {
        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action           = 'item/delete';
        $parameters       = $parameters;
        $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        return $response;
    }

    /**
     * Adding variations of a variable product on shopee
     *
     * @since 1.0.0
     * @param array $data Product data.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function doaddvariation($data, $shop_id)
    {
        $response              = $this->add_variation_to_shopee($data, $shop_id);
        $this->upload_response = $response;
        return $this->upload_response;
    }

    /**
     * Adding variations of a variable product on shopee
     *
     * @since 1.0.0
     * @param array $parameters Parameters required on Shopee.
     * @param int   $shop_id Shopee Shop Id.
     */
    public function add_variation_to_shopee($parameters, $shop_id)
    {
        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action           = 'item/tier_var/init';
        $parameters       = $parameters;
        $send_request_obj = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        return $response;
    }

    /**
     * Getting item details from shopee
     *
     * @since 1.0.0
     * @param int $item_id Shopee Product Listing Id.
     * @param int $shop_id Shopee Shop Id.
     */
    public function get_item_detail($item_id = '', $shop_id)
    {
        if (empty($item_id)) {
            return;
        }

        include_once CED_SHOPEE_DIRPATH . 'admin/shopee/lib/class-class-ced-shopee-send-http-request.php';
        $action                = 'item/get';
        $parameters['item_id'] = $item_id;
        $send_request_obj      = new Class_Ced_Shopee_Send_Http_Request();

        $response = $send_request_obj->send_http_request($action, $parameters, $shop_id);
        return $response;
    }
}
