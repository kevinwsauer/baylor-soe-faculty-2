<?php
/**
 * The blog sidebar containing a widget area.
 *
 * @package SOE-Faculty2
 */
?>
<div id="secondary" class="widget-area clear" role="complementary">       
<?php
	// Menu Widget
	echo '<article class="index-widget"><div class="index-box"><aside class="widget widget_nav_menu">';
	echo '<h1 class="widget-title">' . __( 'Menu' ) . '</h1>';
	soe_faculty2_main_menu();
	echo '</aside></div></article>';

	if ( is_active_sidebar( 'sidebar-2' ) ) : 
		dynamic_sidebar( 'sidebar-2' ); 
	endif;
?>
</div><!-- #secondary -->