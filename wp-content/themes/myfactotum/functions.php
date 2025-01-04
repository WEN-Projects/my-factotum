<?php
/**
 * my factotum functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package my_factotum
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'my_factotum_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function my_factotum_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on my factotum, use a find and replace
		 * to change 'my-factotum' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'my-factotum', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'my-factotum' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'my_factotum_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'my_factotum_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function my_factotum_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'my_factotum_content_width', 640 );
}

add_action( 'after_setup_theme', 'my_factotum_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function my_factotum_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'my-factotum' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'my-factotum' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'widgets_init', 'my_factotum_widgets_init' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

//register all custom post types and taxonomies
if ( file_exists( get_template_directory() . "/inc/custom-post-types.php" ) ) {
	require_once( get_template_directory() . "/inc/custom-post-types.php" );
}

//register meta boxes and meta fields (custom fields)
if ( file_exists( get_template_directory() . "/inc/custom-fields.php" ) ) {
	require_once( get_template_directory() . "/inc/custom-fields.php" );
}

//xml importer functionality for importing the realstate products
if ( file_exists( get_template_directory() . "/inc/xml-importer/class-realstate-xml-importer.php" ) ) {
	require_once( get_template_directory() . "/inc/xml-importer/class-realstate-xml-importer.php" );
}

//functionality for Real state wishlist module
if ( file_exists( get_template_directory() . "/inc/wishlist/class-realstate-wishlist.php" ) ) {
	require_once( get_template_directory() . "/inc/wishlist/class-realstate-wishlist.php" );
}

//functionality for Real state alerte module
if ( file_exists( get_template_directory() . "/inc/alerte/class-realstate-alerte.php" ) ) {
	require_once( get_template_directory() . "/inc/alerte/class-realstate-alerte.php" );
}

//functionality for Send in blue library
if ( file_exists( get_template_directory() . "/inc/sendinblue-api/sendin-blue-api-lib.php" ) ) {
	require_once( get_template_directory() . "/inc/sendinblue-api/sendin-blue-api-lib.php" );
}

//functionality for Send in blue library
if ( file_exists( get_template_directory() . "/inc/wc-customizer.php" ) ) {
	require_once( get_template_directory() . "/inc/wc-customizer.php" );
}

//user roles and accessibility customizer
if ( file_exists( get_template_directory() . "/inc/wc-membership-and-roles-customizer.php" ) ) {
	require_once( get_template_directory() . "/inc/wc-membership-and-roles-customizer.php" );
}

//user login, registration and access handler
if ( file_exists( get_template_directory() . "/inc/user-login-registration-handler.php" ) ) {
	require_once( get_template_directory() . "/inc/user-login-registration-handler.php" );
}


