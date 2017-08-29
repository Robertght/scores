<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'page' );
if ( is_front_page() ) {
	// Add some more contextual info
	$location = pixelgrade_set_location( 'front-page', true );
}

get_header(); ?>

<?php
/**
 * pixelgrade_before_main_content hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_before_main_content', $location );
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			/**
			 * pixelgrade_before_loop hook.
			 *
			 * @hooked nothing - 10 (outputs nothing)
			 */
			do_action( 'pixelgrade_before_loop', $location );
			?>

			<?php while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comments template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

			endwhile; // End of the loop. ?>

			<?php
			/**
			 * pixelgrade_after_loop hook.
			 *
			 * @hooked Pixelgrade_Multipage->the_subpages() - 10 (outputs the subpages)
			 */
			do_action( 'pixelgrade_after_loop', $location );
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
/**
 * pixelgrade_after_main_content hook.
 *
 * @hooked nothing - 10 (outputs nothing)
 */
do_action( 'pixelgrade_after_main_content', $location );
?>


<?php
get_footer();
