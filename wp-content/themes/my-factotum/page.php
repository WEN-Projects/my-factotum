<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package my_factotum
 */

get_header();
?>

    <main id="primary" class="site-main default-page">
		<?php
		while ( have_posts() ) :
			the_post();
			global $post;
			?>
            <section class="post-hero">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
							<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
							<?php my_factotum_post_thumbnail(); ?>
                        </div>
                    </div>
                </div>
            </section><!-- .post-hero -->
            <section class="blog-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1 content-col">
                            <div class="default-content">
								<?php get_template_part( 'template-parts/content', 'page' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section><!-- .blog-content -->
			<?php
		endwhile; // End of the loop.
		?>

    </main><!-- #main -->

<?php
get_footer();
