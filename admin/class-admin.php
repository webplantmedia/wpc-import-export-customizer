<?php
/**
 * WPC Import Export Customizer.
 *
 * @package   WPC_Import_Export_Customizer_Admin
 * @author    Chris Baldelomar <chris@webplantmedia.com>
 * @license   GPL-2.0+
 * @link      http://webplantmedia.com
 * @copyright 2014 Chris Baldelomar
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package   WPC_Import_Export_Customizer_Admin
 * @author  Chris Baldelomar <chris@webplantmedia.com>
 */
class WPC_Import_Export_Customizer_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	protected $plugin_slug = 'wpc-import-export-customizer';
	protected $plugin_prefix = 'wpc_import_export_customizer';

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	const VERSION = '1.1';

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		define( 'WPC_IMPORT_EXPORT_CUSTOMIZER_IS_ACTIVATED', true );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return the plugin prefix.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_prefix() {
		return $this->plugin_prefix;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), self::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_submenu_page(
			'tools.php',
			__( 'Import/Export Customizer', 'wpc-import-export-customizer' ),
			__( 'Import/Export Customizer', 'wpc-import-export-customizer' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'tools.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', 'wpc-import-export-customizer' ) . '</a>'
			),
			$links
		);

	}
	
	public function display_customizer_options() {
		global $wpc2_default;

		if ( ! $mods = get_theme_mods() ) {
			echo '<p>No Data</p>';
			return;
		}

		$uri = get_template_directory_uri();
		$uri_esc = preg_quote( $uri, '/' );

		echo '<pre>';
		foreach ( $wpc2_default as $key => $value ) {
			if ( array_key_exists( $key, $mods ) ) {
				$value = $mods[ $key ];
			}

			$value = "'" . $value . "';";

			if ( preg_match( '/'.$uri_esc.'.*/', $value ) ) {
				$value = 'get_template_directory_uri() . ' . str_replace( $uri, '', $value );
			}
			if ( $value != strip_tags( $value ) ) {
				$value = htmlspecialchars( $value );
			}

			echo '$wpc2_default[\'' . $key . '\'] = '.$value.'<br />';
		}
		echo '</pre>';
	}
	
	public function restore_default_options() {
		global $wpc2_default;
		$restored = false;

		if ( ! $mods = get_theme_mods() ) {
			echo '<p>No Data</p>';
			return;
		}

		echo '<p>The following options have been restored:</p>';
		echo '<p>';
		foreach ( $wpc2_default as $key => $value ) {
			if ( array_key_exists( $key, $mods ) ) {
				remove_theme_mod( $key );
				echo $key . '<br />';
				$restored = true;
			}
		}
		if ( ! $restored ) {
			echo "No options to restore.";
		}
		echo '</p>';
	}
}
