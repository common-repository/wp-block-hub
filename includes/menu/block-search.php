<?php	

if ( ! defined('ABSPATH')) exit;  // if direct access



if(!current_user_can('manage_options')) return;

$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
$paged = isset($_GET['paged']) ? sanitize_text_field($_GET['paged']) : '';
$tabs = isset($_GET['tabs']) ? sanitize_text_field($_GET['tabs']) : 'latest';

$active_plugins = get_option('active_plugins');
$wpblockhub_block_hub_ids = get_option('wpblockhub_block_hub_ids', array());

//$domain = (is_multisite()) ? site_url() : get_bloginfo('url');

$max_num_pages = 0;

//var_dump($_SERVER);

?>
<div class="wrap">
    <h2><?php _e('WP Block Hub', 'wp-block-hub'); ?></h2>

    <div class="wpblockhub-search">

        <div class="wp-filter">
            <ul class="filter-links">
                <li class=""><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&tabs=latest" class="<?php if($tabs == 'latest') echo 'current'; ?>" aria-current="page"><?php _e('Latest', 'wp-block-hub'); ?></a> </li>
                <li class=""><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&tabs=popular" class="<?php if($tabs == 'popular') echo 'current'; ?>" aria-current="page"><?php _e('Popular', 'wp-block-hub'); ?></a> </li>
                <li class=""><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&tabs=top_rate" class="<?php if($tabs == 'top_rate') echo 'current'; ?>" aria-current="page"><?php _e('Top Rated', 'wp-block-hub'); ?></a> </li>
            </ul>

            <form class="block-search-form">
                <span class="loading"></span>
                <input id="block-keyword" type="search" placeholder="<?php _e('Start typing...', 'wp-block-hub'); ?>"
                       value="<?php echo $keyword; ?>">
            </form>
        </div>

        <?php

        $api_params = array(
            'block_hub_remote_action' => 'blockSearch',
            'keyword' => $keyword,
            'paged' => $paged,
            'tabs' => $tabs,

            );

        // Send query to the license manager server
        $response = wp_remote_get(add_query_arg($api_params, wpblockhub_server_url), array('timeout' => 20, 'sslverify' => false));

        /*
         * Check is there any server error occurred
         *
         * */
        if (is_wp_error($response)){

            ?>
            <div class="return-empty">
                <ul>
                    <li><?php echo __("Unexpected Error! The query returned with an error.", 'wp-block-hub'); ?></li>
                    <li><?php echo __("Make sure your internet connection is up.", 'wp-block-hub'); ?></li>
                </ul>
            </div>
            <?php


        }
        else{

            $response_data = json_decode(wp_remote_retrieve_body($response));
            $post_data = isset($response_data->post_data) ? $response_data->post_data : array();
            $post_found = isset($response_data->post_found) ? sanitize_text_field($response_data->post_found) : array();
            $max_num_pages = isset($response_data->max_num_pages) ? sanitize_text_field($response_data->max_num_pages) : 0;

            //echo '<pre>'.var_export($post_data, true).'</pre>';
            //var_dump($response_data->ajax_nonce);
        }

        ?>

        <div class="block-list-items">
            <?php

            if(!empty($post_data)):

                foreach ($post_data as $item_index=>$item):

                    $thumbnail_url      = isset($item->thumbnail_url) ? $item->thumbnail_url : '';
                    $block_title        = isset($item->title) ? $item->title : __('No title', 'wp-block-hub');
                    $post_url           = isset($item->post_url) ? $item->post_url : '';
                    $json_file_url      = isset($item->json_file_url) ? $item->json_file_url : '';
                    $plugins_required   = isset($item->plugins_required) ? $item->plugins_required : '';
                    $author_name        = isset($item->author_name) ? $item->author_name : '';
                    $author_url         = isset($item->author_url) ? $item->author_url : '';
                    $download_count     = isset($item->download_count) ? $item->download_count : '0';
                    $star_rate          = isset($item->star_rate) ? $item->star_rate : '5';
                    $total_star_count   = isset($item->total_star_count) ? $item->total_star_count : '1';
                    $price              = isset($item->price) ? $item->price : '';

                    $star_rate_int          = floor($star_rate);
                    $star_rate_mod          = $star_rate - $star_rate_int;


                    //var_dump($star_rate);
                    //echo $item_index;
                    ?>

                    <div class="item">
                        <div class="item-top-area">

                            <?php if(!empty($thumbnail_url)):?>
                            <div class="block-thumb">
                                <img src="<?php echo $thumbnail_url; ?>">
                            </div>
                            <?php endif; ?>

                            <div class="block-action">
                                <div class="import-wrap ">
                                    <?php
                                    if(!empty($json_file_url)): ?>
<!--                                        <a download href="--><?php //echo esc_url($json_file_url); ?><!--" class="block-import button">--><?php //_e('Download', 'wp-block-hub'); ?><!--</a>-->
                                        <?php
                                    else:

                                        ?>
<!--                                        <span class="button button-link-delete">--><?php //_e('Not Available', 'wp-block-hub'); ?><!--</span>-->
                                        <?php

                                    endif;

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

<!--                                    <div data-id="--><?php //echo $item_index; ?><!--" class="block-save button --><?php //echo
//                                    $save_class; ?><!--" title="--><?php //_e('Save to reusable blocks', 'wp-block-hub');
//                                    ?><!--">-->
<!--                                        <span>--><?php //echo $saved_text; ?><!--</span>-->
<!--                                    </div>-->

                                </div>
                                <div class="demo-wrap"><a class="block-link"  target="_blank" href="<?php echo
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

                                                //echo basename($plugin_zip_url);

                                                //echo implode($active_plugins);

                                                //echo  strpos(implode($active_plugins), basename($plugin_zip_url));


                                                if (!empty($plugin_zip_url) && strpos(implode($active_plugins), basename
                                                ($plugin_zip_url)) !==
                                                    false) {
                                                    $is_installed = 'installed';
                                                    $install_icon = 'dashicons-yes-alt';
                                                    $install_text = 'Plugin is installed';

                                                }else{
                                                    $is_installed = 'not-installed';
                                                    $install_icon = 'dashicons-download';
                                                    $install_text = 'Plugin is not installed';
                                                }

                                                ?>
                                                <?php if(!empty($plugin_name)): ?>
                                                <li title="<?php echo $install_text; ?>"><span class="dashicons <?php
                                                    echo
                                                    $install_icon; ?> <?php echo
                                                    $is_installed; ?>"></span> <a
                                                            href="<?php echo
                                                    esc_url($plugin_zip_url); ?>"><?php echo
                                                        esc_html($plugin_name); ?></a> </li>
                                            <?php endif; ?>
                                            <?php
                                            endforeach;
                                        else:
                                            ?>
                                            <li><span class="dashicons dashicons-buddicons-groups"></span> <?php esc_html_e('No 3rd party plugin required.', 'wp-block-hub'); ?></li>
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



                                            if($star_rate_mod >= 0.5 && $i == ($star_rate_int+1)){
                                                ?>
                                                <span class="dashicons dashicons-star-half"></span>
                                                <?php

                                                continue;
                                            }else{
                                                ?>
                                                <span class="dashicons dashicons-star-empty"></span>
                                                <?php
                                            }






                                            ?>

                                            <?php
                                        }
                                    }
                                    ?>
                                    <span class="star-count">(<?php echo esc_html($star_rate.'/'.$total_star_count); ?>)</span>
                                </div>
                                <div class="download-count">
                                    <?php _e('Download:','wp-block-hub'); ?> <?php echo
                                    esc_html($download_count);
                                    ?>
                                </div>
                            </div>
                            <div class="col-right">
                                <div class="author-link">
                                    <span><?php _e('Author:','wp-block-hub'); ?> <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html
                                            ($author_name); ?></a> </span>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php
                endforeach;

            else:

            echo 'Server return empty. please try again later.';
            endif;

            ?>



        </div>

        <div class="paginate">
            <?php


            $big = 999999999; // need an unlikely integer
            //$max_num_pages = 4;


            //var_dump(get_pagenum_link( $big ));

            echo paginate_links(
                array(
                    'base' => preg_replace('/\?.*/', '', get_pagenum_link()) . '%_%',
                    //'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, $paged ),
                    'total' => $max_num_pages ,
                    'prev_text'          => '« Previous',
                    'next_text'          => 'Next »',



                ));
            ?>
        </div>



    </div>

</div>


