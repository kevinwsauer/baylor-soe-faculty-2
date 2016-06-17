<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SOE-Faculty2
 */
?>

		<?php if ( is_home() || is_archive() || is_search() || is_404() || is_page_template('page-templates/page-rss.php')) : ?>
		
		<?php else: ?>
           	</div><!-- .wrapper -->
        <?php endif; ?>
    </div><!-- #content -->
	         
	<div id="footer">         
        <footer id="colophon" class="site-footer" role="contentinfo">
            <?php get_sidebar( 'footer' ); ?>
        </footer><!-- #colophon .site-footer -->
    </div><!-- #footer -->
    <div id="footer-2">         
        <footer id="colophon-2" class="site-footer wrapper" role="contentinfo">
            <div class="site-info">
                <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'soe-faculty2' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'soe-faculty2' ), 'WordPress' ); ?></a>
                <span class="sep"> | </span>
                <?php printf( __( 'Theme: %1$s by %2$s.', 'soe-faculty2' ), 'SOE-Faculty2', '<a href="http://www.baylor.edu/soe/" rel="designer">Kevin Sauer, Baylor University School of Education</a>' ); ?>
                <br />
                <br />
                <a href="http://www.baylor.edu/search/">Search</a> |
                <a href="http://www.baylor.edu/directory/"> Directory</a> |
                <a href="https://www.baylor.edu/about/index.php?id=89589"> Ask Baylor</a> |
                <a href="http://www.baylor.edu/calendar/"> Calendar</a> |
                <a href="http://www.baylor.edu/profuturis/"> Pro Futuris</a> |
                <a href="http://www.baylor.edu/map/"> Map</a> |
                <a href="http://www.baylor.edu/mediacommunications/"> News</a> |
                <a href="http://www.baylor.edu/lib/"> Libraries</a> |
                <a href="http://www.baylor.edu/research/"> Research</a>
                <?php 
                   echo '<br />';
                    echo '<br />';
                    echo 'Copyright &copy; <a href="http://www.baylor.edu/">Baylor&reg; University</a>. All rights reserved. <a href="http://www.baylor.edu/about/index.php?id=90104">Legal Disclosures</a>.';
                    echo '<br />';
                    echo 'Baylor University • Waco, Texas • 76798 • 1-800-229-5678';
                ?>
            </div><!-- .site-info -->
        </footer><!-- #colophon .site-footer .wrapper -->
    </div><!-- #footer -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
