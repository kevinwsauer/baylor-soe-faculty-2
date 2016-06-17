<?php
// Add FitVids to allow for responsive sizing of videos
function soe_faculty2_fitvids() {
	if (!is_admin() ) {
		wp_register_script( 'fitvids', get_template_directory_uri() . '/fit-vids/js/jquery.fitvids.js', array('jquery'), '1.0', true);    	
    	wp_enqueue_script( 'fitvids');
    	add_action('wp_head', 'add_fitthem');

    	function add_fitthem() { ?>
	    	<script type="text/javascript">
		    	jQuery(document).ready(function() {
	    			jQuery('.video').fitVids();
	    		});
    		</script><?php
	    }
	}
}

add_action('init', 'soe_faculty2_fitvids');

// Automatically append .video class to oembed content (not a perfect solution, but close)
function soe_faculty2_embed_filter( $html, $data, $url ) {
	$return = '<div class="video">'.$html.'</div>';
	return $return;
}

add_filter('oembed_dataparse', 'soe_faculty2_embed_filter', 90, 3 );

?>