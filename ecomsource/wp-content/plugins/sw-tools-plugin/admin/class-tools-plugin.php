<?php 
/**
*** Name: Admin Rent Car Table
*** Author: WpThemeGo
**/

class SW_Tools_Plugin_DB{
	function __construct(){
		register_activation_hook( SWTOOLPLUGINFILE, array( $this, 'swquicktools_tools_plugin_create_table' ) );
	}
	
	public function swquicktools_tools_plugin_create_table(){
		$show_status = '1';
		$show_position = '1';
		$show_top = '196';
		$show_number = '10';
		$show_categories = 'no';
		$show_categories_empty = 'no';
		$show_cart = 'no';
		$show_account = 'no';
		$show_search = 'no';
		$show_recent_view = 'no';
		$show_backtop = 'no';
		
	    update_option( 'swquicktools_tools_plugin_show_status', $show_status );
		update_option( 'swquicktools_tools_plugin_show_position', $show_position );
		update_option( 'swquicktools_tools_plugin_show_top', $show_top );
		update_option( 'swquicktools_tools_plugin_show_number', $show_number );
		update_option( 'swquicktools_tools_plugin_show_categories', $show_categories );
		update_option( 'swquicktools_tools_plugin_show_categories_empty', $show_categories_empty );
		update_option( 'swquicktools_tools_plugin_show_cart', $show_cart );		
		update_option( 'swquicktools_tools_plugin_show_account', $show_account );
		update_option( 'swquicktools_tools_plugin_show_search', $show_search );
		update_option( 'swquicktools_tools_plugin_show_recent_view', $show_recent_view );
		update_option( 'swquicktools_tools_plugin_show_backtop', $show_backtop );

	}
}
new SW_Tools_Plugin_DB(); 