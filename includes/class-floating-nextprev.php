<?php
/**
 * Floating NextPrev.
 *
 * @package Floating_NextPrev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Floating NextPrev class.
 *
 * @package Floating_NextPrev
 */
class Floating_NextPrev {

	/**
	 * Plugin version.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const VERSION = '2.3.0';

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
				'title' => __( 'Design', 'floating-nextprev' ),
				'type'  => 'section',
				'menu'  => 'floating_nextprev',
			),
			'model' => array(
				'title'   => __( 'Model', 'floating-nextprev' ),
				'type'    => 'model',
				'section' => 'design',
				'default' => 'default',
				'menu'    => 'floating_nextprev',
				'options' => array(
					'default',
					'likefb',
					'likefbred',
					'likefbgreen',
					'likefbgray',
					'btnblue',
					'btnred',
					'btngreen',
					'btngray',
				),
			),
			'thumbnail' => array(
				'title'       => __( 'Thumbnail', 'floating-nextprev' ),
				'label'       => __( 'Show the featured image of the posts.', 'floating-nextprev' ),
				'default'     => '',
				'type'        => 'checkbox',
				'section'     => 'design',
				'menu'        => 'floating_nextprev',
				'description' => sprintf( __( 'Note: You must set a %s for your posts.', 'floating-nextprev' ), '<a href="http://codex.wordpress.org/Post_Thumbnails" target="_blank">' . __( 'featured image', 'floating-nextprev' ) . '</a>' ),
			),
		);

		return $settings;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 2.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'floating-nextprev', false, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Installs default settings on plugin activation.
	 *
	 * @since 2.1.0
	 */
	public static function install() {
		$instance = self::get_instance();
		$settings = array();

		foreach ( $instance->get_options() as $key => $value ) {
			if ( 'section' !== $value['type'] ) {
				$settings[ $key ] = $value['default'];
			}
		}

		add_option( 'floating_nextprev', $settings );
	}

	/**
	 * Register front-end scripts.
	 *
	 * @since 2.0.0
	 */
	public function front_end_scripts() {
		if ( is_single() ) {
			$settings = get_option( 'floating_nextprev' );
			$suffix   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( 'floating-nextprev', plugins_url( 'assets/css/frontend/floating-nextprev.css', plugin_dir_path( __FILE__ ) ), array(), null );
			wp_enqueue_script( 'floating-nextprev', plugins_url( 'assets/js/frontend/floating-nextprev' . $suffix . '.js', plugin_dir_path( __FILE__ ) ), array( 'jquery' ), null, true );
			wp_localize_script(
				'floating-nextprev',
				'floating_nextprev_params',
				array(
					'style' => substr( $settings['model'], 0, 3 ),
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
		if ( is_single() && apply_filters( 'floating_nextprev_display', true ) ) {
			$settings            = get_option( 'floating_nextprev' );
			$in_same_cat         = apply_filters( 'floating_nextprev_in_same_cat', false );
			$excluded_categories = apply_filters( 'floating_nextprev_excluded_categories', '' );
			$prev_title          = apply_filters( 'floating_nextprev_prev_title', __( 'Previous', 'floating-nextprev' ) );
			$next_title          = apply_filters( 'floating_nextprev_next_title', __( 'Next', 'floating-nextprev' ) );
			$prev_post           = get_previous_post( $in_same_cat, $excluded_categories );
			$next_post           = get_next_post( $in_same_cat, $excluded_categories );

			include_once dirname( __FILE__ ) . '/views/html-display.php';
		}
	}
}
