<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 	

if( ! class_exists( 'class_wpblockhub_settings' ) ) {
    class class_wpblockhub_settings{

        public function __construct(){

            add_action('admin_menu', array($this, 'admin_menu'), 12);

        }


        public function admin_menu(){

            add_menu_page(__('WP Block Hub', 'wp-block-hub'), __('WP Block Hub', 'wp-block-hub'), 'manage_options',
                'wpblockhub-search', array( $this, 'search' ), 'dashicons-editor-table');

            add_submenu_page( 'wpblockhub-search', __( 'Settings', 'wp-block-hub' ), __( 'Settings', 'wp-block-hub' )
                , 'manage_options', 'wpblockhub-settings', array( $this, 'wpblockhub_settings' ) );
            add_submenu_page( 'wpblockhub-search', __( 'Blocks', 'wp-block-hub' ), __( 'Blocks', 'wp-block-hub' )
                , 'manage_options', 'edit.php?post_type=wp_block', '' );

        }

        public function search(){


            include(wpblockhub_plugin_dir . 'includes/menu/block-search.php');

        }


        public function wpblockhub_settings(){


            include(wpblockhub_plugin_dir . 'includes/menu/settings.php');

        }

    }
}

new class_wpblockhub_settings();