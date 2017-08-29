<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Boilerplate
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function boilerplate_body_classes( $classes ) {
	//bail if we are in the admin area
	if ( is_admin() ) {
		return $classes;
	}

	$classes[] = 'u-content-background';

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	$border = pixelgrade_option( 'main_content_border_width' );
	if ( ! empty( $border ) && intval( $border ) > 0 ) {
		$classes[] = 'has-border';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	//Add a class to the body for the full width page templates
	if ( is_page() && is_page_template( 'page-templates/full-width.php' ) ) {
		$classes[] = 'full-width';
	}

	if ( is_singular() && ! is_attachment() ) {
		$classes[] =  'singular';
	}

	if ( is_single() ) {
		$image_orientation = boilerplate_get_post_thumbnail_aspect_ratio_class();

		if ( ! empty( $image_orientation ) ) {
			$classes[] = 'entry-image--' . boilerplate_get_post_thumbnail_aspect_ratio_class();
		}
	}

	$header_position = pixelgrade_option( 'header_position' );
	if ( 'sticky' == $header_position || empty( $header_position ) ) {
		$classes[] = 'u-site-header-sticky';
	}

	$header_width = pixelgrade_option( 'header_width' );
	if ( 'full' == $header_width || empty( $header_width ) ) {
		$classes[] = 'u-site-header-full-width';
	}

	if ( 'stacked' == pixelgrade_option( 'footer_layout' ) ) {
		$classes[] = 'u-footer-layout-stacked';
	}

	if ( ! empty( pixelgrade_option( 'main_content_underlined_body_links', true, true ) ) ) {
		$classes[] = 'u-underlined-links';
	}

	if ( 'underline' == pixelgrade_option( 'header_links_active_style' ) ) {
		$classes[] = 'u-underlined-header-links';
	}

	$header_text = get_theme_mod( 'header_text' );
	if ( empty( $header_text ) ) {
		$classes[] = 'site-title-hidden';
	}

	if ( is_customize_preview() ) {
		$classes[] = 'is-customizer-preview';
	}

	return $classes;
}
add_filter( 'body_class', 'boilerplate_body_classes' );

function boilerplate_body_attributes( $attributes ) {
	if ( pixelgrade_option( 'use_ajax_loading' ) ) {
		$attributes[ 'data-ajaxloading' ] = '';
	}

	if ( pixelgrade_option( 'main_color' ) ) {
		$attributes[ 'data-color' ] = pixelgrade_option( 'main_color' );
	}

	$attributes[ 'data-parallax' ] = pixelgrade_option( 'parallax_amount' );

	//we use this so we can generate links with post id
	//right now we use it to change the Edit Post link in the admin bar
	if ( ( pixelgrade_option( 'use_ajax_loading' ) == 1 ) ) {
		global $wp_the_query;
		$current_object = $wp_the_query->get_queried_object();

		if ( ! empty( $current_object->post_type )
		     && ( $post_type_object = get_post_type_object( $current_object->post_type ) )
		     && current_user_can( 'edit_post', $current_object->ID )
		     && $post_type_object->show_ui && $post_type_object->show_in_admin_bar ) {

			$attributes[ 'data-curpostid' ] = $current_object->ID;
			if ( isset( $post_type_object->labels ) && isset( $post_type_object->labels->edit_item ) ) {
				$attributes[ 'data-curpostedit' ] = $post_type_object->labels->edit_item;
			}
		} elseif ( ! empty( $current_object->taxonomy )
		           && ( $tax = get_taxonomy( $current_object->taxonomy ) )
		           && current_user_can( $tax->cap->edit_terms )
		           && $tax->show_ui ) {
			$attributes[ 'data-curpostid' ] = $current_object->term_id;
			$attributes[ 'data-curtaxonomy' ] = $current_object->taxonomy;

			if ( isset( $tax->labels ) && isset( $tax->labels->edit_item ) ) {
				$attributes[ 'data-curpostedit' ] = $tax->labels->edit_item;
			}
		} elseif ( is_page_template( 'page-templates/portfolio-archive.php' ) ) {
			$post_type_object = get_post_type_object( 'page' );
			$attributes[ 'data-curpostid' ] = $current_object->ID;
			if (isset($post_type_object->labels) && isset($post_type_object->labels->edit_item)) {
				$attributes[ 'data-curpostedit' ] = $post_type_object->labels->edit_item;
			}
		}
	}

	return $attributes;
}
add_filter( 'pixelgrade_body_attributes', 'boilerplate_body_attributes', 10, 1 );

/**
 * Add custom classes for individual posts
 *
 * @since Boilerplate 1.0
 *
 * @param array $classes
 *
 * @return array
 */
function boilerplate_post_classes( $classes ) {
	//we first need to know the bigger picture - the location this template part was loaded from
	$location = pixelgrade_get_location();

	// This means we are displaying the blog loop
	if ( pixelgrade_in_location( 'index blog post portfolio jetpack', $location, false ) && ! is_single() ) {
		$classes[] = 'c-gallery__item';

		// get info about the width and height of the image
		$image_data = wp_get_attachment_image_src( get_post_thumbnail_id(), "full" );

		// if the image is landscape double its width
		$is_landscape = $image_data[1] > $image_data[2];

		// $classes[] = 'u-width-' . $columns * 25 . '-@desk';
		$classes[] = 'c-gallery__item--' . ( $is_landscape ? 'landscape' : 'portrait' );
	}

	//Add a class to the post for the full width page templates
	if ( is_page() && pixelgrade_in_location( 'full-width', $location ) ) {
		$classes[] = 'full-width';
	}

	return $classes;
}
add_filter( 'post_class', 'boilerplate_post_classes' );

/**
 * Display the classes for the blog wrapper.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 */
function boilerplate_blog_class( $class = '', $location = '' ) {
	// Separates classes with a single space, collates classes
	echo 'class="' . join( ' ', boilerplate_get_blog_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for the portfolio wrapper as an array.
 *
 * @since Boilerplate 1.0
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function boilerplate_get_blog_class( $class = '', $location = '' ) {

	$classes = array();

	$classes[] = 'c-gallery c-gallery--blog';
	// layout
	$grid_layout = pixelgrade_option( 'blog_grid_layout', 'regular' );
	$grid_layout_class = "c-gallery--" . $grid_layout;

	if ( in_array( $grid_layout, array( 'packed', 'regular', 'mosaic' ) ) ) {
		$grid_layout_class .= ' c-gallery--cropped';
	}

	if ( 'mosaic' === $grid_layout ) {
		$grid_layout_class .= ' c-gallery--regular';
	}

	$classes[] = $grid_layout_class;

	// items per row
	$columns_at_desk = intval( pixelgrade_option( 'blog_items_per_row', 3 ) );
	$columns_at_lap = $columns_at_desk >= 5 ? $columns_at_desk - 1 : $columns_at_desk;
	$columns_at_small = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;
	$columns_class = 'o-grid--' . $columns_at_desk . 'col-@desk o-grid--' . $columns_at_lap . 'col-@lap o-grid--' . $columns_at_small . 'col-@small';

	// title position
	$title_position = pixelgrade_option( 'blog_items_title_position', 'regular' );
	$title_position_class = 'c-gallery--title-' . $title_position;

	if ( $title_position == 'overlay' ) {
		$title_alignment_class = 'c-gallery--title-' . pixelgrade_option( 'blog_items_title_alignment_overlay', 'bottom-left' );
	} else {
		$title_alignment_class = 'c-gallery--title-' . pixelgrade_option( 'blog_items_title_alignment_nearby', 'left' );
	}

	$classes[] = $title_position_class;
	$classes[] = $title_alignment_class;
	$classes[] = $columns_class;

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filters the list of CSS classes for the blog wrapper.
	 *
	 * @param array $classes An array of header classes.
	 * @param array $class   An array of additional classes added to the blog wrapper.
	 * @param string|array $location   The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'boilerplate_blog_class', $classes, $class, $location );

	return array_unique( $classes );
}

/**
 * Display the classes for the portfolio wrapper.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 */
function boilerplate_portfolio_class( $class = '', $location = '' ) {
	// Separates classes with a single space, collates classes
	echo 'class="' . join( ' ', boilerplate_get_portfolio_class( $class, $location ) ) . '"';
}

/**
 * Retrieve the classes for the portfolio wrapper as an array.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function boilerplate_get_portfolio_class( $class = '', $location = '' ) {

	$classes = array();

	$classes[] = 'c-gallery c-gallery--portfolio';
	// layout
	$grid_layout = pixelgrade_option( 'portfolio_grid_layout', 'regular' );
	$grid_layout_class = "c-gallery--" . $grid_layout;

	if ( in_array( $grid_layout, array( 'packed', 'regular', 'mosaic' ) ) ) {
		$grid_layout_class .= ' c-gallery--cropped';
	}

	if ( 'mosaic' === $grid_layout ) {
		$grid_layout_class .= ' c-gallery--regular';
	}

	$classes[] = $grid_layout_class;

	// items per row
	$columns_at_desk = intval( pixelgrade_option( 'portfolio_items_per_row', 3 ) );
	$columns_at_lap = $columns_at_desk >= 5 ? $columns_at_desk - 1 : $columns_at_desk;
	$columns_at_small = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;
	$columns_class = 'o-grid--' . $columns_at_desk . 'col-@desk o-grid--' . $columns_at_lap . 'col-@lap o-grid--' . $columns_at_small . 'col-@small';

	// title position
	$title_position = pixelgrade_option( 'portfolio_items_title_position', 'regular' );
	$title_position_class = 'c-gallery--title-' . $title_position;

	if ( $title_position == 'overlay' ) {
		$title_alignment_class = 'c-gallery--title-' . pixelgrade_option( 'portfolio_items_title_alignment_overlay', 'bottom-left' );
	} else {
		$title_alignment_class = 'c-gallery--title-' . pixelgrade_option( 'portfolio_items_title_alignment_nearby', 'left' );
	}

	$classes[] = $title_position_class;
	$classes[] = $title_alignment_class;
	$classes[] = $columns_class;

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filters the list of CSS classes for the portfolio wrapper.
	 *
	 * @param array $classes An array of header classes.
	 * @param array $class   An array of additional classes added to the portfolio wrapper.
	 * @param string|array $location   The place (template) where the classes are displayed.
	 */
	$classes = apply_filters( 'boilerplate_portfolio_class', $classes, $class, $location );

	return array_unique( $classes );
}

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function boilerplate_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="'. get_bloginfo( 'pingback_url', 'display' ). '">';
	}
}
add_action( 'wp_head', 'boilerplate_pingback_header' );

// Use this as a starting point to add your self-hosted fonts
if ( ! function_exists( 'boilerplate_woodfordbourne_font_url' ) ) :
	/**
	 * Generate the WoodfordBourne font URL
	 *
	 * @since Boilerplate 1.0
	 *
	 * @return string
	 */
	function boilerplate_woodfordbourne_font_url() {

		/* Translators: If there are characters in your language that are not
		* supported by WoodfordBourne, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$woodfordbourne = esc_html_x( 'on', 'WoodfordBourne font: on or off', 'boilerplate' );
		if ( 'off' !== $woodfordbourne ) {
			return get_template_directory_uri() . '/assets/fonts/woodfordbourne/stylesheet.css';
		}

		return '';
	}
endif;

if ( ! function_exists( 'boilerplate_google_fonts_url' ) ) :
	/**
	 * Register Google fonts for Boilerplate.
	 *
	 * @since Boilerplate 1.0
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function boilerplate_google_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/* Translators: If there are characters in your language that are not
		* supported by Roboto, translate this to 'off'. Do not translate
		* into your own language.
		*/
		if ( 'off' !== _x( 'on', 'Neuton font: on or off', 'boilerplate' ) ) {
			$fonts[] = 'Neuton:200,300,400,400i,700,800';
		}

		/* Translators: If there are characters in your language that are not
		* supported by Oswald, translate this to 'off'. Do not translate
		* into your own language.
		*/
		if ( 'off' !== _x( 'on', 'PT Serif font: on or off', 'boilerplate' ) ) {
			$fonts[] = 'PT Serif:400,400i,700,700i';
		}

		/* translators: To add an additional character subset specific to your language, translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'boilerplate' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' == $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'devanagari' == $subset ) {
			$subsets .= ',devanagari';
		} elseif ( 'vietnamese' == $subset ) {
			$subsets .= ',vietnamese';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			), '//fonts.googleapis.com/css' );
		}

		return $fonts_url;
	} #function
endif;

// This function should come from Customify, but we need to do our best to make things happen
if ( ! function_exists( 'pixelgrade_option') ) {
	/**
	 * Get option from the database
	 *
	 * @since Boilerplate 1.0
	 *
	 * @param string $option The option name.
	 * @param mixed $default Optional. The default value to return when the option was not found or saved.
	 * @param bool $force_default Optional. When true, we will use the $default value provided for when the option was not saved at least once.
	 *                          When false, we will let the option's default set value (in the Customify settings) kick in first, than our $default.
	 *                          It basically, reverses the order of fallback, first the option's default, then our own.
	 *                          This is ignored when $default is null.
	 *
	 * @return mixed
	 */
	function pixelgrade_option( $option, $default = null, $force_default = true ) {
		/** @var PixCustomifyPlugin $pixcustomify_plugin */
		global $pixcustomify_plugin;

		// if there is set an key in url force that value
		if ( isset( $_GET[ $option ] ) && ! empty( $option ) ) {

			return wp_unslash( sanitize_text_field( $_GET[ $option ] ) );

		} elseif ( $pixcustomify_plugin !== null ) {
			// if there is a customify value get it here

			// First we see if we are not supposed to force over the option's default value
			if ( $default !== null && $force_default == false ) {
				// We will not pass the default here so Customify will fallback on the option's default value, if set
				$customify_value = $pixcustomify_plugin->get_option( $option );

				// We only fallback on the $default if none was given from Customify
				if ( $customify_value == null ) {
					return $default;
				}
			} else {
				$customify_value = $pixcustomify_plugin->get_option( $option, $default );
			}

			return $customify_value;
		}

		return $default;
	}
}

/**
 * Load custom javascript set by theme options
 */
function boilerplate_callback_load_custom_js() {
	$custom_js = pixelgrade_option( 'custom_js' );
	if ( ! empty( $custom_js ) ) {
		//first lets test is the js code is clean or has <script> tags and such
		//if we have <script> tags than we will not enclose it in anything - raw output
		if ( strpos( $custom_js, '</script>' ) !== false ) {
			echo $custom_js . PHP_EOL;
		} else {
			echo '<script>' . PHP_EOL . '(function($){' . PHP_EOL . $custom_js . PHP_EOL . '})(jQuery);' . PHP_EOL . '</script>' . PHP_EOL;
		}
	}
}
// custom javascript handlers - make sure it is the last one added
add_action( 'wp_head', 'boilerplate_callback_load_custom_js', 999 );

function boilerplate_callback_load_custom_js_footer() {
	$custom_js = pixelgrade_option( 'custom_js_footer' );
	if ( ! empty( $custom_js ) ) {
		//first lets test is the js code is clean or has <script> tags and such
		//if we have <script> tags than we will not enclose it in anything - raw output
		if ( strpos( $custom_js, '</script>' ) !== false ) {
			echo $custom_js . PHP_EOL;
		} else {
			echo '<script>' . PHP_EOL . '(function($){' . PHP_EOL . $custom_js . PHP_EOL . '})(jQuery);' . PHP_EOL . '</script>' . PHP_EOL;
		}
	}
}
add_action( 'wp_footer', 'boilerplate_callback_load_custom_js_footer', 999 );

/**
 * Display custom styles set by the page
 *
 * @param string $location A hint regarding where this action was called from
 */
function boilerplate_custom_page_css( $location = '' ) {
	if ( pixelgrade_in_location( 'page', $location ) ) {
		$output     = '';
		$custom_css = get_post_meta( get_the_ID(), 'custom_css_style', true );
		if ( ! empty( $custom_css ) ) {
			$output .= '<div class="custom-css" data-css="' . esc_attr( $custom_css ) . '"></div>' . PHP_EOL;
		}

		echo $output;
	}
}
add_action( 'pixelgrade_before_loop_entry', 'boilerplate_custom_page_css');

/**
 * Get the image src attribute.
 *
 * @since Boilerplate 1.0
 *
 * @param string $target
 * @param string $size Optional.
 *
 * @return string|false
 */
function boilerplate_image_src( $target, $size = null ) {
	if ( isset( $_GET[ $target ] ) && ! empty( $target ) ) {
		return boilerplate_get_attachment_image_src( absint( $_GET[ $target ] ), $size );
	} else { // empty target, or no query
		$image = pixelgrade_option( $target );
		if ( is_numeric( $image ) ) {
			return boilerplate_get_attachment_image_src( $image, $size );
		}
	}
	return false;
}

/**
 * Get the attachment src attribute.
 *
 * @since Boilerplate 1.0
 *
 * @param int $id
 * @param string $size Optional.
 *
 * @return string|false
 */
function boilerplate_get_attachment_image_src( $id, $size = null ) {
	//bail if not given an attachment id
	if ( empty( $id ) || ! is_numeric( $id ) ) {
		return false;
	}

	$array = wp_get_attachment_image_src( $id, $size );

	if ( isset( $array[0] ) ) {
		return $array[0];
	}

	return false;
}

if ( ! function_exists( 'boilerplate_get_post_thumbnail_aspect_ratio_class' ) ) {
	/**
	 * Get the class corresponding to the aspect ratio of the post featured image
	 *
	 * @since Boilerplate 1.0
	 *
	 * @param int|WP_Post $post_id Optional. Post ID or post object.
	 *
	 * @return string Aspect ratio specific class.
	 */
	function boilerplate_get_post_thumbnail_aspect_ratio_class( $post_id = null ) {

		// $post is the thumbnail attachment
		$post = get_post( $post_id );

		$class = 'none';

		$jetpack_show_single_featured_image = get_option( 'jetpack_content_featured_images_post', true );

		// bail if no post or the image is hidden from Jetpack's content options
		if ( empty( $post ) || empty( $jetpack_show_single_featured_image ) ) {
			return $class;
		}

		//$image_data[1] is width
		//$image_data[2] is height
		// we use the full image size to avoid the Photon messing around with the data - at least for now
		$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

		if ( empty( $image_data ) ) return $class;

		//we default to a landscape aspect ratio
		$class = 'landscape';
		if ( ! empty( $image_data[1] ) && ! empty( $image_data[2] ) ) {
			$image_aspect_ratio = $image_data[1] / $image_data[2];

			// now let's begin to see what kind of featured image we have
			// first portrait images
			if ( $image_aspect_ratio <= 1 ) {
				$class = 'portrait';
			}
		}

		return $class;
	} #function

}

if ( ! function_exists( 'boilerplate_search_form' ) ) :
	/**
	 * Custom search form
	 *
	 * @since Boilerplate 1.0
	 *
	 * @param string $form
	 *
	 * @return string
	 */
	function boilerplate_search_form( $form ) {
		$form = '<form role="search" method="get" class="search-form" action="' . esc_attr( home_url( '/' ) ) . '" >
		<label class="screen-reader-text">' . esc_html__( 'Search for:', 'boilerplate' ) . '</label>
		<input type="text" placeholder="' . esc_attr__( 'Search here', 'boilerplate' ) . '" value="' . esc_attr( get_search_query() ) . '" name="s" class="search-field" />
		<button type="submit" class="search-submit"><span>'. esc_html__( 'Search', 'boilerplate' ) .'</span></button>
		</form>';

		return $form;
	}
endif;
add_filter( 'get_search_form', 'boilerplate_search_form', 100 );

if ( ! function_exists( 'boilerplate_comment_form_custom_fields' ) ) :
	/**
	 * Custom comment form fields
	 *
	 * @since Boilerplate 1.0
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	function boilerplate_comment_form_custom_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields =  array(
			'author' =>
				'<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'boilerplate' ) . ( $req ? '*' : '' ) . '</label> ' .
				'<input id="author" name="author" type="text" placeholder="'. esc_attr__('Your name', 'boilerplate') .'" value="' . esc_attr( $commenter['comment_author'] ) .
				'"' . $aria_req . ' /></p>',

			'email' =>
				'<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'boilerplate' ) . ( $req ? '*' : '' ) . '</label> ' .
				'<input id="email" name="email" type="text" placeholder="'. esc_attr__('your@email.com', 'boilerplate') .'" value="' . esc_attr(  $commenter['comment_author_email'] ) .
				'"' . $aria_req . ' /></p>',

			'url' =>
				'<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'boilerplate' ) . '</label>' .
				'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
				'" size="30" /></p>',
		);

		return $fields;
	}
endif;
add_filter('comment_form_default_fields', 'boilerplate_comment_form_custom_fields');

/**
 * Add "Styles" drop-down
 */
function boilerplate_mce_editor_buttons( $buttons ) {
	array_unshift($buttons, 'styleselect' );
	return $buttons;
}

add_filter( 'mce_buttons_2', 'boilerplate_mce_editor_buttons' );

/**
 * Add styles/classes to the "Styles" drop-down
 *
 * @since Boilerplate 1.0
 *
 * @param array $settings
 *
 * @return array
 */
function boilerplate_mce_before_init( $settings ) {

	$style_formats =array(
		array( 'title' => esc_html__( 'Display', 'boilerplate' ), 'block' => 'h1', 'classes' => 'h0'),
		array( 'title' => esc_html__( 'Intro Text', 'boilerplate' ), 'selector' => 'p', 'classes' => 'intro'),
		array( 'title' => esc_html__( 'Dropcap', 'boilerplate' ), 'inline' => 'span', 'classes' => 'dropcap' ),
		array( 'title' => esc_html__( 'Button Directional &#8594;', 'boilerplate' ), 'selector' => 'a', 'classes' => 'c-btn-directional  c-btn-directional--right' ),
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
}

add_filter( 'tiny_mce_before_init', 'boilerplate_mce_before_init' );

/**
 * Prints HTML with meta information for the categories.
 */
function boilerplate_cats_list( $content ) {

	$cats_content = '';

	// Hide category text for pages. Also if a post only has a single category, do not show the cat section under the content as we have nothing to show.
	if ( 'post' == get_post_type() && is_singular( 'post' ) && is_main_query() && count( get_the_category() ) > 1 ) {
		// This is list filtered via 'the_category_list' and the main category is removed on single posts
		$categories_list = get_the_category_list( ' ' );

		if ( ! empty( $categories_list ) ) {
			$cats_content .= '<div class="o-inline o-inline-xs cats"><span class="cats__title h5">' . esc_html__( 'Categories', 'boilerplate' ) . sprintf( '</span>' . esc_html__( '%1$s', 'boilerplate' ), $categories_list ) . '</div>'; // WPCS: XSS OK.
		}
	}

	return $content . $cats_content;
}
// add this filter with a priority smaller than tags - it has 18
add_filter( 'the_content', 'boilerplate_cats_list', 17 );

/**
 * Removes the main category from the category list.
 *
 * @param array $categories
 * @param int $post_id
 *
 * @return array
 */
function boilerplate_remove_main_category_from_list( $categories, $post_id ) {
	if ( is_singular( 'post' ) ) {
		$main_category = boilerplate_get_main_category( $post_id );

		foreach ( $categories as $key => $category ) {
			if ( $main_category->term_id == $category->term_id ) {
				unset( $categories[ $key ] );
			}
		}
	}

	return $categories;
}
add_filter( 'the_category_list', 'boilerplate_remove_main_category_from_list', 10, 2 );

/**
 * Get the main post category WP_Term object based on our custom logic.
 *
 * @param int $post_ID Optional. Defaults to current post.
 *
 * @return WP_Term|bool
 */
function boilerplate_get_main_category( $post_ID = null ) {

	//use the current post ID is none given
	if ( empty( $post_ID ) ) {
		$post_ID = get_the_ID();
	}

	//obviously pages don't have categories
	if ( 'page' == get_post_type( $post_ID ) ) {
		return false;
	}

	$categories = get_the_category();

	if ( empty( $categories ) ) {
		return false;
	}

	// We need to sort the categories like this: first categories with no parent, and secondly ordered DESC by post count
	// Thus parent categories take precedence and categories with more posts take precedence
	usort( $categories, 'boilerplate_special_category_order' );

	// The first category should be the one we are after
	// We allow others to filter this (Yoast primary category maybe?)
	return apply_filters( 'boilerplate_main_category', $categories[0], $post_ID );
}

/**
 * @param WP_Term $a
 * @param WP_Term $b
 *
 * @return int
 */
function boilerplate_special_category_order( $a, $b ) {
	if ( $a->parent == $b->parent ) {
		if ( $a->count == $b->count ) {
			return 0;
		}

		return ( $a->count > $b->count ) ? -1 : 1;
	}

	return ( $a->parent < $b->parent ) ? -1 : 1;
}

/**
 * Prints HTML with meta information for the tags.
 */
function boilerplate_tags_list( $content ) {

	$tags_content = '';

	// Hide tag text for pages.
	if ( 'post' == get_post_type() && is_singular( 'post' ) && is_main_query() ) {
		$tags_list = get_the_tag_list();

		if ( ! empty( $tags_list ) ) {
			$tags_content .= '<div class="o-inline o-inline-xs tags"><div class="tags__title h5">' . esc_html__( 'Tags', 'boilerplate' ) . sprintf( '</div>' . esc_html__( '%1$s', 'boilerplate' ) . '</div>', $tags_list ); // WPCS: XSS OK.
		}
	}

	return $content . $tags_content;
}
// add this filter with a priority smaller than sharedaddy - it has 19
add_filter( 'the_content', 'boilerplate_tags_list', 18 );

/**
 * Flush out the transients used in boilerplate_categorized_blog.
 */
function boilerplate_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'boilerplate_categories' );
}
add_action( 'edit_category', 'boilerplate_category_transient_flusher' );
add_action( 'save_post',     'boilerplate_category_transient_flusher' );

/**
 * Return false to prevent the title markup to be displayed
 *
 * @since Boilerplate 1.0
 *
 * @param bool $display
 * @param string|array $location
 *
 * @return bool
 */
function boilerplate_prevent_entry_title( $show, $location ) {
	//we don't want an header if we are using the Full Width (No Title) or Default (No Title) page template
	if ( is_page() && pixelgrade_in_location( 'no-title', $location ) ) {
		return false;
	}

	if ( is_home() && 'posts' == get_option( 'show_on_front' ) ) {
		// This means we are showing the latest posts on front page and we don't have a page title
		return false;
	}

	if ( pixelgrade_in_location( 'front-page', $location ) ) {
		return false;
	}

	return $show;
}
add_filter( 'pixelgrade_display_entry_header', 'boilerplate_prevent_entry_title', 10, 2 );
