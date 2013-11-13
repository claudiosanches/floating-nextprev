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
     * @since 2.0.0
     */
    private function __construct() {

        $this->main_plugin   = Floating_NextPrev::get_instance();
        $this->plugin_slug   = $this->main_plugin->get_plugin_slug();
        $this->settings_name = $this->main_plugin->get_settings_name();

        // Adds admin menu.
        add_action( 'admin_menu', array( $this, 'menu' ) );

        // Init plugin options form.
        add_action( 'admin_init', array( $this, 'plugin_settings' ) );

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
    }

    /**
     * Return an instance of this class.
     *
     * @since 2.0.0
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$instance )
            self::$instance = new self;

        return self::$instance;
    }

    /**
     * Update plugin settings.
     * Makes upgrades of legacy versions.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function update() {
        if ( get_option( 'fnextprev_style' ) ) {
            $settings = array(
                'model' => get_option( 'fnextprev_style' )
            );

            // Updates options
            update_option( $this->settings_name, $settings );

            // Removes old options.
            delete_option( 'fnextprev_style' );
        } else {
            // Install default options.
            $settings = array();

            foreach ( $this->main_plugin->get_options() as $key => $value ) {
                if ( 'section' != $value['type'] )
                    $settings[ $key ] = $value['default'];
            }

            add_option( $this->settings_name, $settings );
        }
    }

    /**
     * Add plugin settings menu.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function menu() {
        add_options_page(
            __( 'Floating NextPrev', $this->plugin_slug ),
            __( 'Floating NextPrev', $this->plugin_slug ),
            'manage_options',
            $this->plugin_slug,
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
        // Create tabs current class.
        $current_tab = '';
        if ( isset( $_GET['tab'] ) )
            $current_tab = $_GET['tab'];
        else
            $current_tab = 'buttons';
        ?>

        <div class="wrap">
            <?php screen_icon( 'options-general' ); ?>
            <h2><?php _e( 'Floating NextPrev', $this->plugin_slug ); ?></h2>

            <form method="post" action="options.php">
                <?php
                    settings_fields( $this->settings_name );
                    do_settings_sections( $this->settings_name );
                    submit_button();
                ?>
            </form>
        </div>

        <?php
    }

    /**
     * Plugin settings form fields.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function plugin_settings() {
        // Create option in wp_options.
        if ( false == get_option( $this->settings_name ) )
            $this->update();

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
                case 'text':
                    add_settings_field(
                        $key,
                        $value['title'],
                        array( $this, 'text_element_callback' ),
                        $value['menu'],
                        $value['section'],
                        array(
                            'menu' => $value['menu'],
                            'id' => $key,
                            'class' => 'regular-text',
                            'description' => isset( $value['description'] ) ? $value['description'] : ''
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
                            'description' => isset( $value['description'] ) ? $value['description'] : ''
                        )
                    );
                    break;
                case 'select':
                    add_settings_field(
                        $key,
                        $value['title'],
                        array( $this, 'select_element_callback' ),
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

                default:
                    break;
            }

        }

        // Register settings.
        register_setting( $this->settings_name, $this->settings_name, array( $this, 'validate_options' ) );
    }

    /**
     * Text element fallback.
     *
     * @since 2.0.0
     *
     * @param  array $args Field arguments.
     *
     * @return string      Text field.
     */
    public function text_element_callback( $args ) {
        $menu  = $args['menu'];
        $id    = $args['id'];
        $class = isset( $args['class'] ) ? $args['class'] : 'small-text';

        $options = get_option( $menu );

        if ( isset( $options[ $id ] ) )
            $current = $options[ $id ];
        else
            $current = isset( $args['default'] ) ? $args['default'] : '';

        $html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" />', $id, $menu, $current, $class );

        // Displays option description.
        if ( isset( $args['description'] ) )
            $html .= sprintf( '<p class="description">%s</p>', $args['description'] );

        echo $html;
    }

    /**
     * Checkbox field fallback.
     *
     * @since 2.0.0
     *
     * @param  array $args Field arguments.
     *
     * @return string      Checkbox field.
     */
    public function checkbox_element_callback( $args ) {
        $menu = $args['menu'];
        $id   = $args['id'];

        $options = get_option( $menu );

        if ( isset( $options[ $id ] ) )
            $current = $options[ $id ];
        else
            $current = isset( $args['default'] ) ? $args['default'] : '';

        $html = sprintf( '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1"%3$s />', $id, $menu, checked( 1, $current, false ) );

        $html .= sprintf( '<label for="%s"> %s</label><br />', $id, __( 'Activate/Deactivate', $this->plugin_slug ) );

        // Displays option description.
        if ( isset( $args['description'] ) )
            $html .= sprintf( '<p class="description">%s</p>', $args['description'] );

        echo $html;
    }

    /**
     * Select element fallback.
     *
     * @since 2.0.0
     *
     * @param  array $args Field arguments.
     *
     * @return string      Select field.
     */
    function select_element_callback( $args ) {
        $menu = $args['menu'];
        $id   = $args['id'];

        $options = get_option( $menu );

        if ( isset( $options[ $id ] ) )
            $current = $options[ $id ];
        else
            $current = isset( $args['default'] ) ? $args['default'] : '#ffffff';

        $html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $menu );
        $key = 0;
        foreach ( $args['options'] as $label ) {
            $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $label );

            $key++;
        }
        $html .= '</select>';

        // Displays option description.
        if ( isset( $args['description'] ) )
            $html .= sprintf( '<p class="description">%s</p>', $args['description'] );

        echo $html;
    }

    /**
     * Model element fallback.
     *
     * @since 2.0.0
     *
     * @param  array $args Field arguments.
     *
     * @return string      Select field.
     */
    function model_element_callback( $args ) {
        $menu = $args['menu'];
        $id   = $args['id'];

        $options = get_option( $menu );

        if ( isset( $options[ $id ] ) )
            $current = $options[ $id ];
        else
            $current = isset( $args['default'] ) ? $args['default'] : '';

        $html = '';
        foreach (  $args['options'] as $option ) {
            $example = plugins_url( 'assets/images/examples/' .  $option . '.jpg', dirname( __FILE__ ) );

            $html .= sprintf( '<label style="display: block; margin-bottom: 5px;"><input type="radio" name="%2$s[%1$s]" value="%3$s"%4$s /> <img src="%5$s" style="vertical-align: middle;" /></label>', $id, $menu, $option, checked( $current, $option, false ), $example );
        }

        // Displays option description.
        if ( isset( $args['description'] ) )
            $html .= sprintf( '<p class="description">%s</p>', $args['description'] );

        echo $html;
    }

    /**
     * Valid options.
     *
     * @since 2.0.0
     *
     * @param  array $input options to valid.
     *
     * @return array        validated options.
     */
    public function validate_options( $input ) {
        $output = array();

        foreach ( $input as $key => $value ) {
            if ( isset( $input[ $key ] ) )
                $output[ $key ] = sanitize_text_field( $input[ $key ] );
        }

        return $output;
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since 2.0.0
     *
     * @return array
     */
    public function add_action_links( $links ) {
        return array_merge( array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>' ), $links );
    }
}
