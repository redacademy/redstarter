<?php
/**
 * Custom template tags for this theme.
 *
 * @package RED_Starter_Theme
 */

 /**
  * Prints HTML with meta information for the current post-date/time.
  */
 function red_starter_posted_on() {
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

 	$posted_on = sprintf( esc_html( '%s' ), $time_string );

 	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

 }

 /**
  * Prints HTML with meta information for the post author.
  */
 function red_starter_posted_by() {
 	$byline = sprintf(
 		esc_html( 'by %s' ),
 		'<span class="author vcard">' . esc_html( get_the_author() ) . '</span>'
 	);

 	echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

 }

 /**
  * Prints HTML with meta information for the comments with pop-up link.
  */
 function red_starter_comment_count() {
 	if ( is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
 		echo '<span class="comments-link">';
 		comments_popup_link( esc_html( '0 Comments' ), esc_html( '1 Comment' ), esc_html( '% Comments' ) );
 		echo '</span>';
 	}

 }

 /**
  * Prints HTML with meta information for the categories and tags.
  */
 function red_starter_entry_footer() {
 	// Hide category and tag text for pages.
 	if ( 'post' === get_post_type() ) {
 		/* translators: used between list items, there is a space after the comma */
 		$categories_list = get_the_category_list( esc_html( ', ' ) );
 		if ( $categories_list && red_starter_categorized_blog() ) {
 			printf( '<span class="cat-links">' . esc_html( 'Posted in &rarr; %1$s' ) . '</span>', $categories_list ); // WPCS: XSS OK.
 		}

 		/* translators: used between list items, there is a space after the comma */
 		$tags_list = get_the_tag_list( '', esc_html( ', ' ) );
 		if ( $tags_list ) {
 			printf( '<span class="tags-links">' . esc_html( 'Tagged &rarr; %1$s' ) . '</span>', $tags_list ); // WPCS: XSS OK.
 		}
 	}
 }

 /**
  * Returns true if a blog has more than 1 category.
  *
  * @return bool
  */
 function red_starter_categorized_blog() {
 	if ( false === ( $all_the_cool_cats = get_transient( 'red_starter_categories' ) ) ) {
 		// Create an array of all the categories that are attached to posts.
 		$all_the_cool_cats = get_categories( array(
 			'fields'     => 'ids',
 			'hide_empty' => 1,

 			// We only need to know if there is more than one category.
 			'number'     => 2,
 		) );

 		// Count the number of categories that are attached to the posts.
 		$all_the_cool_cats = count( $all_the_cool_cats );

 		set_transient( 'red_starter_categories', $all_the_cool_cats );
 	}

 	if ( $all_the_cool_cats > 1 ) {
 		// This blog has more than 1 category so red_starter_categorized_blog should return true.
 		return true;
 	} else {
 		// This blog has only 1 category so red_starter_categorized_blog should return false.
 		return false;
 	}
 }

 /**
  * Flush out the transients used in red_starter_categorized_blog.
  */
 function red_starter_category_transient_flusher() {
 	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
 		return;
 	}
 	// Like, beat it. Dig?
 	delete_transient( 'red_starter_categories' );
 }
 add_action( 'edit_category', 'red_starter_category_transient_flusher' );
 add_action( 'save_post',     'red_starter_category_transient_flusher' );

 /**
  * Template for comments. We have opted out of displaying pingbacks and trackbacks.
  *
  * Used as a callback by wp_list_comments() for displaying the comments.
  */
 function red_starter_comment_list( $comment, $args, $depth ) {
 	$GLOBALS['comment'] = $comment;
 	?>

 	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
 		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

 			<div class="comment-author-avatar">
 				<?php echo get_avatar( $comment, 64 ); ?>
 			</div>

 			<div class="comment-content">
 				<div class="comment-meta">
 					<div class="comment-author vcard">
 						<?php printf( '<cite class="fn">%s</cite>', get_comment_author() ); ?>
 					</div><!-- .comment-author -->

 					<div class="comment-metadata">
 						<time datetime="<?php comment_time( 'c' ); ?>">
 								<?php printf( '%1$s', get_comment_date('d M Y \a\t g:i a') ); ?>
 						</time>
 						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">#</a>
 					</div><!-- .comment-metadata -->

 					<?php if ( '0' == $comment->comment_approved ) : ?>
 					<p class="comment-awaiting-moderation">Your comment is awaiting moderation.</p>
 					<?php endif; ?>
 				</div><!-- .comment-meta -->

 				<?php comment_text(); ?>

 				<div class="reply">
 					<?php comment_reply_link( array_merge( $args, array(
 						'add_below'  => 'div-comment',
 						'depth'      => $depth,
 						'max_depth'  => $args['max_depth'],
 						'reply_text' => 'Reply &rarr;'
 					) ) ); ?>
 				</div><!-- .reply -->
 			</div><!-- .comment-content -->

 		</article><!-- .comment-body -->

 	<?php

 }

 /**
  * Display numbered post pagination instead of "Older Posts" and "Next Posts".
  */
 function red_starter_numbered_pagination() {
 	global $wp_query;
 	$big = 999999999;

 	if ( $wp_query->max_num_pages > 1 ) {
 		echo '<nav role="navigation" class="search-pagination">';
 		echo paginate_links(
 			array(
 				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
 				'format' => '?paged=%#%',
 				'current' => max( 1, get_query_var('paged') ),
 				'total' => $wp_query->max_num_pages,
 				'prev_text' => esc_html( '&larr; Previous' ),
 				'next_text' => esc_html( 'Next &rarr;' ),
 			)
 		);
 		echo '</nav>';
 	}
 }
