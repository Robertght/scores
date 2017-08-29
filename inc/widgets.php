<?php
/**
 * Handles the definition of sidebars and the loading of various widgets
 *
 * @package Boilerplate
 * @since Boilerplate 1.0.0
 */

/**
 * Register the widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function boilerplate_widgets_init() {
	/**
	 * The main widget area
	 */
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'boilerplate' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'boilerplate' ),
		'before_widget' => '<section id="%1$s" class="c-widget c-widget--side %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="c-widget__title h3">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'boilerplate_widgets_init', 10 );
