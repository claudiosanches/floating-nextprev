# Floating NextPrev #
**Contributors:** [claudiosanches](https://profiles.wordpress.org/claudiosanches)  
**Donate link:** http://claudiosmweb.com/doacoes/  
**Tags:** floating, navigation, jquery  
**Requires at least:** 3.8  
**Tested up to:** 4.6  
**Stable tag:** 2.3.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Displays icons for navigation between your posts so practical and fast.

## Description ##

The Floating NextPrev displays buttons for navigation between your blog posts.
The icons are displayed only within the pages of posts and can promote a better navigation for your visitors.

#### Contribute ####

You can contribute to the source code in our [GitHub](https://github.com/claudiosmweb/floating-nextprev) page.

## Installation ##

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* Navigate to Settings -> Floating NextPrev and select the design model.

## Frequently Asked Questions ##

### What is the plugin license? ###

* This plugin is released under a GPL license.

### Why the plugin is not working? ###

* Probably due to incompatibility of jQuery. Check to see if this plugin modifying the jQuery version of your WordPress.

### How navigating only in posts in the same category? ###

Paste this code in `functions.php` file of your theme:

	add_filter( 'floating_nextprev_in_same_cat', '__return_true' );

### How to hide in some categories? ###

Use this example in `functions.php` file of your theme:

	function floating_nextprev_exclude_categories( $excluded_categories ) {
		$excluded_categories = '1,2,3'; // add your categories ids.

		return excluded_categories;
	}

	add_filter( 'floating_nextprev_excluded_categories', 'floating_nextprev_exclude_categories' );

### How can I control the display? ###

You can control the display using the `floating_nextprev_display` filter.
Example how to display only in news category:

	function floating_nextprev_only_in_news( $display ) {
		return in_category( 'news' );
	}

	add_filter( 'floating_nextprev_display', 'floating_nextprev_only_in_news' );

### How to change the "Previous" and "Next" titles? ###

Use the `floating_nextprev_prev_title` and `floating_nextprev_next_title` filters.
Example:

	function floating_nextprev_prev_title( $title ) {
		return __( '&larr;', 'textdomain' );
	}

	add_filter( 'floating_nextprev_prev_title', 'floating_nextprev_prev_title' );

	function floating_nextprev_next_title( $title ) {
		return __( '&rarr;', 'textdomain' );
	}

	add_filter( 'floating_nextprev_next_title', 'floating_nextprev_next_title' );

## Screenshots ##

### 1. Plugin in action. ###
![Plugin in action.](http://ps.w.org/floating-nextprev/assets/screenshot-1.png)

### 2. Plugin settings. ###
![Plugin settings.](http://ps.w.org/floating-nextprev/assets/screenshot-2.png)


## Changelog ##

### 2.3.0 - 2016/08/10 ###

* Improved how check for the first and last pages in order to avoid wrong arrow buttons.

## License ##
