<?php
/**
 * Popup template
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get ID
$get_id = get_theme_mod( 'owp_popup_page_id' );

// Get the elementor template
$e_template = get_theme_mod( 'owp_popup_elementor_templates' );
if ( ! empty( $e_template ) ) {
    $get_id = $e_template;
}

// Get the template
$template = get_theme_mod( 'owp_popup_template' );
if ( ! empty( $template ) ) {
    $get_id = $template;
}

// Check if page is Elementor page
$elementor  = get_post_meta( $get_id, '_elementor_edit_mode', true );

// Get content
if ( ! empty( $get_id ) ) {

	$template_id = get_post( $get_id );

	if ( $template_id && ! is_wp_error( $template_id ) ) {
		$get_content = $template_id->post_content;
	}

}

// Get elements
$elements 		= owp_popup_elements_positioning();

// Vars
$title 			= oceanwp_tm_translation( 'owp_popup_title_text', get_theme_mod( 'owp_popup_title_text' ) );
$title 			= $title ? $title : esc_html__( 'Item added to your cart', 'ocean-woo-popup' );
$content 		= oceanwp_tm_translation( 'owp_popup_content', get_theme_mod( 'owp_popup_content' ) );
$content 		= $content ? $content : esc_html__( '[oceanwp_woo_cart_items] items in the cart ([oceanwp_woo_total_cart])', 'ocean-woo-popup' );
$continue_btn 	= oceanwp_tm_translation( 'owp_popup_continue_btn_text', get_theme_mod( 'owp_popup_continue_btn_text' ) );
$continue_btn 	= $continue_btn ? $continue_btn : esc_html__( 'Continue Shopping', 'ocean-woo-popup' );
$cart_btn 		= oceanwp_tm_translation( 'owp_popup_cart_btn_text', get_theme_mod( 'owp_popup_cart_btn_text' ) );
$cart_btn 		= $cart_btn ? $cart_btn : esc_html__( 'Go To The Cart', 'ocean-woo-popup' );
$text 			= oceanwp_tm_translation( 'owp_popup_bottom_text', get_theme_mod( 'owp_popup_bottom_text' ) );
$text 			= $text ? $text : '[oceanwp_woo_free_shipping_left]';
$overlay 		= get_theme_mod( 'owp_popup_overlay_color', '#000000' );
$opacity 		= get_theme_mod( 'owp_popup_overlay_opacity', '0.7' ); ?>
	
<div id="woo-popup-wrap" data-color="<?php echo esc_attr( $overlay ); ?>" data-opacity="<?php echo esc_attr( $opacity ); ?>">

	<div id="woo-popup-inner">

		<div class="woo-popup-content clr">

			<?php
			if ( ! empty( $get_id ) ) {

				// If Elementor
				if ( class_exists( 'Elementor\Plugin' ) && $elementor ) {

				    echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $get_id );

				}

				// If Beaver Builder
				else if ( class_exists( 'FLBuilder' ) && ! empty( $get_id ) ) {

				    echo do_shortcode( '[fl_builder_insert_layout id="' . $get_id . '"]' );

				}

				// Else
				else {

				    // Display template content
				    echo do_shortcode( $get_content );

				}

			}

	    	// Else
	    	else { ?>

	    		<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/><path xmlns="http://www.w3.org/2000/svg" class="checkmark-check" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" d="M 14.1 27.2 l 7.1 7.2 l 16.7 -16.8" /></svg>

	    		<?php
				// Loop through elements
				foreach ( $elements as $element ) {

					// Title
					if ( 'title' == $element ) { ?>
						<h3 class="popup-title"><?php echo do_shortcode( $title ); ?></h3>
					<?php
					}

					// Content
					if ( 'content' == $element ) { ?>
						<p class="popup-content"><?php echo do_shortcode( $content ); ?></p>
					<?php
					}

					// Buttons
					if ( 'buttons' == $element ) { ?>
						<div class="buttons-wrap">
			    			<a href="#" class="continue-btn"><?php echo do_shortcode( $continue_btn ); ?></a>
			    			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'cart' ) ) ); ?>" class="cart-btn"><?php echo do_shortcode( $cart_btn ); ?></a>
			    		</div>
					<?php
					}

					// Bottom Text
					if ( 'bottom_text' == $element ) { ?>
						<span class="popup-text"><?php echo do_shortcode( $text ); ?></span>
					<?php
					}

				} ?>

	        <?php } ?>

		</div><!-- .woo-popup-inner -->

	</div><!-- #woo-popup-inner -->

</div><!-- #woo-popup-wrap -->