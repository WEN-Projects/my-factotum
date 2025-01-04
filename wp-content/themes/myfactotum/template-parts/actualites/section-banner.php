<?php
$background_image = home_url() . '/wp-content/uploads/2021/07/D_Actualites_01.jpg'; //default background image, if not set from backend
$title            = __( "Actualités", "factotum" );


$pageID = get_option( 'page_for_posts' ); // page to display the posts
if ( $pageID ) {
	$banner_image = get_field( "banner_image", $pageID ); //banner type
	if ( isset( $banner_image["id"] ) ) {
		$background_image = wp_get_attachment_image_url( $banner_image["id"], 'full' );
	}
	$title = get_the_title( $pageID );
}
?>
<section class="hero blog-hero"
         style="background: url('<?php echo $background_image; ?>') no-repeat center; background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 offset-lg-1">
                <div class="hero-content">
                    <h1 class="banner-title"><?php echo $title; ?></h1>
                </div><!-- .hero-content -->
            </div>
        </div>
    </div>
</section><!-- .hero.blog-hero -->