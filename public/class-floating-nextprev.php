<?php
/**
 * Floating NextPrev.
 *
 * @package   Floating_NextPrev
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @link      https://github.com/claudiosmweb/floating-nextprev
 * @copyright 2013 Claudio Sanches
 */

/**
 * Floating NextPrev class.
 *
 * @package Floating_NextPrev
 * @author  Claudio Sanches <contato@claudiosmweb.com>
 */
class Floating_NextPrev {

	/**
	 * Plugin version.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const VERSION = '2.0.0';

	/**
	 * Plugin slug for text domain.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $plugin_slug = 'floating-nextprev';

	/**
	 * Settings name.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $settings_name = 'floating_nextprev';

	/**
	 * Instance of this class.
	 *
	 * @since 2.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 *
	 * @since 2.0.0
	 */
	private function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Front-end scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'front_end_scripts' ) );

		// Adds footer js.
		add_filter( 'wp_footer', array( $this, 'view' ), 999 );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since 2.0.0
	 *
	 * @return string Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return the settings name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Settings name variable.
	 */
	public function get_settings_name() {
		return $this->settings_name;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 2.1.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Gets the options.
	 *
	 * @since 2.2.0
	 *
	 * @return array Plugin default options.
	 */
	public function get_options() {
		$settings = array(
			'design' => array(
				'title' => __( 'Design', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => $this->get_settings_name()
			),
			'model' => array(
				'title'   => __( 'Model', $this->plugin_slug ),
				'type'    => 'model',
				'section' => 'design',
				'default' => 'default',
				'menu'    => $this->get_settings_name(),
				'options' => array(
					'default',
					'likefb',
					'likefbred',
					'likefbgreen',
					'likefbgray',
					'btnblue',
					'btnred',
					'btngreen',
					'btngray'
				)
			),
			'thumbnail' => array(
				'title'       => __( 'Thumbnail', $this->plugin_slug ),
				'label'       => __( 'Show the featured image of the posts.', $this->plugin_slug ),
				'default'     => '',
				'type'        => 'checkbox',
				'section'     => 'design',
				'menu'        => $this->get_settings_name(),
				'description' => __( 'Note: You must set a featured image for your posts.', $this->plugin_slug )
			)
		);

		return $settings;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Installs default settings on plugin activation.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public static function install() {
		$instance = new self;
		$settings = array();

		foreach ( $instance->get_options() as $key => $value ) {
			if ( 'section' != $value['type'] ) {
				$settings[ $key ] = $value['default'];
			}
		}

		add_option( $instance->get_settings_name(), $settings );
	}

	/**
	 * Register front-end scripts.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function front_end_scripts() {
		if ( is_single() ) {
			$settings = get_option( $this->get_settings_name() );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( $this->plugin_slug, plugins_url( 'assets/css/' . $this->plugin_slug . '.css', __FILE__ ), array(), null );
			wp_enqueue_script( $this->plugin_slug, plugins_url( 'assets/js/' . $this->plugin_slug . '.min.js', __FILE__ ), array( 'jquery' ), null, true );
			wp_localize_script(
				$this->plugin_slug,
				'floating_nextprev_params',
				array(
					'style' => substr( $settings['model'], 0, 3 )
				)
			);
		}
	}

	/**
	 * Display the tabs.
	 *
	 * @since 2.2.0
	 *
	 * @param  string $content Post or page content.
	 *
	 * @return string          Content with socialfblog buttons.
	 */
	public function view() {
		if ( is_single() && apply_filters( $this->get_settings_name() . '_display', true ) ) {
			$settings            = get_option( $this->get_settings_name() );
			$slug                = $this->get_plugin_slug();
			$in_same_cat         = apply_filters( $this->get_settings_name() . '_in_same_cat', false );
			$excluded_categories = apply_filters( $this->get_settings_name() . '_excluded_categories', '' );
			$prev_title          = apply_filters( $this->get_settings_name() . '_prev_title', __( 'Previous', $slug ) );
			$next_title          = apply_filters( $this->get_settings_name() . '_next_title', __( 'Next', $slug ) );
			$prev_post           = get_previous_post( $in_same_cat, $excluded_categories );
			$next_post           = get_next_post( $in_same_cat, $excluded_categories );

			include_once 'views/public.php';
		}
	}
}
