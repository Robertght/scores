<?php
/**
 * The template used for displaying post content on archives
 *
 * @package Boilerplate
 * @since Boilerplate 1.0.0
 */

//we first need to know the bigger picture - the location this template part was loaded from
$location = pixelgrade_get_location( 'post' );
?>

<?php
/**
 * pixelgrade_before_loop_entry hook.
 *
 * @hooked boilerplate_custom_page_css() - 10 (outputs the page's custom css)
 */
do_action( 'pixelgrade_before_loop_entry', $location );
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class() ?>>
		<div class="c-card">
			<?php if ( boilerplate_display_featured_images() ) { ?>
				<div class="c-card__aside c-card__thumbnail-background">
					<div class="c-card__frame">
						<?php if ( has_post_thumbnail() ) {
							the_post_thumbnail();
						}

						if ( pixelgrade_option( 'blog_items_title_position', 'regular' ) != 'overlay' ) {
							echo '<span class="c-card__letter">' . substr( get_the_title(), 0, 1 ) . '</span>';
						}
						?>
					</div><!-- .c-card__frame -->
				</div><!-- .c-card__aside -->
			<?php } ?>
			<div class="c-card__content">
				<?php
				$meta = boilerplate_get_post_meta();

				$blog_items_primary_meta   = pixelgrade_option( 'blog_items_primary_meta', 'category', false );
				$blog_items_secondary_meta = pixelgrade_option( 'blog_items_secondary_meta', 'date', false );

				$primary_meta   = $blog_items_primary_meta !== 'none' && ! empty( $meta[ $blog_items_primary_meta ] ) ? $meta[ $blog_items_primary_meta ] : '';
				$secondary_meta = $blog_items_secondary_meta !== 'none' && ! empty( $meta[ $blog_items_secondary_meta ] ) ? $meta[ $blog_items_secondary_meta ] : '';

				if ( $primary_meta || $secondary_meta ): ?>

					<div class='c-card__meta c-meta'>

						<?php
						if ( $primary_meta ) {
							echo '<div class="c-card__meta-primary">' . $primary_meta . '</div>';

							if ( $secondary_meta ) {
								echo '<div class="c-card__meta-separator"></div>';
							}
						}

						if ( $secondary_meta ) {
							echo '<div class="c-card__meta-secondary">' . $secondary_meta . '</div>';
						} ?>

					</div><!-- .c-card__meta -->

				<?php endif; ?>

				<?php if ( pixelgrade_option( 'blog_items_title_visibility', true ) && get_the_title() ) { ?>
					<h2 class="c-card__title"><span><?php the_title(); ?></span></h2>
				<?php }

				if ( pixelgrade_option( 'blog_items_excerpt_visibility', true ) ) { ?>
					<div class="c-card__excerpt"><?php the_excerpt(); ?></div>
				<?php } ?>

			</div><!-- .c-card__content -->
			<a class="c-card__link" href="<?php the_permalink(); ?>"></a>
		</div>
	</article><!-- #post-<?php the_ID(); ?> -->

<?php
/**
 * pixelgrade_after_loop_entry hook.
 *
 * @hooked nothing() - 10 (outputs nothing)
 */
do_action( 'pixelgrade_after_loop_entry', $location );
