<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package my_factotum
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function my_factotum_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'my_factotum_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function my_factotum_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wp_head', 'my_factotum_pingback_header' );

/**
 * Enqueue scripts and styles.
 */
function my_factotum_scripts() {
	//enqueuing all sytles
	wp_enqueue_style( 'my-factotum-style', get_stylesheet_uri(), array(), _S_VERSION );
	$min = ( 'SCRIPT_DEBUG' == true ) ? '' : '.min';
	wp_enqueue_style( 'ad-main', get_template_directory_uri() . '/assets/css/main' . $min . '.css', array(), _S_VERSION );
	wp_style_add_data( 'my-factotum-style', 'rtl', 'replace' );

	//enqueuing all scripts
	wp_enqueue_script( 'ad-vendor', get_template_directory_uri() . '/assets/js/vendor' . $min . '.js', array( 'jquery' ), _S_VERSION, true );
	wp_enqueue_script( 'ad-main', get_template_directory_uri() . '/assets/js/main' . $min . '.js', array( 'jquery' ), _S_VERSION, true );
	$frontend_js_data = array(
		'ajaxurl'                                  => admin_url( 'admin-ajax.php' ),
		'get_real_state_location_categories_nonce' => wp_create_nonce( "get_real_state_location_categories_nonce" ),
		'real_state_add_to_wishlist_nonce'         => wp_create_nonce( "real_state_add_to_wishlist_nonce" ),
		'real_state_add_to_alerte_nonce'           => wp_create_nonce( "real_state_add_to_alerte_nonce" ),
		'real_state_get_more_by_agent_nonce'       => wp_create_nonce( "real_state_get_more_by_agent_nonce" )
	);
	if ( isset( $_GET["location"] ) ) { // for setting pre selected location in search for of no biens
		$selected_locations = array();
		$i                  = 0;
		foreach ( $_GET["location"] as $location ) {
			$term = get_term_by( 'slug', $location, 'postal-ville' );
			if ( isset( $term->name ) ) { // for all the locations in our database
				$selected_locations[ $i ]["text"] = $term->name;
				$selected_locations[ $i ]["id"]   = $term->slug;
				$i ++;
			} else { // for all the locations entered as free texts
				$selected_locations[ $i ]["text"] = $location;
				$selected_locations[ $i ]["id"]   = $location;
				$i ++;
			}
		}
		$frontend_js_data["selected_locations"] = $selected_locations;
	}
	if ( get_the_ID() ) {
		$frontend_js_data["current_post_id"] = get_the_ID();
	}

	if ( is_user_logged_in() ) {
		$frontend_js_data["current_logged_user"] = get_current_user_id();
	}

	$agent_name = get_query_var( 'agent' );
	if ( $agent_name ) {
		$agent_detail = get_user_by( "login", $agent_name );
		if ( $agent_detail ) { // if agent exists in our database
			$real_states_count = my_factotum_count_realstate_by_agent( $agent_detail->user_email );
			if ( $real_states_count > 3 && $agent_detail->user_email ) {
				$frontend_js_data["current_agent_email"]     = $agent_detail->user_email;
				$frontend_js_data["agent_real_states_count"] = $real_states_count;
			}
		}
	}

	if ( ! empty( $_GET ) ) { // for add to alerte functionality
		$criteria_data = array();
		if ( isset( $_GET["location"] ) ) {
			if ( ! empty( $_GET["location"] ) ) {
				$criteria_data["location"] = $_GET["location"];
			}
		}
		if ( isset( $_GET["pieces"] ) ) {
			if ( ! empty( $_GET["pieces"] ) ) {
				$criteria_data["pieces"] = $_GET["pieces"];
			}
		}
		if ( isset( $_GET["min_surface"] ) ) {
			if ( ! empty( $_GET["min_surface"] ) ) {
				$criteria_data["min_surface"] = $_GET["min_surface"];
			}
		}
		if ( isset( $_GET["max_price"] ) ) {
			if ( ! empty( $_GET["max_price"] ) ) {
				$criteria_data["max_price"] = $_GET["max_price"];
			}
		}
		if ( isset( $_GET["category"] ) ) {
			if ( ! empty( $_GET["category"] ) ) {
				$criteria_data["category"] = $_GET["category"];
			}
		}
		if ( isset( $_GET["type"] ) ) {
			if ( ! empty( $_GET["type"] ) ) {
				$criteria_data["type"] = $_GET["type"];
			}
		}
		$frontend_js_data["alerte_criteria_data"] = $criteria_data;
	}

	wp_localize_script( 'ad-main', 'frontend_js_data_obj', $frontend_js_data );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'my_factotum_scripts' );


// Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	return $classes;
}

add_filter( 'body_class', 'add_slug_body_class' );


// upload svg image
function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['xml'] = 'application/xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );

/* if the svg wont support form the above code add both*/
function fix_mime_type_svg( $data, $file, $filename, $mimes ) {
	$ext = isset( $data['ext'] ) ? $data['ext'] : '';
	if ( strlen( $ext ) < 1 ) {
		$exploded = explode( '.', $filename );
		$ext      = strtolower( end( $exploded ) );
	}
	if ( $ext === 'svg' ) {
		$data['type'] = 'image/svg+xml';
		$data['ext']  = 'svg';
	} elseif ( $ext === 'svgz' ) {
		$data['type'] = 'image/svg+xml';
		$data['ext']  = 'svgz';
	} elseif ( $ext === 'xml' ) {
		$data['type'] = 'application/xml';
		$data['ext']  = 'xml';
	}

	return $data;
}

add_filter( 'wp_check_filetype_and_ext', 'fix_mime_type_svg', 99, 4 );

if ( function_exists( 'acf_add_options_page' ) ) { // adding setting pages for My Factotum Site

	acf_add_options_page( array(
		'page_title' => 'My Factotum Settings',
		'menu_title' => 'My Factotum',
		'menu_slug'  => 'my-factotum-settings',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );

	acf_add_options_sub_page( array(
		'page_title'  => 'Footer Settings',
		'menu_title'  => 'Footer Settings',
		'parent_slug' => 'my-factotum-settings'
	) );

}

//ajax response for location suggestion in location based search

add_action( "wp_ajax_get_real_state_location_categories_by_searchkey", "my_factotum_ajax_get_locations_by_search_key" ); // ajax response functionality to get all the locations based on search key
add_action( "wp_ajax_nopriv_get_real_state_location_categories_by_searchkey", "my_factotum_ajax_get_locations_by_search_key" ); //ajax response functionality to get all the locations based on search key for not loggedin users

function my_factotum_ajax_get_locations_by_search_key() {
	if ( ! wp_verify_nonce( $_REQUEST['nonce'], "get_real_state_location_categories_nonce" ) ) { // if ajax request with invalid nonce
		exit( "No naughty business please" );
	}
	if ( empty( $_REQUEST["search_key"] ) || ! isset( $_REQUEST["search_key"] ) ) { // if search key value is not passed
		exit( "Empty Search Key" );
	}

	global $wpdb;
	$cat_id              = 10;
	$search_key          = $_REQUEST["search_key"];
	$location_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->terms} tr JOIN $wpdb->term_taxonomy tt ON (tr.term_id = tt.term_id) WHERE `name` LIKE '%{$search_key}%' && `taxonomy`='postal-ville'" );

	die( json_encode( array( "status" => 1, "data" => $location_categories ) ) );
}

add_filter( 'option_show_avatars', '__return_false' ); // remove profile picture(gavatar) option for wp users

add_action( 'init', function () {
	$page_id   = 1252; // page id of the fiche-agent
	$page_data = get_post( $page_id );
	if ( ! is_object( $page_data ) ) { // post not there
		return;
	}
	add_rewrite_rule( $page_data->post_name . '/?([^/]*)/?', 'index.php?pagename=' . $page_data->post_name . '&agent=$matches[1]', 'top' );
} );
add_filter( 'query_vars', function ( $vars ) { //but first, pass 'agent' to the query variables so that we can use get_query_var('agent')
	$vars[] = "agent";

	return $vars;
} );

add_action( 'template_redirect', 'my_page_template_redirect' ); //if in fiche agent (agent contact or detail) page, if query variable 'agent' doesnt exists then redirect to 404 page

function my_page_template_redirect() {
	if ( get_page_template_slug() == "templates/fiche-agent.php" ) { // if only, current page templage is fiche agent
		global $wp_query;
		$agent_name = get_query_var( 'agent' );
		if ( isset( $agent_name ) ) {
			$agent_obj = get_user_by( "login", $agent_name );
			$agent_id  = $agent_obj->ID;
			if ( $agent_id ) {
				return; // if agent/user exists ino our database, bo nothing
			}
		}
		$wp_query->set_404(); // if query var 'agent' doesn't exists or agent doesnt exists, send to 404 page
		status_header( 404 );
		nocache_headers();
	}
}

add_filter( 'shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7_filter', 10, 3 ); // using the attribute from the contact 7 form shortcode
function custom_shortcode_atts_wpcf7_filter( $out, $pairs, $atts ) {
	$my_attr = 'agent-email';
	if ( isset( $atts[ $my_attr ] ) ) {
		$out[ $my_attr ] = $atts[ $my_attr ];
	}

	return $out;
}

function wpcf7_before_send_mail_function( $contact_form, $abort, $submission ) { // set the receipent mail as the agent dynamically from the form
	if ( (int) $contact_form->id == 1255 ) { // only if the contact form id is 1255 (i.e agent contact form)
		$post_data = $submission->get_posted_data();
		if ( ! isset( $post_data["agent-email"] ) ) {
			$abort = true;
			$submission->set_response( "An error happened" );

			return;
		}
		$receipent_email = $post_data["agent-email"];
//		$receipent_email                 = "webexpertsnepal13@gmail.com"; // for testing only
		$properties                      = $contact_form->get_properties();
		$properties['mail']['recipient'] = $receipent_email;
		$contact_form->set_properties( $properties );
	}

	return $contact_form;
}

add_filter( 'wpcf7_before_send_mail', 'wpcf7_before_send_mail_function', 10, 3 );

//register sidebar for the blog page

/**
 * Add a sidebar.
 */
/* Add Multiple sidebar
*/
add_action( 'widgets_init', 'my_factotum_register_sidebar' );

function my_factotum_register_sidebar() {
	register_sidebar( array(
		'name'          => 'Blog sidebar',
		'id'            => 'blog-sidebar-id',
		'description'   => 'This sidebar is for the Blog page.',
		'before_widget' => '<div id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

/**
 * Filter the categories archive widget to add a span around post count
 */
function my_factotum_cat_count_span( $links ) {
	$links = str_replace( '</a> (', '</a><sup class="post-count">(', $links );
	$links = str_replace( ')', ')</sup>', $links );

	return $links;
}

add_filter( 'wp_list_categories', 'my_factotum_cat_count_span' );


// search filter for default wordpress search (search only "post" post types)
function SearchFilter( $query ) {
	if ( $query->is_search ) {
		$query->set( 'post_type', 'post' );
	}

	return $query;
}

add_filter( 'pre_get_posts', 'SearchFilter' );

add_action( "wp_ajax_get_real_state_show_more_real_states_by_an_agent", "real_state_show_more_real_states_by_an_agent" ); // ajax response functionality to get more real states by single agent
add_action( "wp_ajax_nopriv_get_real_state_show_more_real_states_by_an_agent", "real_state_show_more_real_states_by_an_agent" ); //ajax response functionality to get more real states by single agent for not loggedin users
function real_state_show_more_real_states_by_an_agent() {
	if ( ! wp_verify_nonce( $_REQUEST['nonce'], "real_state_get_more_by_agent_nonce" ) ) { // if ajax request with invalid nonce
		exit( "No naughty business please" );
	}
	if ( empty( $_REQUEST["agent_email"] ) || ! isset( $_REQUEST["agent_email"] ) ) { // if search key value is not passed
		exit( "Empty Search Key" );
	}
	if ( empty( $_REQUEST["offset"] ) || ! isset( $_REQUEST["offset"] ) ) { // if search key value is not passed
		exit( "Empty Search Key" );
	}

	$args  = array(
		"post_type"      => "real-state-product",
		"post_status"    => "publish",
		"posts_per_page" => 3,
		'offset'         => $_REQUEST["offset"],
		'meta_key'       => '_avancement',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'emailnegociateur',
				'value'   => $_REQUEST["agent_email"],
				'compare' => '=',
			)
		)
	);
	$query = new WP_Query( $args );
	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$content = get_template_part( "template-parts/global/loop/content", "real-state" );

		}
		$value = ob_get_contents();
	}
	ob_end_clean();
	die( json_encode( array( "content" => $value ) ) );
}


//remove class current_page_parent from nav menu item "Actualites" (from  primary menu) when in custom post type or custom post type sinle
function my_factotum_nav_classes( $classes, $item ) {
	if ( ( is_post_type_archive( 'real-state-product' ) || is_singular( 'real-state-product' ) ) ) {
		$classes = array_diff( $classes, array( 'current_page_parent' ) );
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'my_factotum_nav_classes', 10, 2 );


























