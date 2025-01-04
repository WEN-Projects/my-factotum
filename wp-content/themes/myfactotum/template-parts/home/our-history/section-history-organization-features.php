<?php
// Check rows exists.
if ( have_rows( 'organizations_features' ) ) { //ogranization goals, features /visions list
	?>
    <section class="features-sec">
        <div class="container">
            <div class="row">
				<?php
				// Loop through rows.
				while ( have_rows( 'organizations_features' ) ) : the_row();
					// Load sub field value.

					$feature_title        = get_sub_field( 'feature_title' );
					$feature_content_type = get_sub_field( 'feature_content_type' );
					?>
                    <div class="col-lg-6">
						<?php
						echo $feature_title ? "<h2>" . $feature_title . "<span class='underline'></span></h2>" : "";
						if ( $feature_content_type == "image" ) {
							$feature_content_image = get_sub_field( 'feature_content_image' );
							if ( isset( $feature_content_image["ID"] ) ) {
								echo wp_get_attachment_image( $feature_content_image["ID"], 'full' );
							}
						} else {
							echo get_sub_field( 'content' );
						}

						?>
                    </div>
				<?php
					// End loop.
				endwhile;
				// Do something...
				?>
            </div>
        </div>
    </section><!-- .features-sec -->
	<?php
}
