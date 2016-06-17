<?php
/**
 * @package SOE-Faculty2
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
	<?php 
        if (has_post_thumbnail()) {
			echo '<div class="small-index-thumbnail clear">';
			echo '<a href="' . get_permalink() . '" title="' . __('Read ', 'soe-faculty2') . get_the_title() . '" rel="bookmark">';
			echo the_post_thumbnail('index-thumb');
			echo '</a>';
			echo '</div>';
		}
    ?>       
    <header class="entry-header">
    	<?php
            /* translators: used between list items, there is a space after the comma */
            $category_list = get_the_category_list( __( ', ', 'soe-faculty2' ) );
        
            if ( soe_faculty2_categorized_blog() ) {
                echo '<div class="category-list">' . $category_list . '</div>';
            }
        ?> 
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?> 
        <?php echo get_the_tag_list( '<ul class="tag-list"><li><i class="fa fa-tag"></i>', '</li><li><i class="fa fa-tag"></i>', '</li></ul>' ); ?>      
		<div class="entry-meta">
			<?php soe_faculty2_posted_on(); ?>  
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'soe-faculty2' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
        <?php echo edit_post_link( __( 'Edit', 'soe-faculty2' ), '<span class="edit-link">', '</span>' ); ?> 
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
