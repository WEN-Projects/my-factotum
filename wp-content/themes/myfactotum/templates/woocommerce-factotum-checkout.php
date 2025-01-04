<?php
/** Template Name: woocommerce-factotum Checkout */

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
                        <div class="col-lg-12">
                            <h1><?php the_title(); ?></h1>
                        </div>
                    </div>
                </div>
            </section><!-- .post-hero -->
			<section class="checkout-wrap blog-content">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 content-col">
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
