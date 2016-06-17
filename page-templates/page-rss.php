<?php
/**
 * Template Name: Page with RSS Feeds
 *
 * @package SOE-Faculty2
 */

get_header(); ?>
	
    <div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<header class="page-header">
            	<?php if ( is_page( 'faculty-activity-all' )) : ?>
                	<h1 class="page-title">Faculty Activity - <em>All</em></h1>
                <?php endif; ?>
                <?php if ( is_page( 'faculty-activity-research-interests' )) : ?>
                	<h1 class="page-title">Faculty Activity - <em>Research Interests</em></h1>
               	<?php endif; ?>
				<?php if ( is_page( 'faculty-activity-expertise' )) : ?>
					<h1 class="page-title">Faculty Activity - <em>Expertise</em></h1>
                <?php endif; ?>
                <?php if ( is_page( 'faculty-activity-publications' )) : ?>
                	<h1 class="page-title">Faculty Activity - <em>Publications</em></h1>
                <?php endif; ?>
            	<?php if ( is_page( 'faculty-activity-presentations' )) : ?>
					<h1 class="page-title">Faculty Activity - <em>Presentations</em></h1>
                <?php endif; ?>
				<?php if ( is_page( 'faculty-activity-awards' )) : ?>
                	<h1 class="page-title">Faculty Activity - <em>Awards</em></h1>
                <?php endif; ?>
                <?php if ( is_page( 'faculty-activity-grants' )) : ?>
                	<h1 class="page-title">Faculty Activity - <em>Grants</em></h1>
                <?php endif; ?>
				<?php if ( is_page( 'faculty-activity-professional-leadership' )) : ?>
                	<h1 class="page-title">Faculty Activity - <em>Profesional Leadership</em></h1>
                <?php endif; ?>
            </header>
            
            <article id="loading">
                <div class="index-box">
                    <header class="entry-header">
                        <h1 class="entry-title">Loading....</h1>
                    </header>
                
                    <div class="entry-content">
                        <p>Please wait while the 
                        <?php if ( is_page( 'faculty-activity-all' )) : ?>
                            Faculty Activity - <em>All</em>
                        <?php endif; ?>
                        <?php if ( is_page( 'faculty-activity-research-interests' )) : ?>
                            Faculty Activity - <em>Research Interests</em>
                        <?php endif; ?>
                        <?php if ( is_page( 'faculty-activity-expertise' )) : ?>
                            Faculty Activity - <em>Expertise</em>
                        <?php endif; ?>                   
                        <?php if ( is_page( 'faculty-activity-publications' )) : ?>
                            Faculty Activity - <em>Publications</em>
                        <?php endif; ?>
                        <?php if ( is_page( 'faculty-activity-presentations' )) : ?>
                            Faculty Activity - <em>Presentations</em>
                        <?php endif; ?>
                        <?php if ( is_page( 'faculty-activity-awards' )) : ?>
                            Faculty Activity - <em>Awards</em>
                        <?php endif; ?>
                        <?php if ( is_page( 'faculty-activity-grants' )) : ?>
                            Faculty Activity - <em>Grants</em>
                        <?php endif; ?>
                        <?php if ( is_page( 'faculty-activity-professional-leadership' )) : ?>
                            Faculty Activity - <em>Profesional Leadership</em>
                        <?php endif; ?>                  
                         you requested is loaded.</p>
                    </div><!-- .entry-content -->
                </div><!-- .index-box -->
            </article><!-- #loading -->
            
            <section id="masonry-index" class="group clear">
            <?php  
				get_sidebar( '2');
				// RSS Feeds
				if ( is_page( 'faculty-activity-all' )) :
					echo soe_faculty2_rss_reader('all');
				endif;
				if ( is_page( 'faculty-activity-research-interests' )) : 
					echo soe_faculty2_rss_reader('research-interests');
				endif;
				if ( is_page( 'faculty-activity-expertise' )) : 
					echo soe_faculty2_rss_reader('expertise');
				endif;
				if ( is_page( 'faculty-activity-presentations' )) : 
					echo soe_faculty2_rss_reader('presentations');
				endif;
				if ( is_page( 'faculty-activity-publications' )) :
					echo soe_faculty2_rss_reader('publications');
				endif;
				if ( is_page( 'faculty-activity-awards' )) : 
					echo soe_faculty2_rss_reader('awards');
				endif;
				if ( is_page( 'faculty-activity-grants' )) : 
					echo soe_faculty2_rss_reader('grants');
				endif;
				if ( is_page( 'faculty-activity-professional-leadership' )) : 
					echo soe_faculty2_rss_reader('professional-leadership');
				endif;
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>