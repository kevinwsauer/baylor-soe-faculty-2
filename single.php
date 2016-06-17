<?php
/**
 * The template for displaying all single posts.
 *
 * @package SOE-Faculty2
 */

get_header(); ?>
<?php get_sidebar('1'); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php soe_faculty2_post_nav(); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
