<?php
if ( ! defined('ABSPATH')) exit;  // if direct access




/*
 * Ajax Function to fetch block from http://wpblockhub.com/ server
 *
 * */
function wpblockhub_ajax_fetch_block_hub(){



    check_ajax_referer( 'wpblockhub_ajax_nonce', 'wpblockhub_ajax_nonce' );

    if(!current_user_can('manage_options')) return;


    $wpblockhub_block_hub_ids = get_option('wpblockhub_block_hub_ids', array());

    $responses = array();

    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    //$domain = (is_multisite()) ? site_url() : get_bloginfo('url');


    $html = '';
    $api_params = array(
        'block_hub_remote_action' => 'blockSearch',
        'keyword' => $keyword,
        'domain' => '',
    );




    // Send query to the license manager server
    $server_response = wp_remote_get(add_query_arg($api_params, wpblockhub_server_url), array('timeout' => 20, 'sslverify' => false));


    /*
     * Check is there any server error occurred
     *
     * */
    if (is_wp_error($server_response)){
        $responses['error'] = __('There is a server error', 'wp-block-hub');
    }
    else{

        $response_data = json_decode(wp_remote_retrieve_body($server_response));
        $post_data = isset($response_data->post_data) ? $response_data->post_data : array();
        $post_found = isset($response_data->post_found) ? sanitize_text_field($response_data->post_found) : 'no';


        ob_start();


        if(!empty($post_data)):

            foreach ($post_data as $item_index=>$item):

                $thumbnail_url = isset($item->thumbnail_url) ? $item->thumbnail_url : '';
                $block_title = isset($item->title) ? $item->title : __('No title', 'wp-block-hub');
                $post_url = isset($item->post_url) ? $item->post_url : '';

                $json_file_url = isset($item->json_file_url) ? $item->json_file_url : '';

                $plugins_required = isset($item->plugins_required) ? $item->plugins_required : '';
                $author_name = isset($item->author_name) ? $item->author_name : '';
                $author_url = isset($item->author_url) ? $item->author_url : '';
                $download_count = isset($item->download_count) ? $item->download_count : '0';
                $star_rate = isset($item->star_rate) ? $item->star_rate : '5';
                $total_star_count = isset($item->total_star_count) ? $item->total_star_count : '1';
                $price = isset($item->price) ? $item->price : '';

                if(in_array($item_index,$wpblockhub_block_hub_ids)){

                    $is_saved = 'yes';
                    $saved_text = 'Saved';
                    $save_class = 'saved';

                }else{
                    $is_saved = 'no';
                    $saved_text = 'Save';
                    $save_class = 'not-saved';
                }
                ?>

                <div class="item">
                    <div class="item-top-area">
                        <?php if(!empty($thumbnail_url)):?>
                        <div class="block-thumb">
                            <img src="<?php echo $thumbnail_url; ?>">
                        </div>
                        <?php endif; ?>

                        <div class="block-action">
                            <div class="import-wrap">
<!--                                <span class="button block-import">--><?php //_e('Download', 'wp-block-hub'); ?><!--</span>-->

                                <div data-id="<?php echo $item_index; ?>" class="block-save button <?php echo
                                $save_class; ?>" title="<?php _e('Save to reusable blocks', 'wp-block-hub');
                                ?>">
                                    <span><?php echo $saved_text; ?></span>
                                </div>
                            </div>




                            <div class="demo-wrap"><a class="block-link" target="_blank" href="<?php echo
                                esc_url($post_url); ?>"><?php _e('See details', 'wp-block-hub'); ?></a>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="block-name"><?php echo $block_title; ?></div>
                            <div class="plugin-required">
                                <p><strong><?php _e('Plugins required:', 'wp-block-hub'); ?></strong></p>
                                <ul>
                                    <?php
                                    if(!empty($plugins_required)):
                                        foreach ($plugins_required as $plugin):
                                            $plugin_name = $plugin->name;
                                            $plugin_zip_url = $plugin->plugin_zip_url;
                                            $plugin_price_type = $plugin->plugin_price_type;
                                            ?>
                                            <?php if(!empty($plugin_name)): ?>
                                            <li><span class="dashicons dashicons-yes-alt"></span> <a href="<?php echo
                                                esc_url($plugin_zip_url); ?>"><?php echo
                                                    esc_html($plugin_name); ?></a> </li>
                                        <?php endif; ?>
                                        <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <li><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e('No 3rd party plugin required.', 'wp-block-hub'); ?></li>
                                    <?php
                                    endif;
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="item-bottom-area">
                        <div class="col-left">
                            <div class="star-rate">
                                <?php
                                for($i=1; $i<=5; $i++){
                                    if($star_rate >= $i){
                                        ?>
                                        <span class="dashicons dashicons-star-filled"></span>
                                        <?php
                                    }else{
                                        ?>
                                        <span class="dashicons dashicons-star-half"></span>
                                        <?php
                                    }
                                }
                                ?>
                                <span class="star-count">(<?php echo esc_html($total_star_count); ?>)</span>
                            </div>
                            <div class="download-count"><?php _e('Download:','wp-block-hub'); ?> <?php echo
                                esc_html($download_count);
                                ?></div>
                        </div>
                        <div class="col-right">
                            <div class="author-link">
                                <span>
                                    <?php _e('Author:','wp-block-hub'); ?> <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html
                                            ($author_name); ?></a>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        endif;

        $responses['html'] = ob_get_clean();

    }


	echo json_encode( $responses );
	die();
}
add_action('wp_ajax_wpblockhub_ajax_fetch_block_hub', 'wpblockhub_ajax_fetch_block_hub');
//add_action('wp_ajax_nopriv_wpblockhub_ajax_fetch_block_hub', 'wpblockhub_ajax_fetch_block_hub');










/*
 * Ajax Function to fetch block from http://wpblockhub.com/ server
 *
 * */
function wpblockhub_ajax_fetch_block_hub_by_id(){



    check_ajax_referer( 'wpblockhub_ajax_nonce', 'wpblockhub_ajax_nonce' );

    if(!current_user_can('manage_options')) return;

    $wpblockhub_block_hub_ids = get_option('wpblockhub_block_hub_ids', array());

    $responses = array();

    $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : 0;


    $html = '';
    $api_params = array(
        'block_hub_remote_action' => 'blockSearchByID',
        'post_id' => $post_id,
    );




    // Send query to the license manager server
    $server_response = wp_remote_get(add_query_arg($api_params, wpblockhub_server_url), array('timeout' => 20, 'sslverify' => false));


    /*
     * Check is there any server error occurred
     *
     * */
    if (is_wp_error($server_response)){
        $responses['error'] = __('There is a server error', 'wp-block-hub');
    }
    else{

        $response_data = json_decode(wp_remote_retrieve_body($server_response));
        $post_content = isset($response_data->post_content) ? $response_data->post_content : '';
        $post_title = isset($response_data->post_title) ? sanitize_text_field($response_data->post_title) : '';
        $post_id = isset($response_data->post_id) ? sanitize_text_field($response_data->post_id) : 0;
        $download_count = isset($response_data->download_count) ? sanitize_text_field($response_data->download_count) : 0;

        $post_found = isset($response_data->post_found) ? sanitize_text_field($response_data->post_found) : 'no';
        $responses['download_count'] = $download_count;

        $responses['post_content'] = $post_content;

        if(!in_array($post_id,$wpblockhub_block_hub_ids)):

            // Create post object
            $my_post = array(
                'post_title'    => $post_title,
                'post_content'  => $post_content,
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type' => 'wp_block',
            );

            // Insert the post into the database
            $new_post_id = wp_insert_post( $my_post );

            update_post_meta($new_post_id, 'block_hub_id', $post_id);


            update_option('wpblockhub_block_hub_ids', array_merge($wpblockhub_block_hub_ids, array($post_id)));
            $responses['is_saved'] = 'yes';




        else:

            $responses['is_saved'] = 'yes';

        endif;






    }


    echo json_encode( $responses );
    die();
}
add_action('wp_ajax_wpblockhub_ajax_fetch_block_hub_by_id', 'wpblockhub_ajax_fetch_block_hub_by_id');
//add_action('wp_ajax_nopriv_wpblockhub_ajax_fetch_block_hub', 'wpblockhub_ajax_fetch_block_hub');




add_action('wp_trash_post','wpblockhub_trash_wp_block');

function wpblockhub_trash_wp_block($post_id){

    $post_data = get_post($post_id);

    $block_hub_id = get_post_meta($post_id, 'block_hub_id', true);



    if($post_data->post_type  == 'wp_block'){



        $wpblockhub_block_hub_ids = get_option('wpblockhub_block_hub_ids', array());


        $key = array_search($block_hub_id, $wpblockhub_block_hub_ids);

            //update_option('wpblockhub_block_hub_ids_key', $key);


            unset($wpblockhub_block_hub_ids[$key]);


            update_option('wpblockhub_block_hub_ids', $wpblockhub_block_hub_ids);








    }

}







/*
 * Ajax Function to fetch block from http://wpblockhub.com/ server
 *
 * */
function wpblockhub_ajax_submit_to_wpblockhub(){


    check_ajax_referer( 'wpblockhub_ajax_nonce', 'wpblockhub_ajax_nonce' );

    if(!current_user_can('manage_options')) return;

    $responses      = array();
    $remote_data    = array();

    $wpblockhub_settings    = get_option('wpblockhub_settings');


    $api_key                = isset($wpblockhub_settings['api_key']) ? $wpblockhub_settings['api_key'] : '';
    $remote_data['api_key'] = $api_key;


    $post_id                            = isset($_POST['post_id']) ? (int)sanitize_text_field($_POST['post_id']) : 0;
    $remote_data['contribute_post_id']  = $post_id;
    $data_update                        = isset($_POST['data_update']) ? sanitize_text_field($_POST['data_update']) : 'no';



    if($post_id):


        $post_data = get_post($post_id);

        $plugins_required = get_post_meta($post_id, 'plugins_required', true);
        $preview_img = get_post_meta($post_id, 'preview_img', true);
        $design_source = get_post_meta($post_id, 'design_source', true);
        $tags = get_post_meta($post_id, 'tags', true);



        $remote_data['content'] = $post_data->post_content;
        $remote_data['title'] = $post_data->post_title;
        $remote_data['thumbnail'] = get_the_post_thumbnail_url($post_id,'full');
        $remote_data['plugins_required'] = !empty($plugins_required) ? $plugins_required : array();
        $remote_data['preview_img'] = !empty($preview_img) ? $preview_img : '';
        $remote_data['design_source'] = !empty($design_source) ? $design_source : '';
        $remote_data['tags'] = !empty($tags) ? $tags : '';


        $remote_data['data_update'] = $data_update;


        //$responses['content'] = $post_data->post_content;
    else:

    endif;


    $body_args = array(
        'block_hub_remote_action' => 'blockSubmit',
        'remote_data' => $remote_data,


    );

    $api_params = array(

        'method' => 'POST',
        'timeout' => 45,
        'sslverify' => false,
        'body'        => $body_args,
    );



    // Send query to the license manager server
    //$server_response = wp_remote_get(add_query_arg($api_params, wpblockhub_server_url), array('method' => 'POST',
    //'timeout' => 2000, 'sslverify' => false));
    $server_response = wp_remote_post(wpblockhub_server_url, $api_params );


    /*
     * Check is there any server error occurred
     *
     * */
    if (is_wp_error($server_response)){


        $responses['message'] = __('There is a server error', 'wp-block-hub');
        $responses['status'] = $server_response;
    }
    else{

        $response_data = json_decode(wp_remote_retrieve_body($server_response));
        $status = isset($response_data->status) ? $response_data->status : '';
        $api_key = isset($response_data->api_key) ? $response_data->api_key : '';
        $block_hub_exist = isset($response_data->block_hub_exist) ? $response_data->block_hub_exist : '';
        $block_hub_exist_id = isset($response_data->block_hub_exist_id) ? $response_data->block_hub_exist_id : '';
        $message = isset($response_data->message) ? $response_data->message : '';


        $responses['status'] = $status;
        $responses['api_key'] = $api_key;
        $responses['block_hub_exist'] = $block_hub_exist;
        $responses['block_hub_exist_id'] = $block_hub_exist_id;
        $responses['message'] = $message;




    }


    echo json_encode( $responses );
    die();
}
add_action('wp_ajax_wpblockhub_ajax_submit_to_wpblockhub', 'wpblockhub_ajax_submit_to_wpblockhub');
//add_action('wp_ajax_nopriv_wpblockhub_ajax_fetch_block_hub', 'wpblockhub_ajax_fetch_block_hub');




function wpblockhub_ajax_fetch_blockdata(){


    check_ajax_referer( 'wpblockhub_ajax_nonce', 'wpblockhub_ajax_nonce' );

    if(!current_user_can('manage_options')) return;

    $responses      = array();
    $remote_data    = array();


    $keyword        = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    $categories        = isset($_POST['categories']) ? sanitize_text_field($_POST['categories']) : '';
    $paged        = isset($_POST['paged']) ? sanitize_text_field($_POST['paged']) : 1;



//    $api_params = array(
//        'block_hub_remote_action' => 'blockData',
//        'keyword' => $keyword,
//        'categories' => $categories,
//    );
//
//    // Send query to the server
//    $server_response = wp_remote_get(add_query_arg($api_params, wpblockhub_server_url), array('timeout' => 20,
//        'sslverify' => false));



    $body_args = array(
        'block_hub_remote_action' => 'blockData',
        'keyword' => $keyword,
        'categories' => $categories,
        'paged' => $paged,


    );

    $api_params = array(

        'method' => 'POST',
        'timeout' => 45,
        'sslverify' => false,
        'body'        => $body_args,
    );



    // Send query to the license manager server
    //$server_response = wp_remote_get(add_query_arg($api_params, wpblockhub_server_url), array('method' => 'POST',
    //'timeout' => 2000, 'sslverify' => false));
    $server_response = wp_remote_post(wpblockhub_server_url, $api_params );





    if (is_wp_error($server_response)){
        $responses['error'] = __('There is a server error', 'wp-block-hub');
        $responses['data']['posts'] = array();
    }
    else{

        $responses['data'] = json_decode(wp_remote_retrieve_body($server_response));

    }


    //$responses['blockData'] = 'Hello 1';

    echo json_encode( $responses );
    die();
}
add_action('wp_ajax_wpblockhub_ajax_fetch_blockdata', 'wpblockhub_ajax_fetch_blockdata');
//add_action('wp_ajax_nopriv_wpblockhub_ajax_fetch_block_hub', 'wpblockhub_ajax_fetch_block_hub');




// Display link under post list

function wpblockhub_ajax_export_site( $actions, $post ) {


    if (current_user_can('edit_posts') && $post->post_type=='wp_block' ) {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=accordions_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Publish to wpblockup.com</a>';
    }
    return $actions;
}

//add_filter( 'post_row_actions', 'wpblockhub_ajax_export_site', 10, 2 );



