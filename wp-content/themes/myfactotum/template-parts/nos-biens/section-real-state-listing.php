<section class=" card-list-wrap">
    <div class="container">
        <?php
        $to_exclude = array();
        $paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        if ( 1 < $paged ) {
            $to_exclude = get_posts(
                array(
                    "post_type"      => "real-state-product",
                    "post_status"    => "publish",
                    "posts_per_page" => 9,
                    "fields"         => "ids",
                    "orderby"        => 'meta_value_num',
                    "meta_key"       => '_avancement',
                    "order"          => 'ASC',
                )
            );
        }
        $is_search_param_empty = true; // if search parameter is left empty, donot show add alerte button
        $meta_query            = array();
        if ( isset( $_GET["pieces"] ) ) {
            if ( ! empty( $_GET["pieces"] ) ) {
                $is_search_param_empty = false;
                $meta_query[]          = array(
                    'key'     => 'pieces',
                    'value'   => (int) $_GET["pieces"],
                    'compare' => '>=',
                );
            }
        }
        if ( isset( $_GET["max_price"] ) ) {
            if ( ! empty( $_GET["max_price"] ) ) {
                $is_search_param_empty = false;
                $meta_query[]          = array(
                    'key'     => 'montant',
                    'value'   => (int) $_GET["max_price"],
                    'compare' => '<=',
                    'type'    => 'UNSIGNED'
                );
            }
        }
        if ( isset( $_GET["min_surface"] ) ) {
            if ( ! empty( $_GET["min_surface"] ) ) {
                $is_search_param_empty = false;
                $meta_query[]          = array(
                    'key'     => 'surface',
                    'value'   => $_GET["min_surface"],
                    'compare' => '>=',
                    'type'    => 'UNSIGNED'
                );
            }
        }

        $args = array(
            "post_type"      => "real-state-product",
            "post_status"    => "publish",
            "posts_per_page" => 9,
            'paged'          => $paged
        );

        $tax_query = array();
        if ( isset( $_GET["type"] ) ) {
            if ( ! empty( $_GET["type"] ) ) {
                $tax_query[] =
                    array(
                        'taxonomy'         => 'categorie-offre',
                        'field'            => 'slug',
                        'terms'            => $_GET["type"],
                        'include_children' => false
                    );
            }
        }
        if ( isset( $_GET["location"] ) ) {
            if ( ! empty( $_GET["location"] ) ) {
                $location_slugs = array(); //all location slugs to be searched

                foreach ( $_GET["location"] as $location ) {
                    if ( strpos( $location, 'postal-' ) !== false || strpos( $location, 'ville-' ) ) { // if selected from the available term slugs
                        $location_slugs[] = $location;
                    } else { // if free text is entered in location field
                        $location_terms = get_terms( [ //get all terms which contain the string $location on it's slug
                            'taxonomy'   => 'postal-ville',
                            'name__like' => $location,
                            'fields'     => 'slugs'
                        ] );
                        if ( ! empty( $location_terms ) ) {
                            foreach ( $location_terms as $term_slug ) {
                                if ( ! in_array( $term_slug, $location_slugs ) ) {
                                    $location_slugs[] = $term_slug;
                                }
                            }
                        } else {
                            $location_slugs[] = $location;
                        }
                    }
                }
                if ( ! empty( $location_slugs ) ) {
                    $is_search_param_empty = false;
                    $tax_query[]           =
                        array(
                            'taxonomy'         => 'postal-ville',
                            'field'            => 'slug',
                            'terms'            => $location_slugs,
                            'include_children' => false
                        );
                }
            }
        }
        if ( isset( $_GET["category"] ) ) {
            if ( ! empty( $_GET["category"] ) ) {

                $tax_query[] = array(
                    'taxonomy'         => 'type-bien',
                    'field'            => 'slug',
                    'terms'            => $_GET["category"],
                    'include_children' => false
                );
            }
        }
        if ( ! empty( $tax_query ) ) {
            $args["tax_query"] = $tax_query;
        }


        if ( isset( $_GET["sort"] ) ) {
            if ( ! empty( $_GET["sort"] ) ) {
                switch ( $_GET["sort"] ) {
                    case "price-asc" :
                        $args["orderby"]  = 'meta_value_num';
                        $args["meta_key"] = 'montant';
                        $args["order"]    = 'ASC';
                        break;
                    case "price-desc" :
                        $args["orderby"]  = 'meta_value_num';
                        $args["meta_key"] = 'montant';
                        $args["order"]    = 'DESC';
                        break;
                    default :
                        $args["order"]   = "DESC";
                        $args["orderby"] = "date";
                        break;
                }
            }
        } else { //default ordering by avancement
            $args["orderby"]  = 'meta_value_num';
            $args["meta_key"] = '_avancement';
            $args["order"]    = 'ASC';
        }

        if ( ! empty( $meta_query ) ) {
            $args["relation"]   = 'AND';
            $args["meta_query"] = $meta_query;
        }

        if ( ! empty( $to_exclude ) ) {
            $args['exclude'] = $to_exclude;
        }

        $query = new WP_Query( $args );
        if ( $query->have_posts() ) { // if only query has posts, show the sorting options
            ?>
            <div class="filter-row">
                <form class="radio-wrap" action="">
                    <p>Trier par :</p>
                    <?php
                    $sort = isset( $_GET["sort"] ) ? $_GET["sort"] : "";
                    ?>
                    <label for="prix-croissant">
                        Prix <span class="hide-on-mobile">croissant</span><span class="show-on-mobile">O</span>
                        <input <?php echo $sort == "price-asc" ? "checked" : ""; ?> type="radio" id="prix-croissant"
                                                                                    name="short_cards"
                                                                                    value="price-asc">
                        <span class="checkmark"></span>
                    </label>
                    <label for="prix-décroissant">
                        Prix <span class="hide-on-mobile">décroissant</span><span class="show-on-mobile">P</span>
                        <input <?php echo $sort == "price-desc" ? "checked" : ""; ?> type="radio" id="prix-décroissant"
                                                                                     name="short_cards"
                                                                                     value="price-desc">
                        <span class="checkmark"></span>
                    </label>
                    <label for="nouveautés">
                        Nouveautés
                        <input <?php echo $sort == "date-desc" ? "checked" : ""; ?> type="radio" id="nouveautés"
                                                                                    name="short_cards"
                                                                                    value="date-desc">
                        <span class="checkmark"></span>
                    </label>
                </form>
            </div>
            <?php
        }
        ?>

        <div class="card-list row">
            <?php

            $is_result_empty = false; //show alerte option only if search result is.
            if ( $query->have_posts() ) { // if result is found, render all the list of real state
                $is_result_empty = false;
                while ( $query->have_posts() ) {
                    $query->the_post();
                    get_template_part( "template-parts/global/loop/content", "real-state" ); //template for single real state product
                    ?>
                    <?php
                }
            } else { // if result is not found, display the not found message
                $is_result_empty = true;
                echo "<div class='container not-found-text'>" . get_field( "rsso_no_result_message", "option" ) . "</div>";
            }

            ?>
        </div><!-- .card-list -->

        <div class="link-wrap">
            <div class="pagination">
                <?php
                $big              = 999999999; // need an unlikely integer
                $pagination_links = paginate_links( array(
                    'current' => max( 1, get_query_var( 'paged' ) ),
                    'total'   => $query->max_num_pages
                ) );
                if ( $pagination_links ) { //only if pagination links exists
                    ?>
                    <span class="label">Page</span>
                    <?php
                    echo $pagination_links;
                }
                ?>
            </div>
            <div class="btn-wrap">
                <?php
                if ( $is_result_empty & ! $is_search_param_empty ) {
                    ?>
                    <a href="#" id="add-to-alert-btn"
                       class="btn transparent purple"><?php _e( "Créer une alerte", "factotum" ); ?></a>
                    <?php
                }
                ?>

                <?php
                $next_page_url = get_next_posts_page_link( $query->max_num_pages );
                if ( $next_page_url && $pagination_links ) { // if next page url exists
                    ?>
                    <a href="<?php echo $next_page_url; ?>" class="btn"><?php _e( "Voir plus", "factotum" ); ?></a>
                    <?php
                }
                ?>

            </div><!-- .btn-wrap -->
        </div>
        <?php
        wp_reset_postdata();
        ?>
    </div>
</section><!-- .card-list-wrap -->