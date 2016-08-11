<?php
/**
 * Floating NextPrev view.
 *
 * @package Floating_NextPrev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div id="floating-nextprev" class="style-<?php echo esc_attr( $settings['model'] ); ?>">
	<?php if ( isset( $prev_post->ID ) && $prev_post->ID !== get_the_ID() ) : ?>
		<div class="floating-nextprev-prev floating-nextprev-nav">
			<a rel="prev" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
				<div class="floating-nextprev-arrow-left"></div>
				<div class="floating-nextprev-content">
					<strong><?php echo esc_html( $prev_title ); ?></strong>
					<?php echo ( isset( $settings['thumbnail'] ) ) ? get_the_post_thumbnail( $prev_post->ID, array( 50, 50 ) ) : ''; ?>
					<span><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
				</div>
			</a>
		</div>
	<?php endif; ?>

	<?php if ( isset( $next_post->ID ) && $next_post->ID !== get_the_ID() ) : ?>
		<div class="floating-nextprev-next floating-nextprev-nav">
			<a rel="next" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
				<div class="floating-nextprev-arrow-right"></div>
				<div class="floating-nextprev-content">
					<strong><?php echo esc_html( $next_title ); ?></strong>
					<?php echo ( isset( $settings['thumbnail'] ) ) ? get_the_post_thumbnail( $next_post->ID, array( 50, 50 ) ) : ''; ?>
					<span><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
				</div>
			</a>
		</div>
	<?php endif; ?>
</div>
