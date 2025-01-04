<section id="notre-histoire" class="image-content-layout">
	<?php
	// Check rows exists.
	if ( have_rows( 'image_with_content' ) ):

		// Loop through rows.
		while ( have_rows( 'image_with_content' ) ) : the_row();
			// Load sub field value.

			$image_position      = get_sub_field( 'image_position' ); // image positioned left or right
			$image               = get_sub_field( 'image' ); // content image
			$content_title       = get_sub_field( 'content_title' ); // content title
			$content_description = get_sub_field( 'content_description' ); // content description
			?>

            <div class="container-fluid <?php echo $image_position; ?>">
                <div class="row no-gutters match-height">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6 image-col">
                        <div class="animate-image-wrap">
                            <div class="overlay-top"></div>
                            <div class="overlay-right"></div>
                            <img class="animate-image" data-is-rendered="false" src="<?php echo wp_get_attachment_image_url( $image["ID"], 'full' ); ?>" alt="">
                        </div><!-- .animate-image-wrap -->
                    </div>
                </div>
                <div class="container match-height">
                    <div class="row">
                        <div class="content-col col-lg-6">
                            <h2><?php echo $content_title; ?> <span class="underline"></span></h2>
							<?php echo $content_description; ?>
                            <div class="colored-bg"></div>
                        </div>
                        <div class="col-lg-6">

                        </div>
                    </div>
                </div>
            </div>
			<?php
			// End loop.
		endwhile;

// No value.
	else :
		// Do something...
	endif;
	?>
</section>

<?php
