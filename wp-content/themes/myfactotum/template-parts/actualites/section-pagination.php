<div class="link-wrap">
    <div class="pagination">
		<?php
		global $wp_query;
		$big              = 999999999; // need an unlikely integer
		$pagination_links = paginate_links( array( //pagination links to be displayed
			'current' => max( 1, get_query_var( 'paged' ) ),
			'total'   => $wp_query->max_num_pages
		) );
		if ( $pagination_links ) { //only if pagination links exists
			?>
            <span class="label"><?php _e( "Page", "factotum" ); ?></span>
			<?php
			echo $pagination_links;
		}
		?>
    </div>
    <div class="btn-wrap">
		<?php
		$next_page_url = get_next_posts_page_link( $wp_query->max_num_pages ); // link for next page
		if ( $next_page_url && $pagination_links ) { // if next page url exists
			?>
            <a href="<?php echo $next_page_url; ?>"
               class="btn"><?php _e( "Voir plus d’articles", "factotum" ); ?></a>
			<?php
		}
		?>
    </div><!-- .btn-wrap -->
</div>