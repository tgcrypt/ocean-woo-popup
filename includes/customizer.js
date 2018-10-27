/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Declare vars
	var api = wp.customize;

	// Display popup
	api('owp_popup_display', function( value ) {
		value.bind( function( newval ) {
			var bPopup = $j( '#woo-popup-wrap' );
			bPopup.bPopup( {
	            modalClose		: true,
	            modalColor		: bPopup.data( 'color' ),
	            opacity			: bPopup.data( 'opacity' ),
	            positionStyle	: 'fixed'
	        } );
		});
    });

	// Title text
    api('owp_popup_title_text', function( value ) {
		value.bind( function( newval ) {
			$( '#woo-popup-wrap .popup-title' ).html( newval );
		});
    });

	// Content
    api('owp_popup_content', function( value ) {
    	value.bind( function( newval ) {
    		$( '#woo-popup-wrap .popup-content' ).html( newval );
    	});
    });

	// Continue button text
    api('owp_popup_continue_btn_text', function( value ) {
		value.bind( function( newval ) {
			$( '#woo-popup-wrap .buttons-wrap a.continue-btn' ).html( newval );
		});
    });

	// Go cart button text
    api('owp_popup_cart_btn_text', function( value ) {
		value.bind( function( newval ) {
			$( '#woo-popup-wrap .buttons-wrap a.cart-btn' ).html( newval );
		});
    });

	// Bottom text
    api('owp_popup_bottom_text', function( value ) {
		value.bind( function( newval ) {
			$( '#woo-popup-wrap .popup-text' ).html( newval );
		});
    });

    // Popup width
    api( 'owp_popup_width', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_width' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_width">#woo-popup-wrap #woo-popup-inner { width: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup width tablet
    api( 'owp_popup_width_tablet', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_width_tablet' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_width_tablet">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{width: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup width mobile
    api( 'owp_popup_width_mobile', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_width_mobile' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_width_mobile">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{width: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup height
    api( 'owp_popup_height', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_height' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_height">#woo-popup-wrap #woo-popup-inner { height: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup height tablet
    api( 'owp_popup_height_tablet', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_height_tablet' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_height_tablet">@media (max-height: 768px){#woo-popup-wrap #woo-popup-inner{height: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup height mobile
    api( 'owp_popup_height_mobile', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_height_mobile' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_height_mobile">@media (max-height: 480px){#woo-popup-wrap #woo-popup-inner{height: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Popup top padding
    api( 'owp_popup_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_top_padding">#woo-popup-wrap #woo-popup-inner{padding-top: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup right padding
    api( 'owp_popup_right_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_right_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_right_padding">#woo-popup-wrap #woo-popup-inner{padding-right: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup bottom padding
    api( 'owp_popup_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_bottom_padding">#woo-popup-wrap #woo-popup-inner{padding-bottom: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup left padding
    api( 'owp_popup_left_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_left_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_left_padding">#woo-popup-wrap #woo-popup-inner{padding-left: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Tablet popup top padding
    api( 'owp_popup_tablet_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_top_padding">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{padding-top: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Tablet popup right padding
    api( 'owp_popup_tablet_right_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_right_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_right_padding">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{padding-right: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Tablet popup bottom padding
    api( 'owp_popup_tablet_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_bottom_padding">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{padding-bottom: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Tablet popup left padding
    api( 'owp_popup_tablet_left_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_left_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_left_padding">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{padding-left: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Mobile popup top padding
    api( 'owp_popup_mobile_top_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_top_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_top_padding">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{padding-top: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Mobile popup right padding
    api( 'owp_popup_mobile_right_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_right_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_right_padding">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{padding-right: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Mobile popup bottom padding
    api( 'owp_popup_mobile_bottom_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_bottom_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_bottom_padding">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{padding-bottom: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Mobile popup left padding
    api( 'owp_popup_mobile_left_padding', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_left_padding' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_left_padding">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{padding-left: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Popup top border radius
    api( 'owp_popup_top_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_top_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_top_radius">#woo-popup-wrap #woo-popup-inner{border-top-left-radius: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup right border radius
    api( 'owp_popup_right_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_right_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_right_radius">#woo-popup-wrap #woo-popup-inner{border-top-right-radius: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup bottom border radius
    api( 'owp_popup_bottom_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_bottom_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_bottom_radius">#woo-popup-wrap #woo-popup-inner{border-bottom-right-radius: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup left border radius
    api( 'owp_popup_left_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_left_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_left_radius">#woo-popup-wrap #woo-popup-inner{border-bottom-left-radius: ' + to + 'px; }</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Tablet popup top border radius
    api( 'owp_popup_tablet_top_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_top_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_top_radius">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{border-top-left-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Tablet popup right border radius
    api( 'owp_popup_tablet_right_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_right_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_right_radius">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{border-top-right-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Tablet popup bottom border radius
    api( 'owp_popup_tablet_bottom_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_bottom_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_bottom_radius">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{border-bottom-right-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Tablet popup left border radius
    api( 'owp_popup_tablet_left_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_tablet_left_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_tablet_left_radius">@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{border-bottom-left-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Mobile popup top border radius
    api( 'owp_popup_mobile_top_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_top_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_top_radius">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{border-top-left-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Mobile popup right border radius
    api( 'owp_popup_mobile_right_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_right_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_right_radius">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{border-top-right-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Mobile popup bottom border radius
    api( 'owp_popup_mobile_bottom_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_bottom_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_bottom_radius">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{border-bottom-right-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Mobile popup left border radius
    api( 'owp_popup_mobile_left_radius', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_mobile_left_radius' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_mobile_left_radius">@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{border-bottom-left-radius: ' + to + 'px; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Popup background color
	api('owp_popup_bg', function( value ) {
		value.bind( function( newval ) {
	        if ( newval ) {
				$( '#woo-popup-wrap #woo-popup-inner' ).css( 'background-color', newval );
	        }
		});
    });

    // Popup check mark background
    api( 'owp_popup_checkmark_bg', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_checkmark_bg' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_checkmark_bg">#woo-popup-wrap .checkmark{box-shadow: inset 0 0 0 ' + to + '; }#woo-popup-wrap .checkmark-circle{stroke: ' + to + ';}@keyframes fill {100% { box-shadow: inset 0 0 0 100px ' + to + '; }}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup check mark color
    api( 'owp_popup_checkmark_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_checkmark_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_checkmark_color">#woo-popup-wrap .checkmark-check{stroke: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Popup title color
	api('owp_popup_title_color', function( value ) {
		value.bind( function( newval ) {
	        if ( newval ) {
				$( '#woo-popup-wrap .popup-title' ).css( 'color', newval );
	        }
		});
    });

	// Popup content color
	api('owp_popup_content_color', function( value ) {
		value.bind( function( newval ) {
	        if ( newval ) {
				$( '#woo-popup-wrap .popup-content' ).css( 'color', newval );
	        }
		});
    });

    // Popup continue button background color
    api( 'owp_popup_continue_btn_bg', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_continue_btn_bg' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_continue_btn_bg">#woo-popup-wrap .buttons-wrap a.continue-btn{background-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup continue button color
    api( 'owp_popup_continue_btn_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_continue_btn_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_continue_btn_color">#woo-popup-wrap .buttons-wrap a.continue-btn{color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup continue button border color
    api( 'owp_popup_continue_btn_border_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_continue_btn_border_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_continue_btn_border_color">#woo-popup-wrap .buttons-wrap a.continue-btn{border-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup continue button hover background color
    api( 'owp_popup_continue_btn_hover_bg', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_continue_btn_hover_bg' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_continue_btn_hover_bg">#woo-popup-wrap .buttons-wrap a.continue-btn:hover{background-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup continue button hover color
    api( 'owp_popup_continue_btn_hover_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_continue_btn_hover_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_continue_btn_hover_color">#woo-popup-wrap .buttons-wrap a.continue-btn:hover{color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup continue button hover border color
    api( 'owp_popup_continue_btn_hover_border_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_continue_btn_hover_border_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_continue_btn_hover_border_color">#woo-popup-wrap .buttons-wrap a.continue-btn:hover{border-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Popup cart button background color
    api( 'owp_popup_cart_btn_bg', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_cart_btn_bg' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_cart_btn_bg">#woo-popup-wrap .buttons-wrap a.cart-btn{background-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup cart button color
    api( 'owp_popup_cart_btn_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_cart_btn_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_cart_btn_color">#woo-popup-wrap .buttons-wrap a.cart-btn{color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup cart button border color
    api( 'owp_popup_cart_btn_border_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_cart_btn_border_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_cart_btn_border_color">#woo-popup-wrap .buttons-wrap a.cart-btn{border-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup cart button hover background color
    api( 'owp_popup_cart_btn_hover_bg', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_cart_btn_hover_bg' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_cart_btn_hover_bg">#woo-popup-wrap .buttons-wrap a.cart-btn:hover{background-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup cart button hover color
    api( 'owp_popup_cart_btn_hover_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_cart_btn_hover_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_cart_btn_hover_color">#woo-popup-wrap .buttons-wrap a.cart-btn:hover{color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

    // Popup cart button hover border color
    api( 'owp_popup_cart_btn_hover_border_color', function( value ) {
		value.bind( function( to ) {
			var $child = $( '.customizer-owp_popup_cart_btn_hover_border_color' );
			if ( to ) {
				var style = '<style class="customizer-owp_popup_cart_btn_hover_border_color">#woo-popup-wrap .buttons-wrap a.cart-btn:hover{border-color: ' + to + ';}</style>';
				if ( $child.length ) {
					$child.replaceWith( style );
				} else {
					$( 'head' ).append( style );
				}
			} else {
				$child.remove();
			}
		});
	});

	// Popup bottom text color
	api('owp_popup_text_color', function( value ) {
		value.bind( function( newval ) {
	        if ( newval ) {
				$( '#woo-popup-wrap .popup-text' ).css( 'color', newval );
	        }
		});
    });
	
} )( jQuery );
