<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Boilerplate
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( 'post single' );

// Get the class corresponding to the aspect ratio of the post featured image
$featured_image_orientation = boilerplate_get_post_thumbnail_aspect_ratio_class(); ?>

<?php
/**
 * pixelgrade_before_loop_entry hook.
 *
 * @hooked boilerplate_custom_page_css() - 10 (outputs the page's custom css)
 */
do_action( 'pixelgrade_before_loop_entry', $location );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<div class="u-content-width  o-wrapper">
			<div><?php the_date(); ?></div>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<div></div>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content  o-wrapper  u-content-width">
		<?php
		the_content( sprintf(
		/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'boilerplate' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		) );

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'boilerplate' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer  o-wrapper  u-content-width">
		<?php boilerplate_the_author_info_box(); ?>
		<?php boilerplate_entry_footer(); ?>
		<?php boilerplate_the_post_navigation(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->

<?php
/**
 * pixelgrade_after_loop_entry hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_after_loop_entry', $location );
?>
