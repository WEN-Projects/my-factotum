<?php
$count = 0;
if ( have_rows( 'experts_list' ) ) {
	while ( have_rows( 'experts_list' ) ) {
		the_row();
		$title            = get_sub_field( 'title' );
		$content          = get_sub_field( 'content' );
		$image            = get_sub_field( "featured_image" );
		$image_url        = isset( $image['url'] ) ? $image['url'] : "";
		$show_contact_btn = get_sub_field( "show_contact_button" );
		$contact_btn      = get_sub_field( "contact_button" );
		?>
        <section id="experts-popup-content-<?php echo $count; ?>" class="pop-immovable-sec experts-popup">
            <a href="javascript:void(0);" class="icon-close">
                <span></span>
                <span></span>
            </a>
            <div class="container-fluid">
                <div class="row no-gutters">
                    <div class="col-sm-6 img-col match-height"
                         style="background: url('<?php echo $image['url']; ?>') no-repeat center; background-size: cover;">
                    </div>
                    <div class="col-sm-6 content-col match-height">
                        <div class="inner">
							<?php
							echo $title ? "<h2>" . $title . "</h2>" : "";
							echo $content;
							if ( ! empty( $show_contact_btn ) && ! empty( $contact_btn ) ) {
								$url       = isset( $contact_btn["url"] ) ? esc_url( $contact_btn["url"] ) : "";
								$btn_title = isset( $contact_btn["title"] ) ? $contact_btn["title"] : "";
								?>
                                <a class="btn transparent" href="<?php echo $url; ?>"><?php echo $btn_title; ?></a>
								<?php
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<?php
		$count ++;
	}
}

$section_title = get_field( "experts_section_title" ); //section title
$section_disc  = get_field( "experts_section_description" ); //section Disc
?>
<section id="section-select-expert" class="providers-sec">
    <div class="container">
        <h2><?php echo $section_title ? $section_title : ""; ?></h2>
        <div class="two-col-content">
			<?php echo $section_disc ? $section_disc : ""; ?>
        </div><!-- .two-col-content -->
		<?php if ( have_rows( 'experts_list' ) ) { //if have providers, list them
			?>
            <div class="row experts-list-container">
				<?php
				$count = 0;
				while ( have_rows( 'experts_list' ) ) {
					the_row();
					$title     = get_sub_field( 'title' );
					$image     = get_sub_field( "featured_image" );
					$image_url = isset( $image['url'] ) ? $image['url'] : "";
					?>

                    <div class="col-md-6 col-lg-6">
                        <div class="provider-box" style="background: url('<?php echo $image_url; ?>') no-repeat center ; background-size: cover;">
                            <div class="inner">
								<?php echo $title ? "<h2>" . $title . "</h2>" : ""; ?>
                                <span class="link">+</span>
                            </div><!-- .inner -->
                            <a id="expert-<?php echo $count; ?>" href="javascript:void(0)" class="stretched-link"></a>
                        </div><!-- .provider-box -->
                    </div>
					<?php
					$count ++;
				}
				?>
            </div>
			<?php
		}
		?>
        <div class="user-slider">
        </div><!-- .user-slider -->
    </div>
</section><!-- .user-slide-sec -->


