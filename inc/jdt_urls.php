<?php
/**
 * @package Jameel's Toolbox
 *
 * Search and replace. Originally made to replace site url in posts (ie http://...blog...com/ to shortcode [homeurl]. Can be used to replace anything. First saves copy of changes to database, then after human confirmation will update the post.
 *
 * TO DO
 * - Research security for these queries
 * - RegEx matching
 * - Show list of changes, before and after
 * - 
 *
**/
class JDT_URLs {
	function __construct(){
		$this->results = null;
		$this->results_for_humans = '';
		$this->have_results = false;
		add_shortcode( 'homeurl', array($this, 'home_url_shortcode') );
	
	}
	/**
	 * 
	 *
	 * CREATE TABLE `wp_jdttempdata` (
 *	       ID int NOT NULL AUTO_INCREMENT KEY,
 *         post_id int tinyINT,
 *         post_content LONGTEXT
 *     );
	 *
	**/
	function find_and_replace_urls($source = '', $value = ''){
		global $wpdb;
		//track results
		$instances   = 0; //matches found throughout posts
		$occurrances = 0; //posts that had a match
		$pattern = $source;
		//the update process

		$posts_table = $wpdb->prefix . "posts"; // add wp_ or whatever
		$data = array();
		$where = array();
		$format = array('%d');
		$whereformat = array( '%s');

		
		$query = $wpdb->get_results(
			
				"SELECT * 
				FROM  `wp_posts` 
				WHERE  `post_type` =  'page'
				OR  `post_type` =  'post'"
			
		);
		foreach ($query as $index => $row){
			$temp = str_replace($pattern, $value, $row->post_content, $count);
			if( $count > 0 ){

				$data[] = $temp;
				$where[] = $row->ID;

				//probably a much cleaner way to do this.
				/*
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE `wp_posts`
						SET `post_content`=%s
						WHERE `ID` = %s",
						$temp, $row->ID
					)
				);
				*/

				$instances += $count;
				$occurrances++;
			}
		}
		if ( $occurrances > 0 ){
			$this->save_results( $data, $where );
		}
		// if ( !isset($data) || !isset($where) ){
		// 	$results = $wpdb->update(
		// 		$posts_table,
		// 		$data,
		// 		$where,
		// 		$format,
		// 		$whereformat
		// 	);
		// }
		$this->results_for_humans = "Ready to replace $instances instances across $occurrances posts and pages...<br />";
		$this->results_for_humans .= "Proceed?<br />";
		// if ( !isset($results) ) { echo "Error with Query"; $results=0; }
		// echo "Found $instances instances accross $occurrances posts, $results posts were successfully updated.";
		// print_r($source);
		// print_r($value);
		// print_r($where);
		// query

		// get content from posts and pages

		// replace $site_url with [homeurl]

	}
	function save_results($data, $where){
		$this->results['data'] = $data;
		$this->results['where'] = $where;
		$this->have_results = true;
	}
	function update_results(){
		global $wpdb;
		$table = $wpdb->prefix . "jdttempdata";
		$wpdb->delete(
				$table,
				array('*')
		);
		$where = $this->results['where'];
		foreach( $this->results['data'] as $i => $data ){
			$results = $wpdb->insert(
				$table,
				array(
					'post_content' => $data,
					'post_id' => $where[$i]
				), //data
				array('%s', '%d') //data format
			);		
			
		}
		return $results;
	}
	function finalize_results(){
		global $wpdb;
		$count = 0;
		$fromtable = "`" . $wpdb->prefix . "jdttempdata`";
		$totable = $wpdb->prefix . "posts";
		$query = $wpdb->get_results(
			"SELECT * FROM $fromtable"
		);
		foreach( $query as $i => $data ){
			$id = $data->post_id;
			$content = $data->post_content;
			$update = $wpdb->update(
				$totable,
				array('post_content' => $content),
				array('ID'           => $id)
			);
			if ($update > 0 ){
				$count++;
			}
		}/*<div><?php ?>&$%*#\/\//\/*<!-- comment -->*/
		if ($count > 0 ){
			$echo = "$count posts and pages were affected. Congratulations.";
			$this->just_updated = $count;
			return $echo;
		} else {
			$echo = "There appears to have been an error. :(";
			return $echo;
		}
	}
	function home_url_shortcode(){
		$siteurl = site_url();
		return $siteurl;
	}
}
$jdt_urls = new JDT_URLs;
?>