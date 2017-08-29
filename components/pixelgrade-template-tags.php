<?php
/**
 * Various Pixelgrade components template tags.
 *
 * @see 	    https://pixelgrade.com
 * @author 		Pixelgrade
 * @package     Components
 * @version     1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Display the attributes for the body element.
 *
 * @param string|array $attribute One or more attributes to add to the attributes list.
 */
function pixelgrade_body_attributes( $attribute = '' ) {
	//get the attributes
	$attributes = pixelgrade_get_body_attributes( $attribute );

	//generate a string attributes array, like array( 'rel="test"', 'href="boom"' )
	$full_attributes = array();
	foreach ($attributes as $name => $value ) {
		//we really don't want numeric keys as attributes names
		if ( ! empty( $name ) && ! is_numeric( $name ) ) {
			//if we get an array as value we will add them comma separated
			if ( ! empty( $value ) && is_array( $value ) ) {
				$value = join( ', ', $value );
			}

			//if we receive an empty array entry (but with a key) we will treat it like an attribute without value (i.e. itemprop)
			if ( empty( $value ) ) {
				$full_attributes[] = $name;
			} else {
				$full_attributes[] = $name . '="' . esc_attr( $value ) . '"';
			}
		}
	}

	if ( ! empty( $full_attributes ) ) {
		echo join( ' ', $full_attributes );
	}
}

function pixelgrade_get_body_attributes( $attribute = array() ) {
	$attributes = array();

	if ( ! empty( $attribute ) ) {
		$attributes = array_merge( $attributes, $attribute );
	} else {
		// Ensure that we always coerce class to being an array.
		$attribute = array();
	}

	/**
	 * Filters the list of body attributes for the current post or page.
	 *
	 * @since 2.8.0
	 *
	 * @param array $attributes An array of body attributes.
	 * @param array $attribute  An array of additional attributes added to the body.
	 */
	return apply_filters( 'pixelgrade_body_attributes', $attributes, $attribute );
}

/**
 * Display the classes for a element.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 * @param string $prefix Optional. Prefix to prepend to all of the provided classes
 * @param string $suffix Optional. Suffix to append to all of the provided classes
 */
function pixelgrade_css_class( $class = '', $location = '', $prefix = '', $suffix = '' ) {
	// Separates classes with a single space, collates classes for element
	echo 'class="' . join( ' ', pixelgrade_get_css_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for a element as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 * @param string $prefix Optional. Prefix to prepend to all of the provided classes
 * @param string $suffix Optional. Suffix to append to all of the provided classes
 *
 * @return array Array of classes.
 */
function pixelgrade_get_css_class( $class = '', $location = '', $prefix = '', $suffix = '' ) {
	$classes = array();

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		//if we have a prefix then we need to add it to every class
		if ( ! empty( $prefix ) && is_string( $prefix ) ) {
			foreach ( $class as $key => $value ) {
				$class[ $key ] = $prefix . $value;
			}
		}

		//if we have a suffix then we need to add it to every class
		if ( ! empty( $suffix ) && is_string( $suffix ) ) {
			foreach ( $class as $key => $value ) {
				$class[ $key ] = $value . $suffix;
			}
		}

		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filters the list of CSS header classes for the current post or page
	 *
	 * @param array $classes An array of header classes.
	 * @param array $class   An array of additional classes added to the header.
	 * @param string|array $location   The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'pixelgrade_css_class', $classes, $class, $location, $prefix, $suffix );

	return array_unique( $classes );
}

/**
 * Retrieve the aspect ratio type of an image.
 *
 * @param int|WP_Post $image The image attachment ID or the attachment object.
 * @param bool|string Optional. The default to return in case of failure.
 *
 * @return string|bool Returns the aspect ratio type string, or false|$default, if no image is available.
 */
function pixelgrade_get_image_aspect_ratio_type( $image, $default = false ) {
	// We expect to receive an attachment ID or attachment post object
	if ( is_numeric( $image ) ) {
		// In case we've got a number, we will coerce it to an int
		$image = (int) $image;
	}

	// Try and get the attachment post object
	if ( ! $image = get_post( $image ) ) {
		return $default;
	}

	// We only work with real images
	if ( ! wp_attachment_is_image( $image ) ) {
		return $default;
	}

	//$image_data[1] is width
	//$image_data[2] is height
	// we use the full image size to avoid the Photon messing around with the data - at least for now
	$image_data = wp_get_attachment_image_src( $image->ID, 'full' );

	if ( empty( $image_data ) ) {
		return $default;
	}

	// We default to a landscape aspect ratio
	$type = 'landscape';
	if ( ! empty( $image_data[1] ) && ! empty( $image_data[2] ) ) {
		$image_aspect_ratio = $image_data[1] / $image_data[2];

		// now let's begin to see what kind of featured image we have
		// first portrait images
		if ( $image_aspect_ratio <= 1 ) {
			$type = 'portrait';
		}
	}

	return apply_filters( 'pixelgrade_image_aspect_ratio_type', $type, $image );
}
