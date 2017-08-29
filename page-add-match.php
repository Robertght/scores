<?php
/**
 * Template Name: Add Match Page
 */
?>
<?php
var_dump( get_users( array(
		'exclude ' => 1,
	)
) );?>
	<form action="" id="primaryPostForm" method="POST">

		<fieldset>
			<label for="opponentUser"><?php _e( 'Opponent:', 'scores' ) ?></label>
			<select name="opponentUser" id="opponentUser">
				<?php
				$opponent_users = get_users( array(
						'exclude ' => array(
								1,
						),
					)
				);
				foreach ( $opponent_users as $user ) { ?>
					<option value="<?php echo $user->ID; ?>"><?php echo $user->data->display_name; ?></option>
				<?php } ?>
			</select>
		</fieldset>

		<fieldset>
			<label for="firstUserScore"><?php _e( 'First user score:', 'framework' ) ?></label>
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
		'post_title'  => get_user_by( 'ID', get_current_user_id() )->data->display_name . ' vs ' . get_user_by( 'ID', $_POST['opponentUser'] )->data->display_name,
		'meta_input'  => array(
			'_scores_opponent_user_ID'    => $_POST['opponentUser'],
			'_scores_first_player_score'  => $_POST['firstUserScore'],
			'_scores_second_player_score' => $_POST['secondUserScore'],
		),
		'post_type'   => 'matches',
		'post_status' => 'publish'
	);

	wp_insert_post( $post_information );
}