<?php
/*
Plugin Name: Jameel's Developer Tools
Plugin URI: http://www.jameelbokhari.com/jameels-dev-tools/ 
Description: Chalked full of little helpers for developing websites
Version: 1.0.2
Author: Jameel Bokhari
Author URI: http://www.jameelbokhari.com/
License: WTFPL http://www.wtfpl.net/
*/

/**
 *
 * To Do
 * 1) see jdt_columns.php
 * 2) Automatically prepend [homeurl] to links, image sources and other attachements
 * 3) Check if links are broken when moving page/changing parent?
 * 4) Default options should be checked and set on activation
 * 5) Need some serious reorganization
 *
**/

/**
 * @package Jameel's Toolbox
 *
 * FYI: A lot of things will be appended jdt, where the name was originally Jameel's Dev Tools. Fuck no will I be changing that.
 * 
 * In an effort to seem like I know what I'm doing, I've broken this into four php files, three of which have unique classes:
 *
 * Required Files:
 * 1) inc/jdt_admin.php
 *    -	Admin script/styles
 *    -	Adds admin menu (does not create the options though)
 *    -	Uses inc/admin_verbiage.php to handle options page.
 * 2) inc/admin_verbiage.php 
 *    -	Options page form, help/support info and form handle
 * 3) inc/jdt_columns.php 
 *    - Columns class for [col] shortcode
 * 4) inc/jdt_urls.php 
 *    - All the link stuff
 * 5) inc/jdt_wordpress_settings.php
 *    - wpautop settings
 *
**/


require_once('inc/jdt_admin.php');
require_once('inc/admin_verbiage.php');
require_once('inc/jdt_columns.php');
require_once('inc/jdt_urls.php');
require_once('inc/jdt_wordpress_settings.php');


function jdt_do_install_table(){
	global $wpdb;
	$table = $wpdb->prefix . ('jdttempdata');
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$sql = "CREATE TABLE $table (
		ID int NOT NULL AUTO_INCREMENT,
		date_time datetime NOT NULL,
		post_id INT DEFAULT NULL,
		post_content text,
		PRIMARY KEY  (ID)
		)";
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'jdt_do_install_table' );