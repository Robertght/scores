<?php
/**
 * Template Name: Add Match Page
 */
get_header();
?>

    <form class="add-match" action="" id="primaryPostForm" method="POST">

        <div class="add-match-team add-match-home">
            <label for="firstUserName" hidden><?php _e( 'First player:', 'scores' ) ?></label>
            <select class="add-match-player" name="firstUserName" id="firstUserName">
                <?php
                $opponent_users = get_users();
                foreach ( $opponent_users as $user ) { ?>
                    <option value="<?php echo $user->ID; ?>" <?php if ( $user->ID == get_current_user_id() ) : echo 'selected="selected"'; endif; ?>><?php echo $user->data->display_name; ?></option>
                <?php } ?>
            </select>
            <label for="firstUserScore" hidden><?php _e( 'First user score:', 'framework' ) ?></label>
            <input class="add-match-score" type="number" name="firstUserScore" id="firstUserScore" value="0"/>
            <div class="add-match-controls">
                <button class="add-match-increase"><i class="fa fa-plus"></i></button>
                <button class="add-match-decrease"><i class="fa fa-minus"></i></button>
            </div>
        </div>

        <div class="add-match-team add-match-away">
            <label for="secondUserName" hidden><?php _e( 'Second player:', 'scores' ) ?></label>
            <select class="add-match-player" name="secondUserName" id="secondUserName">
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
            <label for="secondUserScore" hidden><?php _e( 'Second user score:', 'framework' ) ?></label>
            <input class="add-match-score" type="number" name="secondUserScore" id="secondUserScore" value="0"/>
            <div class="add-match-controls">
                <button class="add-match-increase"><i class="fa fa-plus"></i></button>
                <button class="add-match-decrease"><i class="fa fa-minus"></i></button>
            </div>
        </div>

        <div class="add-match-confirm">
            <input type="hidden" name="submitted" id="submitted" value="true"/>
            <button type="submit"><?php _e( 'Add Match', 'framework' ) ?></button>
        </div>

    </form>

<?php

if ( isset( $_POST['submitted'] ) ) {
	experience_calculator($_POST['firstUserScore'], $_POST['secondUserScore']);
	$post_information = array(
		'post_title'  => get_user_by( 'ID', $_POST['firstUserName'] )->data->display_name . ' vs ' . get_user_by( 'ID', $_POST['secondUserName'] )->data->display_name,
		'meta_input'  => array(
			'_scores_first_player_ID'    => $_POST['firstUserName'],
			'_scores_second_player_ID'    => $_POST['secondUserName'],
			'_scores_first_player_score'  => $_POST['firstUserScore'],
			'_scores_second_player_score' => $_POST['secondUserScore'],
		),
		'post_type'   => 'matches',
		'post_status' => 'publish'
	);

	// get actual score for both players
	$first_user_score = get_user_meta ( $_POST['firstUserName'], 'experience' , true );
	$second_user_score = get_user_meta ( $_POST['secondUserName'], 'experience' , true );
	$match_points = experience_calculator($_POST['firstUserScore'], $_POST['secondUserScore']);
	wp_insert_post( $post_information );
}

get_footer();