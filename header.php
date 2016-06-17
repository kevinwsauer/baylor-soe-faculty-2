<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package SOE-Faculty2
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'soe-faculty2' ); ?></a>
	<header id="masthead" class="site-header" role="banner">
    	<div id="baylor_header" class="header_show_subtitle">
            <div id="h">
                <div id="h_txt" class="wrapper">
                	<h1 class="main_mark">
						<a href="http://www.baylor.edu/?_buref=1155-90749">
                            <img class="no-svg-support" alt="Baylor University" src="<?php bloginfo('stylesheet_directory'); ?>/images/institutional_mark.png">
                            <img class="has-svg-support" alt="Baylor University" src="<?php bloginfo('stylesheet_directory'); ?>/images/institutional_mark.svg">
						</a>
					</h1>
                </div><!-- #h_txt .wrapper -->
            </div><!-- #h -->
        </div><!-- #baylor_header -->
        <nav id="site-navigation" class="main-navigation" role="navigation">
        	<div class="wrapper">
                <button class="menu-toggle"><?php _e( '<i class="fa fa-bars"></i>', 'soe-faculty2' ); ?></button>
                <?php soe_faculty2_main_menu(); ?>
                <div class="search-toggle">
                    <i class="fa fa-search"></i>
                    <a href="#search-container" class="screen-reader-text"><?php _e( 'Search', 'soe-faculty2' ); ?></a>
                </div>   
                <?php soe_faculty2_social_menu(); ?>	
            </div><!-- .wrapper -->
		</nav><!-- #site-navigation -->
        <div id="search-container" class="search-box-wrapper">
        	<div class="wrapper">
                <div class="search-box clear">
                    <?php get_search_form(); ?>
                </div>
           	</div><!-- .wrapper -->
        </div> 
        <div id="flexslider">
        	<div class="wrapper">
                <!-- Flex Slider Marquee -->
                <?php include (TEMPLATEPATH . '/my-marquee/php/marquee_include.php'); ?>
                <!-- End Flex Slider Marquee -->
        	</div><!-- .wrapper -->
        </div><!-- #flexslider -->
        <div id="site-branding">
            <div class="site-branding wrapper">
                <h1 class="site-title"><a href="<?php echo network_site_url(); ?>">School of Education Faculty</a></h1>
            </div><!-- .site-branding .wrapper -->
        </div><!-- #site-branding -->              
	</header><!-- #masthead -->

	<div id="content" class="site-content">
    	<?php if ( is_home() || is_archive() || is_search() || is_404() || is_page_template('page-templates/page-rss.php')) : ?>
		
		<?php else: ?>
            <div class="wrapper">
        <?php endif; ?>       	