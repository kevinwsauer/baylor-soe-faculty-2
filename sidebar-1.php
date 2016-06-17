<?php
/**
 * The left sidebar containing a widget area.
 *
 * @package SOE-Faculty2
 */
?>
<div id="secondary" class="widget-area" role="complementary">
<?php
	// Menu Widget
	echo '<aside class="widget widget_nav_menu">';
	echo '<h1 class="widget-title">' . __( 'Menu' ) . '</h1>';
	soe_faculty2_main_menu();
	echo '</aside>';

	if ( is_active_sidebar( 'sidebar-1' ) ) :
		dynamic_sidebar( 'sidebar-1' );
	endif;   
?>
</div><!-- #secondary -->