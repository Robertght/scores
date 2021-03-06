
<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

$curauth = wp_get_current_user();

?>

<ul class="footer-nav">
    <li><a href="<?php echo get_page_link ( get_option( 'page_on_front' ) ); ?>"><i class="fa fa-home"></i>Home</a></li>
    <li><a href="<?php echo get_permalink( get_page_by_path( 'leaderboard' ) )?>"><i class="fa fa-list-ol"></i>Leaderboard</a></li>
    <li><a href="<?php echo get_post_type_archive_link( 'badges' ); ?>"><i class="fa fa-trophy"></i>Badges</a></li>
    <li><a href="<?php echo get_author_posts_url(wp_get_current_user()->ID) ?>"><i class="fa fa-user"></i>Profile</a></li>
</ul>

</body>
</html>