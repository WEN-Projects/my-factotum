<?php
$agent_name = get_query_var( 'agent' ); //get agent's username from url
$agent_obj  = get_user_by( "login", $agent_name ); //get user details by username
if ( isset( $agent_obj->user_email ) ) {
	$real_states_count = my_factotum_count_realstate_by_agent( $agent_obj->user_email ); // get total realstate count associated with the agent, to display show more btn to display more realstates bt agent
	$args              = array(
		"post_type"      => "real-state-product",
		"post_status"    => "publish",
		"posts_per_page" => 3,
		'meta_key'   => '_avancement',
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => 'emailnegociateur',
				'value'   => $agent_obj->user_email,
				'compare' => '=',
			)
		)
	); // query get first 3 realstates associated with the agent
	$query             = new WP_Query( $args );
	if ( $query->have_posts() ) { // if result is found, render all the list of real state
		?>
        <section class="card-list-wrap agent-property">
            <div class="container">
                <h2><?php _e( "Les autres biens de cet agent", "factotum" ); ?></h2>
                <div class="card-list row">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part( "template-parts/global/loop/content", "real-state" ); //template for single real state product
					}
					?>
                </div>
                <?php
                    if($real_states_count>3){ // if the agent has more than 3 realstates, show the button view more
                        ?>
                        <div class="link-wrap bottom-links">
                            <div class="btn-wrap">
                                <a href="#" id="load-more-rs-btn"
                                   class="btn transparent purple"><?php _e( "Voir plus", "factotum" ); ?></a>
                            </div><!-- .btn-wrap -->
                        </div><!-- .link-wrap.clearfix -->
                            <?php
                    }
                ?>
            </div>
        </section>
		<?php
	}
	wp_reset_postdata();
}