<?php
/**
 * Admin help message.
 *
 * @package Floating_NextPrev/Admin/Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( apply_filters( 'floating_nextprev_help_message', true ) ) : ?>
	<div class="updated inline">
		<p><?php echo esc_html( sprintf( __( 'Help us keep the %s plugin free making a donation or rate %s on WordPress.org. Thank you in advance!', 'floating-nextprev' ), __( 'Floating NextPrev', 'floating-nextprev' ), '&#9733;&#9733;&#9733;&#9733;&#9733;' ) ); ?></p>
		<p><a href="http://claudiosmweb.com/doacoes/" target="_blank" class="button button-primary"><?php esc_html_e( 'Make a donation', 'floating-nextprev' ); ?></a> <a href="https://wordpress.org/support/view/plugin-reviews/floating-nextprev?filter=5#postform" target="_blank" class="button button-secondary"><?php esc_html_e( 'Make a review', 'floating-nextprev' ); ?></a></p>
	</div>
<?php endif;
