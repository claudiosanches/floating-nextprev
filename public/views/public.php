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
	<div class="<?php echo $slug; ?>-prev <?php echo $slug; ?>-nav">
		<a rel="prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
			<div class="<?php echo $slug; ?>-arrow-left"></div>
			<div class="<?php echo $slug; ?>-content">
				<strong><?php echo $prev_title; ?></strong>
				<span><?php echo get_the_title( $prev_post->ID ); ?></span>
			</div>
		</a>
	</div>
	<div class="<?php echo $slug; ?>-next <?php echo $slug; ?>-nav">
		<a rel="next" href="<?php echo get_permalink( $next_post->ID ); ?>">
			<div class="<?php echo $slug; ?>-arrow-right"></div>
			<div class="<?php echo $slug; ?>-content">
				<strong><?php echo $next_title; ?></strong>
				<span><?php echo get_the_title( $next_post->ID ); ?></span>
			</div>
		</a>
	</div>
</div>
