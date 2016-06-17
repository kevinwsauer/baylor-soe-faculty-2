<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package SOE-Faculty2
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
			
		<?php if ( have_posts() ) : ?>
            
            <header class="page-header">
				<h1 class="page-title">Faculty Activity - <em>All</em></h1>
            </header>
            
            <section id="masonry-index" class="group clear"> 
            
			<?php get_sidebar('2'); ?>   
            
			<?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
            
                <?php
                    get_template_part( 'content', get_post_format() );
                ?>
            
			<?php endwhile; ?>
            
            </section><!-- #masonry-index -->
            
            <?php soe_faculty2_paging_nav(); ?>
		
		<?php else : ?>
		
        	<?php get_template_part( 'content', 'none' ); ?>
		
		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
