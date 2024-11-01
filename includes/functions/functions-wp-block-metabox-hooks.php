<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access


add_action('wp_block_metabox_tabs_content_general','wp_block_metabox_tabs_content_general', 90, 2);

function wp_block_metabox_tabs_content_general($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();

    $plugins_required = get_post_meta($post_id, 'plugins_required', true);
    $plugins_required = !empty($plugins_required) ? $plugins_required : array();

    $preview_img = get_post_meta($post_id, 'preview_img', true);
    $design_source = get_post_meta($post_id, 'design_source', true);
    $tags = get_post_meta($post_id, 'tags', true);



    $_fields = array(
        array(
            'id'		=> 'name',
            'title'		=> __('Plugin Name','wp-block-hub'),
            'details'	=> __('Write plugin name here.','wp-block-hub'),
            'type'		=> 'text',
            'value'		=> '',
            'default'		=> '',
            'placeholder'		=> 'Gutenberg',
        ),
        array(
            'id'		=> 'plugin_zip_url',
            'title'		=> __('Plugin zip file url or wordpress.org plugin url','wp-block-hub'),
            'details'	=> __('Plugin download link here, plugin slug name should match with directory name.','wp-block-hub'),
            'type'		=> 'text',
            'value'		=> '',
            'default'		=> '',
            'placeholder'		=> '',
        ),
        array(
            'id'		=> 'plugin_price_type',
            'title'		=> __('Plugin is free or paid?','wp-block-hub'),
            'details'	=> __('Choose is plugin is paid or free.','wp-block-hub'),
            'type'		=> 'select',
            'value'		=> '',
            'default'		=> 'free',
            'args'		=> array(
                'free'=>__('Free','wp-block-hub'),
                'paid'=>__('Paid','wp-block-hub'),
            ),
        ),

    );
    //$teams_fields = apply_filters('teams_fields', $_fields);



    $args = array(
        'id'		    => 'plugins_required',
        //'parent'		=> '',
        'title'		    => __('Plugins required','wp-block-hub'),
        'details'	    => __('List of plugin required for this block','wp-block-hub'),
        'collapsible'   =>true,
        'type'		    => 'repeatable',
        'limit'		    => 10,
        'title_field'	=> 'name',
        'value'		    => $plugins_required,
        'fields'        => $_fields,
    );
    $settings_tabs_field->generate_field($args);



    $args = array(
        'id'		    => 'preview_img',
        //'parent'		=> '',
        'title'		    => __('Preview Image','wp-block-hub'),
        'details'	    => __('Add a preview image url. <br>You can use <a href="https://imgur.com/upload">https://imgur.com/upload</a> to uplaod image and provide file((.png .jpeg, .jpg .gif)) link here.<br>Please do not use localhost URL','wp-block-hub'),
        'type'		    => 'text',
        'placeholder'		    => 'https://i.imgur.com/au9QXHI.png',
        'value'		    => $preview_img,
    );
    $settings_tabs_field->generate_field($args);

    $args = array(
        'id'		    => 'design_source',
        //'parent'		=> '',
        'title'		    => __('Design Source','wp-block-hub'),
        'details'	    => __('Please write here details about design source. please provide any copyright text if needed.','wp-block-hub'),
        'type'		    => 'textarea',
        'placeholder'		    => 'Copyright to WPBlockHub, link: http://wpblockhub.com/copyright',
        'value'		    => $design_source,
    );
    $settings_tabs_field->generate_field($args);



    //echo '<pre>'.var_export(wpblockhub_api_key_access_status(''), true).'</pre>';

    $args = array(
        'id'		    => 'tags',
        //'parent'		=> '',
        'title'		    => __('Tags','wp-block-hub'),
        'details'	    => __('Add some tags, use comma(,) separated.','wp-block-hub'),
        'type'		    => 'text',
        'placeholder'		    => 'Tag 1, Tag 2',
        'value'		    => $tags,
    );
    $settings_tabs_field->generate_field($args);



}



add_action('wp_block_metabox_tabs_content_contribute','wp_block_metabox_tabs_content_contribute', 90, 2);

function wp_block_metabox_tabs_content_contribute($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();



    $block_hub_id = get_post_meta($post_id, 'block_hub_id', true);

    ?>


    <div class="setting-field">
        <div class="field-lable">Publish to wpblockhub.com</div>
        <div class="field-input">
            <div data-post-id="<?php echo $post_id; ?>" class="button block-publish">Submit for review</div>
            <p class="description"></p>
        </div>
    </div>
    <?php


}
