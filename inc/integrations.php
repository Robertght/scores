<?php
/**
 * Require files that deal with various plugin integrations.
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

/**
 * Load Customify compatibility file.
 * http://pixelgrade.com/
 */
require pixelgrade_get_parent_theme_file_path( '/inc/integrations/customify.php' );

/**
 * Load Pixelgrade Care compatibility file.
 * http://pixelgrade.com/
 */
require pixelgrade_get_parent_theme_file_path( '/inc/integrations/pixcare.php' );

/**
 * Load Jetpack compatibility file.
 * https://jetpack.me/
 */
require pixelgrade_get_parent_theme_file_path( '/inc/integrations/jetpack.php' );

/**
 * Load PixTypes compatibility file.
 */
require pixelgrade_get_parent_theme_file_path( '/inc/integrations/pixtypes.php' );
