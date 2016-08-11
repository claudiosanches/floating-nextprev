<?php
/**
 * Floating NextPrev view.
 *
 * @package   Floating_NextPrev
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @link      https://github.com/claudiosmweb/floating-nextprev
 * @copyright 2013 Claudio Sanches
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div id="floating-nextprev" class="style-<?php echo sanitize_text_field( $settings['model'] ); ?>">
	<div class="floating-nextprev-prev floating-nextprev-nav">
		<a rel="prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
			<div class="floating-nextprev-arrow-left"></div>
			<div class="floating-nextprev-content">
				<strong><?php echo $prev_title; ?></strong>
				<?php echo ( isset( $settings['thumbnail'] ) ) ? get_the_post_thumbnail( $prev_post->ID, array( 50, 50 ) ) : ''; ?>
				<span><?php echo get_the_title( $prev_post->ID ); ?></span>
			</div>
		</a>
	</div>
	<div class="floating-nextprev-next floating-nextprev-nav">
		<a rel="next" href="<?php echo get_permalink( $next_post->ID ); ?>">
			<div class="floating-nextprev-arrow-right"></div>
			<div class="floating-nextprev-content">
				<strong><?php echo $next_title; ?></strong>
				<?php echo ( isset( $settings['thumbnail'] ) ) ? get_the_post_thumbnail( $next_post->ID, array( 50, 50 ) ) : ''; ?>
				<span><?php echo get_the_title( $next_post->ID ); ?></span>
			</div>
		</a>
	</div>
</div>
