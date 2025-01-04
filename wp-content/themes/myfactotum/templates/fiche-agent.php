<?php
/** Template Name: Fiche Agent */

get_header();
$agent_name = get_query_var( 'agent' );
$agent_obj = get_user_by( "login", $agent_name );
$agent_id  = $agent_obj->ID;
$agent_obj->user_email;
?>

    <main id="primary" class="site-main">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				$fiche_agent_sections = array(
					'contact-agent',
					'agent-realstates-listing',
				);
				foreach ( $fiche_agent_sections as $section ) {
					get_template_part( 'template-parts/fiche-agent/section', $section ); // render all fiche agent sections
				}
				?>
			<?php
			endwhile;
		endif;
		?>
    </main><!-- #main -->

<?php
get_footer();
