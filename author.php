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
	<div class="user-level">
		Level <?php echo get_user_meta( get_current_user_id(), 'experience', true ); ?>
	</div>
    <div class="user-xp">
        <div class="user-xp-label">XP 150 / 1000</div>
        <div class="user-xp-progress">
            <div class="user-xp-progress-bar" style="width: 80%"></div>
        </div>
    </div>
	<div class="user-badges">
		<div class="row-title badges-title">Badges</div>
		<?php
		$args        = array(
			'post_type' => 'badges',
			'include'  => explode ( ',', get_user_meta( get_current_user_id(), 'badges', false )[0]),
			'order' => 'ASC',
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
        <div class="row-title badges-title">Ultimele Meciuri</div>
	</div>
</div>

<?php get_footer(); ?>
