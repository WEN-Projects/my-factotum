<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package my_factotum
 */

$footer_left_title              = get_field( "footer_left_title", "option" ); // footer left section title
$footer_left_content            = get_field( "footer_left_content", "option" ); // footer left content
$right_show_legal_notice_button = get_field( "right_show_legal_notice_button", "option" ); // whether to show or hide footer legal notice button

?>
<footer id="colophon" class="site-footer">
    <div class="container">
        <div class="site-info">
            <p class="footer-site-title"><strong><?php echo $footer_left_title; ?></strong></p>
			<?php echo $footer_left_content; ?>
        </div><!-- .site-info -->

        <div class="right-footer">
            <ul class="social">
				<?php
				if ( have_rows( 'footer_social_page_links', "option" ) ) { //render social network links
					while ( have_rows( 'footer_social_page_links', "option" ) ) {
						the_row();
						$icon_image = get_sub_field( 'icon', "option" );
						$icon_link  = get_sub_field( 'link', "option" );
						$url        = isset( $icon_link["url"] ) ? esc_url( $icon_link["url"] ) : "";
						?>
						<?php
						if ( ! empty( $url ) ) { ?>
                            <li>
                                <a href="<?php echo $url; ?>">
                                    <?php echo isset( $icon_image["ID"] ) ? wp_get_attachment_image( $icon_image["ID"], 'full' ) : ""; ?>
                                </a>
                            </li>
						<?php }
					}
				}
				?>
            </ul>
			<?php

			if ( $right_show_legal_notice_button ) {
				$show_legal_notice_link = get_field( "show_legal_notice_link", "option" );
				$button_title           = $show_legal_notice_link["title"] ? $show_legal_notice_link["title"] : ""; //if button title is set
				$button_url             = $show_legal_notice_link["url"] ? $show_legal_notice_link["url"] : ""; //if button url is set
                ?>
                <a href="<?php echo $button_url; ?>"><?php echo $button_title; ?></a>
            <?php
            }
            ?>
        </div>
    </div>
</footer><!-- #colophon -->


</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
