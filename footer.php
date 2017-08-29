
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
?>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info">
        <?php
        /**
         * Fires before the Twenty Fifteen footer text for footer customization.
         *
         * @since Twenty Fifteen 1.0
         */
        do_action( 'twentyfifteen_credits' );
        ?>
        <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'twentyfifteen' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentyfifteen' ), 'WordPress' ); ?></a>
    </div><!-- .site-info -->
</footer><!-- .site-footer -->

</body>
</html>