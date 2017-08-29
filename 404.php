<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

//let the template parts know about our location
$location = pixelgrade_set_location( '404' );

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

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<section class="error-404 not-found">
						<header class="entry-header u-content-width">
							<h1 class="entry-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'boilerplate' ); ?></h1>
						</header><!-- .page-header -->

						<div class="entry-content u-content-width">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'boilerplate' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</section><!-- .error-404 -->

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

		</div>
	</div>

<?php
get_footer();
