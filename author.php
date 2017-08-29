<?php
// Set the Current Author Variable $curauth
$curauth = wp_get_current_user();
?>

<?php get_header(); ?>

<div class="author-profile-card">
	<div class="user-photo">
		<?php echo get_avatar( $curauth->user_email, '90 ' ); ?>
	</div>
	<h2 class="user-name"> <?php echo $curauth->nickname; ?></h2>
	<div class="user-xp">
		Level <?php echo get_user_meta( get_current_user_id(), 'experience', true ); ?>
	</div>
	<div class="user-badges">
		<div class="row-title badges-title">Badges</div>
		<?php
		$args        = array(
			'post_type' => 'badges',
			'post__in'  => get_user_meta( get_current_user_id(), 'badges', false ),
		);
		$posts_array = get_posts( $args );
		?>
		<ul><?php
			foreach ( $posts_array as $badge ) { ?>
				<li><a href="<?php echo get_permalink( $badge->ID ); ?>">
					<?php echo get_the_post_thumbnail( $badge->ID ); ?>
				</a></li>
				<?php } ?>
		</ul>
	</div>
</div>

<?php get_footer(); ?>
