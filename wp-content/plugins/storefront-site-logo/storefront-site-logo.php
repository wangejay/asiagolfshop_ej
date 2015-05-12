<?php
/**
 * Plugin Name:			Storefront Site Logo
 * Plugin URI:			http://wooassist.com/
 * Description:			Lets you add a logo to your site by adding a Branding tab to the customizer where you can choose between "Title and Tagline" or "Logo image" for the Storefront theme.
 * Version:				1.1.2
 * Author:				WooAssist
 * Author URI:			http://wooassist.com/
 * Requires at least:	4.0.0
 * Tested up to:		4.1.0
 *
 * Text Domain: storefront-site-logo
 * Domain Path: /languages/
 *
 * @package Storefront_Site_Logo
 * @category Core
 * @author Wooassist
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of Storefront_Site_Logo to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Storefront_Site_Logo
 */
function Storefront_Site_Logo() {
	return Storefront_Site_Logo::instance();
} // End Storefront_Site_Logo()

Storefront_Site_Logo();

/**
 * Main Storefront_Site_Logo Class
 *
 * @class Storefront_Site_Logo
 * @version	1.0.0
 * @since 1.0.0
 * @package	Storefront_Site_Logo
 */
final class Storefront_Site_Logo {
	/**
	 * Storefront_Site_Logo The single instance of Storefront_Site_Logo.
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
		$this->token 			= 'storefront-site-logo';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.2.0';

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'woa_sf_load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'woa_sf_setup' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'woa_sf_plugin_links' ) );
	}

	/**
	 * Main Storefront_Site_Logo Instance
	 *
	 * Ensures only one instance of Storefront_Site_Logo is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Storefront_Site_Logo()
	 * @return Main Storefront_Site_Logo instance
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
		load_plugin_textdomain( 'storefront-site-logo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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
			'<a href="https://wordpress.org/support/plugin/storefront-site-logo">' . __( 'Support', 'storefront-site-logo' ) . '</a>',
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
		$notices[]		= sprintf( __( '%sThanks for installing the Storefront Site Logo extension. To get started, visit the %sCustomizer%s.%s %sOpen the Customizer%s', 'storefront-site-logo' ), '<p>', '<a href="' . esc_url( $url ) . '">', '</a>', '</p>', '<p><a href="' . esc_url( $url ) . '" class="button button-primary">', '</a></p>' );

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
	 * Child themes can disable this extension using the storefront_site_logo_enabled filter
	 * @return void
	 */
	public function woa_sf_setup() {
		$theme = wp_get_theme();

		if ( 'Storefront' == $theme->name || 'storefront' == $theme->template && apply_filters( 'storefront_site_logo_supported', true ) ) {
			add_action( 'customize_register', array( $this, 'woa_sf_customize_register') );
			add_filter( 'body_class', array( $this, 'woa_sf_body_class' ) );
			add_action( 'admin_notices', array( $this, 'woa_sf_customizer_notice' ) );

			$header_layout = function_exists( 'Storefront_Designer' ) ? get_theme_mod( 'sd_header_layout', 'compact' ) : 'compact';

			// replace default branding function
			if ( $header_layout == 'expanded' ) { // check if Storefront Designer plugin is installed
				remove_action( 'storefront_header', 'storefront_site_branding', 45 );
				add_action( 'storefront_header', array( $this, 'woa_sf_layout_adjustments' ), 45 );
			} else {
				remove_action( 'storefront_header', 'storefront_site_branding', 20 );
				add_action( 'storefront_header', array( $this, 'woa_sf_layout_adjustments' ), 20 );
			}

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
			'woa_sf_branding' , array(
			    'title'      => __( 'Branding', 'storefront-site-logo' ),
			    'priority'   => 30,
			)
		);

		/**
		 * Add new settings
		 */
		$wp_customize->add_setting( 'woa_sf_enable_logo' );
		$wp_customize->add_setting( 'woa_sf_logo' );

		/**
		 * Add new controls and assigning the settings and it's section
		 */
		$wp_customize->add_control(
	        new WP_Customize_Control(
	            $wp_customize,
	            'woa_sf_enable_logo_img',
	            array(
	                'label'      => __( 'Choose branding style', 'storefront-site-logo' ),
	                'section'    => 'woa_sf_branding',
	                'settings'   => 'woa_sf_enable_logo',
	                'type'		 => 'radio',
	                'choices'	 => array(
	                	'title_tagline'		=>	__( 'Title and Tagline', 'storefront-site-logo' ),
	                	'logo_img'			=>	__( 'Logo image', 'storefront-site-logo' )
	                )
	            )
	        )
	    );

		$wp_customize->add_control(
	        new WP_Customize_Image_Control(
	            $wp_customize,
	            'woa_sf_logo_img',
	            array(
	                'label'      => __( 'Logo Image', 'storefront-site-logo' ),
	                'section'    => 'woa_sf_branding',
	                'settings'   => 'woa_sf_logo'
	            )
	        )
	    );
	}

	/**
	 * Storefront Site Logo Body Class
	 * Adds a class based on the extension name and any relevant settings.
	 */
	public function woa_sf_body_class( $classes ) {
		$classes[] = 'storefront-site-logo-active';

		return $classes;
	}

	/**
	 * Layout
	 * Adjusts the default Storefront layout when the plugin is active
	 */
	public function woa_sf_layout_adjustments() {

		$check = get_theme_mod( 'woa_sf_enable_logo', 'title_tagline' );
		$logo = get_theme_mod( 'woa_sf_logo', null );

		if( ( $check == 'logo_img' ) && $logo ) {

			if( is_ssl() ) {
				$logo = str_replace( 'http://', 'https://', $logo );
			}

			?>
			<div class="site-logo-anchor">
				<a href="<?php bloginfo('url'); ?>"><img src="<?php echo $logo; ?>" style="display:inline-block;"></a>
			</div>
		<?php
		}
		else if ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) {
			jetpack_the_site_logo();
		} else { ?>
			<div class="site-branding">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			</div>
		<?php }
	}

} // End Class
