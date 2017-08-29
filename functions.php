<?php


add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
	<h3><?php _e( "Gaming info", "blank" ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="experience"><?php _e( "XP" ); ?></label></th>
			<td>
				<input type="text" name="experience" id="experience"
				       value="<?php echo esc_attr( get_the_author_meta( 'experience', $user->ID ) ); ?>"
				       class="regular-text" readonly/><br/>
				<span class="description"><?php _e( "This is all that you need to know." ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="badges"><?php _e( "Badges" ); ?></label></th>
			<td>
				<input type="text" name="badges" id="badges"
				       value="<?php echo esc_attr( get_the_author_meta( 'badges', $user->ID ) ); ?>"
				       class="regular-text"/><br/>
				<span class="description"><?php _e( "These are all the badges the user has." ); ?></span>
			</td>
		</tr>
	</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_user_meta( $user_id, 'experience', $_POST['experience'] );
	update_user_meta( $user_id, 'badges', $_POST['badges'] );
}

function matched_register_post_type() {
	register_post_type( 'matches',
		array(
			'labels'      => array(
				'name'          => __( 'Matches' ),
				'singular_name' => __( 'Match' ),
				'view_item'     => __( 'View match page' ),
			),
			'public'      => true,
			'has_archive' => true,
			'menu_icon'   => 'dashicons-groups',
			'description' => 'This is where everything begins',
			'supports'    => array('title'),
		)
	);
}

function badges_register_post_type() {
	register_post_type( 'badges',
		array(
			'labels'      => array(
				'name'          => __( 'Badges' ),
				'singular_name' => __( 'Badge' ),
				'view_item'     => __( 'View badge' ),
			),
			'public'      => true,
            'has_archive' => true,
			'description' => 'Badges for players',
			'menu_icon'   => 'dashicons-tickets-alt',
			'supports'    => array( 'title', 'editor', 'thumbnail' ),
		) );
}

add_theme_support( 'post-thumbnails' );

add_action( 'init', 'matched_register_post_type' );
add_action( 'init', 'badges_register_post_type' );

add_action( 'cmb2_admin_init', 'cmb2_sample_metaboxes' );

/**
 * Define the metabox and field configurations.
 */
function cmb2_sample_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_scores_';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'           => 'players',
		'title'        => __( 'Game options', 'cmb2' ),
		'object_types' => array( 'matches', ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	// Status of the match
	$cmb->add_field( array(
		'name'             => 'Status',
		'desc'             => 'Select an option',
		'id'               => $prefix . 'match_status',
		'type'             => 'select',
		'show_option_none' => false,
		'default'          => 'pending',
		'options'          => array(
			'pending' => __( 'Pending', 'cmb2' ),
			'active'  => __( 'Active', 'cmb2' ),
			'done'    => __( 'Done', 'cmb2' ),
			'cancel'  => __( 'Cancel', 'cmb2' )
		),
	) );

	// First player
	$cmb->add_field( array(
		'name' => 'First player ID',
		'id'   => $prefix . 'first_player_ID',
		'desc' => 'The ID of the first player',
		'type' => 'text_small',
	) );

	// Second player
	$cmb->add_field( array(
		'name' => 'Second player ID',
		'id'   => $prefix . 'second_player_ID',
		'desc' => 'The ID of the second player',
		'type' => 'text_small',
	) );

	// First player score
	$cmb->add_field( array(
		'name' => __( 'Player 1 score', 'cmb2' ),
		'desc' => __( 'Score of the first player', 'cmb2' ),
		'id'   => $prefix . 'first_player_score',
		'type' => 'text_small',
	) );

	// First player score
	$cmb->add_field( array(
		'name' => __( 'Player 2 score', 'cmb2' ),
		'desc' => __( 'Score of the second player', 'cmb2' ),
		'id'   => $prefix . 'second_player_score',
		'type' => 'text_small',
	) );
}

add_action( 'init', 'create_book_tax' );

function create_book_tax() {
	register_taxonomy(
		'sport',
		'matches',
		array(
			'labels' => array(
				'name'                       => _x( 'Sport', 'taxonomy general name', 'textdomain' ),
				'singular_name'              => _x( 'Sport', 'taxonomy singular name', 'textdomain' ),
				'search_items'               => __( 'Search Sport', 'textdomain' ),
				'popular_items'              => __( 'Popular Sports', 'textdomain' ),
				'all_items'                  => __( 'All Sports', 'textdomain' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Sport', 'textdomain' ),
				'update_item'                => __( 'Update Sport', 'textdomain' ),
				'add_new_item'               => __( 'Add New Sport', 'textdomain' ),
				'new_item_name'              => __( 'New Sport Name', 'textdomain' ),
				'separate_items_with_commas' => __( 'Separate sports with commas', 'textdomain' ),
				'add_or_remove_items'        => __( 'Add or remove sports', 'textdomain' ),
				'choose_from_most_used'      => __( 'Choose from the most used sports', 'textdomain' ),
				'not_found'                  => __( 'No sports found.', 'textdomain' ),
				'menu_name'                  => __( 'Sports', 'textdomain' ),
			),
			'rewrite' => array( 'slug' => 'sport' ),
			'hierarchical' => false,
		)
	);
}

function add_theme_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
//    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/build/transformed.js' );


	wp_enqueue_script('localized-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ));
	wp_localize_script('localized-script', 'localized_script', array(
			'users' => get_users(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

function experience_calculator ( $score1, $score2 ) {
	$response = array(50, 50); // because each user played the match, we start from 50 xp
	$response[0] += $score1 * 30;
	$response[1] += $score2 * 30;
	if ( $score1 > $score2 ) {
		$response[0] += 250;
	} else {
		$response[1] += 250;
	}

	return $response;
}

function check_badges ( $score, $badges_array ) {

	return ;
}
