<?php

/*
* @Author 		ParaTheme
* @Folder	 	Team/Includes
* @version     3.0.5

* Copyright: 	2015 ParaTheme
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 	

class wpblockhub_post_types{
	
	
	public function __construct(){
		//add_action( 'init', array( $this, '_posttype_wp_block' ), 0 );

		}
	
	
	public function _posttype_wp_block(){
			


			$singular  = __( 'Block Group', 'team' );
			$plural    = __( 'Block Groups', 'team' );
	 
			register_taxonomy( "wp_block_cat",
				apply_filters( 'register_taxonomy_wp_block_cat_object_type', array( 'wp_block' ) ),
	       	 	apply_filters( 'register_taxonomy_wp_block_cat_args', array(
		            'hierarchical' 			=> true,
		            'show_admin_column' 	=> true,					
		            'update_count_callback' => '_update_post_term_count',
		            'label' 				=> $plural,
		            'labels' => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => ucwords( $plural ),
						'search_items'      => sprintf( __( 'Search %s', 'team' ), $plural ),
						'all_items'         => sprintf( __( 'All %s', 'team' ), $plural ),
						'parent_item'       => sprintf( __( 'Parent %s', 'team' ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', 'team' ), $singular ),
						'edit_item'         => sprintf( __( 'Edit %s', 'team' ), $singular ),
						'update_item'       => sprintf( __( 'Update %s', 'team' ), $singular ),
						'add_new_item'      => sprintf( __( 'Add New %s', 'team' ), $singular ),
						'new_item_name'     => sprintf( __( 'New %s Name', 'team' ),  $singular )
	            	),
		            'show_ui' 				=> true,
		            'public' 	     		=> true,
                    'show_in_rest' 	     		=> true,
				    'rewrite' => array(
						'slug' => 'wp_block_cat', // This controls the base slug that will display before each term
						'with_front' => false, // Don't display the category base before "/locations/"
						'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
				),
		        ) )
		    );
	 

		
			}



	

}
	

new wpblockhub_post_types();