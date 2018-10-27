<?php
/**
 * Helpers
 */

/**
 * Returns popup elements for the customizer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'owp_popup_elements' ) ) {

	function owp_popup_elements() {

		// Default elements
		$elements = apply_filters( 'owp_popup_elements', array(
			'title'    			=> esc_html__( 'Title', 'ocean-woo-popup' ),
			'content'       	=> esc_html__( 'Content', 'ocean-woo-popup' ),
			'buttons' 			=> esc_html__( 'Buttons', 'ocean-woo-popup' ),
			'bottom_text' 		=> esc_html__( 'Bottom Text', 'ocean-woo-popup' ),
		) );

		// Return elements
		return $elements;

	}

}

/**
 * Returns popup elements positioning
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'owp_popup_elements_positioning' ) ) {

	function owp_popup_elements_positioning() {

		// Default elements
		$sections = array( 'title', 'content', 'buttons', 'bottom_text' );

		// Get elements from Customizer
		$sections = get_theme_mod( 'owp_popup_elements_positioning', $sections );

		// Turn into array if string
		if ( $sections && ! is_array( $sections ) ) {
			$sections = explode( ',', $sections );
		}

		// Apply filters for easy modification
		$sections = apply_filters( 'owp_popup_elements_positioning', $sections );

		// Return sections
		return $sections;

	}

}