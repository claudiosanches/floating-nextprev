<?php
/**
 * Uninstall actions.
 *
 * @package Floating_NextPrev
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove plugin options.
delete_option( 'floating_nextprev' );
