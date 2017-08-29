<?php /* Template Name: Leaderbord */ ?>

<?php get_header(); ?>

<?php
$blogusers = get_users( array( 'fields' => array( 'display_name' ) ) );
// Array of stdClass objects.
foreach ( $blogusers as $user ) {
    echo '<span>' . esc_html( $user->display_name ) . '</span>';
}

?>
<?php get_footer(); ?>