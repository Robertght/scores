<?php
/**
 *
 * The template for displaying archive pages for portfolio.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Boilerplate
 * @since   Boilerplate 1.0.0
 */

//let the template parts know about our location
$location = 'archive portfolio jetpack';
pixelgrade_set_location( $location );

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
		<main id="main" class="site-main  u-portfolio-sides-spacing" role="main">
			<div class="o-wrapper  u-portfolio-grid-width">

				<?php
				/**
				 * pixelgrade_before_entry_title hook.
				 *
				 * @hooked pixelgrade_the_hero() - 10 (outputs the hero markup)
				 */
				do_action( 'pixelgrade_before_entry_title', $location );
				?>

					<header class="entry-header c-page-header">
						<h1 class="entry-title"><?php esc_html_e( 'Projects', 'noah' ); ?></h1>
						<?php boilerplate_the_taxonomy_dropdown( 'category' ); ?>
						<?php echo term_description(); ?>
					</header>

				<?php
				/**
				 * pixelgrade_after_entry_title hook.
				 *
				 * @hooked nothing() - 10 (outputs nothing)
				 */
				do_action( 'pixelgrade_after_entry_title', $location ); ?>

				<?php if ( have_posts() ) : ?>

					<div id="posts-container">
						<div <?php boilerplate_portfolio_class( '', $location ); ?>>

							<?php while ( have_posts() ) : the_post();
								get_template_part( 'template-parts/content', 'jetpack-portfolio' );
							endwhile; ?>

						</div>
					</div>

				<?php else :
					get_template_part( 'template-parts/content', 'none' );
				endif; ?>

				<?php the_posts_navigation( array(
					'prev_text'          => esc_html__( 'Older projects', 'boilerplate' ),
					'next_text'          => esc_html__( 'Newer projects', 'boilerplate' ),
					'screen_reader_text' => esc_html__( 'Projects navigation', 'boilerplate' ),
				) ); ?>

			</div> <!-- .o-wrapper .u-portfolio-grid-width -->
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

<?php get_footer();
