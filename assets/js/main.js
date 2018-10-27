var $j = jQuery.noConflict();

$j( document ).on( 'ready', function() {
	// Popup
	oceanwpWooPopup();
} );

/* ==============================================
WOO POPUP
============================================== */
function oceanwpWooPopup() {
	"use strict"

	// Var
	var bPopup = $j( '#woo-popup-wrap' );

	// Open popup on shop page
	$j( 'body' ).on( 'added_to_cart', function() {
        bPopup.bPopup( {
            modalClose		: true,
            modalColor		: bPopup.data( 'color' ),
            opacity			: bPopup.data( 'opacity' ),
            positionStyle	: 'fixed'
        } );
    } );

    // Close modal
	$j( '#woo-popup-wrap .continue-btn' ).on( 'click', function( e ) {
		e.preventDefault();
		if ( bPopup ) {
			bPopup.close();
		}
	} );

}