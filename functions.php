<?php
/**
 * SOE-Faculty2 functions and definitions
 *
 * @package SOE-Faculty2
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 780; /* pixels */
}

if ( ! function_exists( 'soe_faculty2_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function soe_faculty2_setup() {
	// This theme styles the visual editor to resemble the theme style.
	$font_url = 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700,800,800italic|Playfair+Display:400,400italic,700,700italic';
	add_editor_style( array( 'inc/editor-style.css', str_replace( ',', '%2C', $font_url ) ) );
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on SOE-Faculty2, use a find and replace
	 * to change 'soe-faculty2' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'soe-faculty2', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('large-thumb', 1280, 650, true);
	add_image_size('index-thumb', 900, 400, true);
	add_image_size('blog-index-thumb', 295, 150, true);
	add_image_size( 'all-faculty-mug', 253, 253, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'main' => __( 'Main Menu', 'soe-faculty' ),
		'social' => __( 'Social Media Menu', 'soe-faculty'),        
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	/*add_theme_support( 'post-formats', array(
		'aside',
	) );*/

	// Set up the WordPress core custom background feature.
	/*add_theme_support( 'custom-background', apply_filters( 'soe_faculty2_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );*/
}
endif; // soe_faculty2_setup
add_action( 'after_setup_theme', 'soe_faculty2_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function soe_faculty2_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar 1', 'soe-faculty2' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Widgets area appears in the left hand side of the site.', 'soe-faculty' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Sidebar 2', 'soe-faculty2' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Widgets area appears in the blog index of the site.', 'soe-faculty' ),
		'before_widget' => '<article class="index-widget"><div class="index-box"><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside></div></article>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widgets', 'soe-faculty2' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Footer widgets area appears in the footer of the site.', 'soe-faculty' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );          
}
add_action( 'widgets_init', 'soe_faculty2_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function soe_faculty2_scripts() {
	wp_enqueue_style( 'soe-faculty2-style', get_stylesheet_uri() );
	
	if ( is_front_page() ) {
		
		wp_enqueue_style( 'soe-faculty2-layout-style', get_stylesheet_directory_uri() . '/layouts/front-page-style.css');	
		               
	} else if ( is_home() || is_archive() || is_search() || is_404() || is_page_template('page-templates/page-rss.php')) {
		
		wp_enqueue_style( 'soe-faculty2-layout-style', get_stylesheet_directory_uri() . '/layouts/blog-style.css');
	
	} else if (is_page_template('page-templates/page-nosidebar.php') ) {
	
		wp_enqueue_style( 'soe-faculty2-layout-style' , get_template_directory_uri() . '/layouts/no-sidebar.css');
	
	} else {
	
		wp_enqueue_style( 'soe-faculty2-layout-style' , get_template_directory_uri() . '/layouts/sidebar-content.css');            
	}
	
	// Droid Serif	
	wp_enqueue_style( 'soe-faculty2-droidserif-fonts', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic' );
	
	// Futura
	wp_enqueue_style( 'soe-faculty2-futura-fonts', get_template_directory_uri() . '/fonts/fonts.css' );
						
	// FontAwesome
	wp_enqueue_style('soe-faculty2-fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );         

	wp_enqueue_script( 'soe-faculty2-superfish', get_template_directory_uri() . '/js/superfish.min.js', array('jquery'), '20140328', true );
	
	wp_enqueue_script( 'soe-faculty2-superfish-settings', get_template_directory_uri() . '/js/superfish-settings.js', array('soe-faculty2-superfish'), '20140328', true ); 
	
	wp_enqueue_script( 'soe-faculty2-hide-search', get_template_directory_uri() . '/js/hide-search.js', array(), '20140404', true );         
                
	wp_enqueue_script( 'soe-faculty2-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'soe-faculty2-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	
	wp_enqueue_script( 'soe-faculty2-masonry', get_template_directory_uri() . '/js/masonry-settings.js', array('masonry'), '20140401', true );
           
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'soe_faculty2_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
//require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
//require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Implement the marquee.
 */
include (TEMPLATEPATH . '/my-marquee/php/marquee_functions_include.php');

/**
 * Add jQuery Tabs
 */ 
 function soe_faculty2_jquery_tabs() {
	if ( !is_admin() ) {
		wp_enqueue_script( 'soe-faculty2-jquery-tabs', get_template_directory_uri() . '/js/jquery-tabs.js', array('jquery'));
		wp_enqueue_script('jquery-ui-tabs');
	}
}
add_action('wp_enqueue_scripts', 'soe_faculty2_jquery_tabs');

/**
 * Implements the faculty custom post types.
 */
include (TEMPLATEPATH . '/custom-post-types/posttypes.php');

/**
 *	Implements the soe-faculty2-metaboxes for custom metaboxes with custom fields
 */
include (TEMPLATEPATH . '/soe-faculty2-metaboxes/soe_faculty2_metaboxes_include.php');

/**
 * Implements FitVids to allow for responsive sizing of videos
 */
include (TEMPLATEPATH . '/fit-vids/fitvids_functions_include.php');

/**
 * Add SOE RSS Reader
 */
include (TEMPLATEPATH . '/rss-reader/rss-reader.php');
include (TEMPLATEPATH . '/rss-reader/rss-json-parser.php');