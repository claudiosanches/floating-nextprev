<?php
/**
 * Floating NextPrev.
 *
 * @package   Floating_NextPrev_Admin
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @link      https://github.com/claudiosmweb/floating-nextprev
 * @copyright 2013 Claudio Sanches
 */

/**
 * Floating NextPrev Admin class.
 *
 * @package Floating_NextPrev_Admin
 * @author  Claudio Sanches <contato@claudiosmweb.com>
 */
class Floating_NextPrev_Admin {

	/**
	 * Settings name.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $settings_name = 'floating_nextprev';

	/**
	 * Instance of this class.
	 *
	 * @since 2.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 2.1.0
	 */
	private function __construct() {
		$this->main_plugin = Floating_NextPrev::get_instance();

		// Adds admin menu.
		add_action( 'admin_menu', array( $this, 'menu' ) );

		// Init plugin options form.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );
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
	 * Update plugin settings.
	 * Makes upgrades of legacy versions.
	 *
	 * @since 2.1.0
	 */
	public function update() {
		if ( get_option( 'fnextprev_style' ) ) {
			$settings = array(
				'model' => get_option( 'fnextprev_style' )
			);

			// Updates options
			update_option( 'floating_nextprev', $settings );

			// Removes old options.
			delete_option( 'fnextprev_style' );
		} else {
			// Install default options.
			$settings = array();

			foreach ( $this->main_plugin->get_options() as $key => $value ) {
				if ( 'section' != $value['type'] ) {
					$settings[ $key ] = $value['default'];
				}
			}

			add_option( 'floating_nextprev', $settings );
		}
	}

	/**
	 * Add plugin settings menu.
	 *
	 * @since 2.0.0
	 */
	public function menu() {
		add_options_page(
			__( 'Floating NextPrev', 'floating-nextprev' ),
			__( 'Floating NextPrev', 'floating-nextprev' ),
			'manage_options',
			'floating-nextprev',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Plugin settings page.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function settings_page() {
		include_once dirname( __FILE__ ) . '/views/html-settings.php';
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @since 2.2.0
	 */
	public function plugin_settings() {
		// Create option in wp_options.
		if ( false == get_option( 'floating_nextprev' ) ) {
			$this->update();
		}

		foreach ( $this->main_plugin->get_options() as $key => $value ) {

			switch ( $value['type'] ) {
				case 'section':
					add_settings_section(
						$key,
						$value['title'],
						'__return_false',
						$value['menu']
					);
					break;
				case 'model':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'model_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu'        => $value['menu'],
							'id'          => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : '',
							'options'     => $value['options']
						)
					);
					break;
				case 'checkbox':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'checkbox_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu'        => $value['menu'],
							'id'          => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : '',
							'label'       => isset( $value['label'] ) ? $value['label'] : '',
						)
					);
					break;

				default:
					break;
			}

		}

		// Register settings.
		register_setting( 'floating_nextprev', 'floating_nextprev', array( $this, 'validate_options' ) );
	}

	/**
	 * Model element fallback.
	 *
	 * @since 2.1.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Radio field group with images.
	 */
	public function model_element_callback( $args ) {
		$menu = $args['menu'];
		$id   = $args['id'];

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '';
		}

		$html = '';
		foreach ( $args['options'] as $option ) {
			$example = plugins_url( 'assets/images/admin/' .  $option . '.png', plugin_dir_path( dirname( __FILE__ ) ) );

			$html .= sprintf( '<label style="display: block; margin-bottom: 5px;"><input type="radio" name="%2$s[%1$s]" value="%3$s"%4$s /> <img src="%5$s" style="vertical-align: middle;" /></label>', $id, $menu, $option, checked( $current, $option, false ), $example );
		}

		// Displays option description.
		if ( isset( $args['description'] ) ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Checkbox element fallback.
	 *
	 * @since 2.2.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Checkbox field.
	 */
	public function checkbox_element_callback( $args ) {
		$menu  = $args['menu'];
		$id    = $args['id'];
		$label = $args['label'];

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '';
		}

		$html = sprintf( '<label><input type="checkbox" name="%2$s[%1$s]" value="1" %3$s /> %4$s</label>', $id, $menu, checked( 1, $current, false ), $label );

		// Displays option description.
		if ( isset( $args['description'] ) ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Valid options.
	 *
	 * @since 2.1.0
	 *
	 * @param  array $input options to valid.
	 *
	 * @return array        validated options.
	 */
	public function validate_options( $input ) {
		$output = array();

		foreach ( $input as $key => $value ) {
			if ( isset( $input[ $key ] ) ) {
				$output[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		return $output;
	}
}
