<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package my_factotum
 */

get_header();
?>

    <main id="primary" class="site-main blog-page">
		<?php
		get_template_part( 'template-parts/actualites/section', 'banner' );
		?>

        <section class="blog-content">
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
								get_template_part( "template-parts/actualites/loop/single-blog" );
							endwhile;
							get_template_part( "template-parts/actualites/section", "pagination" );
						else :
							get_template_part( 'template-parts/actualites/content', 'none' );
						endif; ?>
                    </div>
					<?php
					get_template_part( 'template-parts/actualites/section', 'sidebar' );
					?>
                </div>
            </div>
        </section><!-- .blog-content -->
    </main><!-- #main -->

<?php
get_footer();
