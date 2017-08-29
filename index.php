<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( 'index' );

get_header(); ?>

<?php
/**
 * pixelgrade_before_main_content hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_before_main_content', $location );
?>

	<div class="u-blog-sides-spacing">
		<div class="o-wrapper  u-blog-grid-width">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<?php

					if ( have_posts() ) : /* Start the Loop */ ?>

						<div id="posts-container" <?php boilerplate_blog_class( '', $location ); ?>>
							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php
								/**
								 * Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', get_post_format() ); ?>
							<?php endwhile; ?>
						</div>
						<?php the_posts_navigation(); ?>

					<?php else : ?>
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					<?php endif; ?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div> <!-- .o-wrapper .u-blog-grid-width -->
	</div><!-- .u-blog-sides-spacing -->

<?php
/**
 * pixelgrade_after_main_content hook.
 *
 * @hooked nothing - 10 (outputs nothing)
 */
do_action( 'pixelgrade_after_main_content', $location );
?>

<?php get_footer();
