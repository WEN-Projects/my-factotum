<?php
$images = get_post_meta( get_the_ID(), 'photo', true );
if ( ! is_array( $images ) || empty( $images ) ) { // if no images, use default image set from backend
	$image_details = get_field( "default_real_state_image", "option" );
	if ( isset( $image_details["ID"] ) ) {
		$images = array( $image_details["ID"] );
	} else {
		$images = array();
	}
}

if ( ! empty( $images ) ) {
	?>
    <section class="realstate-hero">
        <div class="full-hero">
			<?php
			foreach ( $images as $image ) { ?>
                <figure class="banner-holder">
					<?php echo wp_get_attachment_image( $image, "full" ); ?>
                </figure>
				<?php
			}
			?>
        </div><!-- .full-hero -->
        <div class="hero-nav-container">
            <div class="container">
                <div class="hero-nav">
					<?php
                    $count = 1;
					foreach ( $images as $image ) { ?>
                        <figure class="thumb-holder" data-bs-toggle="modal" data-bs-target="#imageModal-<?php echo $count; ?>"><?php echo wp_get_attachment_image( $image, "thumbnail" ); ?></figure><!-- .thumb-holder -->
						<?php
						$count++;
					}
					?>

                </div><!-- .hero-nav -->
            </div>
        </div><!-- .hero-nav-container -->


	    <?php
	    $i = 1;
	    foreach ( $images as $image ) { ?>
            <!-- Modal -->
            <div class="modal fade" id="imageModal-<?php echo $i; ?>">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <!--                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                        <a href="javascript:void(0);" class="icon-close btn-close" data-bs-dismiss="modal">
                            <span></span>
                            <span></span>
                        </a>
                        <div class="modal-body text-center">
						    <?php echo wp_get_attachment_image( $image, "full" ); ?>
                        </div>
                    </div>
                </div>
            </div>

		    <?php
		    $i++;
	    }
	    ?>

    </section><!-- .realstate-hero -->
	<?php
}
