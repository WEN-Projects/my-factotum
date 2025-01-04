<?php

if ( ! function_exists( "my_factotum_register_all_cstm_postypes" ) ) {
	// Our custom post type function
	function my_factotum_register_all_cstm_postypes() {
		//register post type my-factotum-team
		register_post_type( 'my-factotum-team',
			// CPT Options
			array(
				'labels'             => array(
					'name'               => __( 'Team', 'factotum' ),
					'singular_name'      => __( 'Team', 'factotum' ),
					'menu_name'          => _x( 'Team', 'Admin Menu text', 'factotum' ),
					'name_admin_bar'     => _x( 'Team', 'Add New on Toolbar', 'factotum' ),
					'add_new'            => __( 'Add New', 'factotum' ),
					'add_new_item'       => __( 'Add New Team Member', 'factotum' ),
					'new_item'           => __( 'New Team Member', 'factotum' ),
					'edit_item'          => __( 'Edit Team Member', 'factotum' ),
					'view_item'          => __( 'View Team Member', 'factotum' ),
					'all_items'          => __( 'Team', 'factotum' ),
					'search_items'       => __( 'Search Team member', 'factotum' ),
					'parent_item_colon'  => __( 'Parent Team:', 'factotum' ),
					'not_found'          => __( 'No team member found.', 'factotum' ),
					'not_found_in_trash' => __( 'No team member found in Trash.', 'factotum' ),
				),
				'public'             => true,
				'has_archive'        => false,
				'rewrite'            => array( 'slug' => 'equipe' ),
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'custom-fields' ),
				'publicly_queryable' => false,
				// completely disable the single and the archive for this custom post type
				'capability_type'    => 'factotumteam',
				'capabilities'       => array(
					'publish_posts'      => 'publish_factotumteam',
					'edit_posts'         => 'edit_factotumteam',
					'edit_others_posts'  => 'edit_others_factotumteam',
					'read_private_posts' => 'read_private_factotumteam',
					'edit_post'          => 'edit_factotumteam',
					'delete_post'        => 'delete_factotumteam',
					'read_post'          => 'read_factotumteam',
				)
			)
		);
		register_post_type( 'xml-import-log', // log to display all the xml import status
			// CPT Options
			array(
				'labels'             => array(
					'name'               => __( 'XML Import Log', 'factotum' ),
					'singular_name'      => __( 'XML Import Log', 'factotum' ),
					'menu_name'          => _x( 'XML Import Log', 'Admin Menu text', 'factotum' ),
					'name_admin_bar'     => _x( 'XML Import Log', 'Add New on Toolbar', 'factotum' ),
					'edit_item'          => __( 'Edit Log', 'factotum' ),
					'view_item'          => __( 'View Log', 'factotum' ),
					'all_items'          => __( 'All Logs', 'factotum' ),
					'search_items'       => __( 'Search Log', 'factotum' ),
					'parent_item_colon'  => __( 'Parent Log:', 'factotum' ),
					'not_found'          => __( 'No log found.', 'factotum' ),
					'not_found_in_trash' => __( 'No log found in Trash.', 'factotum' ),
				),
				'public'             => true,
				'has_archive'        => false,
				'rewrite'            => array( 'slug' => 'xml-import-log' ),
				'show_in_rest'       => true,
				'supports'           => array( 'title', 'editor', 'custom-fields' ),
				'publicly_queryable' => false, // completely disable the single and the archive for this custom post type
				'capability_type'    => 'xml_import',
				'capabilities'       => array(
					'publish_posts'      => 'publish_xml_import',
					'edit_posts'         => 'edit_xml_import',
					'edit_others_posts'  => 'edit_others_xml_import',
					'read_private_posts' => 'read_private_xml_import',
					'edit_post'          => 'edit_xml_import',
					'delete_post'        => 'delete_xml_import',
					'read_post'          => 'read_xml_import',
				)

			)
		);
//		register_taxonomy( 'team-role', 'my-factotum-team', array(
//			'label'        => __( 'Role', 'my-factotum' ),
//			'public'       => true,
//			'rewrite'      => false,
//			'hierarchical' => true
//		) );

		//register post type my-factotum-team
		register_post_type( 'real-state-product',
			// CPT Options
			array(
				'labels'      => array(
					'name'               => __( 'Real States', 'factotum' ),
					'singular_name'      => __( 'Real States', 'factotum' ),
					'menu_name'          => _x( 'Real States', 'Admin Menu text', 'factotum' ),
					'name_admin_bar'     => _x( 'Real States', 'Add New on Toolbar', 'factotum' ),
					'add_new'            => __( 'Add New', 'factotum' ),
					'add_new_item'       => __( 'Add New Real State', 'factotum' ),
					'new_item'           => __( 'New Real State', 'factotum' ),
					'edit_item'          => __( 'Edit Real State', 'factotum' ),
					'view_item'          => __( 'View Real State', 'factotum' ),
					'all_items'          => __( 'All Real States', 'factotum' ),
					'search_items'       => __( 'Search Real State', 'factotum' ),
					'parent_item_colon'  => __( 'Parent Real State:', 'factotum' ),
					'not_found'          => __( 'No team Real State found.', 'factotum' ),
					'not_found_in_trash' => __( 'No team Real State found in Trash.', 'factotum' ),
				),
				'public'      => true,
				'has_archive' => false,
				'rewrite'     => array( 'slug' => 'real-state-product' ),
//				'show_in_rest' => true,
				'supports'    => array( 'title', 'editor', 'custom-fields' ),
//				'publicly_queryable'  => true // completely disable the single and the archive for this custom post type
				'capability_type'    => 'real_states',
				'capabilities'       => array(
					'publish_posts'      => 'publish_real_states',
					'edit_posts'         => 'edit_real_states',
					'edit_others_posts'  => 'edit_others_real_states',
					'read_private_posts' => 'read_private_real_states',
					'edit_post'          => 'edit_real_states',
					'delete_post'        => 'delete_real_states',
					'read_post'          => 'read_real_states',
				)

			)
		);
		register_taxonomy( 'postal-ville', 'real-state-product', array(
			'label'        => __( 'Postal Ville', 'my-factotum' ),
			'public'       => true,
			'rewrite'      => false,
			'hierarchical' => true
		) );
		register_taxonomy( 'type-bien', 'real-state-product', array(
			'label'        => __( 'Type Bien', 'my-factotum' ),
			'public'       => true,
			'rewrite'      => false,
			'hierarchical' => true
		) );
		register_taxonomy( 'categorie-offre', 'real-state-product', array(
			'label'        => __( 'Categorie Offre', 'my-factotum' ),
			'public'       => true,
			'rewrite'      => false,
			'hierarchical' => true
		) );


	}

	// Hooking up our function to theme setup
	add_action( 'init', 'my_factotum_register_all_cstm_postypes' );
}