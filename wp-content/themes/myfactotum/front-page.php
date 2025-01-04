<?php
/**
 * My Factotum Home Template file
 *
 */

get_header();
?>

    <main id="primary" class="site-main">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/global/section', 'banner' ); // render banner section
				?>
                <div class="history-sec-wrap panel">
					<?php
					$history_sections = array(
						'history-description', // histroy description section
						'history-team', // Team section
						'history-video', // history video section
						'history-organization-features', // organization features,goals,visions
						'history-talk-about', // talk about us section
					);
					foreach ( $history_sections as $section ) {
						get_template_part( 'template-parts/home/our-history/section', $section ); // render all history sections
					}
					get_template_part( 'template-parts/global/section', 'join-us' ); // render banner section
					get_template_part( 'template-parts/home/our-history/section', 'history-footer' ); // render banner section
					?>
                </div>
			<?php

			endwhile;
		endif;
		?>

    </main><!-- #main -->

<?php
get_footer();
