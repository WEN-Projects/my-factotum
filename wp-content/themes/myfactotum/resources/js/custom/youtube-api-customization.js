    //scripts for youtube video
    var site_url = "<?php //echo esc_url( home_url( '/' ) ); ?>//";
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var banner_yt_player,history_yt_player,inner_banner_yt_player;
    function onYouTubeIframeAPIReady() {
       if(typeof banner_youtube_video_id !== 'undefined' && document.getElementById("banner-video-player-youtube")){ // script for top banner youtube video
           banner_yt_player = new YT.Player('banner-video-player-youtube', {
               width: '100%',
               videoId: banner_youtube_video_id,
               playerVars: {
                   mute: 1,
                   'autoplay': 1,
                   'playsinline': 1,
                   'loop': 1,
                   'controls': 0,
                   'origin': site_url,
                   'playlist': banner_youtube_video_id,
                   'rel': 0
               },
               events: {
                   'onReady': onPlayerBannerReady
               }
           });
           // 4. The API will call this function when the video player is ready.
           function onPlayerBannerReady(event) {
               event.target.mute();
               event.target.playVideo();
           }

           document.addEventListener("visibilitychange", function (e) { // add autoplay whenever vistior goes out of browser tab and come back again
               if (document.hidden) {
                   // console.log("Browser tab is hidden")
               } else {
                   // console.log("Browser tab is visible");
                   jQuery("#banner-video-player-youtube")[0].src += "&autoplay=1";
               }
           });
       }
       if(typeof history_youtube_video_id !== 'undefined' && document.getElementById("history-youtube-video-player")){ // script for top banner youtube video
           history_yt_player = new YT.Player('history-youtube-video-player', {
               width: '100%',
               videoId: history_youtube_video_id,
               playerVars: {
                   mute: 0,
                   'autoplay': 0,
                   'playsinline': 1,
                   'loop': 0,
                   'controls': 0,
                   'origin': site_url,
                   'playlist': history_youtube_video_id,
                   'rel': 0
               },
               events: {
                   'onReady': onPlayerReadyHistoryVideo,
                   'onStateChange': onPlayerHistoryVideoStateChange
               }
           });
           function onPlayerReadyHistoryVideo(event) {
               // event.target.mute();
               // event.target.playVideo();
           }
           function onPlayerHistoryVideoStateChange(event) {
               if (event.data == YT.PlayerState.PAUSED) {
                   jQuery(".video-about .hero-content").show();
               }
               if (event.data == YT.PlayerState.ENDED) {
                   jQuery(".video-about .hero-content").show();
               }
               if (event.data == YT.PlayerState.PLAYING) {
                   jQuery(".video-about .hero-content").hide();
               }
           }

           jQuery(function ($) {
               $(document).ready(function () {
                   $(".video-about #youtubePlayButton").on("click", function () {
                       history_yt_player.playVideo();
                       $(".video-about .youtube-thumbnail").hide();
                       $(".video-about .youtube-video").show();
                   });
                   $(".video-about iframe").on("click", function () {
                       alert();
                   });
               })
           });
       }
       if(typeof inner_banner_youtube_video_id !== 'undefined' && document.getElementById("inner-banner-yt-video-player")){ // script for top banner youtube video
           inner_banner_yt_player = new YT.Player('inner-banner-yt-video-player', {
               width: '100%',
               videoId: inner_banner_youtube_video_id,
               playerVars: {
                   mute: 0,
                   'autoplay': 0,
                   'playsinline': 1,
                   'loop': 0,
                   'controls': 0,
                   'origin': site_url,
                   'playlist': inner_banner_youtube_video_id,
                   'rel': 0
               },
               events: {
                   'onReady': onPlayerReadyinnerBannerVideo,
                   'onStateChange': onPlayerinnerBannerVideoStateChange
               }
           });
           // 4. The API will call this function when the video player is ready.
           function onPlayerReadyinnerBannerVideo(event) {
               // event.target.mute();
               // event.target.playVideo();
           }

           function onPlayerinnerBannerVideoStateChange(event) {
               if (event.data == YT.PlayerState.PAUSED) {
                   jQuery(".rejoindre-hero .play-button-container").show();
               }
               if (event.data == YT.PlayerState.ENDED) {
                   jQuery(".rejoindre-hero .play-button-container").show();
               }
               if (event.data == YT.PlayerState.PLAYING) {
                   jQuery(".rejoindre-hero .play-button-container").hide();
               }
           }

           jQuery(function ($) {
               $(document).ready(function () {
                   $(".rejoindre-hero #youtubePlayButton").on("click", function () {
                       inner_banner_yt_player.playVideo();
                       $(".rejoindre-hero .youtube-thumbnail").hide();
                       $(".rejoindre-hero .youtube-video").show();
                   });
               })
           });
       }
    }