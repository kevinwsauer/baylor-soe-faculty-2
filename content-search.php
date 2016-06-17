<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package SOE-Faculty2
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="index-box">
    <?php
		if (has_post_thumbnail()) {
			echo '<div class="small-index-thumbnail clear">';
			echo '<a href="' . get_permalink() . '" title="' . __('Read ', 'soe-faculty2') . get_the_title() . '" rel="bookmark">';
			echo the_post_thumbnail('blog-index-thumb');
			echo '</a>';
			echo '</div>';
		}
	?>      
        <header class="entry-header clear">      
            <?php
                /* translators: used between list items, there is a space after the comma */
                $category_list = get_the_category_list( __( ', ', 'soe-faculty2' ) );

                if ( soe_faculty2_categorized_blog() ) {
                    echo '<div class="category-list">' . $category_list . '</div>';
                }
            ?>
            <h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
    		<?php echo get_the_tag_list( '<ul class="tag-list"><li><i class="fa fa-tag"></i>', '</li><li><i class="fa fa-tag"></i>', '</li></ul>' ); ?> 
            <?php if ( 'page' !== get_post_type()  ) : ?>
            <div class="entry-meta">
                <?php soe_faculty2_posted_on(); ?>
                <?php edit_post_link( __( ' Edit', 'soe-faculty2' ), '<span class="edit-link">', '</span>' ); ?>
            </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->
        <div class="entry-content">
		<?php the_excerpt(); ?>
        </div><!-- .entry-content -->
        <footer class="entry-footer continue-reading">
			<a href="<?php echo get_permalink(); ?>" title="<?php echo  __('View ', 'soe-faculty2') . get_the_title(); ?>" rel="bookmark">View <i class="fa fa-arrow-circle-o-right"></i></a>
        </footer><!-- .entry-footer -->         
    </div><!-- .index-box -->
</article><!-- #post-## -->
