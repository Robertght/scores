<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'single post' );

get_header(); ?>

	<div class="u-container-sides-spacings u-content-top-spacing u-content-bottom-spacing">
		<div class="o-wrapper u-container-width">

			<?php
			/**
			 * pixelgrade_before_main_content hook.
			 *
			 * @hooked nothing() - 10 (outputs nothing)
			 */
			do_action( 'pixelgrade_before_main_content', $location );
			?>

			<div id="primary" class="content-area o-layout">
				<main id="main" class="site-main o-layout__main" role="main">

					<?php
					/**
					 * pixelgrade_before_loop hook.
					 *
					 * @hooked nothing - 10 (outputs nothing)
					 */
					do_action( 'pixelgrade_before_loop', $location );
					?>

					<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content-single', get_post_format() );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>

					<?php
					/**
					 * pixelgrade_after_loop hook.
					 *
					 * @hooked nothing - 10 (outputs nothing)
					 */
					do_action( 'pixelgrade_after_loop', $location );
					?>
				</main><!-- #main -->

				<?php get_sidebar(); ?>

			</div><!-- #primary -->

			<?php
			/**
			 * pixelgrade_after_main_content hook.
			 *
			 * @hooked nothing - 10 (outputs nothing)
			 */
			do_action( 'pixelgrade_after_main_content', $location );
			?>

		</div>
	</div>

<?php
get_footer();
