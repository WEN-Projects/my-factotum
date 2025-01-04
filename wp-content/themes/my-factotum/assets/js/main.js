(function ($) {
    var COMMON = {
        init: function () {
            this.cacheDOM();

            $(window).on('resize', this.reCalcOnResize.bind(this))
        },
        cacheDOM: function () {
            this.$body = $('body');
            this.windowWidth = $(window).width();
        },
        reCalcOnResize: function () {
            this.windowWidth = $(window).width();
        }
    };

    var mainNavigation = {
        init: function () {
            this.$mainNavContainer = $('#site-navigation');
            this.$menuToggler = this.$mainNavContainer.find('.menu-toggle');
            this.$mainMenuContainer = this.$mainNavContainer.find('.menu-primary-container');
            this.$mainMenu = this.$mainNavContainer.find('#primary-menu');
            console.log(this.$menuToggler);
            this.$menuToggler.on('click', this.toggleMenu.bind(this));
        },
        toggleMenu: function (e) {
            e.preventDefault();
            this.$mainMenuContainer.toggleClass('shown');
        }
    };

    var supportSVG = {
        init: function () {
            $("img.svg").each(function () {
                var $img = jQuery(this);
                var imgID = $img.attr("id");
                var imgClass = $img.attr("class");
                var imgURL = $img.attr("src");
                var imgwidth = $img.attr("width");
                var imgheight = $img.attr("height");
                $.get(
                    imgURL,
                    function (data) {
                        // Get the SVG tag, ignore the rest
                        var $svg = $(data).find("svg");
                        // Add replaced image's ID to the new SVG
                        if (typeof imgID !== "undefined") {
                            $svg = $svg.attr("id", imgID);
                        }

                        // Add replaced image's classes to the new SVG
                        if (typeof imgClass !== "undefined") {
                            $svg = $svg.attr("class", imgClass + " replaced-svg");
                            $svg = $svg.attr({
                                width: imgwidth,
                                height: imgheight,
                            });
                        }
                        // Remove any invalid XML tags as per http://validator.w3.org
                        $svg = $svg.removeAttr("xmlns:a");
                        // Replace image with new SVG
                        $img.replaceWith($svg);
                    },
                    "xml"
                );
            });
        },
    };

    $(function () {
        COMMON.init();
        mainNavigation.init();
        supportSVG.init();
    });


    // Toggle content on the mobile agent form page
    $('.toggle-detail').click(function (e) {
        e.preventDefault();
        $(this).toggleClass('expand').next('.detail-info').slideToggle()('fast');
    });

    /**
     * Function to Check if element is in viewport
     */
    /*======================================================*/
    /*############## Check if element is in viewport ############*/
    /*======================================================*/
    $.fn.isisInViewportport = function () {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();

        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();

        return elementBottom > viewportTop && elementTop < viewportBottom;
    };


    //Sticky header on scroll

    $(window).on('load scroll', function () {
        var scroll = $(window).scrollTop();
        if (scroll > 1) {
            $("body").addClass("scrolled");
        } else {
            $("body").removeClass("scrolled");
        }
    });

    // header-sticky
    function stickyHeader() {
        if ($(window).width() > 1200) {
            var headerHeight = $('.site-header').outerHeight(true);
            if ($(window).scrollTop() >= headerHeight) {
                $('.site-header').addClass('sticky');
            } else {
                $('.site-header').removeClass('sticky');
            }
            if ($('body').hasClass('home')) {
                var heroHeight = $('.hero').outerHeight(true);
                if ($(window).scrollTop() >= heroHeight + headerHeight) {
                    $('.site-header').addClass('sticky');
                } else {
                    $('.site-header').removeClass('sticky');
                }
            }
        }
    }

    $(window).on("load resize scroll", function () {
        stickyHeader();
    });


    // mobile nav

    function mobileNav() {
        if ($(window).width() <= 1199) {
            var headerHeight = $(".site-header").outerHeight(true);
            $(".main-navigation").css({
                top: headerHeight - 1,
                height: "calc(100vh - " + headerHeight + "px)",
            });
        }
    }

    $(window).on("load resize scroll", function () {
        mobileNav();
    });

    $(".trigger-menu").click(function () {
        $("html").toggleClass("html-overflow");
        $("body").toggleClass("menu-active");
        $(this).toggleClass('open');
    });

    //One fold home section
    $(document).ready(function () {
        if ($('body').hasClass('home')) {
            // $('body').css('overflow', 'hidden');
            $('.scroll-to, .smooth-scroll').on('click', function () { // Remove preventDefault and uncomment below it after history page built
                // e.preventDefault();
                $('.home').css('overflow', 'inherit');
                $("html").removeClass("html-overflow");
                $("body").removeClass("menu-active");
                $('.trigger-menu').removeClass('open');
            });
        }

        //Smooth scrolling with links #anchor
        $('.smooth-scroll a[href*=\\#]').on('click', function (event) {
            // event.preventDefault();
            $('html,body').animate({scrollTop: $(this.hash).offset().top}, 500);
        });

        $("#scroll-to-contact-form-btn").on("click", function (event) { // in agent page, for mobile layout, on click scroll-to-contact-form-btn scroll to the contact form
            event.preventDefault();
            $('html,body').animate({scrollTop: 0}, 'slow');
        });

    });

    $(document).ready(function () { //when we redirect to history section (from other pages), in such case, event ".smooth-scroll(click)" cant be detected, so we run this script manage overflows
        if ($('body').hasClass('home')) {
            var pageURL = window.location.href;
            var lastURLSegment = pageURL.substr(pageURL.lastIndexOf('/') + 1);
            if (lastURLSegment == "#notre-histoire") {
                $('.home').css('overflow', 'inherit');
                $("html").removeClass("html-overflow");
                $("body").removeClass("menu-active");
                $('.trigger-menu').removeClass('open');
            }
            // console.log(lastURLSegment);
        }

    });


// User Slider
    $('.user-slider').slick({
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        centerMode: false,
        centerPadding: 0,
        focusOnSelect: true,
        dots: false,
        arrows: true,
        nextArrow: "<span class='slick-left'>I</span>",
        prevArrow: "<span class='slick-right'>J</span>",
        responsive: [
            {
                breakpoint: 479,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    centerMode: false,
                    infinite: false,
                }
            }
        ]
    });

// Img slider on cards
    $('.img-slider').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        nextArrow: "<span class='slick-left'>Q</span>",
        prevArrow: "<span class='slick-right'>R</span>",
    });


    $(document).ready(function () {
        if ($('body').hasClass('page-template-notre-equipe')) {
            experts_count = $("#section-select-expert .experts-list-container div").length;
            for (var i = 0; i < experts_count; i++) {
                jQuery("#expert-" + i).on("click", {id_index: i}, function (e) {
                    jQuery(".experts-popup").removeClass("show-experts-popup");
                    jQuery("#experts-popup-content-" + e.data.id_index + ".experts-popup").addClass("show-experts-popup");
                    jQuery("body").addClass("expert-popup-visible");
                });
            }
            jQuery(".experts-popup .icon-close").on("click", function () {
                jQuery(".experts-popup").removeClass("show-experts-popup");
                jQuery("body").removeClass("expert-popup-visible");
            });

            experts_count = $("#section-select-prestataires .prestataires-list-container div").length;
            for (var i = 0; i < experts_count; i++) {
                jQuery("#prestatair-" + i).on("click", {id_index: i}, function (e) {
                    jQuery(".prestataires-popup").removeClass("show-experts-popup");
                    jQuery("#prestataires-popup-content-" + e.data.id_index + ".prestataires-popup").addClass("show-experts-popup");
                    jQuery("body").addClass("expert-popup-visible");
                });
            }
            jQuery(".prestataires-popup .icon-close").on("click", function () {
                jQuery(".prestataires-popup").removeClass("show-experts-popup");
                jQuery("body").removeClass("expert-popup-visible");
            });
        }
    });


    $(document).ready(function () { // realstate sorting functionaliy in nos-biens page
        if ($('body').hasClass('page-template-nos-biens')) {
            $('input[type=radio][name=short_cards]').on("change", (function (e) { //on change of sorting option
                e.preventDefault();
                let url = new URL($(location).attr('href'));
                if (url.searchParams.has('sort') == true) { //if url has parameter 'sort', replace the parameter
                    url.searchParams.set('sort', this.value)
                } else { // if url do not have parameter 'sort', add new parameter
                    url.searchParams.append('sort', this.value);
                }
                window.location.href = url.toString();
            }));
            let url = new URL($(location).attr('href'));
            if (url.searchParams.has('sort') == true || url.searchParams.has('type') == true || url.searchParams.has('pieces') == true || url.searchParams.has('max_price') == true || url.searchParams.has('category') == true) { //if url has parameter 'sort', scroll it to the real state listing section
                $('html, body').animate({
                    scrollTop: $(".card-list-wrap").offset().top - 200
                }, 500);
            }
        }
    });


    // Nos Biens search form setting

    $(document).ready(function () {
        console.log(frontend_js_data_obj.selected_locations);
        $('#real-state-search-form-vente input[name=type]').change(function (e) { // on change of tab(radio input value), switch between nos biens en venet/location
            //animate the change in form
            var value = $('#real-state-search-form-vente input[name=type]:checked').val();
            $('#real-state-search-form-vente .tab-content').fadeOut(500).fadeIn(500);
        });

        $('#js-zipcode-multiple').select2({ // initiation of select 2 with ajax search functionality
            placeholder: "Ville, adresse, région, code postal...",
            minimumInputLength: 1, // do not show the result until the keyword is pressed
            "language": {
                "noResults": function () {
                    return "Aucun résultat trouvé";
                },
                searching: function () {
                    return "Recherche...";
                },
                inputTooShort: function () {
                    return 'Veuillez saisir un ou plusieurs caractères';
                }
            },
            tags: true, // allow to add external free texts
            createTag: function (tag) { // allow to add external free texts
                return {id: tag.term, text: tag.term};
            },
            ajax: {
                type: "post",
                url: frontend_js_data_obj.ajaxurl,
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search_key: params.term,
                        action: "get_real_state_location_categories_by_searchkey",
                        nonce: frontend_js_data_obj.get_real_state_location_categories_nonce,
                    }
                    return query;
                },
                processResults: function (response) {
                    var select_options = [];
                    $.each(response.data, function (index, item) {
                        var option = {
                            "id": item.slug,
                            "text": item.name
                        };
                        select_options.push(option);
                    });
                    return {
                        results: select_options
                    };
                    console.log(select_options);
                }
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }

        });

        $(".select2-search__field").on("keydown", function (e) {
            console.log(e.keyCode);
        });

        if (frontend_js_data_obj.selected_locations) {
            let values = [];
            $.each(frontend_js_data_obj.selected_locations, function (key, value) {
                var newOption = new Option(value.text, value.id, false, false);
                $('#js-zipcode-multiple').append(newOption).trigger('change');
                values.push(value.id);
            });
            $('#js-zipcode-multiple').val(values); // Select the option with a value of '1'
            $('#js-zipcode-multiple').trigger('change'); // Notify any JS components that the value changed
        }

        $('#js-type-basic-single').select2({
            placeholder: "Type",
            allowClear: true
        });

        $("#show-criteria-form-btn").on("click", function () { // On click show criteria, show the advanced criteria form
            $(".criteria-form").show();
            $("#show-criteria-form-btn").hide();
            $("#simple-search-submit-btn").hide();
            $('#real-state-search-form-vente .form-inner').addClass('form-expanded');
        });

        //if any of the field is not empty in criteria form, then show the advanced criteria form
        if ($("#real-state-search-form-vente input[name='pieces']").val() || $("#real-state-search-form-vente input[name='min_surface']").val() || $("#real-state-search-form-vente input[name='max_price']").val() || $("#real-state-search-form-vente select[name='category']").val() != 0) {
            $(".criteria-form").show();
            $("#show-criteria-form-btn").hide();
            $("#simple-search-submit-btn").hide();
            $('#real-state-search-form-vente .form-inner').addClass('form-expanded');
        }

        $("#real-state-search-form-vente #reset-btn").on("click", function () { // On click show criteria show the advanced criteria form
            $("#real-state-search-form-vente input[name='pieces']").val("");
            $("#real-state-search-form-vente input[name='min_surface']").val("");
            $("#real-state-search-form-vente input[name='max_price']").val("");
            $('#js-type-basic-single').val(null).trigger('change');
            $('#js-zipcode-multiple').val(null).trigger('change');
        });


    });

    // Realstate hero slider

    $('.full-hero').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        nextArrow: "<span class='slick-left'>Q</span>",
        prevArrow: "<span class='slick-right'>R</span>",
        fade: true,
        asNavFor: '.hero-nav'
    });
    $('.hero-nav').slick({
        arrows: false,
        dots: false,
        asNavFor: '.full-hero',
        variableWidth: true,
        infinite: false
    });


    /*======================================================*/
    /*############## Check if element is in viewport ############*/
    /*======================================================*/
    $.fn.isisInViewportport = function () {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();

        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();

        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

    // stat counter animation
    var fx = function fx(el) {
        // var data = parseInt(this.dataset.n, 10);
        var data = el.attr('data-n');
        $({
            countNum: 0
        }).animate({
                countNum: data
            },
            {
                duration: 2000,
                easing: 'swing',
                step: function () {
                    el.text(Math.floor(this.countNum));
                },
                complete: function () {
                    el.text(this.countNum);
                }

            });

    };
    //Number animation on the ECG section
    var resetNumberAnimation = function reset() {
        $('.customer-number .number').each(function () {
            if (($(this).isisInViewportport()) && (!$(this).hasClass('stop'))) {
                $(this).addClass('stop');
                // $( this ).off("scroll");
                fx($(this));
            }
        });
    };

    $(window).on("scroll resize", resetNumberAnimation);

    //add to wishlist functionality
    $('body').on("click", "#add-to-wishlist .add-to-favorite", function (e) {
        e.preventDefault();
        if (frontend_js_data_obj.current_logged_user) { //action for logged in users
            if (frontend_js_data_obj.current_post_id) {
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: frontend_js_data_obj.ajaxurl,
                    data: {
                        action: "real_state_add_to_wishlist",
                        nonce: frontend_js_data_obj.real_state_add_to_wishlist_nonce,
                        real_state_id: frontend_js_data_obj.current_post_id
                    },
                    success: function (response) {
                        if (response.status && response.message && response.content) {
                            $("#add-to-wishlist").html(response.content);
                            // location.reload();
                        }
                    }
                });
            }
        } else { //action for non logged users
            // alert("S'il vous plait Connectez-vous d'abord");
            $('.my-account-login').addClass('my-account-active');
        }

    });

    //remove from wishlist functionality
    $('body').on("click", "#add-to-wishlist .remove-from-favorite", function (e) {
        e.preventDefault();
        if (frontend_js_data_obj.current_post_id) {
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: frontend_js_data_obj.ajaxurl,
                data: {
                    action: "real_state_remove_from_wishlist",
                    nonce: frontend_js_data_obj.real_state_add_to_wishlist_nonce,
                    real_state_id: frontend_js_data_obj.current_post_id
                },
                success: function (response) {
                    if (response.status && response.message && response.content) {
                        $("#add-to-wishlist").html(response.content);
                        // location.reload();
                    }
                }
            });
        }
    });


    $('body').on("click", "#rs-wishlist .add-to-trash", function (e) {
        let item_id = $(this).attr("itemid");
        if (item_id) {
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: frontend_js_data_obj.ajaxurl,
                data: {
                    action: "real_state_move_remove_from_wishlist_trash",
                    nonce: frontend_js_data_obj.real_state_add_to_wishlist_nonce,
                    real_state_id: item_id,
                    trash_action: 1
                },
                success: function (response) {
                    if (response.status && response.status == 1) {

                        $('a[itemid=' + item_id + ']').addClass("remove-from-trash");
                        $('a[itemid=' + item_id + ']').html("Annuler la suppression");
                        $('a[itemid=' + item_id + ']').parent().find('.post-card').css("opacity", "0.3");
                        $('a[itemid=' + item_id + ']').removeClass("add-to-trash");
                    }
                }
            });
        }
    });

    $('body').on("click", "#rs-wishlist .remove-from-trash", function (e) {
        console.log("sdasd");
        let item_id = $(this).attr("itemid");
        if (item_id) {
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: frontend_js_data_obj.ajaxurl,
                data: {
                    action: "real_state_move_remove_from_wishlist_trash",
                    nonce: frontend_js_data_obj.real_state_add_to_wishlist_nonce,
                    real_state_id: item_id,
                    trash_action: 2
                },
                success: function (response) {
                    if (response.status && response.status == 1) {
                        $('a[itemid=' + item_id + ']').addClass("add-to-trash");
                        $('a[itemid=' + item_id + ']').html("Supprimer");
                        $('a[itemid=' + item_id + ']').parent().find('.post-card').css("opacity", "1");
                        $('a[itemid=' + item_id + ']').removeClass("remove-from-trash");
                    }
                }
            });
        }
    });

    $('body').on("click", "#rs-alerte .add-to-trash", function (event) {
        event.preventDefault();
        let item_id = $(this).attr("itemid");
        console.log(item_id);
        if (item_id) {
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: frontend_js_data_obj.ajaxurl,
                data: {
                    action: "real_state_move_remove_from_alerte_trash",
                    nonce: frontend_js_data_obj.real_state_add_to_alerte_nonce,
                    alerte: JSON.parse(item_id),
                    trash_action: 1
                },
                success: function (response) {
                    if (response.status && response.status == 1) {
                        $('a[itemid=\'' + item_id + '\']').addClass("remove-from-trash");
                        $('a[itemid=\'' + item_id + '\']').html("Annuler la suppression");
                        $('a[itemid=\'' + item_id + '\']').parent().find('.inner').css("opacity", "0.3");
                        $('a[itemid=\'' + item_id + '\']').removeClass("add-to-trash");
                    }
                }
            });
        }
    });

    $('body').on("click", "#rs-alerte .remove-from-trash", function (event) {
        event.preventDefault();
        let item_id = $(this).attr("itemid");
        console.log(item_id);
        if (item_id) {
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: frontend_js_data_obj.ajaxurl,
                data: {
                    action: "real_state_move_remove_from_alerte_trash",
                    nonce: frontend_js_data_obj.real_state_add_to_alerte_nonce,
                    alerte: JSON.parse(item_id),
                    trash_action: 2
                },
                success: function (response) {
                    if (response.status && response.status == 1) {
                        $('a[itemid=\'' + item_id + '\']').addClass("add-to-trash");
                        $('a[itemid=\'' + item_id + '\']').html("Supprimer");
                        $('a[itemid=\'' + item_id + '\']').parent().find('.inner').css("opacity", "1");
                        $('a[itemid=\'' + item_id + '\']').removeClass("remove-from-trash");
                    }
                }
            });
        }
    });

    $("#rs-alerte .add-remove-frm-allow-mail").on("click", function (event) {
        event.preventDefault();
        let alerte_data = $(this).attr("alerte_data");
        if (alerte_data) {
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: frontend_js_data_obj.ajaxurl,
                data: {
                    action: "real_state_change_alerte_send_email_status",
                    nonce: frontend_js_data_obj.real_state_add_to_alerte_nonce,
                    alerte: JSON.parse(alerte_data),
                },
                success: function (response) {
                    if (response.status && response.status == 2) {
                        location.reload();
                    }
                }
            });
        }
    });

    // gsap animation for the history section on the home page and more

    /*----------------------------------------------
                #History section Animation
     ---------------------------------------------- */
    function historyContentAnimation() {
        $(window).on("load resize scroll", function (e) {
            $(".animate-image").each(function () {
                if ($(this).isisInViewportport(200)) {
                    gsap.to($(this).parent().find('.overlay-top'), {
                        duration: 1.5,
                        height: 0,
                    })
                    gsap.to($(this).parent().find('.overlay-right'), {
                        duration: 1.5,
                        width: 0,
                    })
                }

            });
            //For colored-bg
            $(".content-col .colored-bg").each(function () {
                if ($(this).isisInViewportport(200)) {  //400 is offset of the screen
                    gsap.to(this, {
                        duration: 1.5,
                        height: "calc(100% + 80px)",
                        stagger: 1
                    })
                }
            });
            //For colored-bg
            $(".history-team .colored-bg").each(function () {
                if ($(this).isisInViewportport(200)) {  //400 is offset of the screen
                    gsap.to(this, {
                        duration: 1.5,
                        height: "398px",
                    })
                }
            });
            //For title underline
            $(".underline").each(function () {
                if ($(this).isisInViewportport(200)) {  //400 is offset of the screen
                    gsap.to(this, {
                        duration: 1.5,
                        width: "100%",
                        stagger: 1
                    })
                }
            });
            //For fill overlay on cta
            $(".cta-block .container").each(function () {
                if ($(this).isisInViewportport(200)) {  //400 is offset of the screen
                    gsap.to('.cta-back-overlay', {
                        duration: 1,
                        height: "100%"
                    })
                    gsap.to(".cta-block .btn-wrap", {
                        duration: 1,
                        delay: 0.6,
                        width: '100%'
                    })
                    gsap.to(".cta-block .btn .text", {
                        duration: 0.5,
                        delay: 1,
                        opacity: 1
                    })
                }
            });
            //For fill overlay on trust pilot
            $(".trust-pilot-sec .container").each(function () {
                if ($(this).isisInViewportport(200)) {  //400 is offset of the screen
                    gsap.to('.gray-overlay', {
                        duration: 1,
                        height: "100%"
                    })
                }
            });
        });
    }

    historyContentAnimation();


    //Match the height of the components
    setTimeout(function () {
        $(".match-height").matchHeight();
    }, 300);


    //Hide show the my account sticky overlay

    $('.quick-links .my-account').on('click', function (e) {
        e.preventDefault();
        $('.my-account-login').addClass('my-account-active');
        $('body').addClass('show-my-account');
    });
    $('.my-account-login .close-overlay').on('click', function (e) {
        e.preventDefault();
        $('.my-account-login').removeClass('my-account-active');
        $('body').removeClass('show-my-account');
    });

    jQuery.fn.clickOutside = function (callback) {
        var $me = this;
        $(document).mouseup(function (e) {
            if (!$me.is(e.target) && $me.has(e.target).length === 0) {
                callback.apply($me);
            }
        });
    };

    $('.my-account-login').clickOutside(function () {
        $(this).removeClass('my-account-active'); // or `$(this).hide()`, if you must
        $('body').removeClass('show-my-account');
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape") {
            $('.my-account-login').removeClass('my-account-active'); // or `$(this).hide()`, if you must
            $('body').removeClass('show-my-account');
        }
    });

    //login/register slider js
    $(document).ready(function () {
            if (sessionStorage.getItem("slider_login_form")) {
                console.log(sessionStorage.getItem("slider_login_form"));
                change_slider_from(sessionStorage.getItem("slider_login_form")); // set the selected form(login or register) on reload
            }

            $("#slider-register-btn").on("click", function () {
                change_slider_from(2);
            });
            $("#slider-login-btn").on("click", function () {
                change_slider_from(1);
            });

            function change_slider_from(form_id) {
                form_id = parseInt(form_id);
                switch (form_id) {
                    case 1 :
                        $(".my-account-login .form-inner").hide();
                        $("#slider-login-form").show();
                        sessionStorage.removeItem("slider_login_form");
                        break;
                    case 2 :
                        $(".my-account-login .form-inner").hide();
                        $("#slider-register-form").show();
                        set_slider_form_session(2); // setting the session so that form in the slider remains same after reload
                        break;
                    default :
                        $(".my-account-login .form-inner").hide();
                        $("#slider-login-form").show();
                        sessionStorage.removeItem("slider_login_form");
                        ; //clearing the session
                        break;
                }
            }

            function set_slider_form_session(form_id) {
                if (!sessionStorage.getItem("slider_login_form") || sessionStorage.getItem("slider_login_form") != form_id) {
                    sessionStorage.setItem("slider_login_form", form_id);
                }
            }

            if (!frontend_js_data_obj.current_logged_user) { // when non logged in user tries to access the premium blogs, pop up the slider log in form
                $("article.access-restricted").on("click", function (e) { // on left click
                    e.preventDefault();
                    set_slider_form_session(1);
                    $('.my-account-login').addClass('my-account-active');
                    // $('body').addClass('show-my-account');
                });
                $("article.access-restricted").on("contextmenu", function (e) { // on right click
                    e.preventDefault();
                    set_slider_form_session(1);
                    $('.my-account-login').addClass('my-account-active');
                    // $('body').addClass('show-my-account');
                });
                if ($("body").hasClass("access-restricted")) {
                    set_slider_form_session(1);
                    $('.my-account-login').addClass('my-account-active');
                    // $('body').addClass('show-my-account');
                }
            }
        }
    );

    //load more realstates
    $(document).ready(function () {
            if ($("body").has("page-template-fiche-agent") && frontend_js_data_obj.current_agent_email) {
                var loaded_count = 3;
                $("#load-more-rs-btn").on("click", function (event) {
                    event.preventDefault();

                    console.log(frontend_js_data_obj.current_agent_email);

                    jQuery.ajax({
                        type: "post",
                        dataType: "json",
                        url: frontend_js_data_obj.ajaxurl,
                        data: {
                            action: "get_real_state_show_more_real_states_by_an_agent",
                            nonce: frontend_js_data_obj.real_state_get_more_by_agent_nonce,
                            agent_email: frontend_js_data_obj.current_agent_email,
                            offset: loaded_count
                        },
                        success: function (response) {
                            if (response.content) {
                                $(".agent-property .card-list").append(response.content);
                                // Img slider on cards
                                $('.img-slider').not('.slick-initialized').slick({
                                    infinite: true,
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    dots: false,
                                    arrows: true,
                                    nextArrow: "<span class='slick-left'>Q</span>",
                                    prevArrow: "<span class='slick-right'>R</span>",
                                });
                                if (($(".card-list-wrap .card-list").find('.col-md-6')).length == frontend_js_data_obj.agent_real_states_count) {
                                    $("#load-more-rs-btn").hide();
                                }
                                // console.log(($(".card-list-wrap .card-list").find('.col-md-6')).length);
                                // console.log(frontend_js_data_obj.agent_real_states_count);
                            }
                            loaded_count += 3;
                        }
                    });
                });
            }
        }
    );

    $(document).ready(function () {
            $("#add-to-alert-btn").on("click", function (event) {
                event.preventDefault();
                if (frontend_js_data_obj.current_logged_user) { //action for logged in users
                    if (frontend_js_data_obj.alerte_criteria_data) {
                        jQuery.ajax({
                            type: "post",
                            dataType: "json",
                            url: frontend_js_data_obj.ajaxurl,
                            data: {
                                action: "real_state_add_to_alerte",
                                nonce: frontend_js_data_obj.real_state_add_to_alerte_nonce,
                                alerte_criteria: frontend_js_data_obj.alerte_criteria_data
                            },
                            success: function (response) {
                                if (response.status) {
                                    switch (response.status) {
                                        case 1 :
                                            alert("Added to alerte");
                                            break;
                                        case 2 :
                                            alert("Already Exists alerte");
                                            break;
                                        default :
                                            alert("Failed to add to alerte");
                                            break;
                                    }
                                }
                            }
                        });
                    }
                } else {
                    // alert("S'il vous plait Connectez-vous d'abord");
                    $('.my-account-login').addClass('my-account-active');
                }
            });
        }
    );


    //Form edit field setting
    $('.editable .edit-option').on('click', function () {
        $(this).hide();
        $(this).parent().find('input').css('pointer-events', 'all').focus();
    });
    $('.hide-show-trigger .show-option').on('click', function () {
        $(this).hide();
        $(this).parent().find('input').css('pointer-events', 'all').focus();
        $('form .hidden-field').show();
        $('form .show-password-input').show();
    });


    $(document).ready(function () { // appending or removing the units from the search form input fields
            if ($('body').hasClass("page-nos-biens")) {
                if ($("#real-state-search-form-vente input[name='pieces']").val().length > 0) {
                    $("#real-state-search-form-vente input[name='pieces']").val($("#real-state-search-form-vente input[name='pieces']").val() + " pièces min.");
                }
                if ($("#real-state-search-form-vente input[name='min_surface']").val().length > 0) {
                    $("#real-state-search-form-vente input[name='min_surface']").val($("#real-state-search-form-vente input[name='min_surface']").val() + " m² min.");
                }
                if ($("#real-state-search-form-vente input[name='max_price']").val().length > 0) {
                    $("#real-state-search-form-vente input[name='max_price']").val($("#real-state-search-form-vente input[name='max_price']").val() + " € max.");
                }
                $("#real-state-search-form-vente input[name='pieces']").focus(function () {
                    if ($("#real-state-search-form-vente input[name='pieces']").val().length) {
                        $("#real-state-search-form-vente input[name='pieces']").val($("#real-state-search-form-vente input[name='pieces']").val().replace(" pièces min.", ""));
                    }
                });
                $("#real-state-search-form-vente input[name='pieces']").focusout(function () {
                    if ($("#real-state-search-form-vente input[name='pieces']").val().length > 0) {
                        $("#real-state-search-form-vente input[name='pieces']").val($("#real-state-search-form-vente input[name='pieces']").val() + " pièces min.");
                    }
                });

                $("#real-state-search-form-vente input[name='min_surface']").focus(function () {
                    if ($("#real-state-search-form-vente input[name='min_surface']").val().length) {
                        $("#real-state-search-form-vente input[name='min_surface']").val($("#real-state-search-form-vente input[name='min_surface']").val().replace(" m² min.", ""));
                    }
                });
                $("#real-state-search-form-vente input[name='min_surface']").focusout(function () {
                    if ($("#real-state-search-form-vente input[name='min_surface']").val().length > 0) {
                        $("#real-state-search-form-vente input[name='min_surface']").val($("#real-state-search-form-vente input[name='min_surface']").val() + " m² min.");
                    }
                });

                $("#real-state-search-form-vente input[name='max_price']").focus(function () {
                    if ($("#real-state-search-form-vente input[name='max_price']").val().length) {
                        $("#real-state-search-form-vente input[name='max_price']").val($("#real-state-search-form-vente input[name='max_price']").val().replace(" € max.", ""));
                    }
                });
                $("#real-state-search-form-vente input[name='max_price']").focusout(function () {
                    if ($("#real-state-search-form-vente input[name='max_price']").val().length > 0) {
                        $("#real-state-search-form-vente input[name='max_price']").val($("#real-state-search-form-vente input[name='max_price']").val() + " € max.");
                    }
                });

                $("#real-state-search-form-vente").on('submit', function (e) {
                    // your code here
                    if ($("#real-state-search-form-vente input[name='pieces']").val().length) {
                        $("#real-state-search-form-vente input[name='pieces']").val($("#real-state-search-form-vente input[name='pieces']").val().replace(" pièces min.", ""));
                    }
                    if ($("#real-state-search-form-vente input[name='min_surface']").val().length) {
                        $("#real-state-search-form-vente input[name='min_surface']").val($("#real-state-search-form-vente input[name='min_surface']").val().replace(" m² min.", ""));
                    }
                    if ($("#real-state-search-form-vente input[name='max_price']").val().length) {
                        $("#real-state-search-form-vente input[name='max_price']").val($("#real-state-search-form-vente input[name='max_price']").val().replace(" € max.", ""));
                    }
                });


            }
        }
    );
    $(document).ready(function () { // modifying the contact 7 form field (Estimer form field), multiselect
        $("#extra-information-estimer").select2({ // initiation of select 2 with ajax search functionality
            placeholder: "Complément d’informations"
        });
    });

//Home hero section offset on onw up scroll
    gsap.registerPlugin(ScrollTrigger);

    var firstElem = document.querySelector(".panel");

    function goToSection(i, anim) {
        gsap.to(window, {
            scrollTo: {y: i * innerHeight + firstElem.offsetTop, autoKill: false},
            duration: 1
        });

        if (anim) {
            anim.restart();
        }
    }

    gsap.utils.toArray(".panel").forEach(function (panel, i) {
        ScrollTrigger.create({
            trigger: panel,
            onEnter: function () {
                goToSection(i)
            }
        });

        ScrollTrigger.create({
            trigger: panel,
            start: "bottom bottom",
            onEnterBack: function () {
                goToSection(i)
            },
        });
    });


})(jQuery);


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