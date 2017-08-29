<?php
/**
 *
 * The template for displaying blog page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

//let the template parts know about our location
// We use the hero-blog pseudo-location so we can tell the Hero component
$location = pixelgrade_set_location( 'page blog hero-blog' );

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
					/**
					 * pixelgrade_before_entry_title hook.
					 *
					 * @hooked pixelgrade_the_hero() - 10 (outputs the hero markup)
					 */
					do_action( 'pixelgrade_before_entry_title', $location );
					?>

					<?php // allow others to prevent this from displaying
					if ( apply_filters( 'pixelgrade_display_entry_header', true, $location ) ) { ?>
						<header class="entry-header c-page-header">
							<h1 class="entry-title"><?php echo get_the_title( get_option( 'page_for_posts', true ) ); ?></h1>
							<div><?php boilerplate_the_taxonomy_dropdown( 'category' ); ?></div>
						</header>
					<?php } ?>

					<?php
					/**
					 * pixelgrade_after_entry_title hook.
					 *
					 * @hooked nothing() - 10 (outputs nothing)
					 */
					do_action( 'pixelgrade_after_entry_title', $location ); ?>

					<?php if ( have_posts() ) : ?>

						<div id="posts-container" <?php boilerplate_blog_class( '', $location ); ?>>
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
