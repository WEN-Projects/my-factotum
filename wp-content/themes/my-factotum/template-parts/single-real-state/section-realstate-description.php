<?php
global $post;

?>

<section class="realestate-disc">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 detail-col">
                <p class="location-title">
					<?php
					$ville_publique = get_the_terms( get_the_ID(), "postal-ville" ); // villepublique
					if ( ! empty( $ville_publique ) ) {
						if ( ! is_wp_error( $ville_publique ) ) {
							foreach ( $ville_publique as $single ) {
								if ( $single->parent > 0 ) {
									echo "<strong>" . $single->name . "</strong>";
								}
							}
						}
					}
					//					echo get_post_meta( get_the_ID(), 'adresse', true ); //adresse
					//
					//					if ( ! empty( $ville_publique ) ) { //codePostalPublic
					//						if ( ! is_wp_error( $ville_publique ) ) {
					//							foreach ( $ville_publique as $single ) {
					//								if ( $single->parent == 0 ) {
					//									echo ", " . $single->name;
					//								}
					//							}
					//						}
					//					}
					?>
                </p>
				<?php
				$type_bien = get_the_terms( get_the_ID(), "type-bien" ); // realstate type ( Appartment,Land etc )

				$surface = get_post_meta( get_the_ID(), 'surface', true ); //surface
				$montant = get_post_meta( get_the_ID(), 'montant', true ); //montant - price
				$pieces  = get_post_meta( get_the_ID(), "pieces", true ); // pieces
				$texte   = get_the_content(); //texte - description
				?>
                <h2 class="property-title">
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
					?>
                    - <?php echo ! empty( $surface ) ? $surface . " m<sup>2</sup>" : ""; ?></h2>
				<?php
				if ( $montant ) {
					?>
                    <h3 class="price">
						<?php echo number_format( $montant, null, ".", " " ); ?> €
                    </h3>
					<?php
				}
				?>

                <div class="wishlist-col show-on-small-screen">
                    <a href="#" class="add-to-favorite"><img
                                src="<?php echo get_template_directory_uri(); ?>/images/heart-linier.svg"
                                alt=""><?php _e( "Ajouter
                        aux favoris", "factotum" ); ?></a>
                </div><!-- .wishlist-col -->
				<?php
				if ( ! empty( $texte ) ) {
					echo "<p>" . $texte . "</p>";
				}

				?>
				<?php

				$chambres = get_post_meta( get_the_ID(), 'chambres', true ); //chambres - 0 (Number)
				$sdb      = get_post_meta( get_the_ID(), 'sdb', true ); //sdb - 0 (Number)
				$cave     = get_post_meta( get_the_ID(), 'cave', true ); //sdb - 0 (Number)


				$real_state_features_fields = array( //all NON or OUI fields (where OUI refers true and NON refers false)
					array( "nbparking", "Nb parking" ), //first element is key and second one is text
					array( "acceshandicape", "Acces handicape" ),
					array( "alarme", "Alarme" ),
					array( "ascenseur", "Ascenseur" ),
					array( "balcon", "Balcon" ),
					array( "bureau", "Bureau" ),
					array( "cellier", "Cellier" ),
					array( "dependances", "Dependances" ),
					array( "dressing", "Dressing" ),
					array( "gardien", "Gardien" ),
					array( "interphone", "Interphone" ),
					array( "lotissement", "Lotissement" ),
					array( "meuble", "Meuble" ),
					array( "mitoyenne", "Mitoyenne" ),
					array( "piscine", "Piscine" ),
					array( "terrasse", "Terrasse" )
				);


				?>
                <ul class="property-feature">
					<?php
					if ( $chambres ) {
						?>
                        <li><?php echo $chambres ?><?php _e( " chambres", "factotum" ); ?></li>
						<?php
					}
					?>
					<?php
					if ( $sdb ) {
						?>
                        <li><?php echo $sdb ?><?php _e( " salles de bains", "factotum" ); ?></li>
						<?php
					}
					if ( $cave == "oui" ) {
						?>
                        <li><?php _e( "1 cave", "factotum" ); ?></li>
						<?php
					}
					?>
					<?php
					foreach ( $real_state_features_fields as $feature ) {
						echo get_post_meta( get_the_ID(), $feature[0], true ) == "oui" ? "<li>" . $feature[1] . "</li>" : ""; //if NON or OUI
						echo (int ) get_post_meta( get_the_ID(), $feature[0], true ) > 0 ? "<li>" . $feature[1] . "</li>" : ""; //if numbers
					}
					?>
                </ul><!-- .property-feature -->
            </div><!-- .detail-col -->
            <div id="add-to-wishlist" class="col-lg-3 wishlist-col hide-on-small-screen">
				<?php
				if ( class_exists( "MY_Factotum_Wishlist" ) ) {
					global $MY_Factotum_Wishlist;
					if ( $MY_Factotum_Wishlist->isRealStateInWishlist( get_the_ID() ) ) {
						get_template_part( "template-parts/single-real-state/favoris/remove-from-wishlist" );
					} else {
						get_template_part( "template-parts/single-real-state/favoris/add-to-wishlist" );
					}
				}
				?>
            </div><!-- .wishlist-col -->
            <div class="col-lg-3 agent-col">
				<?php
				$agent_email = get_post_meta( get_the_ID(), 'emailnegociateur', true ); // if realstate has an agent and email exists
				if ( $agent_email ) {
					$agent_detail = get_user_by( "email", $agent_email );
					if ( $agent_detail ) { // if agent exists in our database
						$agent_id = $agent_detail->ID;
						?>
                        <div class="agent-box">
                            <h4><?php _e( "Votre agent", "factotum" ); ?></h4>
                            <figure class="agent-image-holder">
								<?php
								if ( function_exists( "my_factotum_get_user_profile_image" ) ) {
									echo my_factotum_get_user_profile_image( $agent_id );
								}
								?>
                            </figure>
                            <p><strong><?php the_author_meta( 'display_name', $agent_id ); ?></strong><br>
								<?php
								echo get_field( "user_description", 'user_' . $agent_id ) // get description of the user
								?>
                            </p>
							<?php
							$contact_page = get_field( "agent_contact_page", "option" );
							if ( $contact_page ) {
								$contact_agent_url = $contact_page . $agent_detail->user_login;
								?>
                                <a href="<?php echo esc_url( $contact_agent_url ); ?>"
                                   class="btn white"><?php _e( "Contactez-le", "factotum" ); ?></a>
								<?php
							}
							?>

                        </div><!-- .agent-box -->
						<?php
					}
				}
				?>

            </div><!-- .agent-col -->
        </div>
    </div>
</section><!-- .realestate-disc -->