<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package my_factotum
 */

get_header();
?>

    <main id="primary" class="site-main search-page">
        <section class="hero blog-hero"
                 style="background: url('<?php echo home_url(); ?>/wp-content/uploads/2021/07/D_Actualites_01.jpg') no-repeat center; background-size: cover;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 offset-lg-1">
                        <div class="hero-content">
                            <h1 class="banner-title">
								<?php
								/* translators: %s: search query. */
								printf( esc_html__( 'Résultats de la recherche pour : %s', 'my-factotum' ), '<span>' . get_search_query() . '</span>' );
								?>
                            </h1>
                        </div><!-- .hero-content -->
                    </div>
                </div>
            </div>
        </section><!-- .hero.blog-hero -->
        <section class="search-content bg-pink-light">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 show-on-mobile">
						<?php dynamic_sidebar( 'blog-sidebar-id' ); ?>
                    </div>
                    <div class="col-lg-6 offset-lg-1 content-col">
						<?php
						if ( have_posts() ) :
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();
								get_template_part( 'template-parts/content', 'search' );
							endwhile;
							get_template_part( "template-parts/actualites/section", "pagination" );
						else :
							get_template_part( 'template-parts/content', 'none' );
						endif; ?>
                    </div>
					<?php
					get_template_part( 'template-parts/actualites/section', 'sidebar' );
					?>
                </div>
            </div>
        </section><!-- .search-content -->

    </main><!-- #main -->

<?php
get_footer();
