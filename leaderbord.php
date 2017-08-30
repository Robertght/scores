<?php /* Template Name: Leaderbord */ ?>

<?php get_header(); ?>

<?php
$blogusers = get_users();
// Array of stdClass objects.
?><div class="leaderbord">
    <div class="row-title"> Leaderboard </div>
    <ul><?php foreach ( $blogusers as $user ) { ?>
    <li><a href="<?php echo '/author/' . $user->data->user_login; ?>">
            <div class="row-group">
                <?php echo get_avatar( $user->ID ); ?>
                <?php echo '<div class="user-name">' . esc_html( $user->display_name ) .'</div>'?> </div>
            <div class="row-group">
                <?php echo '<div class="row-score">' . 15 .'</div>'?>
                <?php echo '<div class="row-xp">' . 1242 .'</div>'?>
            </div>
    </a></li>
<?php } ?>
</div></ul>

<?php get_footer(); ?>