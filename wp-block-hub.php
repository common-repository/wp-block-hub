<?php
/*
Plugin Name: WP Block Hub
Plugin URI: https://www.pickplugins.com/item/wp-block-hub/?ref=dashboard
Description: Search blocks for gutenberg and import to use on your site.
Version: 0.0.15
Author: PickPlugins
Author URI: http://pickplugins.com
Text Domain: wp-block-hub
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

if( ! class_exists( 'WPBlockHub' ) ) {
    class WPBlockHub{

        public function __construct(){

            define('wpblockhub_plugin_url', plugins_url('/', __FILE__));
            define('wpblockhub_plugin_dir', plugin_dir_path(__FILE__));
            define('wpblockhub_plugin_basename', plugin_basename(__FILE__));
            define('wpblockhub_version', '0.0.15');
            define('wpblockhub_server_url', 'https://wpblockhub.com');


            require_once( wpblockhub_plugin_dir . 'includes/classes/class-settings-tabs.php');
            require_once(wpblockhub_plugin_dir . 'includes/classes/class-settings.php');
            require_once(wpblockhub_plugin_dir . 'includes/classes/class-post-types.php');

            require_once(wpblockhub_plugin_dir . 'includes/functions/functions.php');
            require_once(wpblockhub_plugin_dir . 'includes/classes/class-post-meta-boxes.php');
            require_once(wpblockhub_plugin_dir . 'includes/functions/functions-wp-block-metabox-hooks.php');
            require_once(wpblockhub_plugin_dir . 'includes/functions/functions-wpblockhub-settings-hooks.php');

            add_action('wp_enqueue_scripts', array($this, 'wpblockhub_front_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'wpblockhub_admin_scripts'));
            add_action( 'enqueue_block_editor_assets', array($this, 'block_editor_assets'));
            add_action('plugins_loaded', array($this, 'wpblockhub_load_textdomain'));

            add_post_type_support('wp_block' ,'thumbnail');
            add_post_type_support('wp_block' ,'author');

            //add_filter( 'block_categories', array( $this, 'block_categories' ) );

        }


        public function wpblockhub_load_textdomain(){

            /*
             *
             * Load translation from wordpress.org server
             * */
            $locale = apply_filters('plugin_locale', get_locale(), 'wp-block-hub');
            load_textdomain('wp-block-hub', WP_LANG_DIR . '/wp-block-hub/wp-block-hub-' . $locale . '.mo');

            /*
             * Load translation from plugin directory
             *
             * */
            load_plugin_textdomain('wp-block-hub', false, plugin_basename(dirname(__FILE__)) . '/languages/');
        }


        /**
         * Action for install plugin.
         * Action hook: wpblockhub_action_install
         *
         */
        public function wpblockhub_install(){

            do_action('wpblockhub_action_install');

        }

        /**
         * Action for uninstall plugin.
         * Action hook: wpblockhub_action_uninstall
         *
         */
        public function wpblockhub_uninstall(){

            do_action('wpblockhub_action_uninstall');
        }


        /**
         * Action for deactivation plugin.
         * Action hook: wpblockhub_action_deactivation
         *
         */
        public function wpblockhub_deactivation(){

            do_action('wpblockhub_action_deactivation');
        }



        /**
         *
         * Method for enqueue script and style on front-end
         *
         */
        public function wpblockhub_front_scripts(){



        }


        /**
         *
         * Method for enqueue script and style on back-end
         *
         */
        public function wpblockhub_admin_scripts(){

            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');

            $responses = array();
            $api_params = array(
                'block_hub_remote_action' => 'blockData',

            );

            // Send query to the server
            $server_response = wp_remote_get(add_query_arg($api_params, wpblockhub_server_url), array('timeout' => 20,
                'sslverify' => false));


            if (is_wp_error($server_response)){
                $responses['error'] = __('There is a server error', 'wp-block-hub');
                $responses['data']['posts'] = array();
            }
            else{

                $responses['data'] = json_decode(wp_remote_retrieve_body($server_response));

            }


            wp_enqueue_script('wpblockhub_js', plugins_url('assets/admin/js/scripts.js', __FILE__), array('jquery'));
            wp_localize_script('wpblockhub_js', 'wpblockhub_ajax', array(
                    'wpblockhub_ajaxurl' => admin_url('admin-ajax.php'),
                    'ajax_nonce' => wp_create_nonce('wpblockhub_ajax_nonce'),
                    'wpblockhub_data' => $responses,
                )
            );


            wp_enqueue_style('wpblockhub-style', wpblockhub_plugin_url . 'assets/admin/css/style.css');
            wp_enqueue_script('settings-tabs', wpblockhub_plugin_url.'assets/admin/js/settings-tabs.js', array( 'jquery' ));

            wp_enqueue_style('settings-tabs', wpblockhub_plugin_url.'assets/admin/css/settings-tabs.css');
            wp_enqueue_style('fontawesome-5.min', wpblockhub_plugin_url.'assets/global/css/fontawesome-5.min.css');


        }


        public function block_editor_assets() {
            wp_enqueue_script('wpblockhub-block-editor', wpblockhub_plugin_url. 'assets/admin/js/block-editor.js', array( 'wp-plugins', 'wp-edit-post', 'wp-element' ));


//            // Scripts.
//            wp_register_script(
//                'wpblockhub-block-paragraph', // Handle.
//                wpblockhub_plugin_url. 'assets/blocks/block-paragraph/block-paragraph.js', // Block.js: We register the block here.
//                array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-components', 'wp-editor' ) // Dependencies, defined above.
//
//            );
//            // Styles.
//            wp_register_style(
//                'wpblockhub-block-paragraph-style', // Handle.
//                wpblockhub_plugin_url.'assets/blocks/block-paragraph/block-paragraph-style.css', // Block editor CSS.
//                array( 'wp-edit-blocks' )
//
//            );
//            wp_register_style(
//                'wpblockhub-block-paragraph-editor', // Handle.
//                wpblockhub_plugin_url.'assets/blocks/block-paragraph/block-paragraph-editor.css', // Block editor CSS.
//                array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
//
//            );
//            register_block_type(
//                'wpblockhub/paragraph',
//                array(
//                    'editor_script' => 'wpblockhub-block-paragraph',
//                    'editor_style' => 'wpblockhub-block-paragraph-editor',
//                    'style' => 'wpblockhub-block-paragraph-style',
//                )
//            );


        }


        public function block_categories( $categories ) {

            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'wpblockhub',
                        'title' => __( 'Block Hub', 'blocks-builder' ),
                    ),
                )
            );
        }
    }
}
new WPBlockHub();



