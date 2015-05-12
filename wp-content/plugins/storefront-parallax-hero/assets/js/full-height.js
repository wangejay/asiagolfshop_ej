jQuery(document).ready(function($){

	// The hero component height on full width mode
	// Calculated by measuring from the top of the hero to the bottom of the browser window.
	var heroHeight 		= jQuery( window ).height() - jQuery( '.sph-hero .overlay' ).offset().top;

	// The hero component content height
	var contentHeight 	= jQuery( '.sph-hero .overlay .col-full' ).height();

	// Add the calculated heroHeight as a min-height
	jQuery( '.sph-hero .overlay' ).css( 'min-height', heroHeight );

	// When the hero height is larger than the height of the content within vertically align the content by...
	if ( heroHeight > contentHeight ) {
		// Pushing the content down by 50% of the hero height
		jQuery( '.sph-hero .overlay .col-full' ).css( 'padding-top', heroHeight / 2 );

		// Moving the content up by half of it's height
		jQuery( '.sph-hero .overlay .col-full' ).css( 'position', 'relative' ).css( 'top', - contentHeight / 2 );

		// Finally remove the unnecessary padding
		jQuery( '.sph-hero .overlay' ).css( 'padding-top', '0' ).css( 'padding-bottom', '0' );
	}

});