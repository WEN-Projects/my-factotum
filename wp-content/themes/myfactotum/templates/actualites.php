<?php
/** Template Name: actualites  */
get_header();
?>

    <main id="primary" class="site-main">

        <?php
        if ( have_posts() ) :
            /* Start the Loop */
            while ( have_posts() ) :
                the_post();
            endwhile;
        else :
            get_template_part( 'template-parts/content', 'none' );
        endif;
        ?>

    </main><!-- #main -->

<?php
get_footer();

