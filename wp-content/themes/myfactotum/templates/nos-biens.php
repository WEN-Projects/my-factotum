<?php
/** Template Name: Nos Biens */

get_header();
?>

    <main id="primary" class="site-main">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				$nos_biens_sections = array(
					'search-form',
					'real-state-listing',
					'join-us'
				);
				foreach ( $nos_biens_sections as $section ) {
					get_template_part( 'template-parts/nos-biens/section', $section ); // render all nos biens sections
				}
			endwhile;
		endif;
		?>

    </main><!-- #main -->

<?php
get_footer();
