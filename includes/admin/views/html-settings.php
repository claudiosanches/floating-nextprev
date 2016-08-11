<?php
/**
 * Floating NextPrev settings view.
 *
 * @package Floating_NextPrev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'floating_nextprev' );
			do_settings_sections( 'floating_nextprev' );
			submit_button();
		?>
	</form>

</div>
