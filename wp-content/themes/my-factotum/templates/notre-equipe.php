<?php
/** Template Name: notre-equipe */

get_header();
?>

    <main id="primary" class="site-main">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/global/section', 'banner' ); // render home banner section
				?>
                <div class="history-sec-wrap">
					<?php
					$history_sections = array(
						'agents',
						'experts',
						'providers'
					);
					foreach ( $history_sections as $section ) {
						get_template_part( 'template-parts/notre-equipe/section', $section ); // render all history sections
					}
					get_template_part( 'template-parts/global/section', 'join-us' ); // render global join us section
					?>
                </div>
			<?php

			endwhile;
		endif;
		?>

    </main><!-- #main -->

<?php
get_footer();
