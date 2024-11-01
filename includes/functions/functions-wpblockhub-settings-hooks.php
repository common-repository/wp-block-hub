<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access


add_action('wpblockbub_settings_tabs_content_general','wpblockbub_settings_tabs_content_general', 90);

function wpblockbub_settings_tabs_content_general($tab){

    $settings_tabs_field = new settings_tabs_field();

    $wpblockhub_settings = get_option( 'wpblockhub_settings', true);

    $api_key = isset($wpblockhub_settings['api_key']) ? $wpblockhub_settings['api_key'] : '';







    $args = array(
        'id'		    => 'api_key',
        'parent'		=> 'wpblockhub_settings',
        'title'		    => esc_html__('API key','wp-block-hub'),
        'details'	    => __('Get your api key <a target="_blank" href="https://wpblockhub.com/api-key/">here</a>. API is free and we use API to manage your submission on our site.','wp-block-hub'),
        'type'		    => 'text',
        'value'		    => $api_key,
    );
    $settings_tabs_field->generate_field($args);

    ?>



    <?php


}


