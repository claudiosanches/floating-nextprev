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
        <?php previous_post_link( '%link', '<div class="' . $slug . '-arrow-left"></div><div class="' . $slug . '-content"><strong>' . $prev_title . '</strong><span>%title</span></div>', $in_same_cat, $excluded_categories ); ?>
    </div>
    <div class="<?php echo $slug; ?>-next <?php echo $slug; ?>-nav">
        <?php next_post_link( '%link', '<div class="' . $slug . '-arrow-right"></div><div class="' . $slug . '-content"><strong>' . $next_title . '</strong><span>%title</span></div>', $in_same_cat, $excluded_categories ); ?>
    </div>
</div>
