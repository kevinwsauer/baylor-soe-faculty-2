<?php
/*
Plugin Name: SOE Faculty2 Metaboxes
Plugin URI: http://www.baylor.edu/soe
Description: This plugin creates custom metaboxes using CMB2
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

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

add_action( 'cmb2_init', 'soe_faculty2_metaboxes' );

/**
 * Hook in and add the soe_faculty2_metaboxes. Can only happen on the 'cmb2_init' hook.
 */
function soe_faculty2_metaboxes( ) {
	
	// Faculty Profile Metabox
	$soe_faculty2_profile_box = new_cmb2_box( array(
		'id' => 'faculty_profiles_box',
		'title' => __('Faculty Profile Information', 'cmb2' ),
		'object_types' => array('faculty-profiles'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
	) );
	$soe_faculty2_profile_box->add_field( array (
		'id' => 'first_name',
		'name' => __('First Name', 'cmb2'),
		'desc' => __('Enter the first name of the faculty member.', 'cmb2'),
		'type' => 'text',
		'attributes'  => array(
			'placeholder' => __('First Name', 'cmb2'),
			'required'    => 'required',
		)
    ) );		
	$soe_faculty2_profile_box->add_field( array (
		'id' => 'middle_name',
		'name' => __('Middle Name', 'cmb2'),
		'desc' => __('Enter the middle name of the faculty member.', 'cmb2'),
		'type' => 'text',
		'attributes'  => array(
			'placeholder' => __('Middle Name (Optional)', 'cmb2'),
		),
	 ) );
	$soe_faculty2_profile_box->add_field( array (
		'id' => 'last_name',
		'name' => __('Last Name', 'cmb2'),
		'desc' => __('Enter the last name of the faculty member.', 'cmb2'),
		'type' => 'text',
		'attributes'  => array(
			'placeholder' => __('Last Name', 'cmb2'),
			'required'    => 'required',
		),
	 ) );
	$soe_faculty2_profile_box->add_field( array (
		'id' => 'title',
		'name' => __('Title', 'cmb2'),
		'desc' => __('Enter the title of the faculty member. Ex. Ph.D., etc.', 'cmb2'),
		'type' => 'text',
		'attributes'  => array(
			'placeholder' => __('Title', 'cmb2'),
		),
	 ) );
	$soe_faculty2_profile_box->add_field( array (
		'id' => 'department',
		'name' => __('Department', 'cmb2'),
		'desc' => __('Enter the department of the faculty member.', 'cmb2'),
		'type' => 'radio', 
		'options' => array(
			'Curriculum and Instruction' => __( 'Curriculum & Instruction', 'cmb2' ),
			'Educational Administration'   => __( 'Educational Administration', 'cmb2' ),
			'Educational Psychology'     => __( 'Educational Psychology', 'cmb2' ),
			'' => __( 'N/A', 'cmb2' ),
		),
		'default' => '',
		'attributes'  => array(
			'required'    => 'required',
		),
	 ) );
	$soe_faculty2_profile_box->add_field( array (
		'id' => 'positions',
		'name' => __('Position(s)', 'cmb2'),
		'desc' => __('Enter the position(s) of the faculty member.', 'cmb2'),
		'type' => 'textarea_small',
		'attributes'  => array(
			'placeholder' => __('Position(s)', 'cmb2'),
			'required'    => 'required',
		),
	 ) );
	$soe_faculty2_profile_box->add_field( array (
		'id' => 'faculty_page',
		'name' => __('Faculty Site URL', 'cmb2'),
		'desc' => __('Enter the URL for the faculty page of the faculty member.', 'cmb2'),
		'type' => 'text_url',
		'protocols' => array( 'http', 'https' ),
		'attributes'  => array(
			'placeholder' => __('Faculty Site URL', 'cmb2'),
			'required'    => 'required',
		),
	 ) );
}

?>