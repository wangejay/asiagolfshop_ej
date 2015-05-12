jQuery(document).ready( function($){
    "use strict";

    var $body = $('body'),
        content_width   = $('.content').width(),
        container_width = $('.container').width();


    /*************************
     * FEATURES TAB
     *************************/

    $.fn.yiw_features_tab = function( options ) {
        var config = {
            'tabNav' : 'ul.features-tab-labels',
            'tabDivs': 'div.features-tab-wrapper'
        };

        if( options ) $.extend( config, options );

        this.each( function () {
            var tabNav  = $( config.tabNav, this );
            var tabDivs = $( config.tabDivs, this );
            var labelNumber = tabNav.children( 'li' ).length;

            tabDivs.children( 'div' ).hide();

            var currentDiv = tabDivs.children( 'div' ).eq( tabNav.children( 'li.current-feature' ).index() );
            currentDiv.show();

            $( 'li', tabNav ).hover( function() {
                if( !$( this ).hasClass( 'current-feature' ) ) {
                    var currentDiv = tabDivs.children( 'div' ).eq( $( this ).index() );
                    tabNav.children( 'li' ).removeClass( 'current-feature' );

                    $( this ).addClass( 'current-feature' );

                    tabDivs.children( 'div' ).hide().removeClass( 'current-feature' );
                    //console.log('hover');
                    currentDiv.fadeIn( 'slow', function() {
                        $(document).trigger('feature_tab_opened');
                    });
                }
            });

        });
    };

    $( '.features-tab-container' ).yiw_features_tab();

    /*************************
     * TABS
     *************************/

    $.fn.yiw_tabs = function(options) {
        // valori di default
        var config = {
            'tabNav': 'ul.tabs',
            'tabDivs': '.containers',
            'currentClass': 'current'
        };

        if (options) $.extend(config, options);

        this.each(function() {
            var tabsContainer = $(this),
                tabNav = $(config.tabNav, tabsContainer),
                tabDivs = $(config.tabDivs, tabsContainer),
                activeTab,
                maxHeight = 0;

            tabDivs.children('div').hide();

            if ( $('li.'+config.currentClass+' a', tabNav).length > 0 )
                activeTab = '#' + $('li.'+config.currentClass+' a', tabNav).data('tab');
            else
                activeTab = '#' + $('li:first-child a', tabNav).data('tab');

            $(activeTab).show().addClass('showing');
            tabsContainer.trigger('tab-opened', [ $( activeTab ) ]);

            $('li:first-child a', tabNav).parents('li').addClass(config.currentClass);

            $('a', tabNav).click(function(){
                if ( ! $(this).parents('li').hasClass('current') ) {

                    var id = '#' + $(this).data('tab');

                    $('li.'+config.currentClass, tabNav).removeClass(config.currentClass);
                    $(this).parents('li').addClass(config.currentClass);

                    $('.showing', tabDivs).fadeOut(200, function(){
                        $(this).removeClass('showing');
                        $(id).fadeIn(200).addClass('showing');

                        tabsContainer.trigger('tab-opened', [ $( id ) ]);
                    });
                }

                return false;
            });


        });


    };

    $('.tabs-container').yiw_tabs({
        tabNav  : 'ul.tabs',
        tabDivs : '.border-box'
    });

    /*************************
     * IMAGE STYLED
     *************************/

    $(window).on('load', function () {
        if ($.fn.prettyPhoto) {
            $(".image-styled .img_frame a[rel^='prettyPhoto']").prettyPhoto({
                social_tools: ''
            });


        }
    });


    /*************************
     * FIX WIDTH (sections, google maps, ecc...)
     *************************/

    var fixWidth = function(){
        var wrapperWidth = ( $body.hasClass('boxed-layout') ) ? $('#wrapper').outerWidth() : $(window).width();

        $('.portfolio-slider, .section-background, .google-map-frame.full-width .inner').css({
            width:  wrapperWidth
        });
    };

    _onresize( fixWidth );
    fixWidth();


    /*************************
     * SECTION BACKGROUND
     *************************/

    $('.section-background').each( function(){
        var section = $(this),
            section_background_fix_height = function(){
                var current_height = section.data('height');
                if ( current_height == 0 ){
                    var row = section.parents('.wpb_row'),
                        parent_height = row.next().height();

                    row.next().css('margin-bottom','25px');
                    section.css('height', parent_height+60);
                }
            };

        $( window ).on( 'load', section_background_fix_height );
        _onresize( section_background_fix_height );
    });



    /*************************
     * TEASER
     *************************/

    $('.teaser-wrapper').each( function(){

                var teaser = $(this);

                teaser.imagesLoaded(function() {
                    var teaser_height = teaser.find('.image').height();
                    teaser.find('.image_banner_inside').css({height: teaser_height});
                });
    });

    /*************************
     * FAQ
     *************************/

    $('#faqs-container').yit_faq();

    /*************************
     * BLOG SECTION
     *************************/

    $('.blog-slider').each(function(){
        var t = $(this),
            slider = t.find('.blogs_posts'),
            enable_slider = slider.data('slider'),
            owl,
            slides = ( container_width == 1140 && content_width < container_width ) ? 2 : 3,
            fixArrows = function() {
                var active_items  = slider.find('.owl-item.active').length,
                    slides_number = slider.find('.owl-item').length;

                if( slides_number == active_items ) {
                    t.find('.prev-blog, .next-blog').hide();
                } else {
                    t.find('.prev-blog, .next-blog').show();
                }
            };

//        console.log( $.fn.owlCarousel );
//        console.log('here');

        if( enable_slider != 'no' && $.fn.owlCarousel ) {
            t.imagesLoaded(function(){
                owl = slider.owlCarousel({
                    items : slides,
                    responsiveClass:true,
                    responsive:{
                        0 : {
                            items: 1
                        },
                        479 : {
                            items: 2
                        },
                        767 : {
                            items: 2
                        },
                        992 : {
                            items: slides
                        }
                    },
                    addClassActive: true,
                    loop : true
                });

                fixArrows();
            });

            _onresize( fixArrows );

            t.on( 'click', '.prev-blog', function(e){
                e.preventDefault();
                slider.trigger('prev.owl.carousel');
            });

            t.on( 'click', '.next-blog', function(e){
                e.preventDefault();
                slider.trigger('next.owl.carousel');
            });
        }else {
            t.find('.prev-blog, .next-blog').hide();
            slider.find('li.blog_post').css( 'margin-bottom', '30px' );
        }
    });


});