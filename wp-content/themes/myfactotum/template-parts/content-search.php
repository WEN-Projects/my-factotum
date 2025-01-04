<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package my_factotum
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php my_factotum_post_thumbnail(); ?>
    <div class="entry-detail">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
            <div class="entry-meta">
				<?php
				$categories = get_the_category(); // all categories of the post
				if ( ! empty( get_the_category() ) ) {
					foreach ( ( get_the_category() ) as $category ) { ?>
                        <strong><?php echo $category->cat_name . ' '; ?></strong>
					<?php }
				}
				$tags = get_the_tags();
				if ( ! empty( $tags ) ) { //all tags of the post
					foreach ( $tags as $tag ) {
						?>
                        <span class="meta-tag"><?php echo $tag->name; ?></span>
						<?php
					}
				}
				the_time( 'j F Y' ); // published date
				?>
            </div><!-- .entry-meta -->
		<?php endif; ?>
        <div class="default-content">
			<?php
			echo wp_trim_words( get_the_content(), 38, '' );
			?>
        </div>
        <a href="<?php the_permalink(); ?>" class="btn transparent purple post-link">
			<?php _e( "Lire l’article", "factotum" ); ?>
        </a>
    </div><!-- .entry-detail -->
</article><!-- #post-<?php the_ID(); ?> -->
