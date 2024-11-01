<?php

/*
* @Author 		ParaTheme
* @Folder	 	Team/Includes
* @version     3.0.5

* Copyright: 	2015 ParaTheme
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 	

class wpblockhub_post_meta_boxes{
	
	
	public function __construct(){
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box_block_data' ), 0 );
        add_action( 'save_post', array( $this, 'metabox_block_hub_save_post' ), 0 );

		}
	
	

		function add_meta_box_block_data(){

            $screens = array( 'wp_block' );
            global $post;
            $post_id = $post->ID;

            foreach ( $screens as $screen ){
                add_meta_box('blockhub_block_metabox', esc_html__('Block Info', 'wp-block-hub'), array($this, 'block_hub_metabox_input'), $screen);
            }

        }
	


        function block_hub_metabox_input($post){


            global $post;
            wp_nonce_field( 'meta_boxes_block_hub_input', 'meta_boxes_block_hub_input_nonce' );

            $post_id = $post->ID;

            $wcps_settings_tab = array();


            $wcps_settings_tab[] = array(
                'id' => 'general',
                'title' => __('<i class="fas fa-laptop-code"></i> General','wp-block-hub'),
                'priority' => 1,
                'active' => true,
            );


            $wcps_settings_tab[] = array(
                'id' => 'contribute',
                'title' => __('<i class="fas fa-box-open"></i> Contribute','wp-block-hub'),
                'priority' => 2,
                'active' => false,
            );




            $wcps_settings_tabs = apply_filters('block_hub_metabox_tabs', $wcps_settings_tab);


            $tabs_sorted = array();
            foreach ($wcps_settings_tabs as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
            array_multisort($tabs_sorted, SORT_ASC, $wcps_settings_tabs);




            ?>
            <div class="block-data">

                <div class="settings-tabs vertical">
                    <ul class="tab-navs">
                        <?php
                        foreach ($wcps_settings_tabs as $tab){
                            $id = $tab['id'];
                            $title = $tab['title'];
                            $active = $tab['active'];
                            $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                            $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                            ?>
                            <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>"><?php echo $title; ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                    foreach ($wcps_settings_tabs as $tab){
                        $id = $tab['id'];
                        $title = $tab['title'];
                        $active = $tab['active'];


                        ?>

                        <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">


                            <?php
                            do_action('wp_block_metabox_tabs_content_'.$id, $tab, $post_id);
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="clear clearfix"></div>


            </div>
            <?php


        }



    function metabox_block_hub_save_post( $post_id ){

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if (!isset($_POST['meta_boxes_block_hub_input_nonce']))
            return $post_id;

        $nonce = $_POST['meta_boxes_block_hub_input_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'meta_boxes_block_hub_input'))
            return $post_id;

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;


        $plugins_required = stripslashes_deep( $_POST['plugins_required'] );
        update_post_meta( $post_id, 'plugins_required', $plugins_required );

        $preview_img = sanitize_text_field( $_POST['preview_img'] );
        update_post_meta( $post_id, 'preview_img', $preview_img );

        $design_source = sanitize_text_field( $_POST['design_source'] );
        update_post_meta( $post_id, 'design_source', $design_source );





    }



}
	

new wpblockhub_post_meta_boxes();



