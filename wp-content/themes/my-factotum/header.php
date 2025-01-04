<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package my_factotum
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<?php
$inner_page_class = ! is_front_page() ? "inner-page" : "";
?>
<body <?php body_class( $inner_page_class ); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'my-factotum' ); ?></a>

    <header id="masthead" class="site-header">
        <div class="top-header">
            <div class="container">
                <div class="pointer-logo">
                    <img class="svg" src="<?php echo home_url(); ?>/wp-content/uploads/2021/06/MF_isotype.svg" alt="">
                </div>
                <div class="site-branding">
					<?php
					the_custom_logo();
					if ( is_front_page() && is_home() ) :
						?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                               rel="home"><?php bloginfo( 'name' ); ?></a>
                        </h1>
					<?php
					else :
						?>
                        <p class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                               rel="home"><?php bloginfo( 'name' ); ?></a>
                        </p>
					<?php
					endif;
					$my_factotum_description = get_bloginfo( 'description', 'display' );
					if ( $my_factotum_description || is_customize_preview() ) :
						?>
                        <p class="site-description"><?php echo $my_factotum_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?></p>
					<?php endif; ?>
                </div><!-- .site-branding -->
            </div>
        </div><!-- .top-header -->
        <div class="nav-header">
            <div class="container">
                <div class="quick-links">
					<?php
					if ( ! is_user_logged_in() ) {
						?>
                        <a href="#" class="my-account"><?php _e( "Mon compte", "factotum" ); ?></a>
						<?php
					} else {
						?>
                        <a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"
                           class="my-account-white"><?php _e( "Mon compte", "factotum" ); ?></a>
						<?php
					}
					echo do_shortcode( '[gtranslate]' );
					?>
                </div>
                <div class="trigger-menu d-block d-xl-none">
					<?php _e( "Menu", "factotum" ); ?>
                    <a href="javascript:void(0);">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div><!-- .trigger-menu -->
                <nav id="site-navigation" class="main-navigation">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
					?>
                </nav><!-- #site-navigation -->
            </div>
        </div><!-- .nav-header -->
    </header><!-- #masthead -->

	<?php
	get_sidebar( "wc-login" ); //sidebar to popup wc login form
	?>


