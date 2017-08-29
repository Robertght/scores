<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boilerplate
 * @since Boilerplate 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<script type="text/javascript"> performance.mark( "app:start" ); </script>
</head>

<body <?php body_class(); ?> <?php pixelgrade_body_attributes(); ?>>

<?php
/**
 * pixelgrade_after_body_open hook.
 *
 * @hooked nothing() - 10 (outputs nothings)
 */
do_action( 'pixelgrade_after_body_open', 'main' );
?>

<div id="barba-wrapper" class="site  u-wrap-text  js-header-height-padding-top">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'boilerplate' ); ?></a>

	<?php
	/**
	 * pixelgrade_before_header hook.
	 *
	 * @hooked nothing() - 10 (outputs nothing)
	 */
	do_action( 'pixelgrade_before_header', 'main' );
	?>

	<?php
	/**
	 * pixelgrade_header hook.
	 *
	 * @hooked pixelgrade_the_header() - 10 (outputs the header markup)
	 */
	do_action( 'pixelgrade_header', 'main' );
	?>

	<?php
	/**
	 * pixelgrade_after_header hook.
	 *
	 * @hooked nothing() - 10 (outputs nothing)
	 */
	do_action( 'pixelgrade_after_header', 'main' );
	?>

	<div id="content" class="site-content barba-container u-content-background">
