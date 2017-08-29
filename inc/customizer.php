<?php
/**
 * boilerplate Theme Customizer.
 *
 * @package Boilerplate
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function boilerplate_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title',
			'render_callback' => 'boilerplate_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description-text',
			'render_callback' => 'boilerplate_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', 'boilerplate_customize_register' );

/* =========================
 * SANITIZATION FOR SETTINGS - EXAMPLES
 * ========================= */

/**
 * Sanitize the header position options.
 */
function boilerplate_sanitize_header_position( $input ) {
	$valid = array(
		'static' => esc_html__( 'Static', 'noah' ),
		'sticky' => esc_html__( 'Sticky (fixed)', 'noah' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean true if is 1 or '1', false if anything else
 */
function boilerplate_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/* ============================
 * Customizer rendering helpers
 * ============================ */

/**
 * Render the site title for the selective refresh partial.
 *
 * @see boilerplate_customize_register()
 *
 * @return void
 */
function boilerplate_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @see boilerplate_customize_register()
 *
 * @return void
 */
function boilerplate_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function boilerplate_customize_preview_js() {
	wp_enqueue_script( 'boilerplate_customizer', pixelgrade_get_theme_file_uri( '/js/customizer.js' ), array( 'customize-preview' ), '20170701', true );
}
add_action( 'customize_preview_init', 'boilerplate_customize_preview_js' );
