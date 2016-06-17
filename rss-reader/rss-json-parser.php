<?php

// Add JSON parser

function soe_faculty2_rss_json() {
	
	if( !is_admin() ){
		
		// Register the script
		wp_register_script( 'rss-json-parser', get_template_directory_uri() . '/rss-reader/js/rss-json-parser.js' );
		
		// Enqueued script
		wp_enqueue_script( 'rss-json-parser' );
	
	}
	
}

add_action('init', 'soe_faculty2_rss_json');

?>