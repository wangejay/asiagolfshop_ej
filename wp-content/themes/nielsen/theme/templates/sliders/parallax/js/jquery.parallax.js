jQuery(document).ready( function($){
    "use strict";

    var sliders = $('.slider-parallax'),
        primary = $('#primary'),
        windowsize = $(window).width();

    sliders.prev('#header').addClass('header-slider-parallax slider-fixed');

    if( ! $('body').hasClass( 'boxed-layout' ) ){
        $('.header-parallax').css('margin-top', $('#header').height());
    }

    $("#primary .slider-parallax, .header-parallax .parallaxeos_outer").css({
        left: -(( windowsize-$('.container').width())/2),
        width: windowsize
    });

    primary.find(".slider-parallax-item").css({
        left: "auto",
        width: windowsize
    });

    if ($.fn.imagesLoaded && $.fn.owlCarousel ) {

        sliders.each(function(){

            var slider = $(this),
                autoplay = slider.data('autoplay'),
                transition = ( typeof slider.data('transition') != 'undefined' ) ? slider.data('transition') : '1500',
                loop = true,
                nav = true,
                dots = true,
                autoplayTimer = 5000;

            if (autoplay > 0) {
                autoplayTimer = slider.data('autoplay');
                autoplay = true;
            }

            var numchild = slider.find('.slider-parallax-item').length;

            if( numchild == 1){
                loop = false;
                nav = false;
                autoplay = false;
                dots = false;
            }

           slider.imagesLoaded(function(){
                var owl = slider.owlCarousel({
                    items:1,
                    autoplay: autoplay,
                    autoplayTimeout: autoplayTimer,
                    nav: nav,
                    loop: loop,
                    dots: dots,
                    autoplayHoverPause: true,
                    smartSpeed: transition,
                    onInitialize : function() {
                        slider.find('.slider-parallax-item').each(function(){
                            $(this)
                                .addClass('parallaxeos_slider')
                                .find('.parallaxeos_animate').removeClass('animated');
                        });
                    }
                });

                owl.on('changed.owl.carousel', function (event) {
                    var current = $(event.target);
                    current.find('.parallaxeos_animate').removeClass('animated');
                    setTimeout(function () {
                        current.find('.parallaxeos_animate').addClass('animated');
                    }, 50);
                    //current.find('.video-parallaxeos').trigger('video');
                });

                //owl.on('play.owl.video', function (event) {
                //    var current = $(event.target);
                //    current.find('.video-parallaxeos').trigger('play');
                //});
            });

        });
    }

});