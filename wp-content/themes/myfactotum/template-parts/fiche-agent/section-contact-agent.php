<?php
$agent_name   = get_query_var( 'agent' ); //get agent's username from url
$agent_detail = get_user_by( "login", $agent_name ); //get user details by username
if ( $agent_detail ) { // if agent exists in our database
	$agent_id    = $agent_detail->ID;
	$agent_email = get_the_author_meta( 'email', $agent_id ); //get all the agent details
	?>
    <section class="contact-agent">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 contact-col">
                    <div class="form-wrap">
                        <h2><?php _e( "Contacter votre agent", "factotum" ); ?></h2>
						<?php echo do_shortcode( '[contact-form-7 id="1255" title="Contact Agent" agent-email="'.$agent_email.'"]' ); ?>
                    </div><!-- .form-wrap -->
                </div><!-- .contact-col -->
                <div class="col-lg-6 agent-info">

                    <h2><?php the_author_meta( 'display_name', $agent_id ); ?></h2>
                    <div class="row">
                        <div class="col-lg-6">
                            <figure class="agent-image-holder">
								<?php
								if ( function_exists( "my_factotum_get_user_profile_image" ) ) {
									echo my_factotum_get_user_profile_image( $agent_id );
								}
								?>
                            </figure>
                        </div>
                        <div class="col-lg-6">
                            <div class="agent-detail">
                                <p>
                                    <strong class="mobile-visible"><?php the_author_meta( 'display_name', $agent_id ); ?></strong><br><?php
									echo get_field( "user_description", 'user_' . $agent_id ) // get description of the user
									?>
                                </p>
                                <p>
									<?php
									$agent_phone_no = get_field( "contact_number", 'user_' . $agent_id ); // get phoneno of the user
									if ( $agent_phone_no ) {
										?>
                                        <a href="tel: <?php echo $agent_phone_no; ?>"><?php echo $agent_phone_no; ?></a>
										<?php
									}
									?><br>
                                    <a href="mailto:<?php echo $agent_email; ?>"><?php echo $agent_email; ?></a>
                                </p>
                                <p class="spacer">&nbsp;</p><!-- .spacer -->
                                <a href="#" class="toggle-detail mobile-visible"><span>+</span>voir plus</a>
								<?php
								if ( get_field( "user_description_detail", 'user_' . $agent_id ) ) {
									?>
                                    <div class="detail-info">
										<?php
										echo get_field( "user_description_detail", 'user_' . $agent_id ) // get description of the user
										?>
                                    </div><!-- .detail-info -->
									<?php
								}
								?>
                                <a href="#" id="scroll-to-contact-form-btn" class="btn white mobile-visible"><?php _e("Contactez-le","factotum");?></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section><!-- .contact-agent -->
	<?php
}
?>