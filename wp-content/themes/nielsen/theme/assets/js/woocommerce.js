jQuery(document).ready( function($){
    "use strict";

    var $body = $('body'),
        $topbar = $( document.getElementById('topbar') ),
        $header = $( document.getElementById('header') ),
        $products_sliders = $('.products-slider-wrapper, .categories-slider-wrapper'),
        $single_container = $('.fluid-layout.single-product .content');



    /***************************************
     * UPDATE CALCULATE SHIPPING SELECT
    ***************************************/

    // FIX SHIPPING CALCULATOR SHOW
    $( '.shipping-calculator-form' ).show();

    if (parseFloat(yit_woocommerce.version) < 2.3 && $.fn.selectbox ) {

        $('#calc_shipping_state').next('.sbHolder').addClass('stateHolder');

        $body.on('country_to_state_changing', function(){
            $('.stateHolder').remove();
            $('#calc_shipping_state').show().attr('sb', '');

            $('select#calc_shipping_state').selectbox({
                effect: 'fade',
                classHolder: 'stateHolder sbHolder'
            });
        });
    }

    /*************************
     * SHOP STYLE SWITCHER
     *************************/

    $('#list-or-grid').on( 'click', 'a', function() {

        var trigger = $(this),
                view = trigger.attr( 'class' ).replace('-view', '');

            $( 'ul.products li' ).removeClass( 'list grid' ).addClass( view );
            trigger.parent().find( 'a' ).removeClass( 'active' );
            trigger.addClass( 'active' );

            $.cookie( yit_shop_view_cookie, view );

            return false;
    });


    /***************************************************
     * ADD TO CART
     **************************************************/

    var $pWrapper = new Array(),
        $i=0,
        $j= 0,
        $private = false,
        $storageSafari = 'SafariPrivate',
        $storage = window.sessionStorage;

    try {
        $storage.setItem( $storageSafari, 'safari_is_private' );
        $storage.removeItem( $storageSafari );
    } catch (e) {
        if ( e.code == DOMException.QUOTA_EXCEEDED_ERR && $storage.length == 0) {
            $private = true
        } else {
            throw e;
        }
    }

    var add_to_cart = function() {

        $('ul.products').on('click', 'li.product .add_to_cart_button', function () {

            $pWrapper[$i] = $(this).parents('.product-wrapper');
            var $thumb = $pWrapper[$i].find('.thumb-wrapper');

            if( typeof yit.load_gif != 'undefined' ) {
                $thumb.block({message: null, overlayCSS: {background: '#fff url(' + yit.load_gif + ') no-repeat center', opacity: 0.5, cursor: 'none'}});
            }
            else {
                $thumb.block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url.substring(0, woocommerce_params.ajax_loader_url.length - 7) + '.gif) no-repeat center', opacity: 0.3, cursor: 'none'}});
            }

            $i++;

            if( $private ) {
                setTimeout(function () {
                    $body.trigger('unblock_safari_private');
                }, 3000);
            }
        });

    };

    add_to_cart();
    $(document).on('yith-wcan-ajax-filtered', add_to_cart );

    $body.on( 'added_to_cart unblock_safari_private', function( fragmentsJSON, cart_hash ) {

        if ( typeof $pWrapper[$j] === 'undefined' )  return;

        var $thumb = $pWrapper[$j].find( '.thumb-wrapper' );

        if ( YIT_Browser.isMobile() || yit.added_to_cart_layout == 'label' ) {

            var $ico = "<div class='added-to-cart-icon'><span>" + yit.added_to_cart_text + "</span></div>";

            $thumb.addClass( 'no-hover' );
            $thumb.append( $ico );

            setTimeout(function () {
                $thumb.find('.added-to-cart-icon').fadeOut(2000, function () {
                    $thumb.removeClass( 'no-hover' );
                    $(this).remove();
                });
            }, 3000);
        }
        else {

            if ( typeof fragments == 'undefined' ) {
                fragments = $.parseJSON( sessionStorage.getItem( wc_cart_fragments_params.fragment_name ) );
            }

            $.each( fragments, function( key, value ) {

                if ( key == '#popupWrap .message' ) {

                    var $template = $('div.quick-view-overlay');

                    $template.addClass('added-to-cart-popup open');
                    $template.find('.head').append( value );

                    $template.find( 'a.continue-shopping').on( 'click', function (e) {
                        e.preventDefault();

                        var close_button = $template.find('.overlay-close'),
                            wrapper = $template.find('.main');

                            $template.removeClass( 'open' );

                            setTimeout(function () {
                                wrapper.find('.head').html( close_button );
                            }, 2000);
                    });

                    return false;
                }
            });
        }

        $thumb.unblock();
        $j++;

    });

    /*******************************************
     * ADD TO WISHLIST
     *****************************************/

     $('ul.products, div.product div.summary').on( 'click', '.yith-wcwl-add-button a', function () {
         if( typeof yit.load_gif != 'undefined' ) {
             $(this).block({message: null, overlayCSS: {background: '#fff url(' + yit.load_gif + ') no-repeat center', opacity: 0.3, cursor: 'none'}});
         }
         else {
             $(this).block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url.substring(0, woocommerce_params.ajax_loader_url.length - 7) + '.gif) no-repeat center', opacity: 0.3, cursor: 'none'}});
         }

     });

    /*************************
     * PRODUCTS SLIDER
     *************************/

    if( $.fn.owlCarousel && $.fn.imagesLoaded && $products_sliders.length ) {
        var product_slider = function(t) {

                t.imagesLoaded(function(){
                    var cols = t.data('columns') ? t.data('columns') : 4,
                        autoplay = ( t.attr('data-autoplay') == 'true' ) ? true : false;

                    var owl = t.find('.products').owlCarousel({
                        items             : cols,
                        responsiveClass   : true,
                        responsive:{
                            0 : {
                                items: 2
                            },
                            479 : {
                                items: 3
                            },
                            767 : {
                                items: 4
                            },
                            992 : {
                                items: cols
                            }
                        },
                        autoplay          : autoplay,
                        autoplayTimeout   : 2000,
                        autoplayHoverPause: true,
                        loop              : true
                    });

                    // Custom Navigation Events
                    t.on('click', '.es-nav-next', function () {
                        owl.trigger('next.owl.carousel');
                    });

                    t.on('click', '.es-nav-prev', function () {
                        owl.trigger('prev.owl.carousel');
                    });

                    if ( t.hasClass('products-slider-wrapper') ) {

                        var $index = 0;

                        t.find('.cloned ').each(function () {

                            var $button = $(this).find('.product-quick-view-button a'),
                                $id = $button.attr('id'),
                                $new_id = $id + '-' + $index++;

                            $button.attr( 'id', $new_id );
                        });

                        $(document).find('.product-quick-view-button a').off('click');
                        yit_quick_view_init();
                    }

                });
        };

        // initialize slider in only visible tabs
        $products_sliders.each(function(){
            var t = $(this);
            if( ! t.closest('.panel.group').length || t.closest('.panel.group').hasClass('showing')  ){
                product_slider( t );
            }
        });

        $('.tabs-container').on( 'tab-opened', function( e, tab ) {
            product_slider( tab.find( $products_sliders ) );
        });

    }



    /*************************
     * VARIATIONS SELECT
     *************************/

    var variations_select = function(){
        // variations select
        if( $.fn.selectbox ) {
            var form = $('form.variations_form');
            var select = form.find('select');

            if( form.data('wccl') ) {
                select = select.filter(function(){
                    return $(this).data('type') == 'select'
                });
            }

            select.selectbox({
                effect: 'fade',
                onOpen: function() {
                    //$('.variations select').trigger('focusin');
                }
            });

            var update_select = function(event){
                select.selectbox("detach");
                select.selectbox("attach");
            };

            // fix variations select
            form.on( 'woocommerce_update_variation_values', update_select);
            form.find('.reset_variations').on('click.yit', update_select);
        }
    };

    variations_select();

     /*************************
     * INQUIRY FORM
     *************************/

    var $inquiry_form = $(document).find('#inquiry-form .product-inquiry');

    if ( $inquiry_form.length ) {

        $inquiry_form.next('form.contact-form').hide();

        $inquiry_form.on('click', function(){
            $(this).next('form.contact-form').slideToggle('slow');
        });
    }

    if(yit_woocommerce.yit_shop_show_reviews_tab_opened=='yes') {
        // open first reviews tab
        var $reviews_tab = $(document).find('.woocommerce-tabs ul.tabs li.reviews_tab a');

        if( $reviews_tab.length ) {
            $reviews_tab.click();
        }
    }




    /*************************
     * Login Form
     *************************/

    $('#login-form').on('submit', function(){
        var a = $('#reg_password').val();
        var b = $('#reg_password_retype').val();
        if(!(a==b)){
            $('#reg_password_retype').addClass('invalid');
            return false;
        }else{
            $('#reg_password_retype').removeClass('invalid');
            return true;
        }
    });

    /*************************
     * Widget Woo Price Filter
     *************************/

    if( typeof yit != 'undefined' && ( typeof yit.price_filter_slider == 'undefined' || yit.price_filter_slider == 'no' ) ) {
        var removePriceFilterSlider = function() {
            $( 'input#min_price, input#max_price' ).show();
            $('form > div.price_slider_wrapper').find( 'div.price_slider, div.price_label' ).hide();
        };

        $(document).on('ready', removePriceFilterSlider);
    }

    /*************************
     * PRODUCT QUICK VIEW
     *************************/

    if ( $.fn.yit_quick_view && typeof yit_quick_view != 'undefined' ) {

        var yit_quick_view_init = function(){

            $('a.trigger-quick-view').yit_quick_view({

                item_container: 'li.product',
                loader: '.single-product.woocommerce',
                assets: yit_quick_view.assets,
                before: function( trigger, item ) {
                    // add loading in the button
                    if( typeof yit.load_gif != 'undefined' ) {
                        trigger.parents( '.product-wrapper').find('.thumb-wrapper').block({message: null, overlayCSS: {background: '#fff url(' + yit.load_gif +') no-repeat center', opacity: 0.5, cursor: 'none'}});
                    }
                    else {
                        trigger.parents( '.product-wrapper').find('.thumb-wrapper').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url.substring(0, woocommerce_params.ajax_loader_url.length - 7) + '.gif) no-repeat center', opacity: 0.3, cursor: 'none'}});
                    }
                },
                openDialog: function( trigger, item ) {
                    // remove loading from button
                    trigger.parents( '.product-wrapper').find('.thumb-wrapper').unblock();
                },
                completed: function( trigger, item, html, overlay ) {

                    // add main class to dialog container
                    $(overlay).addClass('product-quick-view');

                    //tooltip
                    $.yit_tooltip();

                    //product image slider
                    thumbanils_slider();

                    // quantity fields
                    $('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');

                    variations_select();

                    // add to cart
                    $('form.cart', overlay).on('submit', function (e) {
                        e.preventDefault();

                        var form = $(this),
                            button = form.find('button'),
                            product_url = item.find('a.thumb').attr('href');

                        if( typeof yit.load_gif != 'undefined' ) {
                            button.block({message: null, overlayCSS: {background: '#fff url(' + yit.load_gif + ') no-repeat center', opacity: 0.5, cursor: 'none'}});
                        }
                        else {
                            button.block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url.substring(0, woocommerce_params.ajax_loader_url.length - 7) + '.gif) no-repeat center', opacity: 0.3, cursor: 'none'}});
                        }

                        $.post(product_url, form.serialize() + '&_wp_http_referer=' + product_url, function (result) {
                            var message = $('.woocommerce-message', result),
                                cart_dropdown = $('#header .yit_cart_widget', result);

                            // update dropdown cart
                            $('#header').find('.yit_cart_widget').replaceWith(cart_dropdown);

                            // update fragments
                            $.ajax($fragment_refresh);

                            var $ico,
                                $thumb = $('.quick-view-overlay .single-product div.images');

                            $ico = '<div class="added-to-cart-icon"><span>' + yit.added_to_cart_text + '</span></div>';
                            $thumb.append( $ico );


                            setTimeout(function () {
                                $thumb.find('.added-to-cart-icon').fadeOut(2000, function () {
                                    $(this).remove();
                                });
                            }, 3000);

                            // remove loading
                            button.unblock();
                        });
                    });
                },
                action: 'yit_load_product_quick_view'
            });
        };

        yit_quick_view_init();

        $(document).on( 'yith-wcan-ajax-filtered', yit_quick_view_init );

    }

    var thumbanils_slider = function(){

        var $container = $('.slider-quick-view-container'),
            $slider = $container.find('.slider-quick-view');

        $slider.imagesLoaded( function() {

            $slider.owlCarousel({
                autoPlay  : false,
                pagination: false,
                items     : 1
            });

            $container.on('click', '.es-nav-next', function () {
                $slider.trigger('next.owl.carousel');
            });

            $container.on('click', '.es-nav-prev', function () {
                $slider.trigger('prev.owl.carousel');
            });
        });
    };

    /****************************
     * COMPARE TOOLTIP
     ***************************/

    var $compare = $('.single-product .woocommerce.product.compare-button a');

    if( $compare.length ) {

        //init attr title
        $compare.attr( 'data-original-title', 'Add to compare' );

        $(document).on( 'yith_woocompare_open_popup', function(){
            $compare.attr( 'data-original-title', 'Added to compare' );
        });
    }


    /*************************
     * Header Search
     *************************/

    $.yit_trigger_search();
    $.yit_ajax_search();


    /***************************
     * DROPDOWN WIDGET SHOP
     **************************/

    var $widget_shop = $(document).find( '.widget_price_filter, .yith-woo-ajax-navigation, .widget.vendors-list, .yith-wpv-quick-info, .widget.store-location'),
        $widget_dropdown = function() {

            if ( $widget_shop.length ) {

                $widget_shop.each( function(){

                    var $title = $(this).find('h3');

                    $title.append( '<span class="widget-dropdown border"></span>' );
                    $title.addClass('with-dropdown border-2 open');

                    $title.on('click', function(){
                        $(this).toggleClass('open').next().slideToggle('slow');

                    })
                })
            }
        };

    $(document).on( 'ready yith-wcan-ajax-filtered', $widget_dropdown );


    /***********************************
     * Jquery Scrollbar
     */

    if (yit_woocommerce.shop_minicart_scrollable == 'yes') {

        var create_popup_scrollbar = function () {
            $('.cart_wrapper .widget_shopping_cart_content').scrollbar();
        }

        create_popup_scrollbar();

        $(document).on('added_to_cart', create_popup_scrollbar);
        $(document).on('wc_fragments_refreshed', create_popup_scrollbar);

    }

    $(document).on('click', '.add-request-quote-button', function () {
        $(this).parent().removeClass('show')
    });




});