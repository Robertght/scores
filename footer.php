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

    <?php
    /**
     * pixelgrade_before_footer hook.
     *
     * @hooked nothing() - 10 (outputs nothing)
     */
    do_action( 'pixelgrade_before_footer', 'main' );
    ?>

    <?php
    /**
     * pixelgrade_footer hook.
     *
     * @hooked pixelgrade_the_footer() - 10 (outputs the footer markup)
     */
    do_action( 'pixelgrade_footer', 'main' );
    ?>

    <?php
    /**
     * pixelgrade_after_footer hook.
     *
     * @hooked nothing() - 10 (outputs nothing)
     */
    do_action( 'pixelgrade_after_footer', 'main' );
    ?>

</div><!-- .barba-container -->

<?php wp_footer(); ?>

<div class="c-border"></div>
<div class="c-progress">
	<div class="c-progress__indicator js-reading-progress"></div>
</div>

</div><!-- #barba-wrapper -->

</body>
</html>
