<?php
$banner_image = get_field( "banner_background_image" ); //banner type
$imageUrl     = isset( $banner_image["id"] ) ? wp_get_attachment_image_url( $banner_image["id"], 'full' ) : "";
?>
<section class="hero biens-hero"
         style="background: url('<?php echo esc_url( $imageUrl ); ?>') no-repeat center; background-size: cover;">
    <div class="container">
        <div class="hero-content">
			<?php
			$active_tab = isset( $_GET['type'] ) ? ( $_GET['type'] == 'categorie-offre-location' ? 1 : 0 ) : 0;
			?>
            <form id="real-state-search-form-vente" action="<?php echo home_url(); ?>/nos-biens"
                  method="GET">
                <div class="form-inner">
                    <div class="property-tab">
                        <div class="inner">
                            <input type="radio" class="vente-tab" <?php echo 'checked'; ?> name="type"
                                   value="categorie-offre-vente" id="tab1" checked/>

                            <label for="tab1"><span><?php _e( "Nos biens en ", "factotum" ); ?></span> <?php _e( "Vente", "factotum" ); ?></label>
                            <input type="radio" class="location-tab" <?php echo $active_tab ? 'checked' : ''; ?> name="type"
                                   value="categorie-offre-location" id="tab2"/>
                            <label for="tab2"><span><?php _e( "Nos biens en ", "factotum" ); ?></span> <?php _e( "Location", "factotum" ); ?></label>

                        </div><!-- .inner -->
                    </div><!-- .property-tab -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active"
                             id="sale-form">
                            <div class="property-search-form">

                                <div class="location-wrap">
                                    <a href="javascript:void(0)" id="show-criteria-form-btn"
                                       class="abs-link"><?php _e( "+ de critères", "factotum" ); ?></a>
                                    <select id="js-zipcode-multiple" class="js-zipcode-multiple" name="location[]"
                                            multiple="multiple">
                                    </select>
                                </div>

                                <div class="criteria-form">
                                    <div class="specification-wrap">
                                        <input type="text" placeholder="Nombre de piéces min." name="pieces"
                                               value="<?php echo isset( $_GET["pieces"] ) ? $_GET["pieces"] : ""; ?>">
                                        <input type="text" placeholder="Surface min." name="min_surface"
                                               value="<?php echo isset( $_GET["min_surface"] ) ? $_GET["min_surface"] : ""; ?>">
                                        <input type="text" placeholder="Budget max." name="max_price"
                                               value="<?php echo isset( $_GET["max_price"] ) ? $_GET["max_price"] : ""; ?>">
                                    </div>

                                    <div class="type-wrap">
                                        <select id="js-type-basic-single" class="js-type-basic-single" name="category">
											<?php
											$selected_category = isset( $_GET["category"] ) ? ( ! empty( $_GET["category"] ) ? $_GET["category"] : "" ) : "";
											?>
                                            <option value="0"><?php _e( "Tout type", "factotum" ); ?></option>
											<?php
											$terms = get_terms( array(
												'taxonomy'   => 'type-bien',
												'hide_empty' => false,
											) );
											if ( ! empty( $terms ) ) {
												foreach ( $terms as $term ) {
													?>
                                                    <option <?php echo $selected_category == $term->slug ? "selected" : ""; ?>
                                                            value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
													<?php
												}
											}

											?>
                                        </select>
                                        <div class="action-wrap">
                                            <a ref="javascript:void(0)" id="reset-btn" class="reset-btn"><span>z</span></a>
                                            <button type="submit"
                                                    class="btn"><?php _e( "Rechercher", "factotum" ); ?></button>
                                        </div>

                                    </div>
                                </div>

                            </div><!-- .property-search-form -->
                        </div>
                    </div>
                </div><!-- .form-inner -->
                <button id="simple-search-submit-btn" type="submit"
                        class="btn"><?php _e( "Rechercher", "factotum" ); ?></button>
            </form>
        </div><!-- .hero-content -->
    </div>
</section><!-- .biens-hero -->