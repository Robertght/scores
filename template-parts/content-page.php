<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Boilerplate
 * @since Boilerplate 1.0.0
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( 'page' );

// Here get the content width class
$content_width_class = 'u-content-width';

if ( pixelgrade_in_location( 'full-width', $location ) ) {
	$content_width_class = 'u-content-width--full';
}
?>

<?php
/**
 * pixelgrade_before_loop_entry hook.
 *
 * @hooked boilerplate_custom_page_css() - 10 (outputs the page's custom css)
 */
do_action( 'pixelgrade_before_loop_entry', $location );
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php
		/**
		 * pixelgrade_before_entry_title hook.
		 *
		 * @hooked pixelgrade_the_hero() - 10 (outputs the hero markup)
		 */
		do_action( 'pixelgrade_before_entry_title', $location ); ?>

		<header class="entry-header u-content-width">

			<div class="u-content-top-spacing">
				<?php
				// allow others to prevent this from displaying
				if ( apply_filters( 'pixelgrade_display_entry_header', true, $location ) ) {
					the_title( '<h1 class="entry-title">', '</h1>' );
				} else {
					the_title( '<h1 class="entry-title  screen-reader-text">', '</h1>' );
				} ?>
			</div>

			<?php
			/**
			 * pixelgrade_after_entry_title hook.
			 *
			 * @hooked nothing() - 10 (outputs nothing)
			 */
			do_action( 'pixelgrade_after_entry_title', $location );
			?>

		</header><!-- .entry-header -->

		<div class="u-container-sides-spacings">
			<div class="o-wrapper u-container-width">
				<div class="entry-content u-content-width">
					<?php
					the_content();

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'boilerplate' ),
						'after'  => '</div>',
					) );
					?>

					<?php if ( get_edit_post_link() ) : ?>
						<footer class="entry-footer">
							<?php
							edit_post_link(
								sprintf(
								/* translators: %s: Name of current post */
									esc_html__( 'Edit %s', 'boilerplate' ),
									the_title( '<span class="screen-reader-text">"', '"</span>', false )
								),
								'<span class="edit-link">',
								'</span>'
							);
							?>
						</footer><!-- .entry-footer -->
					<?php endif; ?>
				</div><!-- .u-content-width -->
			</div>
		</div>

	</article><!-- #post-## -->

<?php
/**
 * pixelgrade_after_loop_entry hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_after_loop_entry', $location );
