<?php
/*
Plugin Name: SOE RSS Reader 
Plugin URI: http://www.baylor.edu/soe
Description: This plugin allow for displaying post from SOE child sites using RSS in JSON format
Author: Kevin Sauer
Version: 0.1
Author URI: http://blogs.baylor.edu/kevin_sauer

Copyright 2015  Kevin Sauer  (email : Kevin_Sauer@Baylor.edu)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/

// Adds menu 

function soe_faculty2_rss_reader_plugin_menu() {
	
	if ( is_admin() ) : 
		
		add_menu_page( 'SOE RSS Reader Options', 'SOE RSS Reader', 'manage_options', 'soe-faculty2-rss-reader', 'soe_faculty2_rss_reader_plugin_options', 'dashicons-rss' );
		
		add_action( 'admin_init', 'soe_faculty2_rss_reader_plugin_register_settings' );	
	
	endif;
	
}

add_action( 'admin_menu', 'soe_faculty2_rss_reader_plugin_menu' );

// Register settings

function soe_faculty2_rss_reader_plugin_register_settings() { // whitelist options
  
  register_setting( 'soe_faculty2_rss_reader_group', 'site_urls' );
  
  register_setting( 'soe_faculty2_rss_reader_group', 'max_num_of_post' );
  
  register_setting( 'soe_faculty2_rss_reader_group', 'total_num_of_post_per_page' );
  
  register_setting( 'soe_faculty2_rss_reader_group', 'total_num_of_post_on_most_recent' );
  
}

// Options Form 

function soe_faculty2_rss_reader_plugin_options() {
	
	// Checks user permissions
	
	if ( !current_user_can( 'manage_options' ) )  :
	
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	
	endif;	

?>		
	
    <div class="wrap">
	
    <h2>SOE RSS Reader - Options</h2>
    
    <?php settings_errors(); ?>
	
    <form method="post" action="options.php">
    
	<?php settings_fields( 'soe_faculty2_rss_reader_group' ); ?>
    
	<?php do_settings_sections( 'soe_faculty2_rss_reader_group' ); ?>
    
    <?php 
		
		$total_num_of_post_per_page_default = 20;
		
		$max_num_of_post_default = 100;
		
		$total_num_of_post_on_most_recent_default = 20;
		
	?>
    
    <?php 
		
		$args = array(
			'network_id' => $wpdb->siteid,
			'public'     => NULL,
			'archived'   => 0,
			'mature'     => 0,
			'spam'       => 0,
			'deleted'    => 0,
			'limit'      => 10000,
			'offset'     => 0,
		);	
		
		$sites = wp_get_sites( $args );
		
		$site_urls = get_option( 'site_urls' ); 
		
	?>
    
    <table class="form-table">
    
        <tr valign="top">
    
        <th scope="row">Sites:</th>
    
        <td>
        
        <?php
		
		if ( !empty ( $sites ) ) :
		
			foreach( $sites as $site ) {
		
				$url = 'http://' . $site['domain'] . $site['path'];
	
				echo '<input type="checkbox" name="site_urls[' . $url . ']" value="1"';
				
				checked( isset( $site_urls[$url] ) ); 
				
				echo '/>' . $url . "<br />";
			
			}
		
		else :
			
			echo "No Site(s) Available!";	
			
		endif;
		
        ?>
        
        </td>
    
        </tr>
        
        <tr valign="top">
    
        <th scope="row">Total # of Posts Per Page:</th>
    
        <td><input type="number" name="total_num_of_post_per_page" value="<?php echo esc_attr( get_option( 'total_num_of_post_per_page', $total_num_of_post_per_page_default ) ); ?>" /></td>
    
        </tr>
        
        <tr valign="top">
    
        <th scope="row">Max # of Posts:</th>
    
        <td><input type="number" name="max_num_of_post" value="<?php echo esc_attr( get_option( 'max_num_of_post', $max_num_of_post_default ) ); ?>" /></td>
    
        </tr>
        
        <tr valign="top">
    
        <th scope="row">Total # of Posts on Most Recent Page:</th>
    
        <td><input type="number" name="total_num_of_post_on_most_recent" value="<?php echo esc_attr( get_option( 'total_num_of_post_on_most_recent', $total_num_of_post_on_most_recent_default ) ); ?>" /></td>
    
        </tr>
    
    </table>
    
    <?php submit_button(); ?>

	</form>

	</div>

<?php

}

// Creates list of URLs based on options page

function soe_faculty2_rss_reader_urls() {
	
	$site_urls = get_option( 'site_urls' );
	
	if ( !empty ( $site_urls ) ) :
	
		$site_urls = array_keys( $site_urls );
		
		foreach( $site_urls as $url ) {
	
			$urls[] = $url;
		
		}
	
	else :
		
		$urls = array();
			
	endif;
	
	// Creates array of feeds
	
	if ( !empty( $urls ) ) :
		
		foreach ( $urls as $url ) {
			
			$feeds[] = $url . '?feed=json';
			
		}
	else :
	
		$feeds = array();
		
	endif;

	return $feeds;

}

// Fetches the RSS Feeds in JSON format to be sotred in cache

function soe_faculty2_rss_fetch_feed() {

	// Array of feed urls
		
	$feeds = array();
	
	$feeds = soe_faculty2_rss_reader_urls();
	
	// Loop through the site urls and pull the JSON feed into a tmp array
	
	foreach( $feeds as $feed ) {
		
		$response = wp_remote_get( $feed ); 
		
		//Check for error retrieving feed	
		
		if( !is_wp_error( $response ) ) {
				
			$body = wp_remote_retrieve_body( $response );
			
			$tmpArray[] = json_decode( $body, TRUE );
			
		}
		
	}
	
	// Checks to see if array exists
	
	if ( isset( $tmpArray ) && !empty( $tmpArray ) ) :
	
			// Loop through tmp array to create one combined array
			
			foreach( $tmpArray as $innerArray ) {
				 
				foreach( $innerArray as $value ) {
							
					$json[] = $value;
					
				}
			}
			
			// Sort array based on date in descending order

			function sortFunction( $a, $b ) {
				
				return strtotime( $a["date"] ) <= strtotime( $b["date"] );
			
			}
			
			usort( $json, "sortFunction" );
	else:
	
		$json = array();
			
	endif;
	
	return $json;
	
}

// CACHING

// Updates the existing cache file with a fresh copy

function soe_faculty2_rss_get_fresh() {
		
		$cache = soe_faculty2_rss_fetch_feed(); // Fetch new feed
		
		$option = 'soe_faculty2_rss_cache'; // Set option variable 
		
		update_option( 'soe_faculty2_rss_cache', $cache, 'no' ); // Update or set new option with feed data above
			
}

// RSS Refresh Cron, runs hourly

function soe_faculty2_rss_cron_activation() {
	
	if ( !wp_next_scheduled( 'soe_faculty2_rss_cron' ) ) {
		
		wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'soe_faculty2_rss_cron');
	
	}

}

add_action('wp', 'soe_faculty2_rss_cron_activation');

// Function performed by cron

function soe_faculty2_rss_refresh() {
	
	soe_faculty2_rss_get_fresh();

}

add_action('soe_faculty2_rss_cron', 'soe_faculty2_rss_refresh');

// Fetches post from cache

function soe_faculty2_rss_reader_get_posts() {

	$option = 'soe_faculty2_rss_cache'; // Set option variable to check for cache
	
	// Checks if cache exists
	
	if( get_option( $option ) !== FALSE ) :
		
		$data = get_option( $option ); // fetch feed data from cache
		
	else: // No cache, fetch feeds
		
		$cache = soe_faculty2_rss_fetch_feed();
		
		update_option( 'soe_faculty2_rss_cache', $cache, 'no' );
		
		$data = '';
		
	endif;
	
	return $data;
	
}

// Filters the Posts cache ( if it exists ) by category and creates a new categorized Posts array

function soe_faculty2_rss_reader_filter( $category_slug ) {
	
	$data =  soe_faculty2_rss_reader_get_posts(); // get posts
	
	// Construct array of posts sorted by featured then non-featured based on category
		
	$featured_posts = array(); // Featured
	
	$non_featured_posts = array(); // Not featured
	
	foreach ( $data as $key => $value ) {
		
		if ( $category_slug !== 'all' ) : // If not All posts, then check for category
		
			// get array of categories of post
				
			$category_array = $value["categories"];
			
			$is_category = FALSE; // Default value
			
			foreach( $category_array as $category_data ) {	
			
				if ( $category_data["slug"] === $category_slug ) : // Checks for category match
			
					$is_category = TRUE;
					
				endif;
				
			}
		
		else : // All posts
		
			$is_category = TRUE; 
		
		endif;
		
		// get the featured value
				
		$featured_value = (int) $value["featured"];
		
		$is_featured = FALSE; // Default value
		
		if ( $featured_value === 1 ) : // Checks for featured value of 1
	
			$is_featured = TRUE;
			
		endif;
		
		if ( $is_category === TRUE ): // Check for category			
			
			if( $is_featured === TRUE ) : // Checks if featured
			
				$featured_posts[$key]["featured"] = TRUE;
				
				$featured_posts[$key]["grant_internal_external"] = $value["grant_internal_external"]; // grant posts only

				$featured_posts[$key]["title"] = $value["title"];
			
				$featured_posts[$key]["permalink"] = $value["permalink"];
				
				$featured_posts[$key]["author"] = $value["author"];
				
				$featured_posts[$key]["author_link"] = $value["author_link"];
				
				$featured_posts[$key]["date"] = $value["date"];
				
				$featured_posts[$key]["site_url"] = $value["site_url"];
				
				$featured_posts[$key]["featured_image"] = $value["featured_image"];
				
				$featured_posts[$key]["categories"] = $value["categories"];
				
				$featured_posts[$key]["tags"] = $value["tags"];
				
				$featured_posts[$key]["excerpt"] = $value["excerpt"];
				
				$featured_posts[$key]["content"] = $value["content"];	
			
			else : // non-featured
			
				$non_featured_posts[$key]["featured"] = FALSE;
				
				$non_featured_posts[$key]["grant_internal_external"] = $value["grant_internal_external"]; // grant posts only

				$non_featured_posts[$key]["title"] = $value["title"];
			
				$non_featured_posts[$key]["permalink"] = $value["permalink"];
				
				$non_featured_posts[$key]["author"] = $value["author"];
				
				$non_featured_posts[$key]["author_link"] = $value["author_link"];
				
				$non_featured_posts[$key]["date"] = $value["date"];
				
				$non_featured_posts[$key]["site_url"] = $value["site_url"];
				
				$non_featured_posts[$key]["featured_image"] = $value["featured_image"];
				
				$non_featured_posts[$key]["categories"] = $value["categories"];
				
				$non_featured_posts[$key]["tags"] = $value["tags"];
				
				$non_featured_posts[$key]["excerpt"] = $value["excerpt"];
				
				$non_featured_posts[$key]["content"] = $value["content"];	
			
			endif;
			
		endif;
			
	} // end foreach
	
	$ordered_data = array_merge( $featured_posts, $non_featured_posts ); // Merge arrays with featured posts first then non-featured
	
	// Construct final array of soreted posts limited to Max #
	
	$posts = array(); 
	
	$post_count = 0;
	
	foreach ( $ordered_data as $key => $value ) {
	
		// Find out Max # of Posts and limit Posts to Max #
			
		$max = get_option( 'max_num_of_post' );
		
		if (	 $max === NULL || $max < 1 ) : // Set Max # to default if negative or no value entered
			
			$max = 100;
			
		endif;
		
		if ( $value["grant_internal_external"] !== 'Internal' ) { // Filters the grant posts to external grants only
		
			if ( $max > $post_count ) :  // Limit to Max # of Posts
				
				$posts[$key]["featured"] = $value["featured"];
				
				$posts[$key]["grant_internal_external"] = $value["grant_internal_external"]; // grant posts only
		
				$posts[$key]["title"] = $value["title"];
			
				$posts[$key]["permalink"] = $value["permalink"];
				
				$posts[$key]["author"] = $value["author"];
				
				$posts[$key]["author_link"] = $value["author_link"];
				
				$posts[$key]["date"] = $value["date"];
				
				$posts[$key]["site_url"] = $value["site_url"];
				
				$posts[$key]["featured_image"] = $value["featured_image"];
				
				$posts[$key]["categories"] = $value["categories"];
				
				$posts[$key]["tags"] = $value["tags"];
				
				$posts[$key]["excerpt"] = $value["excerpt"];
				
				$posts[$key]["content"] = $value["content"];	
				
			endif;
			
			$post_count++;
			
		}
			
	}
	
	return $posts;

}

// Paging navigation for the Posts

function soe_faculty2_rss_reader_paging( $start, $length, $nitems ) {
	
	// Let's do our paging controls
		
	$prev = (int) $start - (int) $length;
	
	$next = (int) $start + (int) $length;
 
	// Create the PREVIOUS link
	
	$prevlink = '<li><a class="prev page-numbers rss-prev" href="?start=' . $prev . '&length=' . $length . '">' . __( '← Previous', 'soe-faculty2' ) . '</a></li>';
	
	if ( $prev < 0 && (int) $start > 0 ) :
	
		$prevlink = '<li><a class="prev page-numbers rss.prev" href="?start=0&length=' . $length . '">' . __( '← Previous', 'soe-faculty2' ) . '</a></li>';
	
	elseif ( $prev < 0 ) :
	
		$prevlink = '';
		
	else :
			
		// Do nothing	
		
	endif;
	
	// Create the NEXT link
	
	$nextlink = '<li><a class="next page-numbers rss-next" href="?start=' . $next . '&length=' . $length . '">' . __( 'Next →', 'soe-faculty2' ) . '</a></li>';
	
	if ( $next >= $nitems ) :
		
		$nextlink = '';
	
	endif;
	
	// Paging navigation
	
	$output = '';
	
	if ( $length < $nitems ) :
	
		$output .= '<nav class="navigation paging-navigation" role="navigation">';
		
		$output .= '<h1 class="screen-reader-text">Posts navigation</h1>';
		
		$output .= '<ul class="page-numbers clear">';
		
		$output .= $prevlink;
		
		$output .= $nextlink;
				
		$output .= '</nav>';
		
	endif;
	
	return $output;
}

// Creates the HTML for the message if no Posts found in Posts array

function soe_faculty2_rss_message( $category_slug ) {
	
		$category_name = str_replace( '-', ' ' , $category_slug );
		
		$category_name = ucwords( $category_name );
		
		$output = '';
		
		$output .= '<article class="status-publish hentry">';
		
		$output .= '<div class="index-box">';
		
		$output .= '<div class="no-activity-header"><i class="fa fa-ban"></i> No Faculty Activity</div>';

		$output .= '<header class="entry-header"><h1 class="entry-title">School of Education - Faculty Activity</h1></header>';

		$output .= '<div class="entry-content"><p>We appoligize but there is no faculty activity for <i>' . $category_name . '</i> at this time. Please try again later.</p></div>';
		
		$output .= '</div>';
		
		$output .= '</article>';
		
		return $output;
		
}

// Creates the HTML for the Posts in the Posts array if posts exist

function soe_faculty2_rss_reader_posts( $category_slug ) {
	
	// Set our paging values

	$start = ( isset( $_GET['start'] ) && !empty( $_GET['start'] ) ) ? $_GET['start'] : 0; // Where do we start?
	
	$default_length = get_option( 'total_num_of_post_per_page' );
	
	if (	 $default_length === NULL || $default_length < 1 ) : // Set Total # of Posts per Page to default if negative or no value entered
			
		$default_length = 20;
		
	endif;
	
	$length = ( isset( $_GET['length'] ) && !empty( $_GET['length'] ) ) ? $_GET['length'] : $default_length; // How many per page?
	
	$output = '';
	
	$posts = array();
	
	$posts = soe_faculty2_rss_reader_filter( $category_slug );
	
	$nitems = count( $posts );
	
	if ( !empty( $posts ) ) :
	
		// Loop through the posts
		
		foreach( array_slice( $posts, $start, $length ) as $post ) { 
		
			// get featured
			
			$featured = $post["featured"];
			
			// get internal or external grant value
			
			$grant_internal_external = $post["grant_internal_external"];
			
			// get title from feed
	
			$title = $post["title"];
				
			// get permalink
		
			$permalink = $post["permalink"];
			
			// get author
			
			$author = $post["author"];
			
			// get author link
			
			$author_link = $post["author_link"];
			
			// get date 
			
			$date = $post["date"];
			
			// get site url
			
			$site_url = $post["site_url"];
			
			// get featured image
			
			$featured_image = $post["featured_image"];
			
			// get category info
			
			$categories = $post["categories"];
			
			// get tag info
			
			$tags = $post["tags"];
			
			// get excerpt
			
			$excerpt = nl2br( $post["excerpt"] );
		
			// get content
			
			$content = nl2br( $post["content"] );
			
			$output .= '<article class="status-publish hentry">';	
			
			$output .= '<div class="index-box">';
			
			$header = '<div class="other-header"><i class="fa fa-sticky-note"></i> Other</div>';
			
			foreach( $categories as $category ) {	
				
				if ( 'Research Interests' == $category["title"] ) : 
				
					$header = '<div class="research-header"><i class="fa fa-bar-chart"></i> Research Interest</div>';	
				
				elseif ( 'Expertise' == $category["title"] ) : 
					
					$header = '<div class="expertise-header"><i class="fa fa-lightbulb-o"></i> Expertise</div>';
				
				elseif ( 'Publications' == $category["title"] ) : 
				
					$header = '<div class="publications-header"><i class="fa fa-book"></i> Publication</div>';
				
				elseif ( 'Presentations' == $category["title"] ) : 
				
					$header = '<div class="presentations-header"><i class="fa fa-video-camera"></i> Presentation</div>';
				
				elseif ( 'Awards' == $category["title"] ) : 
				
					$header = '<div class="awards-header"><i class="fa fa-trophy"></i> Award</div>';	
				
				elseif ( 'Grants' == $category["title"] ) : 
				
					$header = '<div class="grants-header"><i class="fa fa-usd"></i> Grant';
					
					if ( $grant_internal_external == 'External' ) {
					
						$header .= ' (External)';
						
					}
						
					$header .= '</div>';	
				
				elseif ( 'Professional Leadership' == $category["title"] ) : 
				
					$header = '<div class="leadership-header"><i class="fa fa-users"></i> Professional Leadership</div>';
				
				else :
			
					// Do nothing
			
				endif;
				
			}
			
			$output .= $header;
			
			if ( $featured_image ) :
				
				$output .= '<div class="small-index-thumbnail clear">';
				
				$output .= '<a href="' . $permalink . '" title="' . __('Read ', 'soe-faculty2') . $title . '" rel="bookmark">';
				
				$output .= $featured_image;
				
				$output .= '</a>';
				
				$output .= '</div>';
				
			endif;
			
			$output .= "<header class='entry-header clear'>";
			
			if ( $featured === TRUE ) :
			
				$output .=  '<div class="featured-post"><i class="fa fa-thumb-tack"></i> Featured</div>';
			
			endif;
			
			$output .= '<div class="category-list">';
			
			$category_count = count( $categories );
			
			$loop_count = 1;
			
			foreach( $categories as $category ) {	
				
				$output .= '<a href="' . $site_url . '/category/' . $category["slug"] . '">' . $category["title"] . '</a>';
				
				if ( $category_count > $loop_count ) {
				
					$output .= ', ';
					
				}
				
				$loop_count++;
			
			}
			 
			$output .= '</div>';
			
			$output .= '<h1 class="entry-title">';
			
			foreach( $categories as $category ) {
				
				if ( 'Research Interests' == $category["title"]  || 	'Expertise' == $category["title"] || 'Publications' == $category["title"] || 'Presentations' == $category["title"] || 'Awards' == $category["title"] || 'Grants' == $category["title"] || 'Professional Leadership' == $category["title"] ) :
			
					// writes the title
			
					$output .= $title;
				
				else : 
				
					if ( $permalink ) :
			
						// writes the link
			
						$output .= '<a href="' . $permalink . '" title="' . __('Read ', 'soe-faculty2') . $title . '" rel="bookmark">';
			
					endif;
	
					// writes the title
			
					$output .= $title;
			
					// end the link ( anchor tag )
			
					if ( $permalink ) :
					
						$output .= '</a>'; 
					
					endif;
				
				endif;	
			}
			
			$output .= '</h1>';
			
			if ( $tags ) :
			
				$output .= '<ul class="tag-list">';
				
				foreach( $tags as $tag ) {	
					
					$output .= '<li><i class="fa fa-tag"></i> <a href="' . $site_url . '/tag/' . $tag["slug"] . '">' . $tag["title"] . '</a></li>';
				
				}
				
				$output .= '</ul>';
			
			endif;
			
			$output .= '<div class="entry-meta">';
		
			$output .= '<span class="byline"><span class="author vcard"><a class="url fn n" href="' . $author_link . '">' . $author . '</a></span></span>';
	
			$output .= '<span class="posted-on"><a href="' . $permalink . '" rel="bookmark">' . $date . '</a></span>';
		
			$output .= '</div>';
			
			$output .= '</header>';
			
			$output .= '<div class="entry-content"><p>';
			
			foreach( $categories as $category ) {	
				
				if ( ('Research Interests' == $category["title"]) || ('Publications' == $category["title"]) || ('Presentations' == $category["title"]) || ('Awards' == $category["title"]) || ('Grants' == $category["title"]) || ('Professional Leadership' == $category["title"]) ) :
					
					$output .= $content;
				
				elseif ( ('Expertise' == $category["title"]) ) :
				
					// Do not include
				
				else: 
				
					$output .= $excerpt;
				
				endif;
				
			}
	
			$output .= '</p></div>';
			
			foreach( $categories as $category ) {
				
				if ( 'Research Interests' == $category["title"]  || 	'Expertise' == $category["title"] || 'Publications' == $category["title"] || 'Presentations' == $category["title"] || 'Awards' == $category["title"] || 'Grants' == $category["title"] || 'Professional Leadership' == $category["title"] ) :
				
					// Do nothing
				
				else:
			
					if ( $permalink ) :
						
						$output .= '<footer class="entry-footer continue-reading">';
						
						$output .= '<a href="' . $permalink . '" title="' . $title . '" rel="bookmark">View <i class="fa fa-arrow-circle-o-right"></i></a>';
						
						$output .=  '</footer>';
					
					endif;
	
				endif;
				
			}
			
			$output .= '</div>';
			
			$output .= '</article>';
	
		} // end foreach
		
		$output .= '</section>';
		
		$output .= soe_faculty2_rss_reader_paging( $start, $length, $nitems );  // Paging navigation
		
	endif;
	
	return $output;
		
}

// Checks to see if Posts exist and returns the appropriate HTML to the browser

function soe_faculty2_rss_reader( $category_slug ) {

	if( soe_faculty2_rss_reader_posts( $category_slug ) != '' ) :
		
		return soe_faculty2_rss_reader_posts( $category_slug );
	
	else : 
		
		return soe_faculty2_rss_message( $category_slug );
	
	endif;

}

// Filters the Posts cache ( if it exists ) by search term and creates a new Posts array

function soe_faculty2_rss_reader_search_filter( $search_term ) {
	
	$data =  soe_faculty2_rss_reader_get_posts(); // get posts
	
	// Construct array of posts sorted by featured then non-featured based on category
		
	$featured_posts = array(); // Featured
	
	$non_featured_posts = array(); // Not featured
	
	foreach ( $data as $key => $value ) {
		
		if ( $search_term !== 'all' ) : // If search term is all, display all posts
		
			$is_match = FALSE; // Default value
			
			// get array of categories of post
			
			$category_array = $value["categories"];
			
			foreach( $category_array as $category_data ) {	
			
				if ( strpos( strtolower( $category_data["title"] ), strtolower( $search_term ) ) !== FALSE ) : // Checks for category match
			
					$is_match = TRUE;
					
				endif;
				
			}
			
			// get array of tags of post
			
			$tag_array = $value["tags"];
			
			foreach( $tag_array as $tag_data ) {	
				
				if ( strpos( strtolower( $tag_data["title"] ), strtolower( $search_term ) ) !== FALSE ) : // Checks for tag match
					
					$is_match = TRUE;
					
				endif;
				
			}
			
			// Checks for match in title, content or excerpt
			if ( strpos( strtolower( $value["title"] ), strtolower( $search_term ) ) !== FALSE 
				|| strpos( strtolower( $value["content"] ), strtolower( $search_term ) ) !== FALSE 
				|| strpos( strtolower( $value["excerpt"] ), strtolower( $search_term ) ) !== FALSE 
				|| strpos( strtolower( $value["author"] ), strtolower( $search_term ) ) !== FALSE ) : 
			
				$is_match = TRUE;
			
			endif;
		
		else : // All posts
		
			$is_match = TRUE; 
		
		endif;
		
		// get the featured value
				
		$featured_value = (int) $value["featured"];
		
		$is_featured = FALSE; // Default value
		
		if ( $featured_value === 1 ) : // Checks for featured value of 1
	
			$is_featured = TRUE;
			
		endif;
		
		if ( $is_match === TRUE ): // Check for category			
			
			if( $is_featured === TRUE ) : // Checks if featured
			
				$featured_posts[$key]["featured"] = TRUE;
				
				$featured_posts[$key]["grant_internal_external"] = $value["grant_internal_external"]; // grant posts only

				$featured_posts[$key]["title"] = $value["title"];
			
				$featured_posts[$key]["permalink"] = $value["permalink"];
				
				$featured_posts[$key]["author"] = $value["author"];
				
				$featured_posts[$key]["author_link"] = $value["author_link"];
				
				$featured_posts[$key]["date"] = $value["date"];
				
				$featured_posts[$key]["site_url"] = $value["site_url"];
				
				$featured_posts[$key]["featured_image"] = $value["featured_image"];
				
				$featured_posts[$key]["categories"] = $value["categories"];
				
				$featured_posts[$key]["tags"] = $value["tags"];
				
				$featured_posts[$key]["excerpt"] = $value["excerpt"];
				
				$featured_posts[$key]["content"] = $value["content"];	
			
			else : // non-featured
			
				$non_featured_posts[$key]["featured"] = FALSE;
				
				$non_featured_posts[$key]["grant_internal_external"] = $value["grant_internal_external"]; // grant posts only

				$non_featured_posts[$key]["title"] = $value["title"];
			
				$non_featured_posts[$key]["permalink"] = $value["permalink"];
				
				$non_featured_posts[$key]["author"] = $value["author"];
				
				$non_featured_posts[$key]["author_link"] = $value["author_link"];
				
				$non_featured_posts[$key]["date"] = $value["date"];
				
				$non_featured_posts[$key]["site_url"] = $value["site_url"];
				
				$non_featured_posts[$key]["featured_image"] = $value["featured_image"];
				
				$non_featured_posts[$key]["categories"] = $value["categories"];
				
				$non_featured_posts[$key]["tags"] = $value["tags"];
				
				$non_featured_posts[$key]["excerpt"] = $value["excerpt"];
				
				$non_featured_posts[$key]["content"] = $value["content"];	
			
			endif;
			
		endif;
			
	} // end foreach
	
	$ordered_data = array_merge( $featured_posts, $non_featured_posts ); // Merge arrays with featured posts first then non-featured
	
	// Construct final array of soreted posts limited to Max #
	
	$posts = array(); 
	
	$post_count = 0;
	
	foreach ( $ordered_data as $key => $value ) {
	
		// Find out Max # of Posts and limit Posts to Max #
			
		$max = get_option( 'max_num_of_post' );
		
		if (	 $max === NULL || $max < 1 ) : // Set Max # to default if negative or no value entered
			
			$max = 100;
			
		endif;
		
		if ( $value["grant_internal_external"] !== 'Internal' ) { // Filters the grant posts to external grants only
		
			if ( $max > $post_count ) :  // Limit to Max # of Posts
				
				$posts[$key]["featured"] = $value["featured"];
				
				$posts[$key]["grant_internal_external"] = $value["grant_internal_external"]; // grant posts only
		
				$posts[$key]["title"] = $value["title"];
			
				$posts[$key]["permalink"] = $value["permalink"];
				
				$posts[$key]["author"] = $value["author"];
				
				$posts[$key]["author_link"] = $value["author_link"];
				
				$posts[$key]["date"] = $value["date"];
				
				$posts[$key]["site_url"] = $value["site_url"];
				
				$posts[$key]["featured_image"] = $value["featured_image"];
				
				$posts[$key]["categories"] = $value["categories"];
				
				$posts[$key]["tags"] = $value["tags"];
				
				$posts[$key]["excerpt"] = $value["excerpt"];
				
				$posts[$key]["content"] = $value["content"];	
				
			endif;
			
			$post_count++;
			
		}
			
	}
	
	return $posts;

}

// Paging navigation for the Posts on the search page

function soe_faculty2_rss_reader_search_paging( $search_term, $start, $length, $nitems ) {
	
	// Let's do our paging controls
		
	$prev = (int) $start - (int) $length;
	
	$next = (int) $start + (int) $length;
 
	// Create the PREVIOUS link
	
	$prevlink = '<li><a class="prev page-numbers rss-prev" href="?s=' . $search_term . '&start=' . $prev . '&length=' . $length . '">' . __( '← Previous', 'soe-faculty2' ) . '</a></li>';
	
	if ( $prev < 0 && (int) $start > 0 ) :
	
		$prevlink = '<li><a class="prev page-numbers rss.prev"  href="?s=' . $search_term . '&start=0&length=' . $length . '">' . __( '← Previous', 'soe-faculty2' ) . '</a></li>';
	
	elseif ( $prev < 0 ) :
	
		$prevlink = '';
		
	else :
			
		// Do nothing	
		
	endif;
	
	// Create the NEXT link
	
	$nextlink = '<li><a class="next page-numbers rss-next" href="?s=' . $search_term . '&start=' . $next . '&length=' . $length . '">' . __( 'Next →', 'soe-faculty2' ) . '</a></li>';
	
	if ( $next >= $nitems ) :
		
		$nextlink = '';
	
	endif;
	
	// Paging navigation
	
	$output = '';
	
	if ( $length < $nitems ) :
	
		$output .= '<nav class="navigation paging-navigation" role="navigation">';
		
		$output .= '<h1 class="screen-reader-text">Posts navigation</h1>';
		
		$output .= '<ul class="page-numbers clear">';
		
		$output .= $prevlink;
		
		$output .= $nextlink;
				
		$output .= '</nav>';
		
	endif;
	
	return $output;
}

// Returns HTML output for Posts on the Search page

function soe_faculty2_rss_reader_search( $search_term ) {
	
	// Set our paging values

	$start = ( isset( $_GET['start'] ) && !empty( $_GET['start'] ) ) ? $_GET['start'] : 0; // Where do we start?
	
	$default_length = get_option( 'total_num_of_post_per_page' );
	
	if (	 $default_length === NULL || $default_length < 1 ) : // Set Total # of Posts per Page to default if negative or no value entered
			
		$default_length = 20;
		
	endif;
	
	$length = ( isset( $_GET['length'] ) && !empty( $_GET['length'] ) ) ? $_GET['length'] : $default_length; // How many per page?
	
	$output = '';
	
	$posts = array();
	
	$posts = soe_faculty2_rss_reader_search_filter( $search_term );
	
	$nitems = count( $posts );
	
	if ( !empty( $posts ) ) :
	
		// Loop through the posts
		
		foreach( array_slice( $posts, $start, $length ) as $post ) { 
		
			// get featured
			
			$featured = $post["featured"];
			
			// get internal or external grant value
			
			$grant_internal_external = $post["grant_internal_external"];
			
			// get title from feed
	
			$title = $post["title"];
				
			// get permalink
		
			$permalink = $post["permalink"];
			
			// get author
			
			$author = $post["author"];
			
			// get author link
			
			$author_link = $post["author_link"];
			
			// get date 
			
			$date = $post["date"];
			
			// get site url
			
			$site_url = $post["site_url"];
			
			// get featured image
			
			$featured_image = $post["featured_image"];
			
			// get category info
			
			$categories = $post["categories"];
			
			// get tag info
			
			$tags = $post["tags"];
			
			// get excerpt
			
			$excerpt = nl2br( $post["excerpt"] );
		
			// get content
			
			$content = nl2br( $post["content"] );
			
			$output .= '<article class="status-publish hentry">';	
			
			$output .= '<div class="index-box">';
			
			$header = '<div class="other-header"><i class="fa fa-sticky-note"></i> Other</div>';
			
			foreach( $categories as $category ) {	
				
				if ( 'Research Interests' == $category["title"] ) : 
				
					$header = '<div class="research-header"><i class="fa fa-bar-chart"></i> Research Interest</div>';	
				
				elseif ( 'Expertise' == $category["title"] ) : 
					
					$header = '<div class="expertise-header"><i class="fa fa-lightbulb-o"></i> Expertise</div>';
				
				elseif ( 'Publications' == $category["title"] ) : 
				
					$header = '<div class="publications-header"><i class="fa fa-book"></i> Publication</div>';
				
				elseif ( 'Presentations' == $category["title"] ) : 
				
					$header = '<div class="presentations-header"><i class="fa fa-video-camera"></i> Presentation</div>';
				
				elseif ( 'Awards' == $category["title"] ) : 
				
					$header = '<div class="awards-header"><i class="fa fa-trophy"></i> Award</div>';	
				
				elseif ( 'Grants' == $category["title"] ) : 
				
					$header = '<div class="grants-header"><i class="fa fa-usd"></i> Grant';
					
					if ( $grant_internal_external == 'External' ) {
					
						$header .= ' (External)';
						
					}
						
					$header .= '</div>';	
				
				elseif ( 'Professional Leadership' == $category["title"] ) : 
				
					$header = '<div class="leadership-header"><i class="fa fa-users"></i> Professional Leadership</div>';
				
				else :
			
					// Do nothing
			
				endif;
				
			}
			
			$output .= $header;
			
			if ( $featured_image ) :
				
				$output .= '<div class="small-index-thumbnail clear">';
				
				$output .= '<a href="' . $permalink . '" title="' . __('Read ', 'soe-faculty2') . $title . '" rel="bookmark">';
				
				$output .= $featured_image;
				
				$output .= '</a>';
				
				$output .= '</div>';
				
			endif;
			
			$output .= "<header class='entry-header clear'>";
			
			if ( $featured === TRUE ) :
			
				$output .=  '<div class="featured-post"><i class="fa fa-thumb-tack"></i> Featured</div>';
			
			endif;
			
			$output .= '<div class="category-list">';
			
			$category_count = count( $categories );
			
			$loop_count = 1;
			
			foreach( $categories as $category ) {	
				
				$output .= '<a href="' . $site_url . '/category/' . $category["slug"] . '">' . $category["title"] . '</a>';
				
				if ( $category_count > $loop_count ) {
				
					$output .= ', ';
					
				}
				
				$loop_count++;
			
			}
			 
			$output .= '</div>';
			
			$output .= '<h1 class="entry-title">';
			
			foreach( $categories as $category ) {
				
				if ( 'Research Interests' == $category["title"]  || 	'Expertise' == $category["title"] || 'Publications' == $category["title"] || 'Presentations' == $category["title"] || 'Awards' == $category["title"] || 'Grants' == $category["title"] || 'Professional Leadership' == $category["title"] ) :
			
					// writes the title
			
					$output .= $title;
				
				else : 
				
					if ( $permalink ) :
			
						// writes the link
			
						$output .= '<a href="' . $permalink . '" title="' . __('Read ', 'soe-faculty2') . $title . '" rel="bookmark">';
			
					endif;
	
					// writes the title
			
					$output .= $title;
			
					// end the link ( anchor tag )
			
					if ( $permalink ) :
					
						$output .= '</a>'; 
					
					endif;
				
				endif;	
			}
			
			$output .= '</h1>';
			
			if ( $tags ) :
			
				$output .= '<ul class="tag-list">';
				
				foreach( $tags as $tag ) {	
					
					$output .= '<li><i class="fa fa-tag"></i> <a href="' . $site_url . '/tag/' . $tag["slug"] . '">' . $tag["title"] . '</a></li>';
				
				}
				
				$output .= '</ul>';
			
			endif;
			
			$output .= '<div class="entry-meta">';
		
			$output .= '<span class="byline"><span class="author vcard"><a class="url fn n" href="' . $author_link . '">' . $author . '</a></span></span>';
	
			$output .= '<span class="posted-on"><a href="' . $permalink . '" rel="bookmark">' . $date . '</a></span>';
		
			$output .= '</div>';
			
			$output .= '</header>';
			
			$output .= '<div class="entry-content"><p>';
			
			foreach( $categories as $category ) {	
				
				if ( ('Research Interests' == $category["title"]) || ('Publications' == $category["title"]) || ('Presentations' == $category["title"]) || ('Awards' == $category["title"]) || ('Grants' == $category["title"]) || ('Professional Leadership' == $category["title"]) ) :
					
					$output .= $content;
				
				elseif ( ('Expertise' == $category["title"]) ) :
				
					// Do not include
				
				else: 
				
					$output .= $excerpt;
				
				endif;
				
			}
	
			$output .= '</p></div>';
			
			foreach( $categories as $category ) {
				
				if ( 'Research Interests' == $category["title"]  || 	'Expertise' == $category["title"] || 'Publications' == $category["title"] || 'Presentations' == $category["title"] || 'Awards' == $category["title"] || 'Grants' == $category["title"] || 'Professional Leadership' == $category["title"] ) :
				
					// Do nothing
				
				else:
			
					if ( $permalink ) :
						
						$output .= '<footer class="entry-footer continue-reading">';
						
						$output .= '<a href="' . $permalink . '" title="' . $title . '" rel="bookmark">View <i class="fa fa-arrow-circle-o-right"></i></a>';
						
						$output .=  '</footer>';
					
					endif;
	
				endif;
				
			}
			
			$output .= '</div>';
			
			$output .= '</article>';
	
		} // end foreach
		
		$output .= '</section>';
		
		$output .= soe_faculty2_rss_reader_search_paging( $search_term, $start, $length, $nitems );  // Paging navigation
		
	endif;
	
	return $output;
		
}

// Returns HTML output for most recent Posts

function soe_faculty2_rss_reader_most_recent() {

	$start = 0;
	
	// Find out Total # of Posts
		
	$length = get_option( 'total_num_of_post_on_most_recent' );
	
	if (	 $length === NULL || $length < 1 ) : // Set Max # to default if negative or no value entered
		
		$length = 20;
		
	endif;
	
	$output = '';
	
	$posts = array();
	
	$posts = soe_faculty2_rss_reader_filter('all');
	
	if ( !empty( $posts ) ) :
		
		// Loop through the posts
		
		foreach( array_slice( $posts, $start, $length ) as $post ) { 
					
			// get featured
			
			$featured = $post["featured"];
			
			// get internal or external grant value
			
			$grant_internal_external = $post["grant_internal_external"];
			
			// get title from feed
	
			$title = $post["title"];
				
			// get permalink
		
			$permalink = $post["permalink"];
			
			// get author
			
			$author = $post["author"];
			
			// get author link
			
			$author_link = $post["author_link"];
			
			// get date 
			
			$date = $post["date"];
			
			// get site url
			
			$site_url = $post["site_url"];
			
			// get featured image
			
			$featured_image = $post["featured_image"];
			
			// get category info
			
			$categories = $post["categories"];
			
			// get tag info
			
			$tags = $post["tags"];
			
			// get excerpt
			
			$excerpt = nl2br( $post["excerpt"] );
		
			// get content
			
			$content = nl2br( $post["content"] );
			
			$output .= '<article class="status-publish hentry">';	
			
			$output .= '<div class="index-box">';
			
			$header = '<div class="other-header"><i class="fa fa-sticky-note"></i> Other</div>';
			
			foreach( $categories as $category ) {	
				
				if ( 'Research Interests' == $category["title"] ) : 
				
					$header = '<div class="research-header"><i class="fa fa-bar-chart"></i> Research Interest</div>';	
				
				elseif ( 'Expertise' == $category["title"] ) : 
					
					$header = '<div class="expertise-header"><i class="fa fa-lightbulb-o"></i> Expertise</div>';
				
				elseif ( 'Publications' == $category["title"] ) : 
				
					$header = '<div class="publications-header"><i class="fa fa-book"></i> Publication</div>';
				
				elseif ( 'Presentations' == $category["title"] ) : 
				
					$header = '<div class="presentations-header"><i class="fa fa-video-camera"></i> Presentation</div>';
				
				elseif ( 'Awards' == $category["title"] ) : 
				
					$header = '<div class="awards-header"><i class="fa fa-trophy"></i> Award</div>';	
				
				elseif ( 'Grants' == $category["title"] ) : 
				
					$header = '<div class="grants-header"><i class="fa fa-usd"></i> Grant';
					
					if ( $grant_internal_external == 'External' ) {
					
						$header .= ' (External)';
						
					}
						
					$header .= '</div>';	
				
				elseif ( 'Professional Leadership' == $category["title"] ) : 
				
					$header = '<div class="leadership-header"><i class="fa fa-users"></i> Professional Leadership</div>';
				
				else :
			
					// Do nothing
			
				endif;
				
			}
			
			$output .= $header;
			
			if ( $featured_image ) :
				
				$output .= '<div class="small-index-thumbnail clear">';
				
				$output .= '<a href="' . $permalink . '" title="' . __('Read ', 'soe-faculty2') . $title . '" rel="bookmark">';
				
				$output .= $featured_image;
				
				$output .= '</a>';
				
				$output .= '</div>';
				
			endif;
			
			$output .= "<header class='entry-header clear'>";
			
			if ( $featured === TRUE ) :
			
				$output .=  '<div class="featured-post"><i class="fa fa-thumb-tack"></i> Featured</div>';
			
			endif;
			
			$output .= '<div class="category-list">';
			
			$category_count = count( $categories );
			
			$loop_count = 1;
			
			foreach( $categories as $category ) {	
				
				$output .= '<a href="' . $site_url . '/category/' . $category["slug"] . '">' . $category["title"] . '</a>';
				
				if ( $category_count > $loop_count ) {
				
					$output .= ', ';
					
				}
				
				$loop_count++;
			
			}
			 
			$output .= '</div>';
			
			$output .= '<h1 class="entry-title">';
			
			foreach( $categories as $category ) {
				
				if ( 'Research Interests' == $category["title"]  || 	'Expertise' == $category["title"] || 'Publications' == $category["title"] || 'Presentations' == $category["title"] || 'Awards' == $category["title"] || 'Grants' == $category["title"] || 'Professional Leadership' == $category["title"] ) :
			
					// writes the title
			
					$output .= $title;
				
				else : 
				
					if ( $permalink ) :
			
						// writes the link
			
						$output .= '<a href="' . $permalink . '" title="' . __('Read ', 'soe-faculty2') . $title . '" rel="bookmark">';
			
					endif;
	
					// writes the title
			
					$output .= $title;
			
					// end the link ( anchor tag )
			
					if ( $permalink ) :
					
						$output .= '</a>'; 
					
					endif;
				
				endif;	
			}
			
			$output .= '</h1>';
			
			if ( $tags ) :
			
				$output .= '<ul class="tag-list">';
				
				foreach( $tags as $tag ) {	
					
					$output .= '<li><i class="fa fa-tag"></i> <a href="' . $site_url . '/tag/' . $tag["slug"] . '">' . $tag["title"] . '</a></li>';
				
				}
				
				$output .= '</ul>';
			
			endif;
			
			$output .= '<div class="entry-meta">';
		
			$output .= '<span class="byline"><span class="author vcard"><a class="url fn n" href="' . $author_link . '">' . $author . '</a></span></span>';
	
			$output .= '<span class="posted-on"><a href="' . $permalink . '" rel="bookmark">' . $date . '</a></span>';
		
			$output .= '</div>';
			
			$output .= '</header>';
			
			$output .= '<div class="entry-content"><p>';
			
			foreach( $categories as $category ) {	
				
				if ( ('Research Interests' == $category["title"]) || ('Publications' == $category["title"]) || ('Presentations' == $category["title"]) || ('Awards' == $category["title"]) || ('Grants' == $category["title"]) || ('Professional Leadership' == $category["title"]) ) :
					
					$output .= $content;
				
				elseif ( ('Expertise' == $category["title"]) ) :
				
					// Do not include
				
				else: 
				
					$output .= $excerpt;
				
				endif;
				
			}
	
			$output .= '</p></div>';
			
			foreach( $categories as $category ) {
				
				if ( 'Research Interests' == $category["title"]  || 	'Expertise' == $category["title"] || 'Publications' == $category["title"] || 'Presentations' == $category["title"] || 'Awards' == $category["title"] || 'Grants' == $category["title"] || 'Professional Leadership' == $category["title"] ) :
				
					// Do nothing
				
				else:
			
					if ( $permalink ) :
						
						$output .= '<footer class="entry-footer continue-reading">';
						
						$output .= '<a href="' . $permalink . '" title="' . $title . '" rel="bookmark">View <i class="fa fa-arrow-circle-o-right"></i></a>';
						
						$output .=  '</footer>';
					
					endif;
	
				endif;
				
			}
	
			$output .= '</div>';
			
			$output .= '</article>';
	
		} // end foreach
		
	endif;
	
	return $output;
	
}

?>