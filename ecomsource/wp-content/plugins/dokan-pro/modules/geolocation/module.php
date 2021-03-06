<?php

namespace WeDevs\DokanPro\Modules\Geolocation;

class Module {

    /**
     * Module version
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $version = null;

    /**
     * Checks admin has set google map api key
     *
     * @since 1.0.0
     *
     * @var bool
     */
    public $has_map_api_key = false;

    /**
     * Class constructor
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : DOKAN_PRO_PLUGIN_VERSION;

        $dokan_appearance = get_option( 'dokan_appearance', array() );

        if ( ! empty( $dokan_appearance['gmap_api_key'] ) && 'google_maps' === $dokan_appearance['map_api_source'] ) {
            $this->has_map_api_key = true;
        } elseif ( ! empty( $dokan_appearance['mapbox_access_token'] ) && 'mapbox' === $dokan_appearance['map_api_source'] ) {
            $this->has_map_api_key = true;
            add_action( 'wp_footer', array( $this, 'render_mapbox_script' ), 30 );
        }

        $this->define_constants();
        $this->includes();
        $this->hooks();
        $this->instances();

        add_action( 'dokan_activated_module_geolocation', array( $this, 'activate' ) );
    }

    /**
     * Module constants
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function define_constants() {
        define( 'DOKAN_GEOLOCATION_VERSION', $this->version );
        define( 'DOKAN_GEOLOCATION_PATH', dirname( __FILE__ ) );
        define( 'DOKAN_GEOLOCATION_URL', plugins_url( '', __FILE__ ) );
        define( 'DOKAN_GEOLOCATION_ASSETS', DOKAN_GEOLOCATION_URL . '/assets' );
        define( 'DOKAN_GEOLOCATION_VIEWS', DOKAN_GEOLOCATION_PATH . '/views' );
    }

    /**
     * Add action and filter hooks
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function hooks() {
        if ( $this->has_map_api_key ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'widgets_init', array( $this, 'register_widget' ) );
            add_action( 'dokan_new_seller_created', array( $this, 'set_default_geolocation_data' ), 35 );
        } else {
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        }
    }

    /**
     * Include module related files
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function includes() {
        require_once DOKAN_GEOLOCATION_PATH . '/functions.php';
        require_once DOKAN_GEOLOCATION_PATH . '/class-geolocation-admin-settings.php';

        if ( $this->has_map_api_key ) {
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-scripts.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-shortcode.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-widget-filters.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-widget-product-location.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-vendor-dashboard.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-vendor-query.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-vendor-view.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-product-query.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-product-view.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-product-single.php';
            require_once DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-product-import.php';
        }
    }

    /**
     * Create module related class instances
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function instances() {
        new \Dokan_Geolocation_Admin_Settings();

        if ( $this->has_map_api_key ) {
            new \Dokan_Geolocation_Scripts();
            new \Dokan_Geolocation_Shortcode();
            new \Dokan_Geolocation_Vendor_Dashboard();
            new \Dokan_Geolocation_Vendor_Query();
            new \Dokan_Geolocation_Vendor_View();
            new \Dokan_Geolocation_Product_Query();
            new \Dokan_Geolocation_Product_View();
            new \Dokan_Geolocation_Product_Single();
            new \Dokan_Geolocation_Product_Import();
        }
    }

    /**
     * Run upon module activation
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function activate() {
        // return if dokan plugin is not active
        if ( ! function_exists( 'dokan' ) ) {
            return;
        }
        $updater_file = DOKAN_GEOLOCATION_PATH . '/class-dokan-geolocation-update-location-data.php';

        include_once $updater_file;
        $processor = new \Dokan_Geolocation_Update_Location_Data();

        $processor->cancel_process();

        $item = array(
            'updating' => 'vendors',
            'paged'    => 1,
        );

        $processor->push_to_queue( $item );
        $processor->save()->dispatch();

        $processes = get_option( 'dokan_background_processes', array() );
        $processes['Dokan_Geolocation_Update_Location_Data'] = $updater_file;

        update_option( 'dokan_background_processes', $processes, 'no' );
    }

    /**
     * Enqueue module scripts
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Use minified libraries if SCRIPT_DEBUG is turned off
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        wp_enqueue_style( 'dokan-geolocation', DOKAN_GEOLOCATION_ASSETS . '/css/geolocation' . $suffix . '.css', array( 'dokan-magnific-popup' ), $this->version );

        $js = DOKAN_GEOLOCATION_ASSETS . '/js/geolocation-vendor-dashboard-product-google-maps' . $suffix . '.js';

        $source = dokan_get_option( 'map_api_source', 'dokan_appearance', 'google_maps' );

        if ( 'mapbox' === $source ) {
            $js = DOKAN_GEOLOCATION_ASSETS . '/js/geolocation-vendor-dashboard-product-mapbox' . $suffix . '.js';
        }

        wp_enqueue_script( 'dokan-geolocation', $js, array( 'jquery', 'dokan-maps' ), $this->version, true );
    }

    /**
     * Register module widgets
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function register_widget() {
        register_widget( 'Dokan_Geolocation_Widget_Filters' );
        register_widget( 'Dokan_Geolocation_Widget_Product_Location' );
    }

    /**
     * Show admin notices
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function admin_notices() {
        dokan_geo_get_template( 'admin-notices' );
    }

    /**
     * Show mapbox some extra scripts only for RTL
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_mapbox_script() {
        if ( is_rtl() ) {
            ?>
            <style type="text/css">
                .mapboxgl-map {
                    text-align: inherit;
                }
            </style>
            <?php
        }
    }

    /**
     * Geolocation data add when new seller
     *
     * @since 3.3.0
     *
     * @param $user_id
     */
    public function set_default_geolocation_data( $user_id ) {
        $default_locations = dokan_get_option( 'location', 'dokan_geolocation' );

        if ( ! is_array( $default_locations ) || empty( $default_locations ) ) {
            $default_locations = array(
                'latitude'  => '',
                'longitude' => '',
                'address'   => '',
            );
        }

        update_user_meta( $user_id, 'dokan_geo_latitude', $default_locations['latitude'] );
        update_user_meta( $user_id, 'dokan_geo_longitude', $default_locations['longitude'] );
        update_user_meta( $user_id, 'dokan_geo_public', 1 );
        update_user_meta( $user_id, 'dokan_geo_address', $default_locations['address'] );

        $dokan_settings   = get_user_meta( $user_id, 'dokan_profile_settings', true );
        $default_location = '';

        if ( ! empty( $default_locations['latitude'] ) && ! empty( $default_locations['longitude'] ) ) {
            $default_location = $default_locations['latitude'] . ',' . $default_locations['longitude'];
        }

        $dokan_settings['location']     = $default_location;
        $dokan_settings['find_address'] = $default_locations['address'];

        update_user_meta( $user_id, 'dokan_profile_settings', $dokan_settings );
    }
}
