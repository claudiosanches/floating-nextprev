<?php
/**
 * Plugin Name: Floating NextPrev
 * Plugin URI: https://github.com/claudiosmweb/floating-nextprev
 * Description: Displays icons for navigation between your posts so practical and fast.
 * Version: 2.3.0
 * Author: Claudio Sanches
 * Author URI: https://claudiosmweb.com/
 * Text Domain: floating-nextprev
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/claudiosmweb/floating-nextprev
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once dirname( __FILE__ ) . '/includes/class-floating-nextprev.php';

/**
 * Install default settings.
 */
register_activation_hook( __FILE__, array( 'Floating_NextPrev', 'install' ) );

/**
 * Load plugin instance.
 */
add_action( 'plugins_loaded', array( 'Floating_NextPrev', 'get_instance' ) );

/**
 * Plugin admin.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/includes/admin/class-floating-nextprev-admin.php';

	add_action( 'plugins_loaded', array( 'Floating_NextPrev_Admin', 'get_instance' ) );
}
