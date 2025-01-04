<?php
/** Template Name: Nos Rejoindre */

get_header();
?>

    <main id="primary" class="site-main rejoindre-page">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				$rejoindre_sections = array( // all rejoindre sections
					'video-banner',
					'content'
				);
				foreach ( $rejoindre_sections as $section ) {
					get_template_part( 'template-parts/nos-rejoindre/section', $section ); // render all nos rejoindre sections
				}
				get_template_part( 'template-parts/global/section', 'join-us' ); // render global join us section
				?>
			<?php
			endwhile;
		endif;
		?>

    </main><!-- #main -->

<?php
get_footer();
