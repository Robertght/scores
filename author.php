<?php
// Set the Current Author Variable $curauth
$curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );
?>

<div class="author-profile-card">
	<div class="user-photo">
		<?php echo get_avatar( $curauth->user_email, '90 ' ); ?>
	</div>
	<h2>About: <?php echo $curauth->nickname; ?></h2>
	<div class="user-xp">
		<?php echo get_user_meta( get_current_user_id(), 'experience', true ); ?>
	</div>
	<div class="user-badges">
		<div class="badges-title">Badges</div>
		<?php
		$args        = array(
			'post_type' => 'badges',
			'post__in'  => get_user_meta( get_current_user_id(), 'badges', false ),
		);
		$posts_array = get_posts( $args );
		?><ul><?php
			foreach ( $posts_array as $badge ) {
			?><li><?php
				echo get_the_post_thumbnail( $badge->ID );
			?></li><?php
			}
		?></ul><?php
		?>
	</div>
</div>