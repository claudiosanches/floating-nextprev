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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
?>

<div id="<?php echo $slug; ?>" class="style-<?php echo sanitize_text_field( $settings['model'] ); ?>">
	<?php if ( $exists_prev_post ) : ?>
	<div class="<?php echo $slug; ?>-prev <?php echo $slug; ?>-nav">
		<a rel="prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
			<div class="<?php echo $slug; ?>-arrow-left"></div>
			<div class="<?php echo $slug; ?>-content">
				<strong><?php echo $prev_title; ?></strong>
				<?php echo ( isset( $settings['thumbnail'] ) ) ? get_the_post_thumbnail( $prev_post->ID, array( 50, 50 ) ) : ''; ?>
				<span><?php echo get_the_title( $prev_post->ID ); ?></span>
			</div>
		</a>
	</div>
	<?php endif; ?>
	<?php if ( $exists_next_post ) : ?>
	<div class="<?php echo $slug; ?>-next <?php echo $slug; ?>-nav">
		<a rel="next" href="<?php echo get_permalink( $next_post->ID ); ?>">
			<div class="<?php echo $slug; ?>-arrow-right"></div>
			<div class="<?php echo $slug; ?>-content">
				<strong><?php echo $next_title; ?></strong>
				<?php echo ( isset( $settings['thumbnail'] ) ) ? get_the_post_thumbnail( $next_post->ID, array( 50, 50 ) ) : ''; ?>
				<span><?php echo get_the_title( $next_post->ID ); ?></span>
			</div>
		</a>
	</div>
	<?php endif; ?>
</div>
