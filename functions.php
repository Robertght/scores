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
				       class="regular-text"/><br/>
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
	update_level( $user_id );
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
			'supports'    => array( 'title' ),
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
			'labels'       => array(
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
			'rewrite'      => array( 'slug' => 'sport' ),
			'hierarchical' => false,
		)
	);
}

function add_theme_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

	wp_enqueue_script( 'localized-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ) );
	wp_localize_script( 'localized-script', 'localized_script', array(
			'users' => get_users(),
		)
	);
}

add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

function calculate_experience( $score1, $score2 ) {
	$response    = array( 50, 50 ); // because each user played the match, we start from 50 xp
	$response[0] += $score1 * 30;
	$response[1] += $score2 * 30;
	if ( $score1 > $score2 ) {
		$response[0] += 250;
	} else {
		$response[1] += 250;
	}

	return $response;
}

function calculate_level( $experience_value ) {
	/*
	 * 1 - 0
	 * 2 - 1000
	 * 3 - 3000
	 * 4 - 6000
	 * 5 - 10000
	 * 6 - 15000
	 * 7 - 21000
	 */
	$xp_levels = array(
			0 => 0,
			1 => 1000,
			2 => 3000,
			3 => 6000,
			4 => 10000,
			5 => 15000,
			6 => 21000
	);
	foreach ($xp_levels as $key => $value ) {
		if ( $experience_value[0] < $value ) {
			return $key;
		}
	}
}

function update_level( $userID ) {
	$user_experience = get_user_meta( $userID, 'experience');
	$user_level = calculate_level( $user_experience );
	update_user_meta( $userID, 'experience_level', $user_level );
}

function get_xp_for_level ( $level ) {
	/*
	 * 1 - 0
	 * 2 - 1000
	 * 3 - 3000
	 * 4 - 6000
	 * 5 - 10000
	 * 6 - 15000
	 * 7 - 21000
	 */
	$xp_levels = array(0, 1000, 3000, 6000, 10000, 15000, 21000);

	foreach ($xp_levels as $key => $value ) {
		if ( $level == $key ) {
			return $value;
		}
	}
}

function progress_percentage_calculator ( $min, $max, $val ) {
	$max = $max - $min;
	$val = $val - $min;
	return $val / $max * 100;
}

function add_badges ( $userID ) {
	$badges_array = array(31, 33, 37, 39, 41, 43, 44, 45, 49, 51, 53, 55, 57, 59, 61, 63);

	$overall_wins = get_user_meta( $userID, 'overall_wins');
	$streak_wins = get_user_meta( $userID, 'streak_wins');
	$fifa_matches = get_user_meta( $userID, 'fifa_matches');
	$tenis_matches = get_user_meta( $userID, 'tenis_matches');
	$badges_string = '';

	switch ( $overall_wins[0] ) {
		case '1':
			$badges_string .= $badges_array[0];
			$badges_string .= ',';
			break;
		case '5':
			$badges_string .= $badges_array[1];
			$badges_string .= ',';
			break;
		case '10':
			$badges_string .= $badges_array[2];
			$badges_string .= ',';
			break;
		case '50':
			$badges_string .= $badges_array[3];
			$badges_string .= ',';
			break;
	}

	switch ( $streak_wins[0] ) {
		case '3':
			$badges_string .= $badges_array[4];
			$badges_string .= ',';
			break;
		case '5':
			$badges_string .= $badges_array[5];
			$badges_string .= ',';
			break;
		case '10':
			$badges_string .= $badges_array[6];
			$badges_string .= ',';
			break;
		case '20':
			$badges_string .= $badges_array[7];
			$badges_string .= ',';
			break;
	}

	switch ( $fifa_matches[0] ) {
		case '1':
			$badges_string .= $badges_array[8];
			$badges_string .= ',';
			break;
		case '10':
			$badges_string .= $badges_array[9];
			$badges_string .= ',';
			break;
		case '25':
			$badges_string .= $badges_array[10];
			$badges_string .= ',';
			break;
		case '50':
			$badges_string .= $badges_array[11];
			$badges_string .= ',';
			break;
	}

	switch ( $tenis_matches[0] ) {
		case '3':
			$badges_string .= $badges_array[12];
			$badges_string .= ',';
			break;
		case '5':
			$badges_string .= $badges_array[13];
			$badges_string .= ',';
			break;
		case '10':
			$badges_string .= $badges_array[14];
			$badges_string .= ',';
			break;
		case '20':
			$badges_string .= $badges_array[15];
			$badges_string .= ',';
			break;
	}
	update_user_meta( $userID, 'badges' , $badges_string);
}

function check_badges( $userID, $score, $win_status, $sportType ) {
	// badges: streak_wins, overall_wins, fifa_matches, tennis_matches
//update_user_meta( $userID, 'streak_wins', 0);
//update_user_meta( $userID, 'overall_wins', 0);
//update_user_meta( $userID, 'fifa_matches', 0);
//update_user_meta( $userID, 'tenis_matches', 0);
//die;
	$streak_wins = get_user_meta( $userID, 'streak_wins' );
	$overall_wins = get_user_meta( $userID, 'overall_wins' ) ;

	if ( $win_status == 'win' ) {
		if ( $streak_wins ) {
			update_user_meta( $userID, 'streak_wins', $streak_wins[0] + 1);
		} else {
			update_user_meta( $userID, 'streak_wins', 1);
		}
		if ( $overall_wins ) {
			update_user_meta( $userID, 'overall_wins', $overall_wins[0] + 1);
		} else {
			update_user_meta( $userID, 'overall_wins', 1);
		}
	} else if ( ( $win_status == 'deuce' ) || ( $win_status == 'lose') ) {
		if ( $streak_wins ) {
			update_user_meta( $userID, 'streak_wins', 0 );
		}
	}

	switch ( $sportType ) {
		case 'Fifa':
			$fifa_meta = get_user_meta( $userID, 'fifa_matches');
//			var_dump($fifa_meta);
//			die;
			if ( $fifa_meta ) {
				update_user_meta( $userID, 'fifa_matches', $fifa_meta[0] + 1 );
			} else {
				update_user_meta( $userID, 'fifa_matches', 1 );
			}
			break;
		case 'Tenis':
			$tenis_meta = get_user_meta( $userID, 'tenis_matches');
			if ( $tenis_meta ) {
				update_user_meta( $userID, 'tenis_matches', ( $tenis_meta[0] + 1 ) );
			} else {
				update_user_meta( $userID, 'tenis_matches', 1 );
				break;
			}
	}

	add_badges( $userID );
}

function get_avatar_src ( $avatar_img ) {
	$array = array();
	preg_match( '/src="([^"]*)"/i', $avatar_img, $array ) ;
	return $array[1];
}
