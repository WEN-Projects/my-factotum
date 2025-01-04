<?php
/** Template Name: Nos Services */

get_header();
?>

    <main id="primary" class="site-main services-page">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/global/section', 'banner' ); // render home banner section
				$services_sections = array(
					'customer-packs'
				);
				foreach ( $services_sections as $section ) {
					get_template_part( 'template-parts/nos-services/section', $section ); // render all history sections
				}
				get_template_part( 'template-parts/global/section', 'join-us' ); // render global join us section
			endwhile;
		endif;
		?>

    </main><!-- #main -->

<?php
get_footer();
