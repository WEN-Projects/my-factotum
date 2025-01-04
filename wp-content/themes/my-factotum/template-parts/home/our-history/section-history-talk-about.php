<?php
$talk_about_us_title = get_field( "talk_about_us_title" );  // title to be displayed(left)
$image               = get_field( "talk_about_us_image" );  // image to be displayed(right)
if ( $talk_about_us_title || $image ) {
	?>
    <section class="talk-about-sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 title-col">
					<?php
					echo $talk_about_us_title ? "<h2>" . $talk_about_us_title . "<span class='underline white'></span></h2>" : "";
					?>
                </div>
                <div class="col-sm-6">

					<?php
					if ( isset( $image["ID"] ) ) {
						?>
                        <a href="<?php echo $image["url"]; ?>" data-bs-toggle="modal" data-bs-target="#imageModal">
							<?php
							echo wp_get_attachment_image( $image["ID"], "full" );
							?>
                        </a>

                        <!-- Modal -->
                        <div class="modal fade" id="imageModal">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <!--                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                                    <a href="javascript:void(0);" class="icon-close btn-close" data-bs-dismiss="modal">
                                        <span></span>
                                        <span></span>
                                    </a>
                                    <div class="modal-body text-center">
										<?php
										echo wp_get_attachment_image( $image["ID"], "full" );
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
					}
					?>

                </div>
            </div>
    </section><!-- .talk-about-sec -->
	<?php
}

