<?php
/**
 * Floating NextPrev administration view.
 *
 * @package   Floating_NextPrev_Admin
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @link      https://github.com/claudiosmweb/floating-nextprev
 * @copyright 2013 Claudio Sanches
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
		<?php
			settings_fields( $settings_name );
			do_settings_sections( $settings_name );
			submit_button();
		?>
	</form>

</div>
