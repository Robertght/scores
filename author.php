<?php
// Set the Current Author Variable $curauth
$curauth = get_queried_object();
?>

<?php get_header(); ?>

<div class="author-profile-card">
	<div class="user-photo">
		<?php echo get_avatar( $curauth->ID, '140' ); ?>
	</div>
	<h2 class="user-name"> <?php echo $curauth->nickname; ?></h2>
	<div class="user-level">
		Level <?php echo get_user_meta( $curauth->ID, 'experience_level' )[0]; ?>
	</div>
	<div class="user-xp">
		<div class="user-xp-label">XP <?php echo get_user_meta( $curauth->ID, 'experience' )[0]; ?>
			/ <?php echo get_xp_for_level( get_user_meta( $curauth->ID, 'experience_level' )[0] ); ?>
		</div>
		<div class="user-xp-progress">
			<div class="user-xp-progress-bar" style="width:
			<?php
			echo progress_percentage_calculator(
				get_xp_for_level( get_user_meta( $curauth->ID, 'experience_level' )[0] - 1 ),
				get_xp_for_level( get_user_meta( $curauth->ID, 'experience_level' )[0] ),
				get_user_meta( $curauth->ID, 'experience' )[0]
			);
			?>%"></div>
		</div>
	</div>

</div>

<div class="row-widgets">
	<div class="user-badges">
		<div class="row-title badges-title">Badges</div>
		<?php
		$args        = array(
			'post_type' => 'badges',
			'include'   => explode( ',', get_user_meta( $curauth->ID, 'badges', false )[0] ),
			'order'     => 'ASC',
		);
		$posts_array = get_posts( $args );
		?>
		<ul><?php
			foreach ( $posts_array as $badge ) { ?>
				<li>
					<a href="<?php echo get_permalink( $badge->ID ); ?>">
						<?php echo get_the_post_thumbnail( $badge->ID ); ?>
					</a>
				</li>
			<?php } ?>
		</ul>
    </div>
    <div class="matches">
		<div class="row-title">Ultimele Meciuri</div>
		<?php
		$args           = array(
			'post_type'   => 'matches',
			'meta_key'    => '_scores_first_player_ID',
			'meta_value'  => $curauth->ID,
			'numberposts' => 5,
		);
		$latest_matches = get_posts( $args );
		?>
		<ul><?php
			foreach ( $latest_matches as $match ) {
                $homeplayer = get_userdata( get_post_meta( $match->ID, '_scores_first_player_ID', true ) );
                $homename = $homeplayer->first_name;
                $awayplayer = get_userdata( get_post_meta( $match->ID, '_scores_second_player_ID', true ) );
                $awayname = $awayplayer->first_name;
                ?>
				<li class="<?php if ( get_post_meta( $match->ID, '_scores_first_player_score', true ) >= get_post_meta( $match->ID, '_scores_second_player_score', true ) ) { echo "won"; } else { echo "lose"; } ?>">
					<div class="first_player_avatar"><?php echo get_avatar( get_post_meta( $match->ID, '_scores_first_player_ID', true ), 48 ); ?></div>
					<div class="first_player_name"><?php echo $homename; ?></div>
					<div class="first_player_score"><?php echo get_post_meta( $match->ID, '_scores_first_player_score', true ); ?></div>
					<div class="second_player_score"><?php echo get_post_meta( $match->ID, '_scores_second_player_score', true ); ?></div>
					<div class="second_player_name"><?php echo $awayname; ?></div>
					<div class="second_player_avatar"><?php echo get_avatar( get_post_meta( $match->ID, '_scores_second_player_ID', true ), 48 ); ?></div>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>

<?php get_footer(); ?>
