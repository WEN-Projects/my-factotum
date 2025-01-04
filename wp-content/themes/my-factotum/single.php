<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package my_factotum
 */

get_header();
?>

    <main id="primary" class="site-main article-page">
		<?php
		while ( have_posts() ) :
			the_post();
			global $post;
			?>
            <section class="post-hero">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
							<?php my_factotum_post_thumbnail(); ?>
                        </div>
                    </div>
                </div>
            </section><!-- .post-hero -->
            <section class="blog-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-1 content-col">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
							<?php
							if ( 'post' === get_post_type() ) :
								?>
                                <div class="entry-meta">
									<?php
									foreach ( ( get_the_category() ) as $category ) { ?>
                                        <strong><?php echo $category->cat_name . ' '; ?></strong>
									<?php }
									?>
									<?php the_time( 'j F Y' ); ?>
                                </div><!-- .entry-meta -->
							<?php endif; ?>
                            <div class="default-content">
								<?php the_content(); ?>
                            </div>
                            <div class="author-row">
                                <p><strong><?php echo get_the_author(); ?></strong><br>
									<?php
									echo get_field( "user_description", 'user_' . $post->post_author ) // get description of the user
									?></p>
                            </div>
                        </div>
                        <div class="col-lg-3 offset-lg-1 sidebar-col">
							<?php dynamic_sidebar( 'blog-sidebar-id' ); ?>
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
