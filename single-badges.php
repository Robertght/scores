<?php /* Template Name: Badges */ ?>

<?php get_header(); ?>

<div class="single-badge">
    <?php while (have_posts() ) : the_post(); ?>
    <div class="badge_thumbnail"> <?php the_post_thumbnail(); ?> </div>
    <div class="badge_title"> <?php the_title(); ?> </div>
    <div class="badge_owned"> Owned </div>
    <div class="badge_content"> <?php the_content(); ?> </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>