<?php
/**
 * Plugin Name:			Ocean Woo Popup
 * Plugin URI:			https://oceanwp.org/extension/ocean-woo-popup/
 * Description:			A simple extension to display a popup when you click on the Add To Cart button of your products.
 * Version:				1.0.6
 * Author:				OceanWP
 * Author URI:			https://oceanwp.org/
 * Requires at least:	4.5.0
 * Tested up to:		4.9.7
 *
 * Text Domain: ocean-woo-popup
 * Domain Path: /languages/
 *
 * @package Ocean_Woo_Popup
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Woo_Popup to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Woo_Popup
 */
function Ocean_Woo_Popup() {
	return Ocean_Woo_Popup::instance();
} // End Ocean_Woo_Popup()

Ocean_Woo_Popup();

/**
 * Main Ocean_Woo_Popup Class
 *
 * @class Ocean_Woo_Popup
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Woo_Popup
 */
final class Ocean_Woo_Popup {
	/**
	 * Ocean_Woo_Popup The single instance of Ocean_Woo_Popup.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	// Customizer preview
	private $enable_postMessage  = true;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'ocean-woo-popup';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.6';

		define( 'OWP_URL', $this->plugin_url );
		define( 'OWP_PATH', $this->plugin_path );

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'owp_load_plugin_textdomain' ) );

		add_filter( 'ocean_register_tm_strings', array( $this, 'register_tm_strings' ) );

		add_action( 'init', array( $this, 'owp_setup' ) );
		add_action( 'init', array( $this, 'owp_updater' ), 1 );
	}

	/**
	 * Initialize License Updater.
	 * Load Updater initialize.
	 * @return void
	 */
	public function owp_updater() {

		// Plugin Updater Code
		if( class_exists( 'OceanWP_Plugin_Updater' ) ) {
			$license	= new OceanWP_Plugin_Updater( __FILE__, 'Woo Popup', $this->version, 'OceanWP' );
		}
	}

	/**
	 * Main Ocean_Woo_Popup Instance
	 *
	 * Ensures only one instance of Ocean_Woo_Popup is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Woo_Popup()
	 * @return Main Ocean_Woo_Popup instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function owp_load_plugin_textdomain() {
		load_plugin_textdomain( 'ocean-woo-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Register translation strings
	 */
	public static function register_tm_strings( $strings ) {

		$strings['owp_popup_title_text'] 			= esc_html__( 'Item added to your cart', 'ocean-woo-popup' );
		$strings['owp_popup_content'] 				= esc_html__( '[oceanwp_woo_cart_items] items in the cart ([oceanwp_woo_total_cart])', 'ocean-woo-popup' );
		$strings['owp_popup_continue_btn_text'] 	= esc_html__( 'Continue Shopping', 'ocean-woo-popup' );
		$strings['owp_popup_cart_btn_text'] 		= esc_html__( 'Go To The Cart', 'ocean-woo-popup' );
		$strings['owp_popup_bottom_text'] 			= '[oceanwp_woo_free_shipping_left]';

		return $strings;

	}

	/**
	 * Setup all the things.
	 * Only executes if OceanWP or a child theme using OceanWP as a parent is active and the extension specific filter returns true.
	 * @return void
	 */
	public function owp_setup() {
		$theme = wp_get_theme();

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			require_once( OWP_PATH .'/includes/helpers.php' );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );
			add_action( 'wp_footer', array( $this, 'popup_template' ) );
			add_filter( 'ocean_head_css', array( $this, 'head_css' ) );
		}
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function customize_preview_js() {
		wp_enqueue_script( 'owp-customizer', plugins_url( '/includes/customizer.min.js', __FILE__ ), array( 'customize-preview' ), '1.0', true );
	}

	/**
	 * Customizer Controls and settings
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function customize_register( $wp_customize ) {

		/**
	     * Add a new section
	     */
        $wp_customize->add_section( 'owp_section' , array(
		    'title'      	=> esc_html__( 'Woo Popup', 'ocean-woo-popup' ),
		    'priority'   	=> 210,
		) );

		/**
		 * Display Popup
		 */
		$wp_customize->add_setting( 'owp_popup_display', array(
			'transport'           	=> 'postMessage',
			'default'           	=> 'switch-one',
			'sanitize_callback' 	=> 'oceanwp_sanitize_select',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Buttonset_Control( $wp_customize, 'owp_popup_display', array(
			'label'	   				=> esc_html__( 'Display Popup', 'ocean-woo-popup' ),
			'description'	   		=> esc_html__( 'This field is just to allow you to display the popup in the customizer preview', 'ocean-woo-popup' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_display',
			'priority' 				=> 10,
			'choices' 				=> array(
				'switch-one' 	=> esc_html__( 'Show', 'ocean-woo-popup' ),
				'switch-two' 	=> esc_html__( 'Show', 'ocean-woo-popup' ),
			),
		) ) );

		/**
		 * Template
		 */
		$wp_customize->add_setting( 'owp_popup_template', array(
			'default'           	=> '0',
			'sanitize_callback' 	=> 'oceanwp_sanitize_select',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'owp_popup_template', array(
			'label'	   				=> esc_html__( 'Select Template', 'oceanwp' ),
			'description'	   		=> esc_html__( 'Choose a template created in Theme Panel > My Library.', 'oceanwp' ),
			'type' 					=> 'select',
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_template',
			'priority' 				=> 10,
			'choices' 				=> $this->helpers( 'template' ),
		) ) );

		/**
		 * Elements Positioning
		 */
		$wp_customize->add_setting( 'owp_popup_elements_positioning', array(
			'default'           	=> array( 'title', 'content', 'buttons', 'bottom_text' ),
			'sanitize_callback' 	=> 'oceanwp_sanitize_multi_choices',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Sortable_Control( $wp_customize, 'owp_popup_elements_positioning', array(
			'label'	   				=> esc_html__( 'Elements Positioning', 'ocean-woo-popup' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_elements_positioning',
			'priority' 				=> 10,
			'choices' 				=> owp_popup_elements(),
		) ) );

		/**
		 * Title Text
		 */
		$wp_customize->add_setting( 'owp_popup_title_text', array(
			'default'           	=> esc_html__( 'Item added to your cart', 'ocean-woo-popup' ),
			'transport'           	=> 'postMessage',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'owp_popup_title_text', array(
			'label'	   				=> esc_html__( 'Title Text', 'ocean-woo-popup' ),
			'type' 					=> 'text',
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_title_text',
			'priority' 				=> 10,
		) ) );

		/**
		 * Title Text
		 */
		$wp_customize->add_setting( 'owp_popup_title_text', array(
			'default'           	=> esc_html__( 'Item added to your cart', 'ocean-woo-popup' ),
			'transport'           	=> 'postMessage',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'owp_popup_title_text', array(
			'label'	   				=> esc_html__( 'Title Text', 'ocean-woo-popup' ),
			'type' 					=> 'text',
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_title_text',
			'priority' 				=> 10,
		) ) );

		/**
		 * Content
		 */
		$wp_customize->add_setting( 'owp_popup_content', array(
			'default'           	=> esc_html__( '[oceanwp_woo_cart_items] items in the cart ([oceanwp_woo_total_cart])', 'ocean-woo-popup' ),
			'transport'           	=> 'postMessage',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Textarea_Control( $wp_customize, 'owp_popup_content', array(
			'label'	   				=> esc_html__( 'Content', 'ocean-woo-popup' ),
			'description'	   		=> sprintf( esc_html__( 'Shortcodes allowed, %1$ssee the list%2$s.', 'ocean-woo-popup' ), '<a href="http://docs.oceanwp.org/category/369-shortcodes" target="_blank">', '</a>' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_content',
			'priority' 				=> 10,
		) ) );

		/**
		 * Continue Button Text
		 */
		$wp_customize->add_setting( 'owp_popup_continue_btn_text', array(
			'default'           	=> esc_html__( 'Continue Shopping', 'ocean-woo-popup' ),
			'transport'           	=> 'postMessage',
			'sanitize_callback' 	=> 'wp_filter_nohtml_kses',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'owp_popup_continue_btn_text', array(
			'label'	   				=> esc_html__( 'Continue Button Text', 'ocean-woo-popup' ),
			'type' 					=> 'text',
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_continue_btn_text',
			'priority' 				=> 10,
		) ) );

		/**
		 * Go Cart Button Text
		 */
		$wp_customize->add_setting( 'owp_popup_cart_btn_text', array(
			'default'           	=> esc_html__( 'Go To The Cart', 'ocean-woo-popup' ),
			'transport'           	=> 'postMessage',
			'sanitize_callback' 	=> 'wp_filter_nohtml_kses',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'owp_popup_cart_btn_text', array(
			'label'	   				=> esc_html__( 'Go Cart Button Text', 'ocean-woo-popup' ),
			'type' 					=> 'text',
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_cart_btn_text',
			'priority' 				=> 10,
		) ) );

		/**
		 * Bottom Text
		 */
		$wp_customize->add_setting( 'owp_popup_bottom_text', array(
			'default'           	=> esc_html__( '[oceanwp_woo_free_shipping_left]', 'ocean-woo-popup' ),
			'transport'           	=> 'postMessage',
			'sanitize_callback' 	=> 'wp_kses_post',
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'owp_popup_bottom_text', array(
			'label'	   				=> esc_html__( 'Bottom Text', 'ocean-woo-popup' ),
			'type' 					=> 'text',
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_bottom_text',
			'priority' 				=> 10,
		) ) );

		/**
		 * Styling Heading
		 */
		$wp_customize->add_setting( 'owp_popup_styling_heading', array(
			'sanitize_callback' 	=> 'wp_kses',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Heading_Control( $wp_customize, 'owp_popup_styling_heading', array(
			'label'    	=> esc_html__( 'Styling', 'ocean-woo-popup' ),
			'section'  	=> 'owp_section',
			'priority' 	=> 10,
		) ) );

		/**
		 * Popup Width
		 */
		$wp_customize->add_setting( 'owp_popup_width', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '600',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_width_tablet', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_setting( 'owp_popup_width_mobile', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Slider_Control( $wp_customize, 'owp_popup_width', array(
			'label'	   				=> esc_html__( 'Width (px)', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' => array(
	            'desktop' 	=> 'owp_popup_width',
	            'tablet' 	=> 'owp_popup_width_tablet',
	            'mobile' 	=> 'owp_popup_width_mobile',
		    ),
			'priority' 				=> 10,
		    'input_attrs' 			=> array(
		        'min'   => 10,
		        'max'   => 5000,
		        'step'  => 1,
		    ),
		) ) );

		/**
		 * Popup Height
		 */
		$wp_customize->add_setting( 'owp_popup_height', array(
			'default'           	=> '600',
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_height_tablet', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_setting( 'owp_popup_height_mobile', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Slider_Control( $wp_customize, 'owp_popup_height', array(
			'label'	   				=> esc_html__( 'Height (px)', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' => array(
	            'desktop' 	=> 'owp_popup_height',
	            'tablet' 	=> 'owp_popup_height_tablet',
	            'mobile' 	=> 'owp_popup_height_mobile',
		    ),
			'priority' 				=> 10,
		    'input_attrs' 			=> array(
		        'min'   => 10,
		        'max'   => 5000,
		        'step'  => 1,
		    ),
		) ) );

		/**
		 * Popup Padding
		 */
		$wp_customize->add_setting( 'owp_popup_top_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '50',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_right_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '25',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_bottom_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '50',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_left_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '25',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );

		$wp_customize->add_setting( 'owp_popup_tablet_top_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '20',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_tablet_right_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '20',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_tablet_bottom_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '20',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_tablet_left_padding', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '20',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );

		$wp_customize->add_setting( 'owp_popup_mobile_top_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_mobile_right_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_mobile_bottom_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_mobile_left_padding', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Dimensions_Control( $wp_customize, 'owp_popup_padding_dimensions', array(
			'label'	   				=> esc_html__( 'Padding (px)', 'oceanwp' ),
			'section'  				=> 'owp_section',				
			'settings'   => array(
	            'desktop_top' 		=> 'owp_popup_top_padding',
	            'desktop_right' 	=> 'owp_popup_right_padding',
	            'desktop_bottom' 	=> 'owp_popup_bottom_padding',
	            'desktop_left' 		=> 'owp_popup_left_padding',
	            'tablet_top' 		=> 'owp_popup_tablet_top_padding',
	            'tablet_right' 		=> 'owp_popup_tablet_right_padding',
	            'tablet_bottom' 	=> 'owp_popup_tablet_bottom_padding',
	            'tablet_left' 		=> 'owp_popup_tablet_left_padding',
	            'mobile_top' 		=> 'owp_popup_mobile_top_padding',
	            'mobile_right' 		=> 'owp_popup_mobile_right_padding',
	            'mobile_bottom' 	=> 'owp_popup_mobile_bottom_padding',
	            'mobile_left' 		=> 'owp_popup_mobile_left_padding',
			),
			'priority' 				=> 10,
		    'input_attrs' 			=> array(
		        'min'   => 0,
		        'step'  => 1,
		    ),
		) ) );

		/**
		 * Popup Border Radius
		 */
		$wp_customize->add_setting( 'owp_popup_top_radius', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '600',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_right_radius', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '600',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_bottom_radius', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '600',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );
		$wp_customize->add_setting( 'owp_popup_left_radius', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '600',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number',
		) );

		$wp_customize->add_setting( 'owp_popup_tablet_top_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_tablet_right_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_tablet_bottom_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_tablet_left_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_setting( 'owp_popup_mobile_top_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_mobile_right_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_mobile_bottom_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );
		$wp_customize->add_setting( 'owp_popup_mobile_left_radius', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_number_blank',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Dimensions_Control( $wp_customize, 'owp_popup_radius_dimensions', array(
			'label'	   				=> esc_html__( 'Border Radius (px)', 'oceanwp' ),
			'section'  				=> 'owp_section',				
			'settings'   => array(
	            'desktop_top' 		=> 'owp_popup_top_radius',
	            'desktop_right' 	=> 'owp_popup_right_radius',
	            'desktop_bottom' 	=> 'owp_popup_bottom_radius',
	            'desktop_left' 		=> 'owp_popup_left_radius',
	            'tablet_top' 		=> 'owp_popup_tablet_top_radius',
	            'tablet_right' 		=> 'owp_popup_tablet_right_radius',
	            'tablet_bottom' 	=> 'owp_popup_tablet_bottom_radius',
	            'tablet_left' 		=> 'owp_popup_tablet_left_radius',
	            'mobile_top' 		=> 'owp_popup_mobile_top_radius',
	            'mobile_right' 		=> 'owp_popup_mobile_right_radius',
	            'mobile_bottom' 	=> 'owp_popup_mobile_bottom_radius',
	            'mobile_left' 		=> 'owp_popup_mobile_left_radius',
			),
			'priority' 				=> 10,
		    'input_attrs' 			=> array(
		        'min'   => 0,
		        'step'  => 1,
		    ),
		) ) );

		/**
		 * Popup Background
		 */
		$wp_customize->add_setting( 'owp_popup_bg', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_bg', array(
			'label'	   				=> esc_html__( 'Popup Background', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_bg',
			'priority' 				=> 10,
		) ) );

		/**
		 * Overlay Color
		 */
		$wp_customize->add_setting( 'owp_popup_overlay_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#000000',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_overlay_color', array(
			'label'	   				=> esc_html__( 'Overlay Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_overlay_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Check Mark Background
		 */
		$wp_customize->add_setting( 'owp_popup_checkmark_bg', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#5bc142',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_checkmark_bg', array(
			'label'	   				=> esc_html__( 'Check Mark Background', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_checkmark_bg',
			'priority' 				=> 10,
		) ) );

		/**
		 * Check Mark Color
		 */
		$wp_customize->add_setting( 'owp_popup_checkmark_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_checkmark_color', array(
			'label'	   				=> esc_html__( 'Check Mark Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_checkmark_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Title Color
		 */
		$wp_customize->add_setting( 'owp_popup_title_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#333333',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_title_color', array(
			'label'	   				=> esc_html__( 'Title Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_title_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Content Color
		 */
		$wp_customize->add_setting( 'owp_popup_content_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#777777',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_content_color', array(
			'label'	   				=> esc_html__( 'Content Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_content_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Continue Button Background
		 */
		$wp_customize->add_setting( 'owp_popup_continue_btn_bg', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_continue_btn_bg', array(
			'label'	   				=> esc_html__( 'Continue Button Background', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_continue_btn_bg',
			'priority' 				=> 10,
		) ) );

		/**
		 * Continue Button Color
		 */
		$wp_customize->add_setting( 'owp_popup_continue_btn_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#13aff0',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_continue_btn_color', array(
			'label'	   				=> esc_html__( 'Continue Button Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_continue_btn_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Continue Button Border Color
		 */
		$wp_customize->add_setting( 'owp_popup_continue_btn_border_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#13aff0',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_continue_btn_border_color', array(
			'label'	   				=> esc_html__( 'Continue Button Border Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_continue_btn_border_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Continue Button Hover Background
		 */
		$wp_customize->add_setting( 'owp_popup_continue_btn_hover_bg', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#13aff0',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_continue_btn_hover_bg', array(
			'label'	   				=> esc_html__( 'Continue Button Background: Hover', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_continue_btn_hover_bg',
			'priority' 				=> 10,
		) ) );

		/**
		 * Continue Button Hover Color
		 */
		$wp_customize->add_setting( 'owp_popup_continue_btn_hover_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_continue_btn_hover_color', array(
			'label'	   				=> esc_html__( 'Continue Button Color: Hover', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_continue_btn_hover_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Continue Button Hover Border Color
		 */
		$wp_customize->add_setting( 'owp_popup_continue_btn_hover_border_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#13aff0',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_continue_btn_hover_border_color', array(
			'label'	   				=> esc_html__( 'Continue Button Border Color: Hover', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_continue_btn_hover_border_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Cart Button Background
		 */
		$wp_customize->add_setting( 'owp_popup_cart_btn_bg', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_cart_btn_bg', array(
			'label'	   				=> esc_html__( 'Cart Button Background', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_cart_btn_bg',
			'priority' 				=> 10,
		) ) );

		/**
		 * Cart Button Color
		 */
		$wp_customize->add_setting( 'owp_popup_cart_btn_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#41c389',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_cart_btn_color', array(
			'label'	   				=> esc_html__( 'Cart Button Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_cart_btn_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Cart Button Border Color
		 */
		$wp_customize->add_setting( 'owp_popup_cart_btn_border_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#41c389',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_cart_btn_border_color', array(
			'label'	   				=> esc_html__( 'Cart Button Border Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_cart_btn_border_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Cart Button Hover Background
		 */
		$wp_customize->add_setting( 'owp_popup_cart_btn_hover_bg', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#41c389',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_cart_btn_hover_bg', array(
			'label'	   				=> esc_html__( 'Cart Button Background: Hover', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_cart_btn_hover_bg',
			'priority' 				=> 10,
		) ) );

		/**
		 * Cart Button Hover Color
		 */
		$wp_customize->add_setting( 'owp_popup_cart_btn_hover_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#ffffff',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_cart_btn_hover_color', array(
			'label'	   				=> esc_html__( 'Cart Button Color: Hover', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_cart_btn_hover_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Cart Button Hover Border Color
		 */
		$wp_customize->add_setting( 'owp_popup_cart_btn_hover_border_color', array(
			'transport' 			=> 'postMessage',
			'default'           	=> '#13aff0',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_cart_btn_hover_border_color', array(
			'label'	   				=> esc_html__( 'Cart Button Border Color: Hover', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_cart_btn_hover_border_color',
			'priority' 				=> 10,
		) ) );

		/**
		 * Bottom Text Color
		 */
		$wp_customize->add_setting( 'owp_popup_text_color', array(
			'transport' 			=> 'postMessage',
			'sanitize_callback' 	=> 'oceanwp_sanitize_color',
		) );

		$wp_customize->add_control( new OceanWP_Customizer_Color_Control( $wp_customize, 'owp_popup_text_color', array(
			'label'	   				=> esc_html__( 'Bottom Text Color', 'oceanwp' ),
			'section'  				=> 'owp_section',
			'settings' 				=> 'owp_popup_text_color',
			'priority' 				=> 10,
		) ) );

	}

	/**
	 * Helpers
	 */
	public static function helpers( $return = NULL ) {

		// Return template array
		if ( 'template' == $return ) {
			$templates 		= array( esc_html__( 'Default', 'ocean-modal-window' ) ); 
			$get_templates 	= get_posts( array( 'post_type' => 'oceanwp_library', 'numberposts' => -1, 'post_status' => 'publish' ) );

		    if ( ! empty ( $get_templates ) ) {
		    	foreach ( $get_templates as $template ) {
					$templates[ $template->ID ] = $template->post_title;
			    }
			}

			return $templates;
		}

	}

	/**
	 * Enqueue scripts.
	 */
	public function scripts() {

		if ( ! class_exists( 'WooCommerce' )
			|| is_cart()
			|| is_checkout() ) {
			return;
		}

		// Load main stylesheet
		wp_enqueue_style( 'owp-style', plugins_url( '/assets/css/style.min.css', __FILE__ ) );
		
		// Load custom js methods.
		wp_enqueue_script( 'owp-js-script', plugins_url( '/assets/js/owp.min.js', __FILE__ ), array( 'jquery', 'oceanwp-main' ), null, true );

	}

	/**
	 * Gets the popup template part.
	 */
	public function popup_template() {

		if ( ! class_exists( 'WooCommerce' )
			|| is_cart()
			|| is_checkout() ) {
			return;
		}

		$file 		= OWP_PATH . 'template/popup.php';
		$theme_file = get_stylesheet_directory() . '/templates/extra/popup.php';

		if ( file_exists( $theme_file ) ) {
			$file = $theme_file;
		}

		if ( file_exists( $file ) ) {
			include $file;
		}

	}

	/**
	 * Get CSS
	 */
	public static function head_css( $output ) {

		// Global vars
		$popup_width							= get_theme_mod( 'owp_popup_width', '600' );
		$popup_width_tablet						= get_theme_mod( 'owp_popup_width_tablet' );
		$popup_width_mobile						= get_theme_mod( 'owp_popup_width_mobile' );
		$popup_height							= get_theme_mod( 'owp_popup_height', '600' );
		$popup_height_tablet					= get_theme_mod( 'owp_popup_height_tablet' );
		$popup_height_mobile					= get_theme_mod( 'owp_popup_height_mobile' );
		$top_padding 							= get_theme_mod( 'owp_popup_top_padding', '50' );
		$right_padding 							= get_theme_mod( 'owp_popup_right_padding', '25' );
		$bottom_padding 						= get_theme_mod( 'owp_popup_bottom_padding', '50' );
		$left_padding 							= get_theme_mod( 'owp_popup_left_padding', '25' );
		$tablet_top_padding 					= get_theme_mod( 'owp_popup_tablet_top_padding', '20' );
		$tablet_right_padding 					= get_theme_mod( 'owp_popup_tablet_right_padding', '20' );
		$tablet_bottom_padding 					= get_theme_mod( 'owp_popup_tablet_bottom_padding', '20' );
		$tablet_left_padding 					= get_theme_mod( 'owp_popup_tablet_left_padding', '20' );
		$mobile_top_padding 					= get_theme_mod( 'owp_popup_mobile_top_padding' );
		$mobile_right_padding 					= get_theme_mod( 'owp_popup_mobile_right_padding' );
		$mobile_bottom_padding 					= get_theme_mod( 'owp_popup_mobile_bottom_padding' );
		$mobile_left_padding 					= get_theme_mod( 'owp_popup_mobile_left_padding' );
		$top_radius 							= get_theme_mod( 'owp_popup_top_radius', '600' );
		$right_radius 							= get_theme_mod( 'owp_popup_right_radius', '600' );
		$bottom_radius 							= get_theme_mod( 'owp_popup_bottom_radius', '600' );
		$left_radius 							= get_theme_mod( 'owp_popup_left_radius', '600' );
		$tablet_top_radius 						= get_theme_mod( 'owp_popup_tablet_top_radius', '20' );
		$tablet_right_radius 					= get_theme_mod( 'owp_popup_tablet_right_radius', '20' );
		$tablet_bottom_radius 					= get_theme_mod( 'owp_popup_tablet_bottom_radius', '20' );
		$tablet_left_radius 					= get_theme_mod( 'owp_popup_tablet_left_radius', '20' );
		$mobile_top_radius 						= get_theme_mod( 'owp_popup_mobile_top_radius' );
		$mobile_right_radius 					= get_theme_mod( 'owp_popup_mobile_right_radius' );
		$mobile_bottom_radius 					= get_theme_mod( 'owp_popup_mobile_bottom_radius' );
		$mobile_left_radius 					= get_theme_mod( 'owp_popup_mobile_left_radius' );
		$popup_bg 								= get_theme_mod( 'owp_popup_bg', '#ffffff' );
		$popup_overlay_color 					= get_theme_mod( 'owp_popup_overlay_color', '#000000' );
		$popup_checkmark_bg 					= get_theme_mod( 'owp_popup_checkmark_bg', '#5bc142' );
		$popup_checkmark_color 					= get_theme_mod( 'owp_popup_checkmark_color', '#ffffff' );
		$popup_title_color 						= get_theme_mod( 'owp_popup_title_color', '#333333' );
		$popup_content_color 					= get_theme_mod( 'owp_popup_content_color', '#777777' );
		$popup_continue_btn_bg 					= get_theme_mod( 'owp_popup_continue_btn_bg' );
		$popup_continue_btn_color 				= get_theme_mod( 'owp_popup_continue_btn_color', '#13aff0' );
		$popup_continue_btn_border_color 		= get_theme_mod( 'owp_popup_continue_btn_border_color', '#13aff0' );
		$popup_continue_btn_hover_bg 			= get_theme_mod( 'owp_popup_continue_btn_hover_bg', '#13aff0' );
		$popup_continue_btn_hover_color 		= get_theme_mod( 'owp_popup_continue_btn_hover_color', '#ffffff' );
		$popup_continue_btn_hover_border_color 	= get_theme_mod( 'owp_popup_continue_btn_hover_border_color', '#13aff0' );
		$popup_cart_btn_bg 						= get_theme_mod( 'owp_popup_cart_btn_bg' );
		$popup_cart_btn_color 					= get_theme_mod( 'owp_popup_cart_btn_color', '#41c389' );
		$popup_cart_btn_border_color 			= get_theme_mod( 'owp_popup_cart_btn_border_color', '#41c389' );
		$popup_cart_btn_hover_bg 				= get_theme_mod( 'owp_popup_cart_btn_hover_bg', '#41c389' );
		$popup_cart_btn_hover_color 			= get_theme_mod( 'owp_popup_cart_btn_hover_color', '#ffffff' );
		$popup_cart_btn_hover_border_color 		= get_theme_mod( 'owp_popup_cart_btn_hover_border_color', '#41c389' );
		$popup_text_color 						= get_theme_mod( 'owp_popup_text_color' );

		// Define css var
		$css = '';

		// Popup width
		if ( ! empty( $popup_width ) && '600' != $popup_width ) {
			$css .= '#woo-popup-wrap #woo-popup-inner{width:'. $popup_width .'px;}';
		}

		// Popup width tablet
		if ( ! empty( $popup_width_tablet ) ) {
			$css .= '@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{width:'. $popup_width_tablet .'px;}}';
		}

		// Popup width mobile
		if ( ! empty( $popup_width_mobile ) ) {
			$css .= '@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{width:'. $popup_width_mobile .'px;}}';
		}

		// Popup height
		if ( ! empty( $popup_height ) && '600' != $popup_height ) {
			$css .= '#woo-popup-wrap #woo-popup-inner{height:'. $popup_height .'px;}';
		}

		// Popup height tablet
		if ( ! empty( $popup_height_tablet ) ) {
			$css .= '@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{height:'. $popup_height_tablet .'px;}}';
		}

		// Popup height mobile
		if ( ! empty( $popup_height_mobile ) ) {
			$css .= '@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{height:'. $popup_height_mobile .'px;}}';
		}

		// Popup padding
		if ( isset( $top_padding ) && '50' != $top_padding && '' != $top_padding
			|| isset( $right_padding ) && '25' != $right_padding && '' != $right_padding
			|| isset( $bottom_padding ) && '50' != $bottom_padding && '' != $bottom_padding
			|| isset( $left_padding ) && '25' != $left_padding && '' != $left_padding ) {
			$css .= '#woo-popup-wrap #woo-popup-inner{padding:'. oceanwp_spacing_css( $top_padding, $right_padding, $bottom_padding, $left_padding ) .'}';
		}

		// Tablet popup padding
		if ( isset( $tablet_top_padding ) && '20' != $tablet_top_padding && '' != $tablet_top_padding
			|| isset( $tablet_right_padding ) && '20' != $tablet_right_padding && '' != $tablet_right_padding
			|| isset( $tablet_bottom_padding ) && '20' != $tablet_bottom_padding && '' != $tablet_bottom_padding
			|| isset( $tablet_left_padding ) && '20' != $tablet_left_padding && '' != $tablet_left_padding ) {
			$css .= '@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{padding:'. oceanwp_spacing_css( $tablet_top_padding, $tablet_right_padding, $tablet_bottom_padding, $tablet_left_padding ) .'}}';
		}

		// Mobile popup padding
		if ( isset( $mobile_top_padding ) && '' != $mobile_top_padding
			|| isset( $mobile_right_padding ) && '' != $mobile_right_padding
			|| isset( $mobile_bottom_padding ) && '' != $mobile_bottom_padding
			|| isset( $mobile_left_padding ) && '' != $mobile_left_padding ) {
			$css .= '@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{padding:'. oceanwp_spacing_css( $mobile_top_padding, $mobile_right_padding, $mobile_bottom_padding, $mobile_left_padding ) .'}}';
		}

		// Popup border radius
		if ( isset( $top_radius ) && '600' != $top_radius && '' != $top_radius
			|| isset( $right_radius ) && '600' != $right_radius && '' != $right_radius
			|| isset( $bottom_radius ) && '600' != $bottom_radius && '' != $bottom_radius
			|| isset( $left_radius ) && '600' != $left_radius && '' != $left_radius ) {
			$css .= '#woo-popup-wrap #woo-popup-inner{border-radius:'. oceanwp_spacing_css( $top_radius, $right_radius, $bottom_radius, $left_radius ) .'}';
		}

		// Tablet popup border radius
		if ( isset( $tablet_top_radius ) && '' != $tablet_top_radius
			|| isset( $tablet_right_radius ) && '' != $tablet_right_radius
			|| isset( $tablet_bottom_radius ) && '' != $tablet_bottom_radius
			|| isset( $tablet_left_radius ) && '' != $tablet_left_radius ) {
			$css .= '@media (max-width: 768px){#woo-popup-wrap #woo-popup-inner{border-radius:'. oceanwp_spacing_css( $tablet_top_radius, $tablet_right_radius, $tablet_bottom_radius, $tablet_left_radius ) .'}}';
		}

		// Mobile popup border radius
		if ( isset( $mobile_top_radius ) && '' != $mobile_top_radius
			|| isset( $mobile_right_radius ) && '' != $mobile_right_radius
			|| isset( $mobile_bottom_radius ) && '' != $mobile_bottom_radius
			|| isset( $mobile_left_radius ) && '' != $mobile_left_radius ) {
			$css .= '@media (max-width: 480px){#woo-popup-wrap #woo-popup-inner{border-radius:'. oceanwp_spacing_css( $mobile_top_radius, $mobile_right_radius, $mobile_bottom_radius, $mobile_left_radius ) .'}}';
		}

		// Popup background color
		if ( ! empty( $popup_bg ) && '#ffffff' != $popup_bg ) {
			$css .= '#woo-popup-wrap #woo-popup-inner{background-color:'. $popup_bg .';}';
		}

		// Popup check mark background
		if ( ! empty( $popup_checkmark_bg ) && '#5bc142' != $popup_checkmark_bg ) {
			$css .= '#woo-popup-wrap .checkmark{box-shadow: inset 0 0 0 '. $popup_checkmark_bg .'; }#woo-popup-wrap .checkmark-circle{stroke: '. $popup_checkmark_bg .';}@keyframes fill {100% { box-shadow: inset 0 0 0 100px '. $popup_checkmark_bg .'; }}';
		}

		// Popup check mark color
		if ( ! empty( $popup_checkmark_color ) && '#ffffff' != $popup_checkmark_color ) {
			$css .= '#woo-popup-wrap .checkmark-check{stroke:'. $popup_checkmark_color .';}';
		}

		// Popup title color
		if ( ! empty( $popup_title_color ) && '#333333' != $popup_title_color ) {
			$css .= '#woo-popup-wrap .popup-title{color:'. $popup_title_color .';}';
		}

		// Popup content color
		if ( ! empty( $popup_content_color ) && '#777777' != $popup_content_color ) {
			$css .= '#woo-popup-wrap .popup-content{color:'. $popup_content_color .';}';
		}

		// Popup continue button background color
		if ( ! empty( $popup_continue_btn_bg ) ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.continue-btn{background-color:'. $popup_continue_btn_bg .';}';
		}

		// Popup continue button color
		if ( ! empty( $popup_continue_btn_color ) && '#13aff0' != $popup_continue_btn_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.continue-btn{color:'. $popup_continue_btn_color .';}';
		}

		// Popup continue button border color
		if ( ! empty( $popup_continue_btn_border_color ) && '#13aff0' != $popup_continue_btn_border_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.continue-btn{border-color:'. $popup_continue_btn_border_color .';}';
		}

		// Popup continue button hover background color
		if ( ! empty( $popup_continue_btn_hover_bg ) && '#13aff0' != $popup_continue_btn_hover_bg ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.continue-btn:hover{background-color:'. $popup_continue_btn_hover_bg .';}';
		}

		// Popup continue button hover color
		if ( ! empty( $popup_continue_btn_hover_color ) && '#ffffff' != $popup_continue_btn_hover_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.continue-btn:hover{color:'. $popup_continue_btn_hover_color .';}';
		}

		// Popup continue button hover border color
		if ( ! empty( $popup_continue_btn_hover_border_color ) && '#13aff0' != $popup_continue_btn_hover_border_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.continue-btn:hover{border-color:'. $popup_continue_btn_hover_border_color .';}';
		}

		// Popup cart button background color
		if ( ! empty( $popup_cart_btn_bg ) ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.cart-btn{background-color:'. $popup_cart_btn_bg .';}';
		}

		// Popup cart button color
		if ( ! empty( $popup_cart_btn_color ) && '#41c389' != $popup_cart_btn_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.cart-btn{color:'. $popup_cart_btn_color .';}';
		}

		// Popup cart button border color
		if ( ! empty( $popup_cart_btn_border_color ) && '#41c389' != $popup_cart_btn_border_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.cart-btn{border-color:'. $popup_cart_btn_border_color .';}';
		}

		// Popup cart button hover background color
		if ( ! empty( $popup_cart_btn_hover_bg ) && '#41c389' != $popup_cart_btn_hover_bg ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.cart-btn:hover{background-color:'. $popup_cart_btn_hover_bg .';}';
		}

		// Popup cart button hover color
		if ( ! empty( $popup_cart_btn_hover_color ) && '#ffffff' != $popup_cart_btn_hover_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.cart-btn:hover{color:'. $popup_cart_btn_hover_color .';}';
		}

		// Popup cart button hover border color
		if ( ! empty( $popup_cart_btn_hover_border_color ) && '#41c389' != $popup_cart_btn_hover_border_color ) {
			$css .= '#woo-popup-wrap .buttons-wrap a.cart-btn:hover{border-color:'. $popup_cart_btn_hover_border_color .';}';
		}

		// Popup bottom text color
		if ( ! empty( $popup_text_color ) ) {
			$css .= '#woo-popup-wrap .popup-text{color:'. $popup_text_color .';}';
		}

		// Return CSS
		if ( ! empty( $css ) ) {
			$output .= '/* Woo Popup CSS */'. $css;
		}

		// Return output css
		return $output;

	}

} // End Class