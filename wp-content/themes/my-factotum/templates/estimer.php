<?php
/** Template Name: Estimer */

get_header();
?>
    <main id="primary" class="site-main estimer-page">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/global/section', 'banner' ); // render home banner section
				?>

                <section class="contact-form-wrap bg-pink-light">
                    <div class="container">
						<?php
						echo get_field( "form_title_description" ); //title before contact form
						?>

						<?php echo do_shortcode( '[contact-form-7 id="2155" title="Estimer form"]' ); ?>
                    </div>
                    <div class="short-info">
                        <div class="container">
							<?php
							//content after contact form
							if ( get_field( "contact_title" ) ) {
								echo "<h2>" . get_field( "contact_title" ) . "</h2>";
							}
							?>
                            <div class="two-col-content">
								<?php
								echo get_field( "contact_content" );
								?>

                            </div>
                        </div>
                    </div>
                </section><!-- .contact-form-wrap -->
			<?php

			endwhile;
		endif;
		?>
    </main><!-- #main -->
<?php
get_footer();
