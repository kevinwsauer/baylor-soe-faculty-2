<?php
/**
 * The template for displaying search results pages.
 *
 * @package SOE-Faculty2
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( soe_faculty2_rss_reader_search( get_search_query() ) !== '' ) : ?>
        
        	<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'soe-faculty2' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->
            
            <section id="masonry-index" class="group clear"> 
			
			<?php echo soe_faculty2_rss_reader_search( get_search_query() ); ?>
            
            </section><!-- #masonry-index -->

		<?php elseif ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'soe-faculty2' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->
            
            <section id="masonry-index" class="group clear"> 
            
			<?php get_sidebar('2'); ?>     
			
			<?php /* Start the Loop */ ?>
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'content', 'search' );
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
