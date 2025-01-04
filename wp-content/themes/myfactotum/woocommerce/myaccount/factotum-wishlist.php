<div id="rs-wishlist">
    <h2><?php _e( 'Favoris', 'factotum' ) ?></h2>
    <div class="row">
		<?php
		//template to display all user's wishlist of realstates
		global $MY_Factotum_Wishlist;
		$wishlist = $MY_Factotum_Wishlist->getAllWishlists(); //get all wishlist of the current user
		if ( ! empty( $wishlist ) ) { // if the current user has realstates
			foreach ( $wishlist as $item ) {
				?>
				<?php
				$id = $item;
				if ( $id ) {
					$surface        = get_post_meta( $id, "surface", true ); // surface area of realstate
					$pieces         = get_post_meta( $id, "pieces", true ); // pieces
					$price          = get_post_meta( $id, "montant", true ); // price of the realstate
					$chambers       = get_post_meta( $id, "chambres", true ); // chambers
					$images         = get_post_meta( $id, "photo", true ); // all images of the realstate
					$type_bien      = get_the_terms( $id, "type-bien" ); // realstate type ( Appartment,Land etc )
					$ville_publique = get_the_terms( $id, "postal-ville" ); // villepublique
					?>
                    <div class="col-lg-6 col-md-6">
                        <div class="post-card match-height">
                            <div class="img-slider">
								<?php
								if ( ! empty( $images ) ) {
									foreach ( $images as $image ) { // render all realstate images
										?>
                                        <figure class="image-holder">
											<?php
											echo wp_get_attachment_image( $image, "full" );
											?>
                                        </figure><!-- .image-holder -->
										<?php
									}
								} else {
									if ( ! empty( get_field( "default_real_state_image", "option" ) ) ) { // if the realstate do not have any image then, display the default image set from backend option
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
                                    <h4 class="price"><?php echo number_format( $price, null, ".", " " ); ?>
                                        €</h4>
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
                                    <a href="<?php echo esc_url( get_permalink( $id ) ); ?>"
                                       class="btn"><span><?php _e( "Voir la fiche", "factotum" ); ?></span>+</a>
                                </div><!-- .link-wrap -->
                            </div><!-- .info -->
                            <a href="<?php echo esc_url( get_permalink( $id ) ); ?>"
                               class="stretched-link"></a>
                        </div><!-- .card -->
                        <a href="javascript:void(0)" class="add-to-trash rs-item" itemid="<?php echo $item; ?>"
                           id="rs-item-<?php echo $item; ?>"><?php _e( "Supprimer", "factotum" ); ?></a>
                    </div>
					<?php
				}
				?>
				<?php
			}
		} else { //if the current user do not have realstate, display empty message
		    ?>
            <div class="col-lg-12">
				<?php
				_e( "<h4>Votre liste est vide</h4>", "factotum" );
				?>
            </div>
			<?php
		} ?>

    </div>
</div>