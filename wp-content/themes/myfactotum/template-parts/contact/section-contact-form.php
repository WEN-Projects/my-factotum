<?php
$form_title   = get_field( "form_title" ); //form title
$form_content = get_field( "form_content" ); //form content
if ( $form_title && $form_content ) {
	?>
    <section class="contact-form-wrap bg-pink">
        <div class="container">
            <h2><?php echo $form_title;?></h2>
			<?php echo do_shortcode( '[contact-form-7 id="1254" title="Contact form"]' ); //shortcode for contact form to be rendered ?>
        </div>
    </section><!-- .contact-form-wrap -->

	<?php
}
?>