<?php /* Template Name: Badges */ ?>
<?php
    $curauth = wp_get_current_user();
    $badges = get_user_meta( $curauth->ID, 'badges', true );
    $badges = array_values(array_map('intval', array_map('trim', explode( ',', $badges ))));
    $owned = in_array( get_the_ID(), $badges ); ?>
<?php get_header(); ?>

	<div class="single-badge">
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="badge_thumbnail"> <?php the_post_thumbnail(); ?> </div>
			<div class="badge_title"> <?php the_title(); ?> </div>
            <?php if ( $owned ) { ?>
                <div class="badge_owned"> Owned </div>
            <?php } else { ?>
                <div class="badge_owned not"> Not Owned </div>
            <?php } ?>

			<div class="badge_content"> <?php the_content(); ?> </div>
		<?php endwhile; ?>
	</div>

<?php get_footer(); ?>