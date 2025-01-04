<?php
$title   = get_field( "content_section_title" ); // section title to be displayed
$content = get_field( "content_section_content" ); // section content to be displayed
if ( $title && $content ) {
	?>
    <section class="services-content-wrap rejoindre-content-wrap">
        <div class="container">
			<?php
			echo $title ? '<h2 class="line-title">' . $title . '</h2>' : '';
			?>
            <div class="two-col-content">
				<?php
				echo $content;
				?>
            </div><!-- .two-col-content -->
        </div>
    </section><!-- .rejoindre-content-wrap -->
	<?php
}
?>

