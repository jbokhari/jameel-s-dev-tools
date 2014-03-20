<?php
//1) admin_verbiage contains all the html and scripts for admin menus

// This page itself is strictly php, the hmtl is in the required file.
class JDT_Admin{
	function __construct(){
		add_action('admin_menu', array($this, 'do_add_menu') );
		// add_action('admin_enqueue_scripts', array($this, 'global_site_url'), 10);
		add_action('admin_enqueue_scripts', array($this, 'enqueue_tiny_mce_scripts'), 9);
		// $this->do_ajax_global_site_link();


	}
	function do_ajax_global_site_link(){
	}
	function enqueue_tiny_mce_scripts( $hook ){
		wp_enqueue_style( 'jdt_columns', plugin_dir_url( __FILE__ ) . '../css/jdt_admin.css', null, false, 'all' );
	    if( 'post.php' == $hook && get_option('preview_homeurl_shortcode', 'on') == 'on'){
			wp_enqueue_script( 'jdt_tmce_buttons', plugin_dir_url( __FILE__ ) . '../js/jdt_tmce_buttons.js', array('jquery'), '1.0.0', true);
			wp_enqueue_script( 'jdt_tmce_home_url', plugin_dir_url( __FILE__ ) . '../js/jdt_tmce_home_url.js', array('jquery'), '1.0.0', true);
			wp_localize_script(
			  'jdt_tmce_home_url',
			  'JDT_Global',
			  array( 'homeurl' => site_url() )
			);
	    }
	}
	function do_add_menu(){
		add_submenu_page("tools.php", "Dev Tools Options", "Jameel's Dev Tools", 'administrator', 'dev_tool_options', array($this, 'do_content_for_menu_page') );
	}
	function do_content_for_menu_page(){
		jdt_options_page();
	}
}
$admin = new JDT_Admin;