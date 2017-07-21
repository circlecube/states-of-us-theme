<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package USA States
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function states_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'states_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function states_jetpack_setup
add_action( 'after_setup_theme', 'states_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function states_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function states_infinite_scroll_render
