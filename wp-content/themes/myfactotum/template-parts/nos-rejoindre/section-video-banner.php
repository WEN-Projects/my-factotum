<?php
$video_source    = get_field( "video_source" );
$video_thumbnail = get_field( "video_thumbnail" );
if ( isset( $video_thumbnail["ID"] ) ) {
	if ( $video_source ) {
		?>
        <section class="hero rejoindre-hero">
            <div class="video-poster">
				<?php
				echo wp_get_attachment_image( $video_thumbnail["ID"], "full" );
				?>
            </div><!-- .video-poster -->
			<?php
			if ( $video_source == "vimeo" ) {
				$vimeo_video_id = get_field( "banner_vimeo_video_id" );
				$video_url      = esc_url( "https://player.vimeo.com/video/" . $vimeo_video_id . "?&muted=0&showinfo=0&controls=0&loop=1" ); // if viemo link is selected then we need a vimeo player to embed the vimeo link
				?>
                <div class="container">
                    <div class="hero-content">
                        <a href="javascript:void(0);" id="vimeoPlayButton" class="trigger-video vimeoPlayButton">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.svg" alt="Play Icon"></a>
                    </div><!-- .hero-content -->
                </div>

                <div class="video-bg vimeo-video" style="display: none">
                    <div class="video-external">
                        <iframe id="banner-video-iframe" class="ytplayer" width="100%"
                                height="100%"
                                src="<?php echo $video_url; ?>" frameborder="0"
                                allow="autoplay; fullscreen; encrypted-media"
                                allowfullscreen></iframe>
                    </div>
                </div><!-- .video-bg -->
                <script>
                    //click to play vimeo player on nos rejoindre page
                    jQuery(function ($) {
                        $(document).ready(function () {
                            var iframe = document.querySelector('.vimeo-video .video-external iframe');
                            var player = new Vimeo.Player(iframe);

                            $('#vimeoPlayButton').on("click", function () {
                                player.play();
                            });

                            player.on('play', function () {
                                $('#vimeoPlayButton').hide();
                                if (!$('rejoindre-hero .vimeo-thumbnail').is(":hidden")) {
                                    $(".rejoindre-hero .vimeo-thumbnail").hide();
                                    $(".rejoindre-hero .vimeo-video").show();
                                }
                            });

                            $('.rejoindre-hero').on("click", function () {
                                //If URL contains "mystring"
                                if ($('#vimeoPlayButton').is(":hidden")) {
                                    player.pause();
                                    $('#vimeoPlayButton').show();
                                }
                            });

                            $(".rejoindre-hero #vimeoPlayButton").on("click", function () {
                                player.play();
                            });
                        });
                    });
                </script>
			<?php
			}
			else {
			$youtube_video_id = get_field( "banner_youtube_video_id" );
			?>
                <div class="container play-button-container">
                    <div class="hero-content">
                        <a href="javascript:void(0);" id="youtubePlayButton" class="trigger-video youtubePlayButton">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.svg" alt="Play Icon"></a>
                    </div><!-- .hero-content -->
                </div>
                <div class="video-bg youtube-video" style="display: none">
                    <div class="video-external" data-ytID="<?php echo $youtube_video_id; ?>">
                        <div id="inner-banner-yt-video-player"></div>
                    </div>
                </div><!-- .video-bg -->
                <script>

                    // 2. This code loads the IFrame Player API code asynchronously.
                    var inner_banner_youtube_video_id = "<?php echo $youtube_video_id; ?>";

                </script>
				<?php
			}
			?>

        </section><!-- .rejoindre-hero -->
		<?php
	}
}
?>