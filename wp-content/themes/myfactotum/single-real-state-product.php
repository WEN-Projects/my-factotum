<?php
/** Template for single realstate  */
get_header();
?>

    <main id="primary" class="site-main">
		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				$single_realstate_sections = array(
					'realstate-gallery',
					'realstate-description',
				);
				foreach ( $single_realstate_sections as $section ) {
					get_template_part( 'template-parts/single-real-state/section', $section ); // render all single realstate sections
				}
			endwhile;
		endif;
		?>

    </main><!-- #main -->

<?php
get_footer();
