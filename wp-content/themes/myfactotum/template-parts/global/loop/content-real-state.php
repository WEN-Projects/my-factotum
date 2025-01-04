<?php
$id = get_the_ID();
if ( $id ) {
	$surface        = get_post_meta( $id, "surface", true ); //surface area
	$pieces         = get_post_meta( $id, "pieces", true ); // pieces
	$price          = get_post_meta( $id, "montant", true ); //price
	$chambers       = get_post_meta( $id, "chambres", true ); // chambers
	$avancement     = get_post_meta( $id, "avancement", true ); //is sold return
	$images         = get_post_meta( $id, "photo", true ); // images ids list of realstate
	$type_bien      = get_the_terms( $id, "type-bien" ); // realstate type ( Appartment,Land etc )
	$ville_publique = get_the_terms( get_the_ID(), "postal-ville" ); // villepublique
	?>
    <div class="col-lg-4 col-md-6">
        <div class="post-card match-height">
	        <?php
	        if ( $avancement == "Compromis" ) { ?>
                <span class="status-tag"><?php _e('Vendu', 'factotum'); ?></span><!-- .status-tag -->
	        <?php } ?>
            <div class="img-slider">
				<?php
				if ( ! empty( $images ) ) {
					foreach ( $images as $image ) {
						?>
                        <figure class="image-holder">
							<?php
							echo wp_get_attachment_image( $image, "full" );
							?>
                        </figure><!-- .image-holder -->
						<?php
					}
				} else {
					if ( ! empty( get_field( "default_real_state_image", "option" ) ) ) {
						$image_details = get_field( "default_real_state_image", "option" );
						if ( isset( $image_details["ID"] ) ) {
							?>
                            <figure class="image-holder">
								<?php
								echo wp_get_attachment_image( $image_details["ID"], "full" );
								?>
                            </figure><!-- .image-holder -->
							<?php
						}
					}
				}
				?>
            </div><!-- .img-slider -->
            <div class="info">
                <h4>
					<?php
					if ( ! empty( $type_bien ) ) {
						if ( ! is_wp_error( $type_bien ) ) {
							foreach ( $type_bien as $single ) {
								echo $single->name;
							}
						}
					}
					if ( ! empty( $pieces ) ) {
						echo " " . $pieces . __( " pièces", "factotum" );
					}
					?> -
					<?php echo $surface . " m<sup>2</sup>"; ?>
					<?php
					if ( ! empty( $ville_publique ) ) { //villepublique
						if ( ! is_wp_error( $ville_publique ) ) {
							foreach ( $ville_publique as $single ) {
								if ( $single->parent > 0 ) {
									echo "<br><strong>" . $single->name . "</strong>";
								}
							}
						}
					}
					?>
                </h4>
				<?php
				if ( $price ) {
					?>
                    <h4 class="price"><?php echo number_format( $price, null, ".", " " ); ?> €</h4>
					<?php
				}
				?>
                <div class="link-wrap">
                    <span class="size">
					<?php if ( $chambers ) {
						?>
						<?php echo $chambers; ?><?php _e( " chambres", "factotum" ); ?>
						<?php
					} ?>
                    </span>
                    <a href="<?php echo esc_url( get_permalink() ); ?>"
                       class="btn"><span><?php _e( "Voir la fiche", "factotum" ); ?></span>+</a>
                </div><!-- .link-wrap -->
            </div><!-- .info -->
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="stretched-link"></a>
        </div><!-- .card -->
    </div>
	<?php
}
?>