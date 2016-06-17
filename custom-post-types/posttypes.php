<?php 
/** 
 * Plugin Name: SOE Faculty Pages: Custom Post Types and Taxonomies
 * Description: A simple plugin that adds custom post types and taxonomies
 * Version: 0.1
 * Author: Kevin Sauer
 * Author URI: http://www.baylor.edu/soe
 * License: GPL2
 */
 
 /*  Copyright 2014  Kevin Sauer

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

function my_custom_posttypes() {
    
	// Faculty Profile post type
	$labels = array(
        'name'               => 'Faculty Profiles',
        'singular_name'      => 'Faculty Profile',
        'menu_name'          => 'Faculty Profiles',
        'name_admin_bar'     => 'Faculty Profile',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Faculty Profile',
        'new_item'           => 'New Faculty Profile',
        'edit_item'          => 'Edit Faculty Profile',
        'view_item'          => 'View Faculty Profile',
        'all_items'          => 'All Faculty Profiles',
        'search_items'       => 'Search Faculty Profiles',
        'parent_item_colon'  => 'Parent Faculty Profiles:',
        'not_found'          => 'No faculty profiles found.',
        'not_found_in_trash' => 'No faculty profiles found in Trash.'
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-businessman',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'faculty-profiles' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'supports'           => array( 'title', 'author', 'thumbnail' )
    );
    register_post_type( 'faculty-profiles', $args );
	
}
add_action( 'init', 'my_custom_posttypes' );

function soe_faculty2_search_filter( $query ) {
	
	if ( $query -> is_search ) {
	
		$query->set('post_type', 'post');
	
	}

return $query;

}
add_filter('pre_get_posts','soe_faculty2_search_filter');
           
 ?>