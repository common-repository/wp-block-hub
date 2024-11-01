<?php	

if ( ! defined('ABSPATH')) exit;  // if direct access



if(!current_user_can('manage_options')) return;


if(!empty($_POST)):

    $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
    if(wp_verify_nonce( $nonce, 'wpblockhub_nonce' )) :

        $wpblockhub_settings = isset($_POST['wpblockhub_settings']) ? stripslashes_deep($_POST['wpblockhub_settings']) : array();
        update_option('wpblockhub_settings', $wpblockhub_settings);

        ?>
        <div class="updated"><p><strong><?php _e('Changes Saved.', 'post-grid' ); ?></strong></p></div>

        <?php
    endif;
else:

    $wpblockhub_settings = get_option( 'wpblockhub_settings' );

endif;










$wcps_settings_tab = array();


$wcps_settings_tab[] = array(
    'id' => 'general',
    'title' => __('<i class="fas fa-laptop-code"></i> General','wp-block-hub'),
    'priority' => 1,
    'active' => true,
);







$wcps_settings_tabs = apply_filters('wpblockbub_settings_tabs', $wcps_settings_tab);


$tabs_sorted = array();
foreach ($wcps_settings_tabs as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
array_multisort($tabs_sorted, SORT_ASC, $wcps_settings_tabs);

?>
<div class="wrap">
    <h2><?php _e('WP Block Hub - Settings', 'wp-block-hub'); ?></h2><br>


    <form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">



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
                    do_action('wpblockbub_settings_tabs_content_'.$id, $tab);
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="clear clearfix"></div>


        <p class="submit">
            <?php wp_nonce_field( 'wpblockhub_nonce' ); ?>
            <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes', 'post-grid' ); ?>" />
        </p>

    </form>
</div>


