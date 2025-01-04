<?php
/** Template Name: woocommerce-factotum */

get_header();
?>
    <main id="primary" class="site-main wc-dashboard-page">

		<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				?>
					<?php
					the_content();
					?>
				<?php

			endwhile;
		endif;
		?>

    </main><!-- #main -->

<?php
get_footer();
