<?php
/**
 * This file is responsible for adjusting the Pixelgrade Components to this theme's specific needs.
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

/**
 * =====================
 * Some of the Customify controls come straight from components.
 * If you need to customize the settings for those controls you can use the appropriate filter.
 * For details search for the add_customify_options() function in the main component class (usually in class-componentname.php).
 *
 */

// For example, to change the Customify options for the Header component, you can go about it like so:
//=== To change the recommended fonts you can use the following
//
//        /**
//         * Modify the Customify recommended fonts for the Header font controls.
//         *
//         * @param array $fonts
//         *
//         * @return array
//         */
//        function boilerplate_change_customify_header_recommended_fonts( $fonts = array() ){
//            // just add some font to the existing list
//            $fonts[] = 'Some Font Family';
//
//            // delete a certain font family
//            if( ( $key = array_search( 'NiceFont', $fonts ) ) !== false) {
//                unset( $fonts[ $key ] );
//            }
//
//            // or just replace the whole array
//            $fonts = array(
//                'Playfair Display',
//                'Oswald',
//                'Lato',
//            );
//
//            // Now return our modified fonts list
//            return $fonts;
//        }
//        add_filter( 'pixelgrade_header_customify_recommended_headings_fonts', 'boilerplate_change_customify_header_recommended_fonts');


//=== To change some options
//
//        /**
//         * Modify the Customify Header section options.
//         *
//         * @param array $options
//         *
//         * @return array
//         */
//        function boilerplate_change_customify_header_section_options( $options = array() ){
//            // just add some option at the end
//            $options['header_section']['options']['header_transparent_header'] = array(
//                'type'    => 'checkbox',
//                'label'   => esc_html__( 'Transparent Header while on Hero', 'components' ),
//                'default' => 1,
//            );
//
//            // or you could add some option after another
//            $header_transparent_option = array(
//                'header_transparent' => array(
//                    'type'    => 'checkbox',
//                    'label'   => esc_html__( 'Transparent Header while on Hero', 'components' ),
//                    'default' => 1,
//                )
//            );
//            $options['header_section']['options'] = pixelgrade_array_insert_after( $options['header_section']['options'], 'header_sides_spacing', $header_transparent_option );
//
//            // delete some option
//            if( array_key_exists( 'header_background', $options['header_section']['options'] ) ) {
//                unset( $options['header_section']['options']['header_background'] );
//            }
//
//            // change some settings for a specific option
//            // First we test to see if we have this option
//            if( array_key_exists( 'header_background', $options['header_section']['options'] ) ) {
//                $options['header_section']['options']['header_background']['default'] = '#555555';
//            }
//
//            // or just replace the whole array
//            $options['header_section'] = array(
//                'title'   => __( 'Header', 'components' ),
//                'options' => array(
//                        //put your options here
//                )
//            );
//
//            // Now return our modified options
//            return $options;
//        }
//        add_filter( 'pixelgrade_header_customify_section_options', 'boilerplate_change_customify_header_section_options');

function boilerplate_change_customify_header_recommended_fonts( $fonts = array() ){

    // or just replace the whole array
    $fonts = array(
        'Roboto',
        'Open Sans',
        'Oswald',
        'Lato',
    );

    // Now return our modified fonts list
    return $fonts;
}
add_filter( 'pixelgrade_header_customify_recommended_headings_fonts', 'boilerplate_change_customify_header_recommended_fonts');

function boilerplate_change_customify_header_section_options( $options = array() ){

	if ( array_key_exists( 'header_page_title_font2', $options['header_section']['options'] ) ) {
		$options['header_section']['options']['header_page_title_font2']['selector'] = '.c-navbar';
		$options['header_section']['options']['header_page_title_font2']['default']  = array(
			'font-family'    => 'Roboto',
			'font-weight'    => 'regular',
			'font-size'      => 15,
			'line-height'    => 1.33,
			'letter-spacing' => 0,
			'text-transform' => 'none'
		);
	}

	if ( array_key_exists( 'header_background', $options['header_section']['options'] ) ) {
		$options['header_section']['options']['header_background']['default'] = '#F5FBFE';
	}

	if ( array_key_exists( 'header_navigation_links_color', $options['header_section']['options'] ) ) {
		$options['header_section']['options']['header_navigation_links_color']['css'] = array(
			array(
				'property' => 'color',
				'selector' => '.c-navbar, .c-navbar li',
			),
		);
	}

    return $options;
}
add_filter( 'pixelgrade_header_customify_section_options', 'boilerplate_change_customify_header_section_options');

function boilerplate_change_customify_footer_section_options( $options = array() ) {
	if ( array_key_exists( 'footer_background', $options['footer_section']['options'] ) ) {
		$options['footer_section']['options']['footer_background']['default'] = '#F5FBFE';
	}

	return $options;
}
add_filter( 'pixelgrade_footer_customify_section_options', 'boilerplate_change_customify_footer_section_options');

/**
 * =====================
 */

/*========================*/
/* CUSTOMIZING THE HERO   */
/*========================*/

/**
 * We change the default hero metaboxes config.
 *
 * @param array $config The PixTypes config for the hero component.
 *
 * @return array
 */
function boilerplate_hero_metaboxes_config( $config ) {
	// Change the hero metaboxes config

	return $config;
}
add_filter( 'pixelgrade_hero_metaboxes_config', 'boilerplate_hero_metaboxes_config', 10, 1 );

/**
 * We make sure that the hero is not shown in cases specific to this theme.
 *
 * @param bool $is_needed
 * @param array|string $location
 *
 * @return bool
 */
function boilerplate_prevent_hero( $is_needed, $location ) {
	//we don't want a hero on no-title pages
	if ( pixelgrade_in_location( 'page no-title', $location ) ) {
		return false;
	}

	return $is_needed;
}
add_filter( 'pixelgrade_hero_is_hero_needed', 'boilerplate_prevent_hero', 10, 2 );

/**
 * We want to make sure that we don't mistakenly account for the hero content when we are not using a template where we use it.
 * The content might have been saved in the database, even if we are not using it (displaying it in the WP admin).
 *
 * @param $has_desc
 * @param $post
 *
 * @return bool
 */
function boilerplate_prevent_hero_description( $has_desc, $post ) {
	if ( is_page( $post->ID ) && in_array( get_page_template_slug( $post->ID ), array(
			'page-templates/no-title.php',
			'page-templates/full-width-no-title.php'
		) )
	) {
		return false;
	}

	if ( is_single( $post->ID ) && 'post' === get_post_type( $post->ID ) ) {
		return false;
	}

	return $has_desc;
}

add_filter( 'pixelgrade_hero_has_description', 'boilerplate_prevent_hero_description', 10, 2 );

/**
 * Adds the scroll down arrow after the hero content when it's needed - when it's a full-screen hero
 *
 * @param string $content
 */
function boilerplate_the_hero_scroll_down_arrow( $content ) {
	$post = null;
	// We might be on a page set as a page for posts and the $post will be the first post in the loop
	// So we check first
	if ( is_home() ) {
		// find the id of the page for posts
		$post = get_option( 'page_for_posts' );
	}

	// First make sure we have a post
	$post = get_post( $post );

	//bail if we don't have a post to work with
	if ( empty( $post ) ) {
		return;
	}

	// Only show the scroll down arrow for full-height heroes
	$show = false;
	if ( 'c-hero--full' == pixelgrade_hero_get_height( '', $post ) ) {
		$show = true;
	}

	// Allow others to have a say in it - like the multipage component
	$show = apply_filters( 'pixelgrade_hero_show_scroll_down_arrow', $show, '', $post->ID );

	if ( true === $show ) {
		get_template_part( 'template-parts/svg/scroll-down-arrow' );
	}
}
add_action( 'pixelgrade_hero_after_the_description', 'boilerplate_the_hero_scroll_down_arrow', 90, 2 );

/**
 * Add the front page hero bottom area after the hero content.
 *
 * @param array|string $location
 */
function boilerplate_frontpage_hero_bottom_area( $location ) {
	// This 'front-page' location  will only be available on the main page, not subpages, so it will work with multipages also
	if ( is_front_page() && pixelgrade_in_location( 'front-page', $location ) && is_active_sidebar( 'sidebar-frontpage' ) ) { ?>
		<div class="c-hero__wrapper--bottom">
			<aside id="front-page-sidebar" class="widget-area  widget-area--front-page js-front-page-sidebar"
			       role="complementary">
				<?php dynamic_sidebar( 'sidebar-frontpage' ); ?>
			</aside><!-- #secondary -->
		</div>
	<?php }
}

add_action( 'pixelgrade_hero_after_content_wrapper', 'boilerplate_frontpage_hero_bottom_area', 100, 1 );
