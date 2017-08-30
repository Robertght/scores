<?php /* Template Name: Leaderbord */ ?>

<?php get_header(); ?>

<?php
$args = array(
	'meta_key' => 'experience',
	'order' => 'ASC',
);
$blogusers = get_users( $args ); ?>
	<div class="leaderbord">
		<div class="row-title"> Leaderboard</div>
		<ul><?php foreach ( $blogusers as $user ) { ?>
				<li>
                    <a href="<?php echo '/author/' . $user->data->user_login; ?>">
						<div class="row-group">
							<?php echo get_avatar( $user->ID ); ?>
							<?php echo '<div class="user-name">' . esc_html( $user->display_name ) . '</div>' ?> </div>
						<div class="row-group">
							<?php echo '<div class="row-score">Level ' . get_user_meta( $user->ID, 'experience_level', true ) . '</div>' ?>
							<?php
                            if ( get_user_meta( $user->ID, 'experience', true ) ) {
                                echo '<div class="row-xp">' . get_user_meta( $user->ID, 'experience', true ) . '</div>';
                            } else {
                                echo '<div class="row-xp">0</div>';
                            } ?>

						</div>
					</a>
                </li>
			<?php } ?>
		</ul>
	</div>

<?php get_footer(); ?>