<?php
/**
 * Boilerplate Customizer Options Config
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

/**
 * Hook into the Customify's fields and settings.
 *
 * The config can turn to be complex so is better to visit:
 * https://github.com/pixelgrade/customify
 *
 * @param $options array - Contains the plugin's options array right before they are used, so edit with care
 *
 * @return mixed The return of options is required, if you don't need options return an empty array
 *
 */

/* =============
 * For customizing the components Customify options you need to use the /inc/components.php file.
 * Also there you will find the example code for making changes.
 * ============= */

add_filter( 'customify_filter_fields', 'boilerplate_add_customify_options', 11, 1 );
add_filter( 'customify_filter_fields', 'boilerplate_add_customify_general_options', 12, 1 );
// Next come the Header options from the Pixelgrade/Header component - priority 20
add_filter( 'customify_filter_fields', 'boilerplate_add_customify_main_content_options', 30, 1 );
// Next come the Footer options from the Pixelgrade/Footer component - priority 40
add_filter( 'customify_filter_fields', 'boilerplate_add_customify_blog_grid_options', 50, 1 );
add_filter( 'customify_filter_fields', 'boilerplate_add_customify_portfolio_grid_options', 60, 1 );
add_filter( 'customify_filter_fields', 'boilerplate_add_customify_import_demo_options', 70, 1 );

function boilerplate_add_customify_options( $options ) {
	$options['opt-name'] = 'boilerplate_options';

	//start with a clean slate - no Customify default sections
	$options['sections'] = array();

	return $options;
}

function boilerplate_add_customify_general_options( $options ) {
	$general_section = array(
		// General
		'general' => array(
			'title'   => esc_html__( 'General', 'boilerplate' ),
			'options' => array(
				'use_ajax_loading' => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Enable dynamic page content loading using AJAX.', 'boilerplate' ),
					'default' => 1,
				),
			),
		),
	);

	//Allow others to make changes
	$general_section = apply_filters( 'pixelgrade_customify_general_section_options', $general_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the general section
	$options['sections'] = $options['sections'] + $general_section;

	return $options;
}

function boilerplate_add_customify_main_content_options( $options ) {
	// Body
	$recommended_body_fonts = apply_filters( 'customify_theme_recommended_body_fonts',
		array(
			'Roboto',
			'Playfair Display',
			'Oswald',
			'Lato',
			'Open Sans',
			'Exo',
			'PT Sans',
			'Ubuntu',
			'Vollkorn',
			'Lora',
			'Arvo',
			'Josefin Slab',
			'Crete Round',
			'Kreon',
			'Bubblegum Sans',
			'The Girl Next Door',
			'Pacifico',
			'Handlee',
			'Satify',
			'Pompiere'
		)
	);

	$main_content_section = array(
		// Main Content
		'main_content' => array(
			'title'   => esc_html__( 'Main Content', 'boilerplate' ),
			'options' => array(
				'main_content_options_customizer_tabs'              => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
							<a href="#section-title-main-layout">' . esc_html__( 'Layout', 'boilerplate' ) . '</a>
							<a href="#section-title-main-colors">' . esc_html__( 'Colors', 'boilerplate' ) . '</a>
							<a href="#section-title-main-fonts">' . esc_html__( 'Fonts', 'boilerplate' ) . '</a>
							</nav>',
				),
				// [Section] Layout
				'main_content_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-main-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'boilerplate' ) . '</span>',
				),
				'main_content_container_width'          => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Container Max Width', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the max width of your site content area.', 'boilerplate' ),
					'live'        => true,
					'default'     => 1300,
					'input_attrs' => array(
						'min'          => 600,
						'max'          => 2600,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-container-width',
							'unit'     => 'px',
						),
					),
				),
				'main_content_container_sides_spacing'  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Container Sides Spacing', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the space separating the site content and the sides of the browser.', 'boilerplate' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-left',
							'selector'        => '.u-container-sides-spacings',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'padding-right',
							'selector'        => '.u-container-sides-spacings',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
					),
				),
				'main_content_container_padding'        => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Container Padding', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the top and bottom distance between the page content and header/footer.', 'boilerplate' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-top',
							'selector'        => '.u-content-top-spacing',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'padding-bottom',
							'selector'        => '.u-content-bottom-spacing',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
					),
				),
				'main_content_content_width'            => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Content Width', 'boilerplate' ),
					'desc'        => esc_html__( 'Decrease the width of your content to create an inset area for your text. The inset size will be the space between Site Container and Content.', 'boilerplate' ),
					'live'        => true,
					'default'     => 720,
					'input_attrs' => array(
						'min'          => 400,
						'max'          => 2600,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-content-width > *:not([class*="align"]):not([class*="gallery"]):not(blockquote), .mce-content-body',
							'unit'     => 'px',
						),
					),
				),
				'main_content_border_width'             => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Site Border Width', 'boilerplate' ),
					'desc'        => '',
					'live'        => true,
					'default'     => 0,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'margin',
							'selector' => 'body',
							'unit'     => 'px',
							'callback_filter' => 'typeline_spacing_cb'
						),
						array(
							'property' => 'border-width',
							'selector' => '.c-border',
							'unit'     => 'px',
							'callback_filter' => 'typeline_spacing_cb'
						),
						array(
							'property' => 'margin-top',
							'selector' => '.has-border:not(.u-static-header) .c-navbar, .c-navbar__label',
							'unit'     => 'px',
							'callback_filter' => 'typeline_spacing_cb'
						),
						array(
							'property' => 'margin-left',
							'selector' => '.c-navbar__label',
							'unit'     => 'px',
							'callback_filter' => 'typeline_spacing_cb'
						),
						array(
							'property'        => 'border-top-width',
							'selector'        => '.c-navbar__container',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'border-left-width',
							'selector'        => '.c-navbar__container',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'border-right-width',
							'selector'        => '.c-navbar__container',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'bottom',
							'selector'        => '.c-slider__bullets',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'margin-top',
							'selector'        => '.c-overlay__close',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),
						array(
							'property'        => 'margin-right',
							'selector'        => '.c-overlay__close',
							'unit'            => 'px',
							'callback_filter' => 'typeline_spacing_cb',
						),

					),
				),
				'main_content_border_color' => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Site Border Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#FFFFFF',
					'css'     => array(
						array(
							'property' => 'border-color',
							'selector' => '.c-border',
						),
					),
				),

				// [Section] COLORS
				'main_content_title_colors_section' => array(
					'type' => 'html',
					'html' => '<span id="section-title-main-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'boilerplate' ) . '</span>',
				),
				'main_content_page_title_color'         => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Page Title Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-page-header__title',
						),
					),
				),
				'main_content_body_text_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Text Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'body',
						),
					),
				),
				'main_content_body_link_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Link Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.entry-content a',
						),
					),
				),
				'main_content_body_link_active_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Body Link Active Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#dfa671',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '
								.entry-content a:hover, 
								.entry-content a:active',
						),
					),
				),
				'main_content_underlined_body_links'    => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Underlined Body Links', 'boilerplate' ),
					'default' => 1,
				),
				// [Sub Section] Headings Color
				'main_content_title_headings_color_section'              => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Headings Color', 'boilerplate' ) . '</span>',
				),
				'main_content_heading_1_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 1', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h1, .h1',
						),
					),
				),
				'main_content_heading_2_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 2', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h2, .h2',
						),
					),
				),
				'main_content_heading_3_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 3', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h3, .h3'
						),
					),
				),
				'main_content_heading_4_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 4', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h4, .h4',
						),
					),
				),
				'main_content_heading_5_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 5', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h5, .h5',
						),
					),
				),
				'main_content_heading_6_color'          => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Heading 6', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => 'h6, .h6',
						),
					),
				),

				// [Sub Section] Backgrounds
				'main_content_title_backgrounds_section'            => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Backgrounds', 'boilerplate' ) . '</span>',
				),
				'main_content_content_background_color' => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Content Background Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#F5FBFE',
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.u-content-background',
						),
					),
				),

				// [Section] FONTS
				'main_content_title_fonts_section'             => array(
					'type' => 'html',
					'html' => '<span id="section-title-main-fonts" class="separator section label large">&#x1f4dd;  ' . esc_html__( 'Fonts', 'boilerplate' ) . '</span>',
				),

				'main_content_page_title_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Page Title Font', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-title',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '300',
						'font-size'      => 72,
						'line-height'    => 1.11,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,


					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_body_text_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Body Text Font', 'boilerplate' ),
					'desc'     => '',
					'selector' => 'body, .entry-content p, .comment-content p',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '300',
						'font-size'      => 17,
						'line-height'    => 1.52,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_quote_block_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Quote Block Font', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-content blockquote',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => "Roboto",
						'font-weight'    => '300',
						'font-size'      => 30,
						'line-height'    => 1.5,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				// [Sub Section] Headings Fonts
				'main_content_title_headings_fonts_section'     => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Headings Fonts', 'boilerplate' ) . '</span>',
				),

				'main_content_heading_1_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 1', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-content h1, .h1',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '300',
						'font-size'      => 40,
						'line-height'    => 1.25,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,
					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_2_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 2', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-content h2, .h2',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '300',
						'font-size'      => 30,
						'line-height'    => 1.33,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_3_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 3', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-content h3, .h3',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '400',
						'font-size'      => 24,
						'line-height'    => 1.41,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_4_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 4', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-content h4, .h4',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '300',
						'font-size'      => 20,
						'line-height'    => 1.5,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_5_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 5', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-content h5, .h5',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '400',
						'font-size'      => 17,
						'line-height'    => 1.17,
						'letter-spacing' => 0.28,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'main_content_heading_6_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Heading 6', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.entry-content h6, .h6',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => 'regular',
						'font-size'      => 14,
						'line-height'    => 1.181,
						'letter-spacing' => 0.17,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),
			)
		),
	);

	//Allow others to make changes
	$main_content_section = apply_filters( 'pixelgrade_customify_main_content_section_options', $main_content_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the main content section
	$options['sections'] = $options['sections'] + $main_content_section;

	return $options;
}

function boilerplate_add_customify_blog_grid_options( $options ) {
	// Body
	$recommended_body_fonts = apply_filters( 'customify_theme_recommended_body_fonts',
		array(
			'Roboto',
			'Playfair Display',
			'Oswald',
			'Lato',
			'Open Sans',
			'Exo',
			'PT Sans',
			'Ubuntu',
			'Vollkorn',
			'Lora',
			'Arvo',
			'Josefin Slab',
			'Crete Round',
			'Kreon',
			'Bubblegum Sans',
			'The Girl Next Door',
			'Pacifico',
			'Handlee',
			'Satify',
			'Pompiere'
		)
	);

	$blog_grid_section = array(
		// Blog Grid
		'blog_grid' => array(
			'title'   => esc_html__( 'Blog Grid Items', 'boilerplate' ),
			'options' => array(
				'blog_grid_options_customizer_tabs'          => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
								<a href="#section-title-blog-layout">' . esc_html__( 'Layout', 'boilerplate' ) . '</a>
								<a href="#section-title-blog-colors">' . esc_html__( 'Colors', 'boilerplate' ) . '</a>
								<a href="#section-title-blog-fonts">' . esc_html__( 'Fonts', 'boilerplate' ) . '</a>
								</nav>',
				),

				// [Section] Layout
				'blog_grid_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-blog-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'boilerplate' ) . '</span>',
				),
				'blog_grid_width'                     => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Blog Grid Max Width', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the max width of the blog area.', 'boilerplate' ),
					'live'        => true,
					'default'     => 1300,
					'input_attrs' => array(
						'min'          => 600,
						'max'          => 2600,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-blog-grid-width',
							'unit'     => 'px',
						),
					),
				),
				'blog_container_sides_spacing'        => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Container Sides Spacing', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the space separating the site content and the sides of the browser.', 'boilerplate' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-left',
							'selector'        => '.u-blog-sides-spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
						array(
							'property'        => 'padding-right',
							'selector'        => '.u-blog-sides-spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

				// [Sub Section] Items Grid
				'blog_grid_title_items_grid_section'             => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label large">' . esc_html__( 'Items Grid', 'boilerplate' ) . '</span>',
				),
				'blog_grid_layout'                    => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Grid Layout', 'boilerplate' ),
					'desc'    => esc_html__( 'Choose whether the items display in a fixed height regular grid, or in a packed style layout.', 'boilerplate' ),
					'default' => 'regular',
					'choices' => array(
						'regular' => esc_html__( 'Regular Grid', 'boilerplate' ),
						'masonry' => esc_html__( 'Masonry', 'boilerplate' ),
						'mosaic'  => esc_html__( 'Mosaic', 'boilerplate' ),
						'packed'  => esc_html__( 'Packed', 'boilerplate' ),
					)
				),
				'blog_items_aspect_ratio'             => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Aspect Ratio', 'boilerplate' ),
					'desc'        => esc_html__( 'Leave the images to their original ratio or crop them to get a more defined grid layout.', 'boilerplate' ),
					'live'        => true,
					'default'     => 130,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 200,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'dummy',
							'selector'        => '.c-gallery--blog.c-gallery--regular .c-card__frame',
							'callback_filter' => 'boilerplate_aspect_ratio_cb',
							'unit'            => '%',
						),
					),
				),
				'blog_items_per_row'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items per Row', 'boilerplate' ),
					'desc'        => esc_html__( 'Set the desktop-based number of columns you want and we automatically make it right for other screen sizes.', 'boilerplate' ),
					'live'        => false,
					'default'     => 3,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
					'css'         => array(
						array(
							'property' => 'dummy',
							'selector' => '.dummy',
							'unit'     => 'px',
						),
					),
				),
				'blog_items_vertical_spacing'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Vertical Spacing', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the spacing between individual items in your grid.', 'boilerplate' ),
					'live'        => true,
					'default'     => 80,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 300,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => '',
							'selector'        => '.dummy',
							'callback_filter' => 'boilerplate_blog_grid_vertical_spacing_cb',
							'unit'            => 'px',
						),
					),
				),
				'blog_items_horizontal_spacing'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Horizontal Spacing', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the spacing between individual items in your grid.', 'boilerplate' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => '',
							'selector'        => '.dummy',
							'callback_filter' => 'boilerplate_blog_grid_horizontal_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

				// [Sub Section] Items Title
				'blog_grid_title_items_title_section'            => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Title', 'boilerplate' ) . '</span>',
				),
				'blog_items_title_position'           => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Title Position', 'boilerplate' ),
					'desc'    => esc_html__( 'Choose whether the items titles are placed nearby the thumbnail or show as an overlay cover on  mouse over.', 'boilerplate' ),
					'default' => 'below',
					'choices' => array(
						'above'   => esc_html__( 'Above', 'boilerplate' ),
						'below'   => esc_html__( 'Below', 'boilerplate' ),
						'overlay' => esc_html__( 'Overlay', 'boilerplate' ),
					)
				),
				'blog_items_title_alignment_nearby'   => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Above/Below)', 'boilerplate' ),
					'desc'    => esc_html__( 'Adjust the alignment of your title.', 'boilerplate' ),
					'default' => 'left',
					'choices' => array(
						'left'   => esc_html__( '← Left', 'boilerplate' ),
						'center' => esc_html__( '↔ Center', 'boilerplate' ),
						'right'  => esc_html__( '→ Right', 'boilerplate' ),
					),
					'active_callback' => 'boilerplate_blog_items_title_alignment_nearby_control_show',
				),
				'blog_items_title_alignment_overlay'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Overlay)', 'boilerplate' ),
					'desc'    => esc_html__( 'Adjust the alignment of your hover title.', 'boilerplate' ),
					'default' => 'middle-center',
					'choices' => array(
						'top-left'   => esc_html__( '↑ Top     ← Left', 'boilerplate' ),
						'top-center' => esc_html__( '↑ Top     ↔ Center', 'boilerplate' ),
						'top-right'  => esc_html__( '↑ Top     → Right', 'boilerplate' ),

						'middle-left'   => esc_html__( '↕ Middle     ← Left', 'boilerplate' ),
						'middle-center' => esc_html__( '↕ Middle     ↔ Center', 'boilerplate' ),
						'middle-right'  => esc_html__( '↕ Middle     → Right', 'boilerplate' ),

						'bottom-left'   => esc_html__( '↓ Bottom     ← Left', 'boilerplate' ),
						'bottom-center' => esc_html__( '↓ Bottom     ↔ Center', 'boilerplate' ),
						'bottom-right'  => esc_html__( '↓ Bottom     → Right', 'boilerplate' ),
					),
					'active_callback' => 'boilerplate_blog_items_title_alignment_overlay_control_show',
				),

				// Title Visiblity
				// Title + Checkbox
				'blog_items_title_visibility_title'   => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Title Visibility', 'boilerplate' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'boilerplate' ) . '</span>',
				),
				'blog_items_title_visibility'         => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Title', 'boilerplate' ),
					'default' => 1,
				),

				// [Sub Section] Items Excerpt
				'blog_grid_title_items_excerpt_section'            => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Excerpt', 'boilerplate' ) . '</span>',
					'active_callback' => 'boilerplate_blog_items_excerpt_visibility_control_show',
				),

				// Excerpt Visiblity
				// Title + Checkbox
				'blog_items_excerpt_visibility_title' => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Excerpt Visibility', 'boilerplate' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'boilerplate' ) . '</span>',
					'active_callback' => 'boilerplate_blog_items_excerpt_visibility_control_show',
				),
				'blog_items_excerpt_visibility'       => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Excerpt Text', 'boilerplate' ),
					'default' => 1,
					'active_callback' => 'boilerplate_blog_items_excerpt_visibility_control_show',
				),

				// [Sub Section] Items Meta
				'blog_grid_title_items_meta_section'          => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Meta', 'boilerplate' ) . '</span>',
					'active_callback' => 'boilerplate_blog_items_meta_control_show',
				),

				'blog_items_primary_meta' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Primary Meta Section', 'boilerplate' ),
					'desc'    => esc_html__( 'Set the meta info that display around the title. ', 'boilerplate' ),
					'default' => 'category',
					'choices' => array(
						'none'     => esc_html__( 'None', 'boilerplate' ),
						'category' => esc_html__( 'Category', 'boilerplate' ),
						'author'   => esc_html__( 'Author', 'boilerplate' ),
						'date'     => esc_html__( 'Date', 'boilerplate' ),
						'tags'     => esc_html__( 'Tags', 'boilerplate' ),
						'comments' => esc_html__( 'Comments', 'boilerplate' ),
					),
					'active_callback' => 'boilerplate_blog_items_primary_meta_control_show',
				),

				'blog_items_secondary_meta'         => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Secondary Meta Section', 'boilerplate' ),
					'desc'    => '',
					'default' => 'date',
					'choices' => array(
						'none'     => esc_html__( 'None', 'boilerplate' ),
						'category' => esc_html__( 'Category', 'boilerplate' ),
						'author'   => esc_html__( 'Author', 'boilerplate' ),
						'date'     => esc_html__( 'Date', 'boilerplate' ),
						'tags'     => esc_html__( 'Tags', 'boilerplate' ),
						'comments' => esc_html__( 'Comments', 'boilerplate' ),
					),
					'active_callback' => 'boilerplate_blog_items_secondary_meta_control_show',
				),

				// [Section] COLORS
				'blog_grid_title_colors_section'        => array(
					'type' => 'html',
					'html' => '<span id="section-title-blog-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'boilerplate' ) . '</span>',
				),
				'blog_item_title_color'             => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Item Title Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#252525',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__title',
						),
					),
				),
				'blog_item_meta_primary_color'      => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Primary', 'boilerplate' ),
					'live'    => true,
					'default' => '#3B3B3B',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__meta-primary',
						),
					),
				),
				'blog_item_meta_secondary_color'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Secondary', 'boilerplate' ),
					'live'    => true,
					'default' => '#818282',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--blog .c-card__meta-secondary',
						),
					),
				),
				'blog_item_thumbnail_background'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Thumbnail Background', 'boilerplate' ),
					'live'    => true,
					'default' => '#EEEEEE',
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.c-gallery--blog .c-card__thumbnail-background',
						),
					),
				),

				// [Sub Section] Thumbnail Hover
				'blog_grid_title_thumbnail_hover_section'        => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Thumbnail Hover', 'boilerplate' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Customize the mouse over effect for your thumbnails.', 'boilerplate' ) . '</span>',
				),
				'blog_item_thumbnail_hover_opacity' => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Thumbnail Background Opacity', 'boilerplate' ),
					'desc'        => '',
					'live'        => true,
					'default'     => 0.7,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 1,
						'step'         => 0.1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'opacity',
							'selector' => '.c-gallery--blog .c-card:hover .c-card__frame',
							'unit'     => '',
						),
					),
				),

				// [Section] FONTS
				'blog_grid_title_fonts_section'          => array(
					'type' => 'html',
					'html' => '<span id="section-title-blog-fonts" class="separator section label large">&#x1f4dd;  ' . esc_html__( 'Fonts', 'boilerplate' ) . '</span>',
				),

				'blog_item_title_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Title Font', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.c-gallery--blog .c-card__title',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '300',
						'font-size'      => 24,
						'line-height'    => 1.25,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'blog_item_meta_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Meta Font', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.c-gallery--blog .c-card__meta-primary, .c-gallery--blog .c-card__meta-secondary',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => 'regular',
						'font-size'      => 15,
						'line-height'    => 1.5,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),
			),
		),
	);

	//Allow others to make changes
	$blog_grid_section = apply_filters( 'pixelgrade_customify_blog_grid_section_options', $blog_grid_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the blog grid section
	$options['sections'] = $options['sections'] + $blog_grid_section;

	return $options;
}

function boilerplate_add_customify_portfolio_grid_options( $options ) {
	// Body
	$recommended_body_fonts = apply_filters( 'customify_theme_recommended_body_fonts',
		array(
			'Roboto',
			'Playfair Display',
			'Oswald',
			'Lato',
			'Open Sans',
			'Exo',
			'PT Sans',
			'Ubuntu',
			'Vollkorn',
			'Lora',
			'Arvo',
			'Josefin Slab',
			'Crete Round',
			'Kreon',
			'Bubblegum Sans',
			'The Girl Next Door',
			'Pacifico',
			'Handlee',
			'Satify',
			'Pompiere'
		)
	);

	$portfolio_grid_section = array(
		// Portfolio Grid
		'portfolio_grid' => array(
			'title'   => esc_html__( 'Portfolio Grid Items', 'boilerplate' ),
			'options' => array(
				'portfolio_grid_options_customizer_tabs'               => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
							<a href="#section-title-portfolio-layout">' . esc_html__( 'Layout', 'boilerplate' ) . '</a>
							<a href="#section-title-portfolio-colors">' . esc_html__( 'Colors', 'boilerplate' ) . '</a>
							<a href="#section-title-portfolio-fonts">' . esc_html__( 'Fonts', 'boilerplate' ) . '</a>
							</nav>',
				),

				// [Section] Layout
				'portfolio_grid_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-portfolio-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'boilerplate' ) . '</span>',
				),
				'portfolio_grid_width'                     => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Portfolio Grid Max Width', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the max width of the portfolio area.', 'boilerplate' ),
					'live'        => true,
					'default'     => 1300,
					'input_attrs' => array(
						'min'          => 600,
						'max'          => 2600,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-width',
							'selector' => '.u-portfolio-grid-width',
							'unit'     => 'px',
						),
					),
				),
				'portfolio_container_sides_spacing'        => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Container Sides Spacing', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the space separating the site content and the sides of the browser.', 'boilerplate' ),
					'live'        => true,
					'default'     => 60,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 140,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'padding-left',
							'selector'        => '.u-portfolio-sides-spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
						array(
							'property'        => 'padding-right',
							'selector'        => '.u-portfolio-sides-spacing',
							'callback_filter' => 'typeline_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

				// [Sub Section] Items Grid
				'portfolio_grid_title_items_grid_section'                  => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label large">' . esc_html__( 'Items Grid', 'boilerplate' ) . '</span>',
				),
				'portfolio_grid_layout'                    => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Grid Layout', 'boilerplate' ),
					'desc'    => esc_html__( 'Choose whether the items display in a fixed height regular grid, or in a packed style layout.', 'boilerplate' ),
					'default' => 'packed',
					'choices' => array(
						'regular' => esc_html__( 'Regular Grid', 'boilerplate' ),
						'masonry' => esc_html__( 'Masonry', 'boilerplate' ),
						'mosaic'  => esc_html__( 'Mosaic', 'boilerplate' ),
						'packed'  => esc_html__( 'Packed', 'boilerplate' ),
					)
				),
				'portfolio_items_aspect_ratio'             => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Aspect Ratio', 'boilerplate' ),
					'desc'        => esc_html__( 'Leave the images to their original ratio or crop them to get a more defined grid layout.', 'boilerplate' ),
					'live'        => true,
					'default'     => 100,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 200,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => 'dummy',
							'selector'        => '.c-gallery--portfolio.c-gallery--regular .c-card__frame',
							'callback_filter' => 'boilerplate_aspect_ratio_cb',
							'unit'            => '%',
						),
					),
				),
				'portfolio_items_per_row'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items per Row', 'boilerplate' ),
					'desc'        => esc_html__( 'Set the desktop-based number of columns you want and we automatically make it right for other screen sizes.', 'boilerplate' ),
					'live'        => false,
					'default'     => 4,
					'input_attrs' => array(
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					),
					'css'         => array(
						array(
							'property' => 'dummy',
							'selector' => '.dummy',
							'unit'     => 'px',
						),
					),
				),
				'portfolio_items_vertical_spacing'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Vertical Spacing', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the spacing between individual items in your grid.', 'boilerplate' ),
					'live'        => true,
					'default'     => 150,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 300,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => '',
							'selector'        => '.c-gallery--portfolio',
							'callback_filter' => 'boilerplate_portfolio_grid_vertical_spacing_cb',
							'unit'            => 'px',
						),
					),
				),
				'portfolio_items_horizontal_spacing'                  => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Items Horizontal Spacing', 'boilerplate' ),
					'desc'        => esc_html__( 'Adjust the spacing between individual items in your grid.', 'boilerplate' ),
					'live'        => true,
					'default'     => 40,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 10,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property'        => '',
							'selector'        => '.c-gallery--portfolio',
							'callback_filter' => 'boilerplate_portfolio_grid_horizontal_spacing_cb',
							'unit'            => 'px',
						),
					),
				),

				// [Sub Section] Items Title
				'portfolio_grid_title_items_title_section'                 => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Title', 'boilerplate' ) . '</span>',
				),
				'portfolio_items_title_position'           => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Title Position', 'boilerplate' ),
					'desc'    => esc_html__( 'Choose whether the items titles are placed nearby the thumbnail or show as an overlay cover on  mouse over.', 'boilerplate' ),
					'default' => 'below',
					'choices' => array(
						'above'   => esc_html__( 'Above', 'boilerplate' ),
						'below'   => esc_html__( 'Below', 'boilerplate' ),
						'overlay' => esc_html__( 'Overlay', 'boilerplate' ),
					)
				),
				'portfolio_items_title_alignment_nearby'   => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Above/Below)', 'boilerplate' ),
					'desc'    => esc_html__( 'Adjust the alignment of your title.', 'boilerplate' ),
					'default' => 'left',
					'choices' => array(
						'left'   => esc_html__( '← Left', 'boilerplate' ),
						'center' => esc_html__( '↔ Center', 'boilerplate' ),
						'right'  => esc_html__( '→ Right', 'boilerplate' ),
					),
					'active_callback' => 'boilerplate_portfolio_items_title_alignment_nearby_control_show',
				),
				'portfolio_items_title_alignment_overlay'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Title Alignment (Overlay)', 'boilerplate' ),
					'desc'    => esc_html__( 'Adjust the alignment of your hover title.', 'boilerplate' ),
					'default' => 'middle-center',
					'choices' => array(
						'top-left'   => esc_html__( '↑ Top     ← Left', 'boilerplate' ),
						'top-center' => esc_html__( '↑ Top     ↔ Center', 'boilerplate' ),
						'top-right'  => esc_html__( '↑ Top     → Right', 'boilerplate' ),

						'middle-left'   => esc_html__( '↕ Middle     ← Left', 'boilerplate' ),
						'middle-center' => esc_html__( '↕ Middle     ↔ Center', 'boilerplate' ),
						'middle-right'  => esc_html__( '↕ Middle     → Right', 'boilerplate' ),

						'bottom-left'   => esc_html__( '↓ Bottom     ← Left', 'boilerplate' ),
						'bottom-center' => esc_html__( '↓ Bottom     ↔ Center', 'boilerplate' ),
						'bottom-right'  => esc_html__( '↓ Bottom     → Right', 'boilerplate' ),
					),
					'active_callback' => 'boilerplate_portfolio_items_title_alignment_overlay_control_show',
				),

				// Title Visibility
				// Title + Checkbox
				'portfolio_items_title_visibility_title'   => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Title Visibility', 'boilerplate' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'boilerplate' ) . '</span>',
				),
				'portfolio_items_title_visibility'         => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Title', 'boilerplate' ),
					'default' => 1,
				),

				// [Sub Section] Items Excerpt
				'portfolio_grid_title_items_excerpt_section'                 => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Excerpt', 'boilerplate' ) . '</span>',
					'active_callback' => 'boilerplate_portfolio_items_excerpt_visibility_control_show',
				),

				// Excerpt Visiblity
				// Title + Checkbox
				'portfolio_items_excerpt_visibility_title' => array(
					'type' => 'html',
					'html' => '<span class="customize-control-title">' . esc_html__( 'Excerpt Visibility', 'boilerplate' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Select whether to show or hide the summary.', 'boilerplate' ) . '</span>',
					'active_callback' => 'boilerplate_portfolio_items_excerpt_visibility_control_show',
				),
				'portfolio_items_excerpt_visibility'       => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Excerpt Text', 'boilerplate' ),
					'default' => 0,
					'active_callback' => 'boilerplate_portfolio_items_excerpt_visibility_control_show',
				),

				// [Sub Section] Items Meta
				'portfolio_grid_title_items_meta_section'               => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Items Meta', 'boilerplate' ) . '</span>',
				),

				'portfolio_items_primary_meta' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Primary Meta Section', 'boilerplate' ),
					'desc'    => esc_html__( 'Set the meta info that display around the title. ', 'boilerplate' ),
					'default' => 'none',
					'choices' => array(
						'none'     => esc_html__( 'None', 'boilerplate' ),
						'category' => esc_html__( 'Category', 'boilerplate' ),
						'author'   => esc_html__( 'Author', 'boilerplate' ),
						'date'     => esc_html__( 'Date', 'boilerplate' ),
						'tags'     => esc_html__( 'Tags', 'boilerplate' ),
						'comments' => esc_html__( 'Comments', 'boilerplate' ),
					),
				),

				'portfolio_items_secondary_meta' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Secondary Meta Section', 'boilerplate' ),
					'desc'    => '',
					'default' => 'category',
					'choices' => array(
						'none'     => esc_html__( 'None', 'boilerplate' ),
						'category' => esc_html__( 'Category', 'boilerplate' ),
						'author'   => esc_html__( 'Author', 'boilerplate' ),
						'date'     => esc_html__( 'Date', 'boilerplate' ),
						'tags'     => esc_html__( 'Tags', 'boilerplate' ),
						'comments' => esc_html__( 'Comments', 'boilerplate' ),
					),
				),

				// [Section] COLORS
				'portfolio_grid_title_colors_section'             => array(
					'type' => 'html',
					'html' => '<span id="section-title-portfolio-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'boilerplate' ) . '</span>',
				),
				'portfolio_item_title_color'             => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Item Title Color', 'boilerplate' ),
					'live'    => true,
					'default' => '#222222',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__title',
						),
					),
				),
				'portfolio_item_meta_primary_color'      => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Primary', 'boilerplate' ),
					'live'    => true,
					'default' => '#222222',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__meta-primary',
						),
					),
				),
				'portfolio_item_meta_secondary_color'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Meta Secondary', 'boilerplate' ),
					'live'    => true,
					'default' => '#818282',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.c-gallery--portfolio .c-card__meta-secondary',
						),
					),
				),
				'portfolio_item_thumbnail_background'    => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Thumbnail Background', 'boilerplate' ),
					'live'    => true,
					'default' => '#FFF',
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.c-gallery--portfolio .c-card__thumbnail-background',
						),
					),
				),

				// [Sub Section] Thumbnail Hover
				'portfolio_grid_title_thumbnail_hover_section'             => array(
					'type' => 'html',
					'html' => '<span class="separator sub-section label">' . esc_html__( 'Thumbnail Hover', 'boilerplate' ) . '</span><span class="description customize-control-description">' . esc_html__( 'Customize the mouse over effect for your thumbnails.', 'boilerplate' ) . '</span>',
				),
				'portfolio_item_thumbnail_hover_opacity' => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Thumbnail Background Opacity', 'boilerplate' ),
					'desc'        => '',
					'live'        => true,
					'default'     => 0.7,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 1,
						'step'         => 0.1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'opacity',
							'selector' => '.c-gallery--portfolio .c-card:hover .c-card__frame',
							'unit'     => '',
						),
					),
				),

				// [Section] FONTS
				'portfolio_grid_title_fonts_section'               => array(
					'type' => 'html',
					'html' => '<span id="section-title-portfolio-fonts" class="separator section label large">&#x1f4dd;  ' . esc_html__( 'Fonts', 'boilerplate' ) . '</span>',
				),

				'portfolio_item_title_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Title Font', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.c-gallery--portfolio .c-card__title',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => 'regular',
						'font-size'      => 17,
						'line-height'    => 1.5,
						'letter-spacing' => 0,
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),

				'portfolio_item_meta_font' => array(
					'type'     => 'font',
					'label'    => esc_html__( 'Item Meta Font', 'boilerplate' ),
					'desc'     => '',
					'selector' => '.c-gallery--portfolio .c-card__meta-primary, .c-gallery--portfolio .c-card__meta-secondary',
					'callback' => 'typeline_font_cb',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Roboto',
						'font-weight'    => '300',
						'font-size'      => 17,
						'line-height'    => 1.5,
						'letter-spacing' => '0',
						'text-transform' => 'none'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_body_fonts,

					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 90,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),
			),
		),
	);

	//Allow others to make changes
	$portfolio_grid_section = apply_filters( 'pixelgrade_customify_portfolio_grid_section_options', $portfolio_grid_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the portfolio grid section
	$options['sections'] = $options['sections'] + $portfolio_grid_section;

	return $options;
}

function boilerplate_add_customify_import_demo_options( $options ) {
	$import_demo_section = array(
		// Import Demo Data
		'import_demo_data' => array(
			'title'       => __( 'Demo Data', 'boilerplate' ),
			'description' => esc_html__( 'If you would like to have a "ready to go" website as the Boileplate\'s demo page here is the button', 'boilerplate' ),
			'priority'    => 999999,
			'options'     => array(
				'import_demodata_button' => array(
					'title' => esc_html__( 'Import', 'boilerplate' ),
					'type'  => 'html',
					'html'  => '<input type="hidden" name="wpGrade-nonce-import-posts-pages" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_posts_pages' ) . '" />
									<input type="hidden" name="wpGrade-nonce-import-theme-options" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_theme_options' ) . '" />
									<input type="hidden" name="wpGrade-nonce-import-widgets" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_widgets' ) . '" />
									<input type="hidden" name="wpGrade_import_ajax_url" value="' . admin_url( "admin-ajax.php" ) . '" />' .
					           '<span class="description customize-control-description">(' . esc_html__( 'Note: We cannot serve you the original images due the ', 'boilerplate' ) . '<strong>&copy;</strong>)</span></br>' .
					           '<a href="#" class="button button-primary" id="wpGrade_import_demodata_button" style="width: 70%; text-align: center; padding: 10px; display: inline-block; height: auto;  margin: 0 15% 10% 15%;">
										' . esc_html__( 'Import demo data', 'boilerplate' ) . '
									</a>

									<div class="wpGrade-loading-wrap hidden">
										<span class="wpGrade-loading wpGrade-import-loading"></span>
										<div class="wpGrade-import-wait">' .
					           esc_html__( 'Please wait a few minutes (between 1 and 3 minutes usually, but depending on your hosting it can take longer) and ', 'boilerplate' ) .
					           '<strong>' . esc_html__( 'don\'t reload the page', 'boilerplate' ) . '</strong>.' .
					           esc_html__( 'You will be notified as soon as the import has finished!', 'boilerplate' ) . '
										</div>
									</div>

									<div class="wpGrade-import-results hidden"></div>
									<div class="hr"><div class="inner"><span>&nbsp;</span></div></div>'
				)
			)
		)
	);

	//Allow others to make changes
	$import_demo_section = apply_filters( 'pixelgrade_customify_import_demo_section_options', $import_demo_section, $options );

	//make sure we are in good working order
	if ( empty( $options['sections'] ) ) {
		$options['sections'] = array();
	}

	//append the general section
	$options['sections'] = $options['sections'] + $import_demo_section;

	return $options;
}

/**
 * Returns the custom CSS rules for the portfolio grid spacing depending on the Customizer settings.
 *
 * @param mixed $value The value of the option.
 * @param string $selector The CSS selector for this option.
 * @param string $property The CSS property of the option.
 * @param string $unit The CSS unit used by this option.
 *
 * @return string
 */
function boilerplate_portfolio_grid_vertical_spacing_cb( $value, $selector, $property, $unit ) {

	$output = '';

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();

	// Some sanity check before processing the config
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];

		$ratio = 2.275;

		// from 80em
		$columns = pixelgrade_option( 'portfolio_items_per_row', 4 );
		$normal   = 'calc(' . ( 100 * $ratio / $columns . '%' ) . ' - ' . $value * $ratio . 'px);';
		$featured = 'calc(' . ( ( 200 * $ratio / $columns . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

		// 50em to 80em
		$columns_at_lap = $columns >= 5 ? $columns - 1 : $columns;
		$factor_at_lap = ( typeline_get_y( $value, $points ) - 1 ) * 1 / 3 + 1;
		$value_at_lap = round( $value / $factor_at_lap );
		$normal_at_lap   = 'calc(' . ( 100 * $ratio / $columns_at_lap . '%' ) . ' - ' . $value_at_lap * $ratio . 'px);';
		$featured_at_lap = 'calc(' . ( ( 200 * $ratio / $columns_at_lap . '%' ) . ' - ' . ( $value_at_lap * ( 2 * $ratio - 1 ) ) ) . 'px);';

		// 35em to 50em
		$columns_at_small = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;
		$factor_at_small = ( typeline_get_y( $value, $points ) - 1 ) * 2 / 3 + 1;
		$value_at_small = round( $value / $factor_at_small );
		$normal_at_small   = 'calc(' . ( 100 * $ratio / $columns_at_small . '%' ) . ' - ' . $value_at_small * $ratio . 'px);';
		$featured_at_small = 'calc(' . ( ( 200 * $ratio / $columns_at_small . '%' ) . ' - ' . ( $value_at_small * ( 2 * $ratio - 1 ) ) ) . 'px);';

		$output .=
			'.c-gallery--portfolio.c-gallery--packed,' . PHP_EOL .
			'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
				'margin-top: 0' .
			'}' . PHP_EOL .
			'@media only screen and (min-width: 35em) {' . PHP_EOL .
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
					'padding-top: ' . $normal_at_small . PHP_EOL .
					'margin-bottom: ' . $value_at_small . 'px' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
					'padding-top: ' . $featured_at_small . PHP_EOL .
				'}' . PHP_EOL .
			'}' . PHP_EOL .
			'@media only screen and (min-width: 50em) {' . PHP_EOL .
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
					'padding-top: ' . $normal_at_lap . PHP_EOL .
					'margin-bottom: ' . $value_at_lap . 'px' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
					'padding-top: ' . $featured_at_lap . PHP_EOL .
				'}' . PHP_EOL .
			'}' . PHP_EOL .
			'@media only screen and (min-width: 80em) {' . PHP_EOL .
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' . PHP_EOL .
					'padding-top: ' . $normal . PHP_EOL .
					'margin-bottom: ' . $value . 'px' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' . PHP_EOL .
					'padding-top: ' . $featured . PHP_EOL .
				'}' . PHP_EOL .
			'}' . PHP_EOL;

		$output .=
			'.c-gallery--portfolio {' . PHP_EOL .
				'margin-top: calc(-' . $value . 'px);' . PHP_EOL .
			'}' . PHP_EOL .
			'.c-gallery--portfolio .c-gallery__item {' . PHP_EOL .
				'margin-top: ' . $value . 'px;' . PHP_EOL .
			'}' . PHP_EOL;

		for ( $i = 0; $i < count( $breakpoints ); $i ++ ) {
			$ratio    = ( typeline_get_y( $value, $points ) - 1 ) * ( $i + 1 ) / count( $breakpoints ) + 1;
			$newValue = round( $value / $ratio );

			$output .=
				'@media only screen and (max-width: ' . $breakpoints[ $i ] . ') {' . PHP_EOL .
					'.c-gallery--portfolio {' . PHP_EOL .
						'margin-top: calc(-' . $newValue . 'px);' . PHP_EOL .
					'}' . PHP_EOL .
					'.c-gallery--portfolio .c-gallery__item {' . PHP_EOL .
						'margin-top: ' . $newValue . 'px;' . PHP_EOL .
					'}' . PHP_EOL .
				'}' . PHP_EOL;
		}
	}

	return $output;
}

function boilerplate_portfolio_grid_horizontal_spacing_cb( $value, $selector, $property, $unit ) {
	$output = '';

	$output .=
		'.c-gallery--portfolio {' . PHP_EOL .
		'margin-left: -' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--portfolio .c-gallery__item {' . PHP_EOL .
		'padding-left: ' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--portfolio.c-gallery--packed .c-card {' . PHP_EOL .
		'left: ' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL;

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();
	// Some sanity check before processing the config
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];


		for ( $i = 0; $i < count( $breakpoints ); $i ++ ) {
			$ratio    = ( typeline_get_y( $value, $points ) - 1 ) * ( $i + 1 ) / count( $breakpoints ) + 1;
			$newValue = round( $value / $ratio );

			$output .=
				'@media only screen and (max-width: ' . $breakpoints[ $i ] . ') {' . PHP_EOL .
				'.c-gallery--portfolio {' . PHP_EOL .
				'margin-left: -' . $newValue . 'px;' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--portfolio .c-gallery__item {' . PHP_EOL .
				'padding-left: ' . $newValue . 'px;' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--portfolio.c-gallery--packed .c-card {' . PHP_EOL .
				'left: ' . $newValue . 'px;' . PHP_EOL .
				'}' . PHP_EOL .
				'}' . PHP_EOL;
		}
	}

	return $output;
}

/**
 * Inline enqueues the JS code used in the Customizer for the portfolio grid spacing live preview.
 */
function boilerplate_portfolio_grid_vertical_spacing_cb_customizer_preview() {
	$js = '';

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();

	// Some sanity check before processing the config
	// There is no need for this code since we have nothing to work with
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];

		$js .= 'var points = [[' . $points[0][0] . ', ' . $points[0][1] . '], [' . $points[1][0] . ', ' . $points[1][1] . '], [' . $points[2][0] . ', ' . $points[2][1] . ']],
breakpoints = ["' . $breakpoints[0] . '", "' . $breakpoints[1] . '", "' . $breakpoints[2] . '"];

function getY( x ) {
	if ( x < points[1][0] ) {
		var a = points[0][1],
			b = (points[1][1] - points[0][1]) / Math.pow(points[1][0], 3);
		return a + b * Math.pow(x, 3);
	} else {
		return (points[1][1] + (points[2][1] - points[1][1]) * (x - points[1][0]) / (points[2][0] - points[1][0]));
	}
}' . PHP_EOL;

	}

	$js .= "
function boilerplate_portfolio_grid_vertical_spacing_cb( value, selector, property, unit ) {

	var css = '',
		style = document.getElementById('portfolio_grid_vertical_spacing_style_tag'),
		head = document.head || document.getElementsByTagName('head')[0];" . PHP_EOL;

	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {

	$js .= "

	var ratio = 2.275,
		columns = " . pixelgrade_option( 'portfolio_items_per_row', 4 ) . ",
		normal = 'calc(' + ( (100 * ratio / columns + '%') + ' - ' + ( value * ratio ) ) + 'px);',
		featured = 'calc(' + ( (200 * ratio / columns + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);',
		
		columns_at_lap = columns === 1 ? 1 : columns > 4 ? columns - 1 : columns,
		factor_at_lap = (getY(value) - 1) * 1 / 3 + 1,
		value_at_lap = Math.round(value / factor_at_lap),
		normal_at_lap = 'calc(' + ( (100 * ratio / columns_at_lap + '%') + ' - ' + ( value_at_lap * ratio ) ) + 'px);',
		featured_at_lap = 'calc(' + ( (200 * ratio / columns_at_lap + '%') + ' - ' + ( value_at_lap * (2 * ratio - 1) ) ) + 'px);',

		factor_at_small = (getY(value) - 1) * 2 / 3 + 1,
		value_at_small = Math.round(value / factor_at_small),
		columns_at_small = columns_at_lap > 1 ? columns_at_lap - 1 : columns_at_lap,
		normal_at_small = 'calc(' + ( (100 * ratio / columns_at_small + '%') + ' - ' + ( value_at_small * ratio ) ) + 'px);',
		featured_at_small = 'calc(' + ( (200 * ratio / columns_at_small + '%') + ' - ' + ( value_at_small * (2 * ratio - 1) ) ) + 'px);';

	css +=
		'.c-gallery--portfolio.c-gallery--packed,' +
		'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' +
			'margin-top: 0' +
		'}' +
		'@media only screen and (min-width: 35em) {' +
			'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal_at_small +
				'margin-bottom: ' + value_at_small +
			'}' +
			'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
				'padding-top: ' + featured_at_small +
			'}' +
		'}' +
		'@media only screen and (min-width: 50em) {' +
			'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal_at_lap +
				'margin-bottom: ' + value_at_lap +
			'}' +
			'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
				'padding-top: ' + featured_at_lap +
			'}' +
		'}' +
		'@media only screen and (min-width: 80em) {' +
			'.c-gallery--portfolio.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal +
				'margin-bottom: ' + value +
			'}' +
			'.c-gallery--portfolio.c-gallery--packed .c-gallery__item.jetpack-portfolio-tag-featured {' +
				'padding-top: ' + featured +
			'}' +
		'}';

	css += '.c-gallery--portfolio {' +
		'margin-top: calc(-' + value + 'px);' +
		'}' +
		'.c-gallery--portfolio .c-gallery__item {' +
		'margin-top: ' + value + 'px;' +
		'}';
		
	for ( var i = 0; i <= breakpoints.length - 1; i++ ) {
		var newRatio = (getY(value) - 1) * (i + 1) / breakpoints.length + 1,
			newValue = Math.round(value / newRatio);

		css += '@media only screen and (max-width: ' + breakpoints[i] + 'px) {' +
			'.c-gallery--portfolio {' +
			'margin-top: calc(-' + value + 'px);' +
			'}' +
			'.c-gallery--portfolio .c-gallery__item {' +
			'margin-top: ' + newValue + 'px;' +
			'}' +
			'}';
	}" . PHP_EOL;

	}

	$js .= "
	if ( style !== null ) {
		style.innerHTML = css;
	} else {
		style = document.createElement('style');
		style.setAttribute('id', 'portfolio_grid_spacing_style_tag');

		style.type = 'text/css';
		if ( style.styleSheet ) {
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);
	}
}" . PHP_EOL;

	wp_add_inline_script( 'customify-previewer-scripts', $js );
}
add_action( 'customize_preview_init', 'boilerplate_portfolio_grid_vertical_spacing_cb_customizer_preview', 20 );

/**
 * Inline enqueues the JS code used in the Customizer for the portfolio grid spacing live preview.
 */
function boilerplate_portfolio_grid_horizontal_spacing_cb_customizer_preview() {
	$js = '';

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();

	// Some sanity check before processing the config
	// There is no need for this code since we have nothing to work with
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];

		$js .= 'var points = [[' . $points[0][0] . ', ' . $points[0][1] . '], [' . $points[1][0] . ', ' . $points[1][1] . '], [' . $points[2][0] . ', ' . $points[2][1] . ']],
breakpoints = ["' . $breakpoints[0] . '", "' . $breakpoints[1] . '", "' . $breakpoints[2] . '"];

function getY( x ) {
	if ( x < points[1][0] ) {
		var a = points[0][1],
			b = (points[1][1] - points[0][1]) / Math.pow(points[1][0], 3);
		return a + b * Math.pow(x, 3);
	} else {
		return (points[1][1] + (points[2][1] - points[1][1]) * (x - points[1][0]) / (points[2][0] - points[1][0]));
	}
}' . PHP_EOL;

	}

	$js .= "
function boilerplate_portfolio_grid_horizontal_spacing_cb( value, selector, property, unit ) {

	var css = '',
		style = document.getElementById('portfolio_grid_horizontal_spacing_style_tag'),
		head = document.head || document.getElementsByTagName('head')[0];

	css += '.c-gallery--portfolio {' +
		'margin-left: -' + value + 'px;' +
		'}' +
		'.c-gallery--portfolio .c-gallery__item {' +
		'padding-left: ' + value + 'px;' +
		'}' +
		'.c-gallery--portfolio.c-gallery--packed .c-card {' +
		'left: ' + value + 'px;' +
		'}';" . PHP_EOL;

	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {

		$js .= "
	for ( var i = 0; i <= breakpoints.length - 1; i++ ) {
		var newRatio = (getY(value) - 1) * (i + 1) / breakpoints.length + 1,
			newValue = Math.round(value / newRatio);

		css += '@media only screen and (max-width: ' + breakpoints[i] + 'px) {' +
			'.c-gallery--portfolio {' +
			'margin-left: -' + value + 'px;' +
			'}' +
			'.c-gallery--portfolio .c-gallery__item {' +
			'padding-left: ' + newValue + 'px;' +
			'}' +
			'.c-gallery--portfolio.c-gallery--packed .c-card {' +
			'left: ' + newValue + 'px;' +
			'}' +
			'}';
	}" . PHP_EOL;

	}

	$js .= "
	if ( style !== null ) {
		style.innerHTML = css;
	} else {
		style = document.createElement('style');
		style.setAttribute('id', 'portfolio_grid_spacing_style_tag');

		style.type = 'text/css';
		if ( style.styleSheet ) {
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);
	}
}" . PHP_EOL;

	wp_add_inline_script( 'customify-previewer-scripts', $js );
}
add_action( 'customize_preview_init', 'boilerplate_portfolio_grid_horizontal_spacing_cb_customizer_preview', 20 );


/**
 * Returns the custom CSS rules for the blog grid spacing depending on the Customizer settings.
 *
 * @param mixed $value The value of the option.
 * @param string $selector The CSS selector for this option.
 * @param string $property The CSS property of the option.
 * @param string $unit The CSS unit used by this option.
 *
 * @return string
 */
function boilerplate_blog_grid_vertical_spacing_cb( $value, $selector, $property, $unit ) {

	$output = '';

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();

	// Some sanity check before processing the config
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];

		$ratio = 2.275;

		// from 80em
		$columns = pixelgrade_option( 'blog_items_per_row', 4 );
		$normal   = 'calc(' . ( 100 * $ratio / $columns . '%' ) . ' - ' . $value * $ratio . 'px);';
		$featured = 'calc(' . ( ( 200 * $ratio / $columns . '%' ) . ' - ' . ( $value * ( 2 * $ratio - 1 ) ) ) . 'px);';

		// 50em to 80em
		$columns_at_lap = $columns >= 5 ? $columns - 1 : $columns;
		$factor_at_lap = ( typeline_get_y( $value, $points ) - 1 ) * 1 / 3 + 1;
		$value_at_lap = round( $value / $factor_at_lap );
		$normal_at_lap   = 'calc(' . ( 100 * $ratio / $columns_at_lap . '%' ) . ' - ' . $value_at_lap * $ratio . 'px);';
		$featured_at_lap = 'calc(' . ( ( 200 * $ratio / $columns_at_lap . '%' ) . ' - ' . ( $value_at_lap * ( 2 * $ratio - 1 ) ) ) . 'px);';

		// 35em to 50em
		$columns_at_small = $columns_at_lap >= 4 ? $columns_at_lap - 1 : $columns_at_lap;
		$factor_at_small = ( typeline_get_y( $value, $points ) - 1 ) * 2 / 3 + 1;
		$value_at_small = round( $value / $factor_at_small );
		$normal_at_small   = 'calc(' . ( 100 * $ratio / $columns_at_small . '%' ) . ' - ' . $value_at_small * $ratio . 'px);';
		$featured_at_small = 'calc(' . ( ( 200 * $ratio / $columns_at_small . '%' ) . ' - ' . ( $value_at_small * ( 2 * $ratio - 1 ) ) ) . 'px);';

		$output .=
			'.c-gallery--blog.c-gallery--packed,' . PHP_EOL .
			'.c-gallery--blog.c-gallery--packed .c-gallery__item {' . PHP_EOL .
			'margin-top: 0' .
			'}' . PHP_EOL .
			'@media only screen and (min-width: 35em) {' . PHP_EOL .
			'.c-gallery--blog.c-gallery--packed .c-gallery__item {' . PHP_EOL .
			'padding-top: ' . $normal_at_small . PHP_EOL .
			'margin-bottom: ' . $value_at_small . 'px' . PHP_EOL .
			'}' . PHP_EOL .
			'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-blog-tag-featured {' . PHP_EOL .
			'padding-top: ' . $featured_at_small . PHP_EOL .
			'}' . PHP_EOL .
			'}' . PHP_EOL .
			'@media only screen and (min-width: 50em) {' . PHP_EOL .
			'.c-gallery--blog.c-gallery--packed .c-gallery__item {' . PHP_EOL .
			'padding-top: ' . $normal_at_lap . PHP_EOL .
			'margin-bottom: ' . $value_at_lap . 'px' . PHP_EOL .
			'}' . PHP_EOL .
			'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-blog-tag-featured {' . PHP_EOL .
			'padding-top: ' . $featured_at_lap . PHP_EOL .
			'}' . PHP_EOL .
			'}' . PHP_EOL .
			'@media only screen and (min-width: 80em) {' . PHP_EOL .
			'.c-gallery--blog.c-gallery--packed .c-gallery__item {' . PHP_EOL .
			'padding-top: ' . $normal . PHP_EOL .
			'margin-bottom: ' . $value . 'px' . PHP_EOL .
			'}' . PHP_EOL .
			'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-blog-tag-featured {' . PHP_EOL .
			'padding-top: ' . $featured . PHP_EOL .
			'}' . PHP_EOL .
			'}' . PHP_EOL;

		$output .=
			'.c-gallery--blog {' . PHP_EOL .
			'margin-top: calc(-' . $value . 'px);' . PHP_EOL .
			'}' . PHP_EOL .
			'.c-gallery--blog .c-gallery__item {' . PHP_EOL .
			'margin-top: ' . $value . 'px;' . PHP_EOL .
			'}' . PHP_EOL;

		for ( $i = 0; $i < count( $breakpoints ); $i ++ ) {
			$ratio    = ( typeline_get_y( $value, $points ) - 1 ) * ( $i + 1 ) / count( $breakpoints ) + 1;
			$newValue = round( $value / $ratio );

			$output .=
				'@media only screen and (max-width: ' . $breakpoints[ $i ] . ') {' . PHP_EOL .
				'.c-gallery--blog {' . PHP_EOL .
				'margin-top: calc(-' . $newValue . 'px);' . PHP_EOL .
				'}' . PHP_EOL .
				'.c-gallery--blog .c-gallery__item {' . PHP_EOL .
				'margin-top: ' . $newValue . 'px;' . PHP_EOL .
				'}' . PHP_EOL .
				'}' . PHP_EOL;
		}
	}

	return $output;
}

function boilerplate_blog_grid_horizontal_spacing_cb( $value, $selector, $property, $unit ) {
	$output = '';

	$output .=
		'.c-gallery--blog {' . PHP_EOL .
			'margin-left: -' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--blog .c-gallery__item {' . PHP_EOL .
			'padding-left: ' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL .
		'.c-gallery--blog.c-gallery--packed .c-card {' . PHP_EOL .
			'left: ' . $value . 'px;' . PHP_EOL .
		'}' . PHP_EOL;

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();
	// Some sanity check before processing the config
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];


		for ( $i = 0; $i < count( $breakpoints ); $i ++ ) {
			$ratio    = ( typeline_get_y( $value, $points ) - 1 ) * ( $i + 1 ) / count( $breakpoints ) + 1;
			$newValue = round( $value / $ratio );

			$output .=
				'@media only screen and (max-width: ' . $breakpoints[ $i ] . ') {' . PHP_EOL .
					'.c-gallery--blog {' . PHP_EOL .
						'margin-left: -' . $newValue . 'px;' . PHP_EOL .
					'}' . PHP_EOL .
					'.c-gallery--blog .c-gallery__item {' . PHP_EOL .
						'padding-left: ' . $newValue . 'px;' . PHP_EOL .
					'}' . PHP_EOL .
					'.c-gallery--blog.c-gallery--packed .c-card {' . PHP_EOL .
						'left: ' . $newValue . 'px;' . PHP_EOL .
					'}' . PHP_EOL .
				'}' . PHP_EOL;
		}
	}

	return $output;
}

/**
 * Inline enqueues the JS code used in the Customizer for the blog grid spacing live preview.
 */
function boilerplate_blog_grid_vertical_spacing_cb_customizer_preview() {
	$js = '';

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();

	// Some sanity check before processing the config
	// There is no need for this code since we have nothing to work with
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];

		$js .= 'var points = [[' . $points[0][0] . ', ' . $points[0][1] . '], [' . $points[1][0] . ', ' . $points[1][1] . '], [' . $points[2][0] . ', ' . $points[2][1] . ']],
breakpoints = ["' . $breakpoints[0] . '", "' . $breakpoints[1] . '", "' . $breakpoints[2] . '"];

function getY( x ) {
	if ( x < points[1][0] ) {
		var a = points[0][1],
			b = (points[1][1] - points[0][1]) / Math.pow(points[1][0], 3);
		return a + b * Math.pow(x, 3);
	} else {
		return (points[1][1] + (points[2][1] - points[1][1]) * (x - points[1][0]) / (points[2][0] - points[1][0]));
	}
}' . PHP_EOL;

	}

	$js .= "
function boilerplate_blog_grid_vertical_spacing_cb( value, selector, property, unit ) {

	var css = '',
		style = document.getElementById('blog_grid_vertical_spacing_style_tag'),
		head = document.head || document.getElementsByTagName('head')[0];" . PHP_EOL;

	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {

		$js .= "

	var ratio = 2.275,
		columns = " . pixelgrade_option( 'blog_items_per_row', 4 ) . ",
		normal = 'calc(' + ( (100 * ratio / columns + '%') + ' - ' + ( value * ratio ) ) + 'px);',
		featured = 'calc(' + ( (200 * ratio / columns + '%') + ' - ' + ( value * (2 * ratio - 1) ) ) + 'px);',
		
		columns_at_lap = columns === 1 ? 1 : columns > 4 ? columns - 1 : columns,
		factor_at_lap = (getY(value) - 1) * 1 / 3 + 1,
		value_at_lap = Math.round(value / factor_at_lap),
		normal_at_lap = 'calc(' + ( (100 * ratio / columns_at_lap + '%') + ' - ' + ( value_at_lap * ratio ) ) + 'px);',
		featured_at_lap = 'calc(' + ( (200 * ratio / columns_at_lap + '%') + ' - ' + ( value_at_lap * (2 * ratio - 1) ) ) + 'px);',

		factor_at_small = (getY(value) - 1) * 2 / 3 + 1,
		value_at_small = Math.round(value / factor_at_small),
		columns_at_small = columns_at_lap > 1 ? columns_at_lap - 1 : columns_at_lap,
		normal_at_small = 'calc(' + ( (100 * ratio / columns_at_small + '%') + ' - ' + ( value_at_small * ratio ) ) + 'px);',
		featured_at_small = 'calc(' + ( (200 * ratio / columns_at_small + '%') + ' - ' + ( value_at_small * (2 * ratio - 1) ) ) + 'px);';

	css +=
		'.c-gallery--blog.c-gallery--packed,' +
		'.c-gallery--blog.c-gallery--packed .c-gallery__item {' +
			'margin-top: 0' +
		'}' +
		'@media only screen and (min-width: 35em) {' +
			'.c-gallery--blog.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal_at_small +
				'margin-bottom: ' + value_at_small +
			'}' +
			'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-blog-tag-featured {' +
				'padding-top: ' + featured_at_small +
			'}' +
		'}' +
		'@media only screen and (min-width: 50em) {' +
			'.c-gallery--blog.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal_at_lap +
				'margin-bottom: ' + value_at_lap +
			'}' +
			'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-blog-tag-featured {' +
				'padding-top: ' + featured_at_lap +
			'}' +
		'}' +
		'@media only screen and (min-width: 80em) {' +
			'.c-gallery--blog.c-gallery--packed .c-gallery__item {' +
				'padding-top: ' + normal +
				'margin-bottom: ' + value +
			'}' +
			'.c-gallery--blog.c-gallery--packed .c-gallery__item.jetpack-blog-tag-featured {' +
				'padding-top: ' + featured +
			'}' +
		'}';

	css += '.c-gallery--blog {' +
		'margin-top: calc(-' + value + 'px);' +
		'}' +
		'.c-gallery--blog .c-gallery__item {' +
		'margin-top: ' + value + 'px;' +
		'}';
		
	for ( var i = 0; i <= breakpoints.length - 1; i++ ) {
		var newRatio = (getY(value) - 1) * (i + 1) / breakpoints.length + 1,
			newValue = Math.round(value / newRatio);

		css += '@media only screen and (max-width: ' + breakpoints[i] + 'px) {' +
			'.c-gallery--blog {' +
			'margin-top: calc(-' + value + 'px);' +
			'}' +
			'.c-gallery--blog .c-gallery__item {' +
			'margin-top: ' + newValue + 'px;' +
			'}' +
			'}';
	}" . PHP_EOL;

	}

	$js .= "
	if ( style !== null ) {
		style.innerHTML = css;
	} else {
		style = document.createElement('style');
		style.setAttribute('id', 'blog_grid_spacing_style_tag');

		style.type = 'text/css';
		if ( style.styleSheet ) {
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);
	}
}" . PHP_EOL;

	wp_add_inline_script( 'customify-previewer-scripts', $js );
}
add_action( 'customize_preview_init', 'boilerplate_blog_grid_vertical_spacing_cb_customizer_preview', 20 );

/**
 * Inline enqueues the JS code used in the Customizer for the blog grid spacing live preview.
 */
function boilerplate_blog_grid_horizontal_spacing_cb_customizer_preview() {
	$js = '';

	// Get the Typeline configuration for this theme
	$typeline_config = typeline_get_theme_config();

	// Some sanity check before processing the config
	// There is no need for this code since we have nothing to work with
	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {
		$points      = $typeline_config['spacings']['points'];
		$breakpoints = $typeline_config['spacings']['breakpoints'];

		$js .= 'var points = [[' . $points[0][0] . ', ' . $points[0][1] . '], [' . $points[1][0] . ', ' . $points[1][1] . '], [' . $points[2][0] . ', ' . $points[2][1] . ']],
breakpoints = ["' . $breakpoints[0] . '", "' . $breakpoints[1] . '", "' . $breakpoints[2] . '"];

function getY( x ) {
	if ( x < points[1][0] ) {
		var a = points[0][1],
			b = (points[1][1] - points[0][1]) / Math.pow(points[1][0], 3);
		return a + b * Math.pow(x, 3);
	} else {
		return (points[1][1] + (points[2][1] - points[1][1]) * (x - points[1][0]) / (points[2][0] - points[1][0]));
	}
}' . PHP_EOL;

	}

	$js .= "
function boilerplate_blog_grid_horizontal_spacing_cb( value, selector, property, unit ) {

	var css = '',
		style = document.getElementById('blog_grid_horizontal_spacing_style_tag'),
		head = document.head || document.getElementsByTagName('head')[0];

	css += '.c-gallery--blog {' +
			'margin-left: -' + value + 'px;' +
		'}' +
		'.c-gallery--blog .c-gallery__item {' +
			'padding-left: ' + value + 'px;' +
		'}' +
		'.c-gallery--blog.c-gallery--packed .c-card {' +
			'left: ' + value + 'px;' +
		'}';" . PHP_EOL;

	if ( ! empty( $typeline_config['spacings']['points'] ) && ! empty( $typeline_config['spacings']['breakpoints'] ) ) {

		$js .= "
	for ( var i = 0; i <= breakpoints.length - 1; i++ ) {
		var newRatio = (getY(value) - 1) * (i + 1) / breakpoints.length + 1,
			newValue = Math.round(value / newRatio);

		css += '@media only screen and (max-width: ' + breakpoints[i] + 'px) {' +
				'.c-gallery--blog {' +
					'margin-left: -' + value + 'px;' +
				'}' +
				'.c-gallery--blog .c-gallery__item {' +
					'padding-left: ' + newValue + 'px;' +
				'}' +
				'.c-gallery--blog.c-gallery--packed .c-card {' +
					'left: ' + newValue + 'px;' +
				'}' +
			'}';
	}" . PHP_EOL;

	}

	$js .= "
	if ( style !== null ) {
		style.innerHTML = css;
	} else {
		style = document.createElement('style');
		style.setAttribute('id', 'blog_grid_spacing_style_tag');

		style.type = 'text/css';
		if ( style.styleSheet ) {
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);
	}
}" . PHP_EOL;

	wp_add_inline_script( 'customify-previewer-scripts', $js );
}
add_action( 'customize_preview_init', 'boilerplate_blog_grid_horizontal_spacing_cb_customizer_preview', 20 );

/**
 * Returns the custom CSS rules for the aspect ratio depending on the Customizer settings.
 *
 * @param mixed $value The value of the option.
 * @param string $selector The CSS selector for this option.
 * @param string $property The CSS property of the option.
 * @param string $unit The CSS unit used by this option.
 *
 * @return string
 */
function boilerplate_aspect_ratio_cb( $value, $selector, $property, $unit ) {
	$min = 0;
	$max = 200;

	$value  = intval( $value );
	$center = ( $max - $min ) / 2;
	$offset = $value / $center - 1;

	if ( $offset >= 0 ) {
		$padding = 100 + $offset * 100 . '%';
	} else {
		$padding = 100 + $offset * 50 . '%';
	}

	$output = '';

	$output .= $selector . ' {' . PHP_EOL .
	           'padding-top: ' . $padding . ';' . PHP_EOL .
	           '}'. PHP_EOL;

	return $output;
}

/**
 * Outputs the inline JS code used in the Customizer for the aspect ratio live preview.
 */
function boilerplate_aspect_ratio_cb_customizer_preview() {

	$js = "
function boilerplate_aspect_ratio_cb( value, selector, property, unit ) {

    var css = '',
        style = document.getElementById('boilerplate_aspect_ratio_cb_style_tag'),
        head = document.head || document.getElementsByTagName('head')[0];

    var min = 0,
        max = 200,
        center = (max - min) / 2,
        offset = value / center - 1,
        padding;

    if ( offset >= 0 ) {
        padding = 100 + offset * 100 + '%';
    } else {
        padding = 100 + offset * 50 + '%';
    }

    css += selector + ' {' +
        'padding-top: ' + padding +
        '}';

    if ( style !== null ) {
        style.innerHTML = css;
    } else {
        style = document.createElement('style');
        style.setAttribute('id', 'boilerplate_aspect_ratio_cb_style_tag');

        style.type = 'text/css';
        if ( style.styleSheet ) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);
    }
}" . PHP_EOL;

	wp_add_inline_script( 'customify-previewer-scripts', $js );
}
add_action( 'customize_preview_init', 'boilerplate_aspect_ratio_cb_customizer_preview', 20 );

function boilerplate_add_customify_theme_fonts( $fonts ) {
	// Follow this example
//	$fonts['Woodford Bourne'] = array(
//		'family'   => 'Woodford Bourne',
//		'src'      => get_template_directory_uri() . '/assets/fonts/woodfordbourne/stylesheet.css',
//		'variants' => array( '300', '700' )
//	);

	return $fonts;
}

add_filter( 'customify_theme_fonts', 'boilerplate_add_customify_theme_fonts' );



/* ====================================
 * PORTFOLIO GRID CONTROLS CONDITIONALS
 * ==================================== */

/**
 * Decides when to show the project nearby alignment control.
 *
 * @return bool
 */
function boilerplate_portfolio_items_title_alignment_nearby_control_show() {
	$position = pixelgrade_option( 'portfolio_items_title_position' );
	// We hide it when displaying as overlay
	if ( 'overlay' == $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the project overlay alignment control.
 *
 * @return bool
 */
function boilerplate_portfolio_items_title_alignment_overlay_control_show() {
	$position = pixelgrade_option( 'portfolio_items_title_position' );
	// We hide it when not displaying as overlay
	if ( 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the portfolio show excerpt control.
 *
 * @return bool
 */
function boilerplate_portfolio_items_excerpt_visibility_control_show() {
	$layout = pixelgrade_option( 'portfolio_grid_layout' );
	$position = pixelgrade_option( 'portfolio_items_title_position' );

	// We hide it if the layout is packed or the position is overlay
	if ( 'overlay' == $position ) {
		return false;
	}

	return true;
}

/* ===============================
 * BLOG GRID CONTROLS CONDITIONALS
 * =============================== */

/**
 * Decides when to show the blog nearby alignment control.
 *
 * @return bool
 */
function boilerplate_blog_items_title_alignment_nearby_control_show() {
	$position = pixelgrade_option( 'blog_items_title_position' );
	// We hide it when displaying as overlay
	if ( 'overlay' == $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog overlay alignment control.
 *
 * @return bool
 */
function boilerplate_blog_items_title_alignment_overlay_control_show() {
	$position = pixelgrade_option( 'blog_items_title_position' );
	// We hide it when not displaying as overlay
	if ( 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog show excerpt control.
 *
 * @return bool
 */
function boilerplate_blog_items_excerpt_visibility_control_show() {
	$layout = pixelgrade_option( 'blog_grid_layout' );
	$position = pixelgrade_option( 'blog_items_title_position' );

	// We hide it if the layout is packed or the position is overlay
	if ( 'packed' == $layout || 'overlay' == $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog primary meta control.
 *
 * @return bool
 */
function boilerplate_blog_items_primary_meta_control_show() {
	$layout = pixelgrade_option( 'blog_grid_layout' );
	$position = pixelgrade_option( 'blog_items_title_position' );

	// We hide the primary meta when the layout is packed and we display above or below
	if ( 'packed' == $layout && 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog secondary meta control.
 *
 * @return bool
 */
function boilerplate_blog_items_secondary_meta_control_show() {
	$layout = pixelgrade_option( 'blog_grid_layout' );
	$position = pixelgrade_option( 'blog_items_title_position' );

	// We hide the primary meta when the layout is packed and we display above or below
	if ( 'packed' == $layout && 'overlay' != $position ) {
		return false;
	}

	return true;
}

/**
 * Decides when to show the blog items meta section title.
 *
 * @return bool
 */
function boilerplate_blog_items_meta_control_show() {
	$layout = pixelgrade_option( 'blog_grid_layout' );
	$position = pixelgrade_option( 'blog_items_title_position' );

	// We hide the item meta when the layout is packed and we display above or below
	if ( 'packed' == $layout && 'overlay' != $position ) {
		return false;
	}

	return true;
}

