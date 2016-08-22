<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @package RED_Starter_Theme
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html( 'Nothing Found' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php echo esc_html( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php echo esc_html( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
