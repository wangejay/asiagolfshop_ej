jQuery(document).ready( function($){
    "use strict";

    var $window   = $(window),
        $body     = $(document.body),

        header    = document.getElementById('header'),
        nav       = document.getElementById('nav'),
        primary   = document.getElementById('primary'),
        footer    = document.getElementById('footer'),
        copyright = document.getElementById('copyright'),

        $header    = $( header ),
        $nav       = $( '.nav' ),
        $primary   = $( primary ),
        $footer    = $( footer ),
        $copyright = $( copyright ),

        hAdminBar = $('#wpadminbar').length ? $('#wpadminbar').height() : 0,

        onScrollEnd,

        detectDevice = function(){
            if ( YIT_Browser.isViewportBetween( 1024 ) ) {
                $body.addClass('isMobile');
                $("#animate-css").attr("disabled", "disabled");
            }
            else {
                $body.removeClass('isMobile');
                $("#animate-css").attr("disabled", false);
            }

            if ( YIT_Browser.isViewportBetween( 1024, 768 ) ) {
                $body.addClass('isIpad');
            }
            else {
                $body.removeClass('isIpad');
            }

            if ( YIT_Browser.isViewportBetween( 767 ) ) {
                $body.addClass('isIphone');
            }
            else {
                $body.removeClass('isIphone');
            }
        };

    /*************************
     * MISC
     *************************/

    if ( YIT_Browser.isIE8() ) {
        $('*:last-child').addClass('last-child');
    }

    if( YIT_Browser.isIE10() ) {
        $( 'html' ).attr( 'id', 'ie10' ).addClass( 'ie' );
    }

    // placeholder support
    if($.fn.placeholder) {
        $('input[placeholder], textarea[placeholder]').placeholder();
    }

    // detect device and add the class to body
    _onresize( detectDevice );
    detectDevice();

    /*************************
     * Smooth Scroll Onepage
     *************************/
    $.fn.yit_onepage = function(){
        var nav = $(this);

        //smooth scrolling
        nav.on( 'click', 'a[href*=#]:not([href=#])', function(e) {

            var onepage_url = $('#logo-img').attr('href') + '/',
                current_page_url = location.origin + location.pathname;

            if ( onepage_url != current_page_url ){
                e.preventDefault();
                window.location.href = onepage_url + this.hash;
            }else if ( location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname ) {
                var target = $(this.hash),
                    offsetSize = 34,
                    header_container_height = $('#header-container').height();

                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');


                if( $header.hasClass('sticky-header') && ! $body.hasClass('force-sticky-header') && target.offset().top - offsetSize > header_container_height ){
                    offsetSize += header_container_height ;
                }

                if ( $body.hasClass('admin-bar') ) {
                    offsetSize += $('#wpadminbar').height();
                }

                if ( target.length ) {
                    $('html,body').animate({
                        scrollTop: target.offset().top - offsetSize
                    }, 1000, 'easeOutCirc');

                    return false;
                }
            }
        });
    };

    $nav.yit_onepage();


    /*************************
     * Smooth Scroll
     *************************/

    if ( $.srSmoothscroll && navigator.userAgent.indexOf('Mac') == -1 && $.browser.webkit ) {

        $.srSmoothscroll({
            step  : 160,
            speed : 380,
            ease  : "easeOutCirc"
        });

    }

    //$window.on('scroll', function(){
    //    $(".owl-carousel").each(function(){
    //        var owl = $(this).data('owlCarousel');
    //
    //        if ( onScrollEnd ) clearTimeout( onScrollEnd );
    //
    //        onScrollEnd = setTimeout(function(){
    //            owl.play();
    //        }, 500 );
    //
    //        owl.stop();
    //    });
    //});

    /*************************
     * Custom select
     *************************/

    if ( $.fn.selectbox ) {

        /*fix wc 2.3 */
        var wc_version = 2.2;
        if(typeof yit_woocommerce != 'undefined')  wc_version = parseFloat( yit_woocommerce.version );
        var calculate_shipping_select = '';
        if(wc_version < 2.3) calculate_shipping_select = '.woocommerce table.shop_table.shipping p select,';
        var custom_selects = $('.woocommerce-ordering select, .faq-filters select,'+calculate_shipping_select+' .widget_product_categories select, .widget.widget_archive select, .widget.widget_categories #cat, .widget.woocommerce.widget_layered_nav > select , select#message-type-select, select#display_name, #dropdown_layered_nav_color, .wcml_currency_switcher');
        if ( custom_selects.length > 0 ) {
            custom_selects.selectbox({
                effect: 'fade'
            });
        }
    }

    /*************************
     * Sticky Footer
     *************************/

    if ( $.fn.imagesLoaded ) {
        $primary.imagesLoaded(function () {
            if ( $footer.length > 0) {
                $footer.stickyFooter();
            }
            else {
                $copyright.stickyFooter();
            }
        });
    }

    /*************************
     * Replace type="number" in opera
     *************************/

    $('.opera').find('.quantity input.input-text.qty').replaceWith(function() {
        return '<input type="text" class="input-text qty text" name="quantity" value="' + $(this).attr('value') + '" />';
    });

    /*************************
     * Back to top
     *************************/

    if( $('#back-top').length ){
        $.yit_backToTop();
    }

    /*************************
     * MENU
     *************************/

    $( 'div:not(.vertical).nav li:has(.submenu), .mobile-nav li:has(ul) > a, .mobile-nav li:has(.submenu) > a' ).doubleTapToGo();
    $.yit_fix_menu_position();

    /*************************
     * sticky header
     *************************/

    if ($header.hasClass('sticky-header')) {
        $header.imagesLoaded(function () {

            var sticky_header = $header.hasClass('skin3') || $header.hasClass('skin2') ? $('#header') : $('#header-container'),
                header_height = sticky_header.outerHeight(),
                headerBottomPos = sticky_header.offset().top + header_height,
                hPlaceholder = $('<div />').addClass('header-placeholder').height(header_height),
                logo = $('#logo');

            if (sticky_header.length) {

                var header_sticky_scroll = function () {

                    hPlaceholder = hPlaceholder.height(header_height);

                    if ($window.scrollTop() + hAdminBar > headerBottomPos) {
                        var top = hAdminBar;
                        if ($window.width() <= 600) {
                            top = 0;
                        }

                        if (!sticky_header.hasClass('fixed')) {
                            sticky_header.hide().height('')
                                .addClass('fixed')
                                .css({
                                    top            : -header_height,
                                    display        : 'block',
                                    backgroundColor: $header.css('backgroundColor'),
                                    backgroundImage: $header.css('backgroundImage')
                                })
                                .delay(500)
                                .animate({top: top}, 500);

                            $header.removeClass('search-opened').addClass('search-closed');

                            hPlaceholder.insertAfter(sticky_header);
                        }

                    } else if ($window.scrollTop() + hAdminBar <= hPlaceholder.offset().top) {

                        sticky_header.stop(true, true).removeClass("fixed")
                            .css({
                                top   : 0,
                                height: header_height
                            })
                            .show();

                        setTimeout(function () {
                            sticky_header.height('');
                        }, 1000);

                        hPlaceholder.remove();

                    }

                };

                header_sticky_scroll();
                $window.on('scroll', header_sticky_scroll);
            }
        });
    }

    /*************************
     * Bigmenu
     *************************/

    $nav.yit_bigmenu();


    /*************************
     * FULL WIDTH SECTION
     *************************/

    $('.section_fullwidth').yit_fullwidth_section();


    /*************************
     * PARALLAX
     *************************/

    $.yit_parallax();


    /*************************
     * PRETTYPHOTO
     *************************/

    if ($.fn.prettyPhoto) {
        $(".video-button a[rel^='prettyPhoto']").prettyPhoto({
            social_tools  : '',
            default_width : 650,
            default_height: 487,
            show_title    : false
        });
    }


    /*************************
     * MASONRY
     *************************/

    var add_masonry = function(){

        if ( $.fn.imagesLoaded && $.fn.masonry ) {
            $('.blog-masonry, ul.products.masonry, .testimonials').each( function(){
                var container = $(this),
                    item = container.data('item');

                if( item === 'undefined' ){
                    item = '.masonry_item';
                }

                container.imagesLoaded( function(){
                    container.masonry({
                        itemSelector: item,
                        isAnimated: true,
                        isRTL: yit.isRtl
                    });
                }).css( 'visibility', 'visible' );
            });
        }
    };

    $(window).on( 'load resize', add_masonry );

    /*************************
     * Newlsetter Placeholder
     *************************/

    $('.widget input.email-field.text-field.autoclear').each(function(){
        var placeholder = $(this).attr('placeholder');

        $(this)
            .on( 'focus', function(){
               var $this = $(this);
               $this.attr('placeholder', '');
            })
            .on( 'blur', function(){
                var $this = $(this);
                $this.attr('placeholder', placeholder );
            });
    });


    /*************************
     * PostFormat Gallery
     *************************/

    if( $body.hasClass( 'single-format-gallery' ) ){
        $( '.masterslider' ).yit_gallery_post_format();
    }



    /*************************
     * WAYPOINT
     *************************/

    if ( ! YIT_Browser.isMobile() ) {
        $('.yit_animate:not(.animated), .parallaxeos_animate:not(.animated)').each(function(){
            $(this).yit_waypoint();
        });
    }



    /*************************
     * WIDGETS
     *************************/

    $('.yit_toggle_menu ul.menu').each(function(){
        var menu = $(this);

        menu.filter('.open_first').find("> li:first-child").addClass("opened");
        menu.filter('.open_all').find("> li").addClass("opened");

        menu.filter('.open_active').find('li').filter('.current-menu-ancestor').addClass("opened");
        menu.filter('.open_active').find('li').filter('.current-menu-parent').addClass("opened");
        menu.filter('.open_active').find('li.current-menu-item').addClass("opened");

        menu.find('> li > ul').hide();
        menu.find('> li.opened > ul').show();

        menu.on( 'click', '> li > a', function (e) {
            e.preventDefault();

            var submenu = $(this).next("ul"),
                li = submenu.parent("li");

            li.hasClass("opened") ? li.removeClass("opened") : li.addClass("opened");

            submenu.slideToggle('slow');
        });
    });

    if ( $.fn.owlCarousel ) {
        $( '.slides-reviews-widget').each( function(){
            var slider = $(this);

            slider.owlCarousel({
                items           : 1,
                singleItem      : slider.data('singleitem'),
                pagination      : false,
                nav             : slider.data('navigation'),
                navText         : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                slideSpeed      : slider.data('slidespeed'),
                autoplay        : slider.data('autoplay'),
                loop            : true
            });
        });

        if ( $.fn.imagesLoaded ) {

            $('.logos-slides').imagesLoaded( function(){
                var t       = $('.logos-slides'),
                    speed   = t.data( 'speed'),
                    elementsNum  = t.find('li').size(),
                    itemsNum = t.data( 'items' ),
                    shownav = ( elementsNum <= itemsNum ) ? false : true,
                    owl     = t.owlCarousel({
                        items: itemsNum,
                        responsiveClass:true,
                        responsive:{
                            0 : {
                                items: 1
                            },
                            479 : {
                                items: 3
                            },
                            767 : {
                                items: 4
                            },
                            992 : {
                                items: itemsNum
                            }
                        },
                        autoplay: 3000,
                        paginationSpeed: speed,
                        loop : true
                    });

                // Custom Navigation Events
                if( shownav ){
                    t.closest('.logos-slider').on('click', '.next', function(e){

                        e.preventDefault();
                        owl.trigger('next.owl.carousel');
                    });

                    t.closest('.logos-slider').on('click', '.prev', function(e){
                        e.preventDefault();
                        owl.trigger('prev.owl.carousel');
                    });
                }else{
                    t.closest('.logos-slider').find('.nav').css('display','none');
                }

            });
        }

        if ( $.fn.BlackAndWhite ) {
            $('.bwWrapper').BlackAndWhite({
                hoverEffect : true, // default true
                // set the path to BnWWorker.js for a superfast implementation
                webworkerPath : false,
                // for the images with a fluid width and height
                responsive:true,
                speed: { //this property could also be just speed: value for both fadeIn and fadeOut
                    fadeIn: 200, // 200ms for fadeIn animations
                    fadeOut: 300 // 800ms for fadeOut animations
                }
            });
        }

        $("a.bwWrapper[href='#']").click(function(){ return false })


    }



    /****************************
     *toggle
     *************************/
    $('.toggle-content:not(.opened), .content-tab:not(.opened)').hide();
    $('.toggle .toggle-title').on('click', function(){

        var $this = $(this),
            opened_class = $this.children('span').data('opened'),
            closed_class = $this.children('span').data('closed');

        $this.next().slideToggle(600, 'easeOutExpo');
        $this.toggleClass('tab-opened tab-closed');
        $this.children('span').toggleClass(closed_class+' '+opened_class);
        $this.attr('title', ($(this).attr('title') == 'Close') ? 'Open' : 'Close');
        return false;
    });


    /****************************
     * dropdown products category
     *************************/

    var widget = $('.widget.woocommerce.widget_product_categories, .widget.widget_categories');
    var main_ul = widget.find('> ul');

    if ( main_ul.length ) {
        var dropdown_widget_nav = function() {

            main_ul.find('> li').each(function () {

                var main = $(this),
                    link = main.find('> a'),
                    ul = main.find('> ul.children');

                if ( ul.length ) {

                    //init widget
                    if ( main.hasClass('closed') ) {
                        ul.hide();
                        link.after('<i class="icon-plus"></i>');
                    }
                    else if ( main.hasClass('opened') ) {
                        link.after('<i class="icon-minus"></i>');
                    }
                    else {
                        main.addClass('opened');
                        link.after('<i class="icon-minus"></i>');
                    }

                    // on click
                    main.find('i').on('click', function () {

                        ul.slideToggle('slow');

                        if ( main.hasClass('closed') ) {
                            main.removeClass('closed').addClass('opened');
                            main.find('i').removeClass('icon-plus').addClass('icon-minus');
                        }
                        else {
                            main.removeClass('opened').addClass('closed');
                            main.find('i').removeClass('icon-minus').addClass('icon-plus');
                        }
                    });
                }
            });
        };

        $(document).on('yith-wcan-ajax-filtered' );
        dropdown_widget_nav();
    }

    /***********************
    * MOBILE MENU FIX
    ***********************/
    var respmenuclick = $('.st-menu ul.level-1 > li.menu-item-has-children');
    respmenuclick.on('click',function(){
        var t = $(this);
        if ( t.hasClass('open')){
            t.removeClass('open')
        }
        else{
            respmenuclick.removeClass('open');
            t.addClass('open')
        }
    });



    /***********************
     * GOOGLE MAP
     ***********************/

    $.initializeMap =  function ( gmap_id, lat, lng, address, zoom, marker_icon, black ) {
        var map,
            isDraggable = $window.width() > 768 ? true : false,
            mapStyles;
        if( black ){
            var mapStyles = [
                {
                    stylers: [
                        {hue: "#666666" },
                        {saturation: "-100"},
                        {lightness: "-40"},
                        {gamma: 1}
                    ]
                }
            ];
        }

        var yitMapType = new google.maps.StyledMapType(mapStyles,
            {name: "YIT Map"}),
            geocoder = new google.maps.Geocoder(),
            latlng = new google.maps.LatLng( lat, lng),

            mapOptions = {
            zoom: zoom,
            scrollwheel: false,
            draggable: isDraggable,
            center: latlng,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: false,
            scaleControlOptions: {
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            streetViewControl: false,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            panControl: false,
            panControlOptions: {
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            mapTypeControl: false,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'yit_style'],
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            mapTypeId: 'yit_style'
        };


        map = new google.maps.Map(document.getElementById(gmap_id), mapOptions);
        map.mapTypes.set('yit_style', yitMapType);

        if (geocoder) {

            geocoder.geocode( { address: address , location: latlng }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map     : map,
                        icon    : marker_icon,
                        title   : address
                    });
                }
            });
        }

    };


    $(".map_canvas").each( function(){
        var gmap = $(this),
            gmap_id = gmap.attr('id'),
            lat = gmap.data('lat'),
            lng = gmap.data('lng'),
            zoom = gmap.data('zoom'),
            marker = gmap.data('marker'),
            style  = ( gmap.data('style') == 'color') ? false : true,
            address = gmap.data('address');

        $.initializeMap( gmap_id, lat, lng, address, zoom, marker, style );


    });

    /*************************
     * Fix map inside a woocommerce tab
     *************************/
    $('.woocommerce-tabs ul.tabs li a').on( 'click', function(  ) {
        var $tab = $(this),
            $tabs_wrapper = $tab.closest( '.woocommerce-tabs'),
            $tabcon = $( 'div' + $tab.attr( 'href' ), $tabs_wrapper);
        if( $tabcon.find('.map_canvas').length > 0 ){

            var gmap = $tabcon.find('.map_canvas'),
                gmap_id = gmap.attr('id'),
                lat = gmap.data('lat'),
                lng = gmap.data('lng'),
                zoom = gmap.data('zoom'),
                marker = gmap.data('marker'),
                style  = ( gmap.data('style') == 'color') ? false : true,
                address = gmap.data('address');

            $.initializeMap( gmap_id, lat, lng, address, zoom, marker, style );
        }

    });

    /*************************
     * Fix mobile video
     *************************/

    $.yit_video_mobile_fix();

    /***********************
     * Tooltip
     *********************/

    $.yit_tooltip();


    /**************************
     * Portfolio Image changer
     ************************/

    $.yit_portfolio_image_changer();


    /***************************************
     * YIT Shop By Cathegory Dropdown
     **************************************/
    $.yit_shop_by_category_dropdown();


    var $vertical_menu = $(document).find('.nav.vertical');

    if ( YIT_Browser.isMobile() && $vertical_menu.length ) {

        $vertical_menu.find('.nav').addClass( 'mobile-nav' );

        $('ul#menu-shop-by-category > li.bigmenu.menu-item-has-children > a').on('click', function(e){
            e.preventDefault();
            $(this).siblings('.submenu').toggle();
        });
    }

});