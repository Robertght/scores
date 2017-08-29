<?php
/**
 * Guides required or recommended plugins
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

require_once get_template_directory()  . '/inc/required-plugins/class-tgm-plugin-activation.php';

function boilerplate_register_required_plugins() {
	$plugins = array(
		array(
			'name'               => 'Pixelgrade Care',
			'slug'               => 'pixelgrade-care',
			'force_activation'   => true,
			'force_deactivation' => true,
			'required'           => true,
			'source'             => 'https://wupdates.com/api_wupl_version/JxbVe/2v5t1czd3vw4kmb5xqmyxj1kkwmnt9q0463lhj393r5yxtshdyg05jssgd4jglnfx7A2vdxtfdcf78r9r1sm217k4ht3r2g7pkdng5f6tgwyrk23wryA0pjxvs7gwhhb',
			'external_url'       => 'https://github.com/pixelgrade/pixelgrade_care',
			'version'            => '1.2.3',
			'is_automatic'       => true
		),
		array(
			'name'               => 'Customify',
			'slug'               => 'customify',
			'required'           => true
		),
		array(
			'name'               => 'Gridable',
			'slug'               => 'gridable',
			'required'           => true
		),
		array(
			'name'               => 'PixTypes',
			'slug'               => 'pixtypes',
			'required'           => true
		),
		array(
			'name'               => 'Jetpack',
			'slug'               => 'jetpack',
			'required'           => false,
		),
	);

	$config = array(
		'domain'           => 'boilerplate', // Text domain - likely want to be the same as your theme.
		'default_path'     => '', // Default absolute path to pre-packaged plugins
		'menu'             => 'install-required-plugins', // Menu slug
		'has_notices'      => true, // Show admin notices or not
		'is_automatic'     => false, // Automatically activate plugins after installation or not
		'message'          => '', // Message to output right before the plugins table
		'strings'          => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'boilerplate' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'boilerplate' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'boilerplate' ),
			// %1$s = plugin name
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'boilerplate' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'boilerplate' ),
			// %1$s = plugin name(s)
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'boilerplate' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'boilerplate' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'boilerplate' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'boilerplate' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'boilerplate' )
			// %1$s = dashboard link
		)
	);

	tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'boilerplate_register_required_plugins', 999 );
