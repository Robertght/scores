<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Boilerplate
 */

if ( ! function_exists( 'boilerplate_the_posts_pagination' ) ) {
	/**
	 * Displays a paginated navigation to next/previous set of posts, when applicable.
	 *
	 * @param array $args Optional. See paginate_links() for available arguments.
	 *                    Default empty array.
	 */
	function boilerplate_the_posts_pagination( $args = array() ) {
		echo boilerplate_get_the_posts_pagination( $args );
	}
}

if ( ! function_exists( 'boilerplate_get_the_posts_pagination' ) ) {
	/**
	 * Retrieves a paginated navigation to next/previous set of posts, when applicable.
	 *
	 * @param array $args Optional. See paginate_links() for options.
	 *
	 * @return string Markup for pagination links.
	 */
	function boilerplate_get_the_posts_pagination( $args = array() ) {
		// Put our own defaults in place
		$args = wp_parse_args( $args, array(
			'end_size'           => 1,
			'mid_size'           => 2,
			'type'               => 'list',
			'prev_text'          => esc_html_x( '&laquo; Previous', 'previous set of posts', 'boilerplate' ),
			'next_text'          => esc_html_x( 'Next &raquo;', 'next set of posts', 'boilerplate' ),
			'screen_reader_text' => esc_html__( 'Posts navigation', 'boilerplate' ),
		) );

		return get_the_posts_pagination( $args );
	}
}

if ( ! function_exists( 'boilerplate_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function boilerplate_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
		/* translators: %s: The current post's posted date, in the post header */
			esc_html_x( '%s', 'post date', 'boilerplate' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
		/* translators: %s: Name of current post's author, in the post header */
			esc_html_x( '%s', 'post author', 'boilerplate' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<div class="post-meta"><span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span></div>'; // WPCS: XSS OK.

	}
}

if ( ! function_exists( 'boilerplate_entry_footer' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 *
	 * @param int|WP_Post $post_id Optional.
	 */
	function boilerplate_entry_footer( $post_id = null ) {

		if ( ! is_single( $post_id ) && ! post_password_required( $post_id ) && ( comments_open( $post_id ) || get_comments_number( $post_id ) ) ) {
			echo '<span class="comments-link">';
			/* translators: %s: post title */
			comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'boilerplate' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title( $post_id ) ) );
			echo '</span>';
		}

		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'boilerplate' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>',
			$post_id
		);
	}
}

if ( ! function_exists( 'boilerplate_get_main_category_link' ) ) {
	/**
	 * Returns an anchor of the main category of a post
	 *
	 * @since Boilerplate 1.0
	 *
	 * @param string $before
	 * @param string $after
	 * @param string $category_class Optional. A CSS class that the category will receive.
	 *
	 * @return string
	 */
	function boilerplate_get_main_category_link( $before = '', $after = '', $category_class = '' ) {
		$category = boilerplate_get_main_category();

		// Bail if we have nothing to work with
		if ( empty( $category ) || is_wp_error( $category ) ) {
			return '';
		}

		$class_markup = '';

		if ( ! empty( $category_class ) ) {
			$class_markup = 'class="' . $category_class . '" ';
		}
		return $before . '<a ' . $class_markup . ' href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( $category->name ) . '">' . $category->name . '</a>' . $after;

	} #function
}

if ( ! function_exists( 'boilerplate_the_main_category_link' ) ) {
	/**
	 * Prints an anchor of the main category of a post
	 *
	 * @since Boilerplate 1.0
	 *
	 * @param string $before
	 * @param string $after
	 * @param string $category_class Optional. A CSS class that the category will receive.
	 */
	function boilerplate_the_main_category_link( $before = '', $after = '', $category_class = '' ) {
		echo boilerplate_get_main_category_link( $before, $after, $category_class );

	} #function
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function boilerplate_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'boilerplate_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'boilerplate_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so boilerplate_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so boilerplate_categorized_blog should return false.
		return false;
	}
}

if ( ! function_exists( 'boilerplate_the_author_info_box' ) ) {
	function boilerplate_the_author_info_box() {
		echo boilerplate_get_the_author_info_box();
	}
}

if ( ! function_exists( 'boilerplate_get_the_author_info_bo' ) ) {

	function boilerplate_get_the_author_info_box() {
		// Get the current post for easy use
		$post = get_post();

		// Bail if no post
		if ( empty( $post ) ) {
			return '';
		}

		$options            = get_theme_support( 'jetpack-content-options' );
		$author_bio         = ( ! empty( $options[0]['author-bio'] ) ) ? $options[0]['author-bio'] : null;
		$author_bio_default = ( isset( $options[0]['author-bio-default'] ) && false === $options[0]['author-bio-default'] ) ? '' : 1;

		// If the theme doesn't support 'jetpack-content-options[ 'author-bio' ]', don't continue.
		if ( true !== $author_bio ) {
			return;
		}

		// If 'jetpack_content_author_bio' is false, don't continue.
		if ( ! get_option( 'jetpack_content_author_bio', $author_bio_default ) ) {
			return;
		}

		// If we aren't on a single post, don't continue.
		if ( ! is_single() ) {
			return;
		}

		$author_details = '';

		// Detect if it is a single post with a post author
		if ( is_single() && isset( $post->post_author ) ) {

			// Get author's display name
			$display_name = get_the_author_meta( 'display_name', $post->post_author );

			// If display name is not available then use nickname as display name
			if ( empty( $display_name ) ) {
				$display_name = get_the_author_meta( 'nickname', $post->post_author );
			}

			// Get author's biographical information or description
			$user_description = get_the_author_meta( 'user_description', $post->post_author );


			if ( ! empty( $user_description ) ) {
				$author_details .= '<div class="c-author has-description" itemscope itemtype="http://schema.org/Person">';
			} else {
				$author_details .= '<div class="c-author" itemscope itemtype="http://schema.org/Person">';
			}

			// Get link to the author archive page
			$user_posts = get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) );

			$author_avatar = get_avatar( get_the_author_meta( 'user_email' ), 100 );

			if ( ! empty( $author_avatar ) ) {
				$author_details .= '<div class="c-author__avatar">' . $author_avatar . '</div>';
			}

			$author_details .= '<div class="c-author__details">';

			if ( ! empty( $display_name ) ) {
				$author_details .= '<span class="c-author__name h3">' . $display_name . '</span>';
			}

			// Author avatar and bio
			if ( ! empty( $user_description ) ) {
				$author_details .= '<p class="c-author__description" itemprop="description">' . nl2br( $user_description ) . '</p>';
			}

			$author_details .= '<footer class="c-author__footer  h6">';

			$author_details .= '<a class="c-author__link-website" href="' . esc_url( $user_posts ) . '" rel="author" title="' . esc_attr( sprintf( __( 'View all posts by %s', 'boilerplate' ), get_the_author() ) ) . '">' . esc_html__( 'All posts', 'boilerplate' ) . '</a>';

			$author_details .= boilerplate_get_author_bio_links( $post->ID );

			$author_details .= '</footer>';
			$author_details .= '</div><!-- .c-author__details -->';
			$author_details .= '</div><!-- .c-author -->';
		}

		return $author_details;
	}
}

if ( ! function_exists( 'boilerplate_get_author_bio_links' ) ) {
	/**
	 * Return the markup for the author bio links.
	 * These are the links/websites added by one to it's Gravatar profile
	 *
	 * @param int|WP_Post $post_id Optional. Post ID or post object.
	 * @return string The HTML markup of the author bio links list.
	 */
	function boilerplate_get_author_bio_links( $post_id = null ) {
		$post = get_post( $post_id );
		$markup = '';
		if ( empty( $post ) ) {
			return $markup;
		}

		// Get author's website URL
		$user_website = get_the_author_meta( 'url', $post->post_author );

		$str = wp_remote_fopen( 'https://www.gravatar.com/' . md5( strtolower( trim( get_the_author_meta( 'user_email' ) ) ) ) . '.php' );
		$profile = unserialize( $str );
		if ( is_array( $profile ) && ! empty( $profile['entry'][0]['urls'] ) ) {
			$markup .= '<span class="c-author__links">' . PHP_EOL;
			foreach ( $profile['entry'][0]['urls'] as $link ) {
				if ( ! empty( $link['value'] ) && ! empty( $link['title'] ) ) {
					$markup .= '<a class="c-author__social-link" href="' . esc_url( $link['value'] ) . '" target="_blank">' . $link['title'] . '</a>' . PHP_EOL;
				}
			}
			$markup .= '</span><!-- .c-author__links -->' . PHP_EOL;
		} elseif ( ! empty( $user_website ) ) {
			$markup .= '<span class="c-author__links">' . PHP_EOL;
			$markup .= '<a class="c-author__social-link" href="' . esc_url( $user_website ) . '" target="_blank">' . esc_html__( 'Website', 'boilerplate' ) . '</a>' . PHP_EOL;
			$markup .= '</span><!-- .c-author__links -->' . PHP_EOL;
		}

		return $markup;
	} #function
}

if ( ! function_exists( 'boilerplate_the_post_navigation' ) ) {
	/**
	 * Displays the navigation to next/previous post, when applicable.
	 *
	 * @param array $args Optional. See get_the_post_navigation() for available arguments.
	 *                    Default empty array.
	 */
	function boilerplate_the_post_navigation( $args = array() ) {
		echo boilerplate_get_the_post_navigation( $args );
	}
}

if ( ! function_exists( 'boilerplate_get_the_post_navigation' ) ) {
	/**
	 * Retrieves the navigation to next/previous post, when applicable.
	 *
	 * @param array $args {
	 *     Optional. Default post navigation arguments. Default empty array.
	 *
	 * @type string $prev_text Anchor text to display in the previous post link. Default '%title'.
	 * @type string $next_text Anchor text to display in the next post link. Default '%title'.
	 * @type bool $in_same_term Whether link should be in a same taxonomy term. Default false.
	 * @type array|string $excluded_terms Array or comma-separated list of excluded term IDs. Default empty.
	 * @type string $taxonomy Taxonomy, if `$in_same_term` is true. Default 'category'.
	 * @type string $screen_reader_text Screen reader text for nav element. Default 'Post navigation'.
	 * }
	 * @return string Markup for post links.
	 */
	function boilerplate_get_the_post_navigation( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'prev_text'          => '%title',
			'next_text'          => '%title',
			'in_same_term'       => false,
			'excluded_terms'     => '',
			'taxonomy'           => 'category',
			'screen_reader_text' => esc_html__( 'Post navigation', 'boilerplate' ),
		) );

		$navigation = '';

		$previous = get_previous_post_link(
			'<div class="nav-previous"><span class="nav-links__label  nav-links__label--previous">' . esc_html__( 'Previous article', 'boilerplate' ) . '</span><span class="h3 nav-title  nav-title--previous">%link</span></div>',
			$args['prev_text'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		$next = get_next_post_link(
			'<div class="nav-next"><span class="nav-links__label  nav-links__label--next">' . esc_html__( 'Next article', 'boilerplate' ) . '</span><span class="h3 nav-title  nav-title--next">%link</span></div>',
			$args['next_text'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		// Only add markup if there's somewhere to navigate to.
		if ( $previous || $next ) {
			$navigation = _navigation_markup( $previous . $next, 'post-navigation', $args['screen_reader_text'] );
		}

		return $navigation;
	}
}

if ( ! function_exists( 'boilerplate_get_post_meta' ) ) {
	/**
	 * Get all the needed meta for a post.
	 *
	 * @return array
	 */
	function boilerplate_get_post_meta() {
		// Gather up all the meta we might need to display
		// But first initialize please
		$meta = array(
			'category' => false,
			'tags'     => false,
			'author'   => false,
			'date'     => false,
			'comments' => false,
		);

		// And get the options
		$items_primary_meta   = pixelgrade_option( 'blog_items_primary_meta', 'category', false );
		$items_secondary_meta = pixelgrade_option( 'blog_items_secondary_meta', 'date', false );

		if ( 'category' == $items_primary_meta || 'category' == $items_secondary_meta ) {
			$category = '';

			if ( is_page() ) {
				// if we are on a page then we only want to the main category
				$main_category = boilerplate_get_main_category_link();
				if ( ! empty( $main_category ) ) {
					$category .= '<span class="screen-reader-text">' . esc_html__( 'Main Category', 'boilerplate' ) . '</span><ul>' . PHP_EOL;
					$category .= '<li>' . $main_category . '</li>' . PHP_EOL;
					$category .= '</ul>' . PHP_EOL;
				}
			} else {
				// On archives we want to show all the categories, not just the main one
				$categories = get_the_terms( get_the_ID(), 'category' );
				if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
					$category .= '<span class="screen-reader-text">' . esc_html__( 'Categories', 'boilerplate' ) . '</span><ul class="cats">' . PHP_EOL;
					foreach ( $categories as $this_category ) {
						$category .= '<li><a href="' . esc_url( get_category_link( $this_category ) ) . '" rel="category">' . $this_category->name . '</a></li>' . PHP_EOL;
					};
					$category .= '</ul>' . PHP_EOL;
				}
			}
			$meta['category'] = $category;
		}

		if ( 'tags' == $items_primary_meta || 'tags' == $items_secondary_meta ) {
			$post_tags = get_the_terms( get_the_ID(), 'post_tag' );
			$tags      = '';
			if ( ! is_wp_error( $post_tags ) && ! empty( $post_tags ) ) {
				$tags .= '<span class="screen-reader-text">' . esc_html__( 'Tags', 'boilerplate' ) . '</span><ul class="tags">' . PHP_EOL;
				foreach ( $post_tags as $post_tag ) {
					$tags .= '<li><a href="' . esc_url( get_term_link( $post_tag ) ) . '" rel="tag">' . $post_tag->name . '</a></li>' . PHP_EOL;
				};
				$tags .= '</ul>' . PHP_EOL;
			}
			$meta['tags'] = $tags;
		}

		$meta['author'] = '<span class="byline">' . get_the_author() . '</span>';
		$meta['date']   = '<span class="posted-on">' . get_the_date() . '</span>';

		$comments_number = get_comments_number(); // get_comments_number returns only a numeric value
		if ( comments_open() ) {
			if ( $comments_number == 0 ) {
				$comments = esc_html__( 'No Comments', 'boilerplate' );
			} else {
				$comments = sprintf( _n( '%d Comment', '%d Comments', $comments_number, 'boilerplate' ), $comments_number );
			}
			$meta['comments'] = '<a href="' . esc_url( get_comments_link() ) . '">' . esc_html( $comments ) . '</a>';
		} else {
			$meta['comments'] = '';
		}

		return $meta;
	}
}

if ( ! function_exists( 'boilerplate_shape_comment' ) ) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Boilerplate 1.0
	 */
	function boilerplate_shape_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' : ?>
				<li class="post pingback">
				<p><?php esc_html_e( 'Pingback:', 'boilerplate' ); ?><?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'boilerplate' ), ' ' ); ?></p>
				<?php
				break;
			default : ?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="div-comment-<?php comment_ID(); ?>" class="comment__wrapper">
					<?php if ( 0 != $args['avatar_size'] ) : ?>
						<div class="comment__avatar"><?php echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
					<?php endif; ?>
					<div class="comment__body">
						<header class="c-meta">
							<div class="comment__author vcard">
								<?php
								/* translators: %s: comment author link */
								printf( __( '%s <span class="says">says:</span>', 'boilerplate' ),
									sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
								);
								?>
							</div><!-- .comment-author -->

							<div class="comment__metadata">
								<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
									<time datetime="<?php comment_time( 'c' ); ?>">
										<?php
										/* translators: 1: comment date, 2: comment time */
										printf( __( '%1$s at %2$s', 'boilerplate' ), get_comment_date( '', $comment ), get_comment_time() );
										?>
									</time>
								</a>
								<?php edit_comment_link( esc_html__( 'Edit', 'boilerplate' ), '<span class="edit-link">', '</span>' ); ?>
							</div><!-- .comment-metadata -->

							<?php if ( '0' == $comment->comment_approved ) : ?>
								<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'boilerplate' ); ?></p>
							<?php endif; ?>
						</header><!-- .comment-meta -->

						<div class="comment__content entry-content">
							<?php comment_text(); ?>
						</div><!-- .comment-content -->

						<?php
						comment_reply_link( array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>'
						) ) );
						?>
					</div>
				</article><!-- .comment-body -->
				<?php break;
		endswitch;
	}
} // ends check for shape_comment()

/**
 * Check if according to the Content Options we need to display the featured image.
 *
 * @return bool
 */
function boilerplate_display_featured_images() {
	if ( function_exists( 'jetpack_featured_images_get_settings' ) ) {
		$opts = jetpack_featured_images_get_settings();

		// Returns false if the archive option or singular option is unticked.
		if ( ( true === $opts['archive'] && ( is_home() || is_archive() || is_search() ) && ! $opts['archive-option'] )
		     || ( true === $opts['post'] && is_single() && ! $opts['post-option'] )
		     || ( true === $opts['page'] && is_singular() && is_page() && ! $opts['page-option'] )
		) {
			return false;
		}
	}

	return true;
}

if ( ! function_exists( 'boilerplate_the_taxonomy_dropdown' ) ) {

	function boilerplate_the_taxonomy_dropdown( $taxonomy, $selected = '' ) {
		$output = '';

		$id = $taxonomy . '-dropdown';

		$terms = get_terms( $taxonomy );

		$taxonomy_obj = get_taxonomy( $taxonomy );
		// bail if we couldn't get the taxonomy object or other important data
		if ( empty( $taxonomy_obj ) || empty( $taxonomy_obj->object_type ) ) {
			return false;
		}

		// get the first post type
		$post_type = reset( $taxonomy_obj->object_type );
		// get the post type's archive URL
		$archive_link = get_post_type_archive_link( $post_type );

		$output .= '<select class="taxonomy-select js-taxonomy-dropdown" name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '">';

		$selected_attr = '';
		if ( empty( $selected ) ) {
			$selected_attr = 'selected';
		}
		$output .= '<option value="' . esc_attr( $archive_link ) . '" ' . esc_attr( $selected_attr ) . '>' . esc_html__( 'Everything', 'noah' ) . '</option>';

		foreach ( $terms as $term ) {
			$selected_attr = '';
			if ( ! empty( $selected ) && $selected == $term->slug ) {
				$selected_attr = 'selected';
			}
			$output .= '<option value="' . esc_attr( get_term_link( intval( $term->term_id ), $taxonomy ) ) . '" ' . esc_attr( $selected_attr ) . '>' . esc_html( $term->name ) . '</option>';
		}
		$output .= '</select>';

		// Allow others to have a go at it
		$output = apply_filters( 'boilerplate_the_taxonomy_dropdown', $output, $taxonomy, $selected );

		// Display it
		echo $output;
	} #function
}
