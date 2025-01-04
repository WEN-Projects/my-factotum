<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package my_factotum
 */

get_header();
?>

    <main id="primary" class="site-main page-without-banner">

        <section class="error-404 not-found bg-pink">
            <div class="container">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! Cette page est introuvable.', 'my-factotum' ); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <p><?php esc_html_e( 'Il semble que rien n\'a été trouvé à cet endroit. Essayez peut-être l\'un des liens ci-dessous ou une recherche?', 'my-factotum' ); ?></p>

					<?php
					get_search_form();

					//the_widget( 'WP_Widget_Recent_Posts' );
					?>
                </div><!-- .page-content -->
            </div>
        </section><!-- .error-404 -->

    </main><!-- #main -->

<?php
get_footer();
