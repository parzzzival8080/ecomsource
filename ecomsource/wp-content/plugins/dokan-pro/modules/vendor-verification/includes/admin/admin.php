<?php

add_filter( 'dokan_settings_sections', 'dokan_verification_admin_settings' );

function dokan_verification_admin_settings( $sections ) {
    $sections[] = [
        'id'    => 'dokan_verification',
        'title' => __( 'Seller Verification', 'dokan' ),
        'icon'  => 'dashicons-unlock',
    ];
    $sections[] = [
        'id'    => 'dokan_verification_sms_gateways',
        'title' => __( 'Verification SMS Gateways', 'dokan' ),
        'icon'  => 'dashicons-email',
    ];

    return $sections;
}

add_filter( 'dokan_settings_fields', 'dokan_verification_admin_settings_fields' );

function dokan_verification_admin_settings_fields( $settings_fields ) {
    $callback = dokan_get_navigation_url( 'settings/verification' );

    $settings_fields['dokan_verification'] = [
        'facebook_app_details' => [
            'name'  => 'facebook_app_details',
            'label' => __( 'Facebook', 'dokan' ),
            'type'  => 'sub_section',
        ],
        'facebook_app_label'   => [
            'name'  => 'fb_app_label',
            'label' => __( 'Facebook App Settings', 'dokan' ),
            'type'  => 'html',
            'desc'  => '<a target="_blank" href="https://developers.facebook.com/apps/">' . __( 'Create an App', 'dokan' ) . '</a> if you don\'t have one and fill App ID and Secret below.',
        ],
        'facebook_app_url'     => [
            'name'  => 'fb_app_url',
            'label' => __( 'Site Url', 'dokan' ),
            'type'  => 'html',
            'desc'  => "<input class='regular-text' type='text' disabled value='{$callback}'>",
        ],
        'facebook_app_id'      => [
            'name'  => 'fb_app_id',
            'label' => __( 'App Id', 'dokan' ),
            'type'  => 'text',
        ],
        'facebook_app_secret'  => [
            'name'  => 'fb_app_secret',
            'label' => __( 'App Secret', 'dokan' ),
            'type'  => 'text',
        ],
        'twitter_app_details'  => [
            'name'  => 'twitter_app_details',
            'label' => __( 'Twitter', 'dokan' ),
            'type'  => 'sub_section',
        ],
        'twitter_app_label'    => [
            'name'  => 'twitter_app_label',
            'label' => __( 'Twitter App Settings', 'dokan' ),
            'type'  => 'html',
            'desc'  => '<a target="_blank" href="https://apps.twitter.com/">' . __( 'Create an App', 'dokan' ) . '</a> if you don\'t have one and fill Consumer key and Secret below.',
        ],
        'twitter_app_url'      => [
            'name'  => 'twitter_app_url',
            'label' => __( 'Callback URL', 'dokan' ),
            'type'  => 'html',
            'desc'  => "<input class='regular-text' type='text' disabled value='{$callback}'>",
        ],
        'twitter_app_id'       => [
            'name'  => 'twitter_app_id',
            'label' => __( 'Consumer Key', 'dokan' ),
            'type'  => 'text',
        ],
        'twitter_app_secret'   => [
            'name'  => 'twitter_app_secret',
            'label' => __( 'Consumer Secret', 'dokan' ),
            'type'  => 'text',
        ],
        'google_app_details'   => [
            'name'  => 'google_app_details',
            'label' => __( 'Google', 'dokan' ),
            'type'  => 'sub_section',
        ],
        'google_app_label'     => [
            'name'  => 'google_app_label',
            'label' => __( 'Google App Settings', 'dokan' ),
            'type'  => 'html',
            'desc'  => '<a target="_blank" href="https://console.developers.google.com/project">' . __( 'Create an App', 'dokan' ) . '</a> if you don\'t have one and fill Client ID and Secret below.',
        ],
        'google_app_url'       => [
            'name'  => 'google_app_url',
            'label' => __( 'Redirect URI', 'dokan' ),
            'type'  => 'html',
            'desc'  => "<input class='regular-text' type='text' disabled value='{$callback}'>",
        ],
        'google_app_id'        => [
            'name'  => 'google_app_id',
            'label' => __( 'Client ID', 'dokan' ),
            'type'  => 'text',
        ],
        'google_app_secret'    => [
            'name'  => 'google_app_secret',
            'label' => __( 'Client secret', 'dokan' ),
            'type'  => 'text',
        ],
        'linkedin_app_details' => [
            'name'  => 'linkedin_app_details',
            'label' => __( 'Linkedin', 'dokan' ),
            'type'  => 'sub_section',
        ],
        'linkedin_app_label'   => [
            'name'  => 'linkedin_app_label',
            'label' => __( 'Linkedin App Settings', 'dokan' ),
            'type'  => 'html',
            'desc'  => '<a target="_blank" href="https://www.linkedin.com/developer/apps">' . __( 'Create an App', 'dokan' ) . '</a> if you don\'t have one and fill Client ID and Secret below.',
        ],
        'linkedin_app_url'     => [
            'name'  => 'linkedin_app_url',
            'label' => __( 'Redirect URL', 'dokan' ),
            'type'  => 'html',
            'desc'  => "<input class='regular-text' type='text' disabled value='{$callback}'>",

        ],
        'linkedin_app_id'      => [
            'name'  => 'linkedin_app_id',
            'label' => __( 'Client ID', 'dokan' ),
            'type'  => 'text',
        ],
        'linkedin_app_secret'  => [
            'name'  => 'linkedin_app_secret',
            'label' => __( 'Client Secret', 'dokan' ),
            'type'  => 'text',
        ],
    ];

    $gateways            = [];
    $gateway_obj         = WeDevs_dokan_SMS_Gateways::instance();
    $registered_gateways = $gateway_obj->get_gateways();

    foreach ( $registered_gateways as $gateway => $option ) {
        $gateways[ $gateway ] = $option['label'];
    }

    $settings_fields['dokan_verification_sms_gateways'] = [
        'section_label'    => [
            'name'  => 'section_label',
            'label' => __( 'Verification SMS Gateways', 'dokan' ),
            'type'  => 'sub_section',
        ],
        'sender_name'      => [
            'name'    => 'sender_name',
            'label'   => __( 'Sender Name', 'dokan' ),
            'default' => 'weDevs Team',
            'type'    => 'text',
        ],
        'sms_text'         => [
            'name'    => 'sms_text',
            'label'   => __( 'SMS Text', 'dokan' ),
            'type'    => 'textarea',
            'default' => __( 'Your verification code is: %CODE%', 'dokan' ),
            'desc'    => __( 'will be displayed in SMS. <strong>%CODE%</strong> will be replaced by verification code', 'dokan' ),
        ],
        'sms_sent_msg'     => [
            'name'    => 'sms_sent_msg',
            'label'   => __( 'SMS Sent Success', 'dokan' ),
            'default' => __( 'SMS sent. Please enter your verification code', 'dokan' ),
            'type'    => 'text',
        ],
        'sms_sent_error'   => [
            'name'    => 'sms_sent_error',
            'label'   => __( 'SMS Sent Error', 'dokan' ),
            'default' => __( 'Unable to send sms. Contact admin', 'dokan' ),
            'type'    => 'text',
        ],
        'active_gateway'   => [
            'name'    => 'active_gateway',
            'label'   => __( 'Active Gateway', 'dokan' ),
            'type'    => 'select',
            'options' => $gateways,
        ],
        'nexmo_details'    => [
            'name'  => 'nexmo_details',
            'label' => __( 'Nexmo', 'dokan' ),
            'type'  => 'sub_section',
        ],
        'nexmo_header'     => [
            'name'  => 'nexmo_header',
            'label' => __( 'Nexmo App Settings', 'dokan' ),
            'type'  => 'html',
            'desc'  => 'Configure your gateway from <a target="_blank" href="https://www.nexmo.com/">' . __( 'here', 'dokan' ) . '</a> and fill the details below',
        ],
        'nexmo_username'   => [
            'name'  => 'nexmo_username',
            'label' => __( 'API Key', 'dokan' ),
            'type'  => 'text',
        ],
        'nexmo_pass'       => [
            'name'  => 'nexmo_pass',
            'label' => __( 'API Secret', 'dokan' ),
            'type'  => 'text',
        ],
        'twilio_details'   => [
            'name'  => 'twilio_details',
            'label' => __( 'Twilio', 'dokan' ),
            'type'  => 'sub_section',
        ],
        'twilio_header'    => [
            'name'  => 'twilio_header',
            'label' => __( 'Twilio App Settings', 'dokan' ),
            'type'  => 'html',
            'desc'  => 'Configure your gateway from <a target="_blank" href="https://www.twilio.com/">' . __( 'here', 'dokan' ) . '</a>  and fill the details below',
        ],
        'twilio_number'    => [
            'name'  => 'twilio_number',
            'label' => __( 'From Number', 'dokan' ),
            'type'  => 'text',
        ],
        'twilio_username'  => [
            'name'  => 'twilio_username',
            'label' => __( 'Account SID', 'dokan' ),
            'type'  => 'text',
        ],
        'twilio_pass'      => [
            'name'  => 'twilio_pass',
            'label' => __( 'Auth Token', 'dokan' ),
            'type'  => 'text',
        ],
        'twilio_code_type' => [
            'name'    => 'twilio_code_type',
            'label'   => __( 'SMS Code type', 'dokan' ),
            'type'    => 'select',
            'options' => [
                'numeric'      => 'Numeric',
                'alphanumeric' => 'Alphanumeric',
            ],
            'default' => 'numeric',
        ],
    ];

    return $settings_fields;
}
