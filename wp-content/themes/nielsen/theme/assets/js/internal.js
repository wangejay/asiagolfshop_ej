(function ($, window, document) {
    "use strict";

    var $window   = $(window),
        $body     = $(document.body),

        wrapper   = document.getElementById('wrapper'),
        topbar    = document.getElementById('topbar'),
        header    = document.getElementById('header'),
        nav       = document.getElementById('nav'),
        primary   = document.getElementById('primary'),
        footer    = document.getElementById('footer'),
        copyright = document.getElementById('copyright'),

        $wrapper   = $( wrapper ),
        $topbar    = $( topbar ),
        $header    = $( header ),
        $nav       = $( '.nav' ),
        $primary   = $( primary ),
        $footer    = $( footer ),
        $copyright = $( copyright),

        hHeader = $header.height(),
        hIsTransparent = $header.hasClass('transparent'),
        hIsSticky = $header.hasClass('sticky-header');

    /*************************
     * Back to top
     *************************/

    $.yit_backToTop = function() {
        var $backToTop = $( document.getElementById("back-top") );

        if ( $backToTop.length ) {
            // hide #back-top first
            $backToTop.hide();

            // fade in #back-top
            $(window).on( 'scroll', function () {
                if ( $window.scrollTop() > 100 ) {
                    $backToTop.fadeIn();
                } else {
                    $backToTop.fadeOut();
                }
            });

            // scroll body to 0px on click
            $backToTop.on( 'click', 'a', function (e) {
                e.preventDefault();

                $('body,html').animate({
                    scrollTop: 0
                }, 800);
            });
        }
    };

    /*************************
     * Menu
     *************************/

    $.yit_fix_menu_position = function(){
        $('#nav > div > ul > li.menu-item-has-children:has(div.submenu), #topbar .nav > ul > li.dropdown ')
            .on( 'mouseenter', function(){
                var submenu = $(this).children('div.submenu'),
                    left_submenu = submenu.offset().left,
                    right_submenu = left_submenu + submenu.outerWidth(),
                    container = submenu.closest('#wrapper'),
                    right_container = container.offset().left + container.outerWidth();

                if ( right_submenu > right_container ) {
                    submenu.css({
                        left: right_container - right_submenu
                    });
                }
            })
            .on( 'mouseleave', function(){
                var submenu = $(this).children('div.submenu');
                submenu.delay(500).css({
                    left: ''
                });
            });
    };

    /*************************
     * Big Menu
     *************************/

    $.fn.yit_bigmenu = function () {
        var $this = $(this);
        //add custom image as background
        $this.find('.bigmenu').each( function () {

            var bigmenu = $(this),

                custom_image  =  bigmenu.find('.custom-item-yitimage'),
                col_width = 190, //width of a column
                maxcol = 3,      //max columns in a row
                col = 1,         //min num of column

                paddingright = 0,
                paddingbottom = 0;

            if( bigmenu.is("[class*='padding-bottom-']") || bigmenu.is("[class*='padding-right-']") ){
                var classes = bigmenu.attr('class').split(" ");

                $.each( classes, function( i, val ) {
                    if( val.indexOf('padding-bottom-') != -1 ){
                        paddingbottom = val.replace('padding-bottom-', '');
                    }
                    if( val.indexOf('padding-right-') != -1 ){
                        paddingright = val.replace('padding-right-', '');
                    }
                });
            }

            var nchild = bigmenu.find('.submenu > ul.sub-menu').children('li.menu-item-has-children').length;

            if ( Math.ceil(nchild / maxcol) > 1 ) {
                col = 3;
            } else {
                col = nchild;
            }

            var cal_width = col_width * col + col*20 +10;

            if ( custom_image.length > 0 ) {

                var image_item = custom_image.find('img').attr('src'),
                    height     = custom_image.find('img').attr('height'),
                    width      = custom_image.find('img').attr('width');

                if( cal_width < width ) cal_width = width;

                /* added by Andrea Frascaspata rtl fix */
                var background_position = ( yit.isRtl ) ? "left bottom" : "right bottom";
                //------------------------------------------------

                custom_image.next('.submenu').children('ul.sub-menu').css({
                    'min-height'         : height + 'px',
                    'background-image'   : 'url(' + image_item + ')',
                    'background-repeat'  : 'no-repeat',
                    'background-position': background_position,
                    'padding-right'      : paddingright + 'px',
                    'padding-bottom'     : paddingbottom + 'px',
                    'width'              : cal_width + 'px'
                });

                custom_image.remove();
            }
            else if ( nchild > 0 ) {

                bigmenu.find('> .submenu > ul.sub-menu').css({
                    'min-height'    : '150px',
                    'height'        : 'auto',
                    'padding-right' : paddingright + 'px',
                    'padding-bottom': paddingbottom + 'px',
                    'width'         : cal_width + 'px'
                });
            }
        });
    }

    /*************************
     * PostFormat Gallery
     *************************/

    $.fn.yit_gallery_post_format = function () {
        var gallery_sliders = new Array();

        $(this).each(function () {

            var t               = $(this),
                height          = t.data('height'),
                width           = t.data('width'),
                view            = t.data('view'),
                postID          = t.data('postid'),
                sliderContainer = 'galleryslider-' + postID;

            gallery_sliders[ postID ] = new MasterSlider();
            gallery_sliders[ postID ].control('arrows');

            gallery_sliders[ postID ].setup(sliderContainer, {
                width       : width,     // slider standard width
                height      : height,    // slider standard height
                view        : view,      //slider view
                heightLimit : false,
                swipe       : true,
                autoplay    : true,
                loop        : true
            });
        });
    };


    /*************************
     * Fullwidth Section
     *************************/

    $.fn.yit_fullwidth_section = function () {

        var sections = this,
            init = function() {
                sections.each(function () {
                    var windowsize = $body.hasClass('boxed-layout') ? $wrapper.outerWidth() : $window.width(),
                        section = $(this);

                    if ( ! section.closest('.slider').length ) {
                        section.css({
                            left: -( windowsize / 2 ),
                            width: windowsize
                        });
                    }

                    // content vertical center
                    if ( section.find('.vertical_center').length ) {
                        section.find('.vertical_center').css({
                            height: section.find('.vertical_center').height(),
                            top: ( section.height() - section.find('.vertical_center').height() ) / 2,
                            marginBottom: 'auto'
                        });
                    }

                    // fix text position aligned on top when header is transparent
                    if ( section.find('.vertical_top').length && hIsTransparent ) {
                        section.find('.vertical_top').css({ marginTop: hHeader });
                    }

                    // fix video background
                    if ( section.find('video').length ) {
                        section.find('video').yit_video_size();
                    }
                });
            };

        init();
        sections.imagesLoaded( init );
        $window.on( 'resize scroll', init );
    };



    /*************************
     * PARALLAX
     *************************/

    $.yit_parallax = function() {

        if ( YIT_Browser.isMobile() ) {
            return;
        }

        var selectors = $('.parallaxeos, .video-parallaxeos'),
            init = function(){
                selectors.each(function(){
                    var parallax = $(this),
                        is_video = parallax.hasClass('video-parallaxeos') ? true : false,

                        speed = 5,
                        $container = parallax.closest('.parallaxeos_outer'),
                        $content = $container.find('.parallaxeos_content'),
                        headerHeight = hIsSticky ? hHeader + $header.position().top : 0,
                        winScroll = $window.scrollTop(),
                        elScrollViewport = $container.offset().top - winScroll,
                        yPos = -( elScrollViewport + ( winScroll - $container.offset().top ) / speed );

                    if ( ! is_video ) {
                        parallax.css({ backgroundPosition: '50% ' + yPos + 'px', height: $window.height() });
                    } else {
                        parallax.translate3d({ y: yPos });
                    }

                    // center the text
                    yPos += hIsSticky && ! hIsTransparent ? hHeader + $topbar.height() : 0;
                    if ( ! parallax.closest('#primary').length ) {
                        var ratio = Math.abs( winScroll / ( parallax.height() + headerHeight ) );

                        parallax.css({ height: '' });

                        $content.translate3d({
                            y: yPos
                        }).css({
                                opacity: 1 - ratio
                            });
                    }

                });
            };

        init();
        $window.on( 'resize scroll', init );

    };

    // Video Parallax
    $.fn.yit_video_size = function() {

        var video = this,

            resizeVideo = function(videoObject) {
                var percentWidth = videoObject.clientWidth * 100 / videoObject.videoWidth;
                var videoHeight = videoObject.videoHeight * percentWidth / 100;
                video.height(videoHeight);
            },

            onLoadMetaData = function(e) {
                resizeVideo(e.target);
            };

        video.trigger('play');
        video.on("loadedmetadata", onLoadMetaData);

        _onresize( function(){
            video.trigger('loadedmetadata');
        });

    };


    /*************************
     * WAYPOINT
     *************************/

    $.fn.yit_waypoint = function() {
        var $this = $(this);

        if (typeof jQuery.fn.waypoint !== 'undefined') {
            $this.waypoint(function() {
                var delay = $this.data('delay'),
                    animation = $this.data('animate');

                if( typeof animation != 'undefined' && ! $this.hasClass('animated') ){
                    $this.removeClass(animation);
                }

                $this.delay(delay).queue(function(next){
                    if( typeof animation != 'undefined' ){
                        $this.addClass('animated '+ animation );
                    }else{
                        $this.addClass('animated');
                    }
                    $this.css('opacity','1');
                    next();
                });

            }, {offset: '98%'});
        }
    };

    /*************************
     * CENTER
     *************************/

    $.fn.center = function () {
        this.css("position","fixed");
        this.css("top", Math.max(0, (( jQuery( window ).height() - this.outerHeight() ) / 2 ) ) + "px" );
        this.css("left", Math.max(0, (( jQuery( window ).width() - this.outerWidth() ) / 2 ) ) + "px" );
        return this;
    };

    /*************************
     * YIT FAQ
     *************************/

    $.yit_faq = function( options, element ) {
        this.element = $( element );
        this._init( options );
    };

    $.yit_faq.defaults  = {
        elements : {
            items: $('.faq-wrapper'),
            header: '.faq-title',
            content: '.faq-item',
            filters: $('.filters li a, .faq-filters a')
        }
    };

    $.yit_faq.prototype = {
        _init : function( options ) {

            this.options = $.extend( true, {}, $.yit_faq.defaults, options );

            this._initSizes();
            this._initEvents();
        },

        _firstOpen: function() {
            var faqs_container  = this.element.context,
                faq_wrapper     = faqs_container.firstElementChild,
                first_faq       = $( faq_wrapper.firstElementChild );

            first_faq.trigger( 'click.yit' );
        },

        _initSizes : function() {
            $(this.options.elements.content, this.element).each(function(){
                var parentWidth = $(this).parent().width();
                $(this).width(parentWidth);
            })
        },

        _initEvents : function() {
            var elements = this.options.elements;
            var self = this;

            //filters
            elements.filters.on('click.yit', function(e){
                e.preventDefault();

                if( !$(this).hasClass('active') ) {
                    elements.filters.removeClass('active').filter(this).addClass('active');

                    self._closeAll();
                    self._filterItems( $(this).data('option-value') );
                }
            });

            //single items
            elements.items.on('click.yit', elements.header, function(e){

                e.preventDefault();
                self._toggle( $(this) );
            });

            $(window).resize(function(e){
                self._initSizes();
            });

            self.element.resize(function(){
                $(window).trigger('sticky');
            });

            this._firstOpen();
        },

        _filterItems : function( selector ) {
            var self = this;
            var items = this.options.elements.items;

            items.filter(':visible').fadeOut('slow', function(){
                items.filter(selector).fadeIn({
                    'complete': function(){
                        $(window).trigger('sticky');
                    }
                });
            });
        },

        _toggle : function( selector ) {
            if( selector.next().is(':visible') ) {
                this._close( selector );
            } else {
                this._open( selector );
            }
        },

        _open : function( selector ) {
            this._closeAll();
            selector.toggleClass('active').find(':first-child').toggleClass('closed').toggleClass('open').toggleClass('fa-plus').toggleClass('fa-minus');
            selector.siblings( this.options.elements.content ).slideDown({
                'complete': function(){
                    $(window).trigger('sticky');
                }
            });
        },

        _close : function( selector, animation ) {
            selector.toggleClass('active').find(':first-child').toggleClass('closed').toggleClass('open').toggleClass('fa-plus').toggleClass('fa-minus');
            selector.siblings( this.options.elements.content ).slideUp({
                'complete': function(){
                    $(window).trigger('sticky');
                }
            });
        },

        _closeAll : function() {
            var headers = $( this.options.elements.header ).filter('.active');
            var self = this;

            headers.each(function(){
                self._close( $(this) );
            });
        }

    };

    $.fn.yit_faq = function( options ) {

        if ( typeof options === 'string' ) {
            var args = Array.prototype.slice.call( arguments, 1 );

            this.each(function() {
                var instance = $.data( this, 'yit_faq' );
                if ( !instance ) {
                    console.error( "cannot call methods on yit_checkout prior to initialization; " +
                        "attempted to call method '" + options + "'" );
                    return;
                }
                if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
                    console.error( "no such method '" + options + "' for yit_faq instance" );
                    return;
                }
                instance[ options ].apply( instance, args );
            });
        }
        else {
            this.each(function() {
                var instance = $.data( this, 'yit_faq' );
                if ( !instance ) {
                    $.data( this, 'yit_faq', new $.yit_faq( options, this ) );
                }
            });
        }
        return this;
    };

    /*************************
     * MOBILE MENU
     *************************/

    /**
     * sidebarEffects.js v1.0.0
     * http://www.codrops.com
     *
     * Licensed under the MIT license.
     * http://www.opensource.org/licenses/mit-license.php
     *
     * Copyright 2013, Codrops
     * http://www.codrops.com
     */
    var SidebarMenuEffects = (function() {

        function hasParentClass( e, classname ) {
            if(e === document) return false;
            if( classie.has( e, classname ) ) {
                return true;
            }
            return e.parentNode && hasParentClass( e.parentNode, classname );
        }

        function mobilecheck() {
            var check = false;
            (function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
            return check;
        }

        function initMarkup() {
            var $wrapper = $('#wrapper'),
                nav      = $('.mobile-nav').length ? $('.mobile-nav').removeClass('hidden') : $('#nav').clone(true, true).attr('id', '').addClass('mobile-nav').removeClass('nav dropdown'),
                sidebar  = $('.mobile-sidebar').length ? $('.mobile-sidebar').removeClass('hidden') : '',
                search   = $('form#yith-ajaxsearchform, form#searchform, form.woocommerce-product-search').clone();

            $wrapper.wrap('<div id="st-container" class="st-container" />');
            $wrapper.wrap('<div class="st-pusher" />');
            $wrapper.wrap('<div class="st-content" />');
            $wrapper.wrap('<div class="st-content-inner" />');

            // adjust search
            search.attr('id', 'searchform');
            search.find('#yith-s').attr('id', 's');
            search.find('#yith-searchsubmit').attr('id', 'searchsubmit').addClass('button').attr( 'value', yit.search_button );

            $('#st-container').prepend('<nav class="st-menu st-effect-4" id="menu-1" />');

            // remove categories select from search
            search.find( 'select.search_categories, #sbHolder_' + search.find('select.search_categories').attr('sb')).remove();

            // remove the search inside the nav container
            nav.find('#header-search').remove();

            // change .nav class to .mobile-nav
            if ( sidebar ) sidebar.find('.nav').removeClass('nav').addClass('mobile-nav');

            $('nav.st-menu').append( search ).append( nav ).append( sidebar );
        }

        function init() {

            initMarkup();

            var container = document.getElementById( 'st-container' ),
                buttons = Array.prototype.slice.call( document.querySelectorAll( '#mobile-menu-trigger > a' ) ),
            // event type (if mobile use touch events)
                eventtype = mobilecheck() ? 'touchstart' : 'click',
                resetMenu = function() {
                    classie.remove( container, 'st-menu-open' );
                },
                bodyClickFn = function(evt) {
                    if( !hasParentClass( evt.target, 'st-menu' ) ) {
                        resetMenu();
                        document.removeEventListener( eventtype, bodyClickFn );
                    }
                };

            buttons.forEach( function( el, i ) {
                var effect = el.getAttribute( 'data-effect' );

                el.addEventListener( eventtype, function( ev ) {
                    ev.stopPropagation();
                    ev.preventDefault();
                    container.className = 'st-container'; // clear
                    classie.add( container, effect );
                    setTimeout( function() {
                        classie.add( container, 'st-menu-open' );
                    }, 25 );
                    document.addEventListener( eventtype, bodyClickFn );
                });
            } );

        }

        var fix_menu_pos = function(){
            if ( $body.is('.admin-bar') ) {
                var pos = $('#wpadminbar').height() - $window.scrollTop();
                $('.st-menu').css({
                    top: pos > 0 ? pos : 0
                });
            }
        }

        init();

        $window.on('scroll', fix_menu_pos);
        $(document).ready(fix_menu_pos);


        var mobileSubMenu = function(){
            // event type (if mobile use touch events)
            var eventtype = mobilecheck() ? 'touchstart' : 'click',
                trigger_click = $('.st-menu ul > li:has(ul.sub-menu) > a');

            trigger_click.on( eventtype, function (e) {
                e.stopPropagation();
                var t = $(this);
                /*
                 t.parent().find('ul.sub-menu').css({
                 'display': 'block',
                 'border': 'none'
                 }
                 )
                 */
                //$( '.st-menu' ).find('ul.sub-menu').not( t ).hide();

                t.parent().find('ul.sub-menu').toggle();
            })
        };

        $(document).ready( mobileSubMenu );

    })();




    /*************************
     * YITH Woocommerce AJAX Search
     *************************/

    $.yit_trigger_search = function(){
        $header.on('click', '.search-trigger a', function(e){
            e.preventDefault();

            var timeout = 0;

            if ( $(this).closest('.fixed').length ) {
                $('html,body').animate({
                    scrollTop: 0
                }, 300, 'easeOutCirc');

                timeout = 400;
            }

            setTimeout( function(){
                if ( $header.hasClass('search-small') ) {

                    if ( ! $header.hasClass('search-closed') && ! $header.hasClass('search-opened') ) {
                        $header.addClass('search-opened');
                    } else {
                        $header.toggleClass('search-opened search-closed');
                    }

                }
            }, timeout);
        });
    };

    $.yit_ajax_search = function(){

        //if( $('.yith-search-premium').length ) return false;

        if ( $.fn.autocomplete ) {

            /*fix wc 2.3 */
            var loader_url = '';

            if( typeof yit.load_gif != 'undefined' ) {
                loader_url = yit.load_gif;
            }   else {
                loader_url = woocommerce_params.ajax_loader_url;
            }

            /* end fix wc 2.3 */

            var ajax_search = $('#yith-s'),
                loader_icon = ajax_search.data('loader-icon') == '' ? loader_url : ajax_search.data('loader-icon'),
                min_chars = ajax_search.data('min-chars'),
                search_categories = $('#search_categories');

            ajax_search.autocomplete({
                minChars: min_chars,
                appendTo: '.yith-ajaxsearchform-container .nav-searchfield',
                serviceUrl: woocommerce_params.ajax_url + '?action=yith_ajax_search_products',
                onSearchStart: function () {
                    $(this).css('background', 'url(' + loader_icon + ') no-repeat 99% center');
                },
                onSearchComplete: function () {
                    $(this).css('background', 'transparent');
                },
                onSelect: function (suggestion) {
                    if (suggestion.id != -1) {
                        window.location.href = suggestion.url;
                    }
                },

                formatResult: function (suggestion, currentValue) {

                    var pattern = '(' + $.Autocomplete.utils.escapeRegExChars(currentValue) + ')';
                    var html = '';

                    if( $('.yith-search-premium').length ){

                        if ( typeof suggestion.img !== 'undefined' ) {
                            html += suggestion.img;
                        }
                        html += '<div class="yith_wcas_result_content">';
                        html += suggestion.value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');

                        if ( typeof suggestion.div_badge_open !== 'undefined' ) {
                            html += suggestion.div_badge_open;
                        }

                        if ( typeof suggestion.on_sale !== 'undefined' ) {
                            html += suggestion.on_sale;
                        }

                        if ( typeof suggestion.featured !== 'undefined' ) {
                            html += suggestion.featured;
                        }

                        if ( typeof suggestion.div_badge_close !== 'undefined' ) {
                            html += suggestion.div_badge_close;
                        }

                        if ( typeof suggestion.excerpt !== 'undefined' ) {
                            html += ' ' + suggestion.excerpt.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');
                        }

                        if ( typeof suggestion.price !== 'undefined' ) {
                            html += ' ' + suggestion.price;
                        }
                        html += '</div>';
                    }else{
                        var html = suggestion.value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');

                        if ( typeof suggestion.price !== 'undefined' ) {
                            html += ' ' + suggestion.price;
                        }

                        if ( typeof suggestion.img !== 'undefined' ) {
                            html += suggestion.img;
                        }
                    }

                    return html;
                }
            });

            // categories select
            search_categories.selectbox({
                effect: 'fade',
                onOpen: function() {
                    //$('.variations select').trigger('focusin');
                }
            });

            if( search_categories.length ){
                search_categories.on( 'change', function( e ){
                    var ac = ajax_search.autocomplete();

                    if( search_categories.val() != '' ) {
                        ac.setOptions({
                            serviceUrl:  woocommerce_params.ajax_url + '?action=yith_ajax_search_products&product_cat=' + search_categories.val()
                        });
                    }else{
                        ac.setOptions({
                            serviceUrl:  woocommerce_params.ajax_url + '?action=yith_ajax_search_products'
                        });
                    }

                    // update suggestions
                    ac.hide();
                    ac.onValueChange();
                });
            }
        }
    };

    /*************************
     * YITH Video Mobile Fix
     *************************/

    $.yit_video_mobile_fix = function() {

        var $video_parallax = $(document).find('.video-parallaxeos');

        if( YIT_Browser.isMobile() && YIT_Browser.isTablet() && ! $('body').hasClass('isAndroid') && $video_parallax.length ) {

            $video_parallax.each( function(){
                var $id = $(this).attr('id'),
                    $elem = document.getElementById( $id),
                    f = $(this).data('executed');

                if ( typeof f == 'undefined' || f != true ) {
                    $(document).on( 'touchstart', function(){
                        $elem.play();
                    }).trigger('touchstart');

                    $(this).data('executed', true);
                }
            });

        }
    };

    /*************************
     * YITH Shop By Category
     *************************/

    $.yit_shop_by_category = function() {

        $header.find('.shop-category .list-trigger').on('click', function(e){
            e.preventDefault();
            $(this).parents('.shop-category').toggleClass('opened');
        });

    };

    /*************************
     * QUICK VIEW PLUGIN
     *************************/

    if ( typeof Modernizr != 'undefined' && $('.quick-view-overlay').length ) {

        $.fn.yit_quick_view = function(options) {

            $(this).each(function(){

                var trigger = $(this),
                    $window = $(window),

                    settings = $.extend({
                        item_container: 'li',
                        completed: function() {},
                        before: function() {},
                        openDialog: function() {},
                        action: 'yit_load_quick_view',
                        loader: '.main-content',
                        assets: null,
                        loadPage: false
                    }, options ),

                    trigger_id = trigger.attr('id'),
                    item = trigger.closest( settings.item_container ),
                    container = document.getElementById( 'wrapper' ),
                    triggerBttn = $( '#' + trigger_id ),
                    overlay = document.querySelector( 'div.quick-view-overlay' ),
                    closeBttn = overlay.querySelector( 'a.overlay-close'),

                    openQuickView = function(e) {
                        e.preventDefault();

                        var completed = function( data, html ) {

                            // load css assets
                            data.find('link').each(function(){
                                if ( $('#cache-dynamics-css').length ) {
                                    $(this).insertBefore( $('#cache-dynamics-css') );
                                } else {
                                    $(this).appendTo('head');
                                }
                            });

                            // load js scripts for the single product content
                            if ( settings.assets != null ) {
                                $.each(settings.assets, function (index, value) {
                                    $.ajax({
                                        type    : "GET",
                                        url     : value,
                                        dataType: "script"
                                    });
                                });
                            }

                            settings.completed( trigger, item, html, overlay );

                            // open effect
                            $(overlay).imagesLoaded(function () {
                                settings.openDialog( trigger, item );

                                classie.add(overlay, 'open');
                            });

                            $(document).trigger('yit_quick_view_loaded');
                        };

                        if ( ! classie.has(overlay, 'close') ) {
                            settings.before( trigger, item );

                            if ( settings.loadPage ) {
                                var href = trigger.attr('href');

                                $.get( href, { popup: true }, function(html){

                                    var data = $('<div>' + html + '</div>'),
                                        product_html = data.find( settings.loader ).wrap('<div/>').parent().html();

                                    // main content
                                    $(overlay).find('.main').find('.overlay-close').after(product_html);

                                    completed( data, html );

                                });
                            }
                            else {
                                $.post( yit.ajaxurl, { action: settings.action, item_id: trigger.data('item_id') }, function (html) {

                                    var data = $('<div>' + html + '</div>'),
                                        product_html = data.find( settings.loader ).wrap('<div/>').parent().html();

                                    // main content
                                    $(overlay).find('.main').find('.overlay-close').after(product_html);

                                    completed( data, html );

                                });
                            }
                        }
                    },

                    closeQuickView = function (e) {
                        e.preventDefault();

                        if ( classie.has(overlay, 'open') ) {

                            var close_button = $(overlay).find('.overlay-close'),
                                wrapper = $(overlay).find('.main');

                            classie.remove(overlay, 'open');

                            setTimeout(function () {
                                wrapper.find('.head').html( close_button );
                            }, 1000);

                        }
                    };

                triggerBttn.on( 'click', openQuickView );
                closeBttn.addEventListener( 'click', closeQuickView );
            });
        };
    }

    /*****************************
     * YITH Portfolio Image Changer
     ******************************/

    $.yit_portfolio_image_changer = function () {

        var image_wrap          = $( '#portfolio-big-image-wrap'),
            single_thumb        = $primary.find( '.portfolio-single-thumb'),
            image_tag           = image_wrap.find( 'img'),
            image_tag_height    = image_tag.height();

        single_thumb.on( 'click', function(e) {

            var t               = $(this),
                image_url       = t.data('image-url'),
                image_height    = image_wrap.height();

            if( ! t.hasClass( 'active' ) ) {
                $primary.find( '.portfolio-single-thumb.active').removeClass( 'active' );
                t.addClass( 'active' );

                if( image_tag_height > image_height ) {
                    image_wrap.height( image_tag_height );
                }

                image_wrap.css( 'min-height', image_height );

                if( 'undefined' != yit.load_gif ) {
                    image_wrap.block({message: null, overlayCSS: {background: '#fff url(' + yit.load_gif + ') no-repeat center', opacity: 0.6, cursor: 'none'}});
                }

                image_tag.attr( 'src', image_url ).imagesLoaded( function(){
                    image_wrap.unblock();
                })
            }
        });
    };

    /***************************************
     * YIT Tooltip
     **************************************/

    $.yit_tooltip = function(){

        $( 'a.with-tooltip, a.link_socials, a.compare, #yith-wcwl-form .yith-wcwl-share ul li a').each( function() {
            var $placement = ( $(this).data('placement') ) ? $(this).data('placement') : 'bottom';

            $(this).tooltip({
                placement: $placement
            });

        });
    }

    /***************************************
     * YIT Shop By Cathegory Dropdown
     **************************************/
    $.yit_shop_by_category_dropdown = function(){
        $header.find('#header-search .shop-by-category .list-trigger').on('click', function(e){
            e.preventDefault();
            var shop_by_category_menu = $header.find('#header-search .shop-by-category');
            if( $window.width() <= 767 || ( shop_by_category_menu.hasClass('opened') && shop_by_category_menu.hasClass('can-close') ) ){
                $('.shop-by-category .submenu-group').toggle();
            }
        });
    }

})( jQuery, window, document );



