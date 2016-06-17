<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package SOE-Faculty2
 */
?>

<section class="<?php if ( is_404() ) { echo 'error-404'; } else { echo 'no-results'; } ?> not-found">
    
    <article>
        <div class="index-box">
            <header class="entry-header">
                <h1 class="entry-title">
                    <?php 
                    if ( is_404() ) { _e( 'Page not available', 'soe-faculty2' ); }
                    else if ( is_search() ) { printf( __( 'Nothing found for <em>', 'soe-faculty2') . get_search_query() . '</em>' ); }
                    else { _e( 'Nothing Found', 'soe-faculty2' );}
                    ?>
                </h1>
            </header>
        
            <div class="entry-content">
                <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
        
                <p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'soe-faculty2' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
                
                <?php elseif ( is_404() ) : ?>
                
                <p><?php _e( 'You seem to be lost. To find what you are looking for check out the faculty activity below or try a search:', 'soe-faculty2' ); ?></p>
                <?php get_search_form(); ?>
                
                <?php elseif ( is_search() ) : ?>
                
                <p><?php _e( 'Nothing matched your search terms. Check out the faculty activity below or try searching for something else:', 'soe-faculty2' ); ?></p>
                <?php get_search_form(); ?>
                
                <?php else : ?>
                
                <p><?php _e( 'It seems we can’t find what you’re looking for. To find what you are looking for check out the faculty activity below or try a search:', 'soe-faculty2' ); ?></p>
                <?php get_search_form(); ?>
                
                <?php endif; ?>
            </div><!-- .entry-content -->
        </div><!-- .index-box -->
    </article>
    
        <header class="page-header"><h1 class="page-title">Most recent activity:</h1></header>
        <section id="masonry-index" class="group clear"> 
			<?php echo soe_faculty2_rss_reader_most_recent('all'); ?>        
   		</section><!-- #masonry-index -->
</section><!-- .no-results -->