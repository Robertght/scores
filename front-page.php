<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<?php
$args           = array(
	'post_type'   => 'matches',
	'meta_key'    => '_scores_first_player_ID',
	'meta_value'  => $curauth->ID,
	'numberposts' => 5,
);
$home_matches = get_posts( $args );
?>
	<div class="home-matches">
		<div class="row-title">Matches happening right now!</div>
		<ul><?php
			foreach ( $home_matches as $match ) { ?>
				<li>
					<div class="first_player_avatar"><?php echo get_avatar( get_post_meta( $match->ID, '_scores_first_player_ID', true ), 48 ); ?></div>
					<div class="first_player_name"><?php echo get_userdata( get_post_meta( $match->ID, '_scores_first_player_ID', true ) )->display_name; ?></div>
					<div class="first_player_score"><?php echo get_post_meta( $match->ID, '_scores_first_player_score', true ); ?></div>
					<div class="second_player_score"><?php echo get_post_meta( $match->ID, '_scores_second_player_score', true ); ?></div>
					<div class="second_player_name"><?php echo get_userdata( get_post_meta( $match->ID, '_scores_second_player_ID', true ) )->display_name; ?></div>
					<div class="second_player_avatar"><?php echo get_avatar( get_post_meta( $match->ID, '_scores_second_player_ID', true ), 48 ); ?></div>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php get_footer(); ?>