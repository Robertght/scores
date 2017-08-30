<?php
/**
 * Template Name: Add Match Page
 */
get_header();
?>

    <form class="add-match" action="" id="primaryPostForm" method="POST">

        <div class="add-match-sport">
            <label for="sportType" hidden><?php _e('Sport type:', 'scores'); ?></label>
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
        </div>

        <div class="add-match-team add-match-home">
            <div class="add-match-content">
                <div class="add-match-avatar"></div>
                <div class="add-match-info">
                    <label for="firstUserName" hidden><?php _e( 'First player:', 'scores' ) ?></label>
                    <select class="add-match-player" name="firstUserName" id="firstUserName">
                        <?php
                        $opponent_users = get_users();
                        foreach ( $opponent_users as $user ) { ?>
                            <option data-avatar="<?php echo get_avatar_src( get_avatar( $user->ID )); ?>" value="<?php echo $user->ID; ?>" <?php if ( $user->ID == get_current_user_id() ) : echo 'selected="selected"'; endif; ?>><?php echo $user->data->display_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="add-match-controls">
                <button class="add-match-increase"><i class="fa fa-plus"></i></button>
                <button class="add-match-decrease"><i class="fa fa-minus"></i></button>
            </div>
        </div>

        <div class="add-match-team add-match-away">
            <div class="add-match-content">
                <div class="add-match-avatar"></div>
                <div class="add-match-info">
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
                            <option data-avatar="<?php echo get_avatar_src ( get_avatar ( $user->ID ) ); ?>" value="<?php echo $user->ID; ?>"><?php echo $user->data->display_name; ?></option>
                        <?php } ?>
                    </select>
                    <label for="secondUserScore" hidden><?php _e( 'Second user score:', 'framework' ) ?></label>
                    <input class="add-match-score" type="number" name="secondUserScore" id="secondUserScore" value="0"/>
                </div>
            </div>
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
	update_user_meta( $_POST['firstUserName'], 'experience', $match_points[0] );
	update_user_meta( $_POST['secondUserName'], 'experience', $match_points[1] );

	update_level( $_POST['firstUserName'] );
	update_level( $_POST['secondUserName'] );

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

get_footer();