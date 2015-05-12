<?php
/**
 * Plugin Name:			Storefront Top Bar
 * Plugin URI:			http://wooassist.com/
 * Description:			Adds two bars on top of the Storefront theme header.
 * Version:				1.0.0
 * Author:				WooAssist
 * Author URI:			http://woothemes.com/
 * Requires at least:	4.0.0
 * Tested up to:		4.1.0
 *
 * Text Domain: storefront-top-bar
 * Domain Path: /languages/
 *
 * @package Storefront_Top_Bar
 * @category Core
 * @author WooAssist
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of Storefront_Top_Bar to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Top_Bar
 */
function Storefront_Top_Bar() {
	return Storefront_Top_Bar::instance();
} // End Storefront_Top_Bar()

Storefront_Top_Bar();

/**
 * Main Storefront_Top_Bar Class
 *
 * @class Storefront_Top_Bar
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Top_Bar
 */
final class Storefront_Top_Bar {
	/**
	 * Storefront_Top_Bar The single instance of Storefront_Top_Bar.
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

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'storefront-top-bar';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.0';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'woa_sf_load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'woa_sf_setup' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'woa_sf_plugin_links' ) );
	}

	/**
	 * Main Storefront_Top_Bar Instance
	 *
	 * Ensures only one instance of Storefront_Top_Bar is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Top_Bar()
	 * @return Main Storefront_Top_Bar instance
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
	public function woa_sf_load_plugin_textdomain() {
		load_plugin_textdomain( 'storefront-top-bar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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
	 * Plugin page links
	 *
	 * @since  1.0.0
	 */
	public function woa_sf_plugin_links( $links ) {
		$plugin_links = array(
			'<a href="http://support.woothemes.com/">' . __( 'Support', 'storefront-top-bar' ) . '</a>',
			'<a href="http://docs.woothemes.com/document/storefront-top-bar/">' . __( 'Docs', 'storefront-top-bar' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
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

		if( 'storefront' != basename( TEMPLATEPATH ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'Sorry, you can&rsquo;t activate this plugin unless you have installed the Storefront theme.' );
		}

		// get theme customizer url
		$url = admin_url() . 'customize.php?';
		$url .= 'url=' . urlencode( site_url() . '?storefront-customizer=true' ) ;
		$url .= '&return=' . urlencode( admin_url() . 'plugins.php' );
		$url .= '&storefront-customizer=true';

		$notices 		= get_option( 'woa_sf_activation_notice', array() );
		$notices[]		= sprintf( __( '%sThanks for installing the Storefront Top Bar extension. To get started, visit the %sCustomizer%s.%s %sOpen the Customizer%s', 'storefront-top-bar' ), '<p>', '<a href="' . esc_url( $url ) . '">', '</a>', '</p>', '<p><a href="' . esc_url( $url ) . '" class="button button-primary">', '</a></p>' );

		update_option( 'woa_sf_activation_notice', $notices );
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
	 * Setup all the things.
	 * Only executes if Storefront or a child theme using Storefront as a parent is active and the extension specific filter returns true.
	 * Child themes can disable this extension using the storefront_top_bar_enabled filter
	 * @return void
	 */
	public function woa_sf_setup() {
		$theme = wp_get_theme();

		if ( 'Storefront' == $theme->name || 'storefront' == $theme->template && apply_filters( 'storefront_top_bar_supported', true ) ) {
			add_action( 'customize_register', array( $this, 'woa_sf_customize_register' ) );
			add_filter( 'body_class', array( $this, 'woa_sf_body_class' ) );
			$this->register_widget_area( 'Top Bar', 'woa-top-bar-', 2);
			add_action( 'storefront_before_header', array( $this, 'woa_sf_layout_adjustments' ) );
			add_action( 'admin_notices', array( $this, 'woa_sf_customizer_notice' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'woa_sf_styles' ),	9 );
			add_action( 'wp_head', array( $this, 'inline_css') );

			// Hide the 'More' section in the customizer
			add_filter( 'storefront_customizer_more', '__return_false' );
		}
	}

	/**
	 * Admin notice
	 * Checks the notice setup in install(). If it exists display it then delete the option so it's not displayed again.
	 * @since   1.0.0
	 * @return  void
	 */
	public function woa_sf_customizer_notice() {
		$notices = get_option( 'woa_sf_activation_notice' );

		if ( $notices = get_option( 'woa_sf_activation_notice' ) ) {

			foreach ( $notices as $notice ) {
				echo '<div class="updated">' . $notice . '</div>';
			}

			delete_option( 'woa_sf_activation_notice' );
		}
	}

	/**
	 * Customizer Controls and settings
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function woa_sf_customize_register( $wp_customize ) {

		/**
		 * Add new section
		 */
		$wp_customize->add_section( 
			'woa_sf_top_bar' , array(
			    'title'      => __( 'Top Bar', 'wooassist' ),
			    'priority'   => 30,
			)
		);

		/**
		 * Add new settings
		 */
		$wp_customize->add_setting( 
			'woa_sf_topbar_bgcolor',
			array(
				'default' => apply_filters( 'woa_sf_topbar_default_bgcolor', '#5b5b5b' ),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_setting( 
			'woa_sf_topbar_txtcolor',
			array(
				'default' => apply_filters( 'woa_sf_topbar_default_txtcolor', '#efefef' ),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_setting( 
			'woa_sf_topbar_linkcolor',
			array(
				'default' => apply_filters( 'woa_sf_topbar_default_linkcolor', '#ffffff' ),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_setting( 
			'woa_sf_topbar_mobile_display',
			array(
				'default' => 'show-on-mobile',
			)
		);

		/**
		 * Add controls and apply respective settings and hook on section
		 */
		$wp_customize->add_control( 
			new WP_Customize_Color_Control( 
			$wp_customize, 
			'woa_sf_topbar_bgcolor', 
			array(
				'label'      => __( 'Background Color', 'mytheme' ),
				'section'    => 'woa_sf_top_bar',
				'settings'   => 'woa_sf_topbar_bgcolor',
			) ) 
		);

		$wp_customize->add_control( 
			new WP_Customize_Color_Control( 
			$wp_customize, 
			'woa_sf_topbar_txtcolor', 
			array(
				'label'      => __( 'Text Color', 'mytheme' ),
				'section'    => 'woa_sf_top_bar',
				'settings'   => 'woa_sf_topbar_txtcolor',
			) ) 
		);

		$wp_customize->add_control( 
			new WP_Customize_Color_Control( 
			$wp_customize, 
			'woa_sf_topbar_linkcolor', 
			array(
				'label'      => __( 'Link Color', 'mytheme' ),
				'section'    => 'woa_sf_top_bar',
				'settings'   => 'woa_sf_topbar_linkcolor',
			) ) 
		);

		$wp_customize->add_control(
		    new WP_Customize_Control(
		        $wp_customize,
		        'woa_sf_topbar_mobile_display',
		        array(
		            'label'          => __( 'Mobile Display', 'wooassist' ),
		            'section'        => 'woa_sf_top_bar',
		            'settings'       => 'woa_sf_topbar_mobile_display',
		            'type'           => 'radio',
		            'choices'        => array(
		                'show-on-mobile'   => __( 'Show' ),
		                'hide-on-mobile'  => __( 'Hide' )
		            )
		        )
		    )
		);

	}

	/**
	 * Adjust hex color brightness
	 * @param $hex $steps
	 */
	function adjust_brightness($hex, $steps) {
	    // Steps should be between -255 and 255. Negative = darker, positive = lighter
	    $steps = max(-255, min(255, $steps));

	    // Normalize into a six character long hex string
	    $hex = str_replace('#', '', $hex);
	    if (strlen($hex) == 3) {
	        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
	    }

	    // Split into three parts: R, G and B
	    $color_parts = str_split($hex, 2);
	    $return = '#';

	    foreach ($color_parts as $color) {
	        $color   = hexdec($color); // Convert to decimal
	        $color   = max(0,min(255,$color + $steps)); // Adjust color
	        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
	    }

	    return $return;
	}

	/**
	 * External Styles
	 */
	function woa_sf_styles() {

		wp_enqueue_style( 'storefront-top-bar', plugins_url( '/assets/css/storefront-top-bar.css', __FILE__ ) );
	}


	/**
	 * Inline CSS
	 */
	function inline_css() {

		$bg_color		= 	get_theme_mod( 'woa_sf_topbar_bgcolor', apply_filters( 'woa_sf_topbar_default_bgcolor', '#5b5b5b' ) );
		$submenu_bg		=	$this->adjust_brightness( $bg_color, -25 );
		$txt_color		= 	get_theme_mod( 'woa_sf_topbar_txtcolor', apply_filters( 'woa_sf_topbar_default_txtcolor', '#efefef' ) );
		$link_color		=	get_theme_mod( 'woa_sf_topbar_linkcolor', apply_filters( 'woa_sf_topbar_default_linkcolor', '#ffffff' ) );

		?>
		<style type="text/css">
			.woa-top-bar-wrap, .woa-top-bar .block .widget_nav_menu ul li .sub-menu { background: <?php echo $bg_color; ?>; } .woa-top-bar .block .widget_nav_menu ul li .sub-menu li a:hover { background: <?php echo $submenu_bg; ?> } .woa-top-bar-wrap * { color: <?php echo $txt_color; ?>; } .woa-top-bar-wrap a, .woa-top-bar-wrap .widget_nav_menu li.current-menu-item > a { color: <?php echo $link_color; ?> !important; } .woa-top-bar-wrap a:hover { opacity: 0.9; }
		</style>
		<?php
	}

	/**
	 * Storefront Top Bar Body Class
	 * Adds a class based on the extension name and any relevant settings.
	 */
	public function woa_sf_body_class( $classes ) {
		$classes[] = 'storefront-top-bar-active';

		return $classes;
	}

	/**
	 * Layout
	 * Adjusts the default Storefront layout when the plugin is active
	 */
	public function woa_sf_layout_adjustments() {
		
		if ( is_active_sidebar( 'woa-top-bar-2' ) ) {
			$widget_columns = apply_filters( 'woa_top_widget_regions', 2 );
		} elseif ( is_active_sidebar( 'woa-top-bar-1' ) ) {
			$widget_columns = apply_filters( 'woa_top_widget_regions', 1 );
		} else {
			$widget_columns = apply_filters( 'woa_top_widget_regions', 0 );
		}

		$mobile_toggle = get_theme_mod( 'woa_sf_topbar_mobile_display', 'show-on-mobile' );

		if ( $widget_columns > 0 ) : ?>

			<div class="woa-top-bar-wrap<?php echo ' ' . $mobile_toggle; ?>">

				<div class="col-full">

					<section class="woa-top-bar col-<?php echo intval( $widget_columns ); ?> fix">

						<?php $i = 0; while ( $i < $widget_columns ) : $i++; ?>

							<?php if ( is_active_sidebar( 'woa-top-bar-' . $i ) ) : ?>

								<section class="block woa-top-bar-<?php echo intval( $i ); ?>">
						        	<?php dynamic_sidebar( 'woa-top-bar-' . intval( $i ) ); ?>
								</section>

					        <?php endif; ?>

						<?php endwhile; ?>

						<div class="clear"></div>

					</section>

				</div>

			</div>

		<?php endif;
	}

	/**
	 * Register widget area function
	 */
	function register_widget_area( $widget_area, $id_prefix , $i = 1 ) {
		for($n=1; $n <= $i; $n++){
			$args = array(
				'name'          => "$widget_area $n",
				'id'            => $id_prefix . $n,
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1 class="widgettitle">',
				'after_title'   => '</h1>'
			);
			register_sidebar( $args );
		}
	}

} // End Class
