<?php
/**
 * Template Name: Add Match Page
 */
?>

	<form action="" id="primaryPostForm" method="POST">

		<fieldset>
			<label for="firstUserName"><?php _e( 'First player:', 'scores' ); ?></label>
			<select name="firstUserName" id="firstUserName">
				<?php
				$opponent_users = get_users();
				foreach ( $opponent_users as $user ) { ?>
					<option value="<?php echo $user->ID; ?>" <?php if ( $user->ID == get_current_user_id() ) : echo 'selected="selected"'; endif; ?>><?php echo $user->data->display_name; ?></option>
				<?php } ?>
			</select>
		</fieldset>

		<fieldset>
			<label for="secondUserName"><?php _e( 'Second player:', 'scores' ); ?></label>
			<select name="secondUserName" id="secondUserName">
				<?php
				$args = array(
					'exclude' => array(
						get_current_user_id()
					),
				);
				$opponent_users = get_users( $args );
				foreach ( $opponent_users as $user ) { ?>
					<option value="<?php echo $user->ID; ?>"><?php echo $user->data->display_name; ?></option>
				<?php } ?>
			</select>
		</fieldset>

		<fieldset>
			<label for="sportType"><?php _e('Sport type:', 'scores'); ?></label>
			<select name="sportType" id="sportType">
				<?php
				$sports = get_terms( array(
					'taxonomy' => 'sport',
					'hide_empty' => false,
					)
				);
				foreach ( $sports as $sport ) { ?>
					<option value="<?php echo $sport->name; ?>"><?php echo $sport->name; ?></option>
				<?php } ?>
			</select>
		</fieldset>

		<fieldset>
			<label for="firstUserScore"><?php _e( 'First user score:', 'framework' ); ?></label>
			<input type="number" name="firstUserScore" id="firstUserScore" class="required"/>
		</fieldset>

		<fieldset>
			<label for="secondUserScore"><?php _e( 'Second user score:', 'framework' ) ?></label>
			<input type="number" name="secondUserScore" id="secondUserScore" class="required"/>
		</fieldset>

		<fieldset>
			<input type="hidden" name="submitted" id="submitted" value="true"/>
			<button type="submit"><?php _e( 'Add Match', 'framework' ) ?></button>
		</fieldset>

	</form>

<?php

if ( isset( $_POST['submitted'] ) ) {

	$post_information = array(
		'post_title'  => get_user_by( 'ID', $_POST['firstUserName'] )->data->display_name . ' vs ' . get_user_by( 'ID', $_POST['secondUserName'] )->data->display_name,
		'meta_input'  => array(
			'_scores_first_player_ID'    => $_POST['firstUserName'],
			'_scores_second_player_ID'    => $_POST['secondUserName'],
			'_scores_first_player_score'  => $_POST['firstUserScore'],
			'_scores_second_player_score' => $_POST['secondUserScore'],
		),
		'tax_input' => array(
			'sport' => $_POST['sportType'],
		),
		'post_type'   => 'matches',
		'post_status' => 'publish'
	);

	// get actual score for both players
	$first_user_score = get_user_meta ( $_POST['firstUserName'], 'experience' , true );
	$second_user_score = get_user_meta ( $_POST['secondUserName'], 'experience' , true );
	$match_points = calculate_experience($_POST['firstUserScore'], $_POST['secondUserScore']);
	if ( $_POST['firstUserScore'] > $_POST['secondUserScore'] ) {
		check_badges( $_POST['firstUserName'],  $_POST['firstUserScore'], 'win', $_POST['sportType']);
		check_badges( $_POST['secondUserName'],  $_POST['secondUserScore'], 'lose', $_POST['sportType']);
	} else if ( $_POST['firstUserScore'] < $_POST['secondUserScore'] ){
		check_badges( $_POST['firstUserName'],  $_POST['firstUserScore'], 'lose', $_POST['sportType']);
		check_badges( $_POST['secondUserName'],  $_POST['secondUserScore'], 'win', $_POST['sportType']);
	} else {
		check_badges( $_POST['firstUserName'],  $_POST['firstUserScore'], 'deuce', $_POST['sportType']);
		check_badges( $_POST['secondUserName'],  $_POST['secondUserScore'], 'deuce', $_POST['sportType']);
	}

	wp_insert_post( $post_information );
}