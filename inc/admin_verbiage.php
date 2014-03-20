<?php
/**
 *
 * ADMIN VERBIAGE
 * admin-page UI
 *
 *
 *
 * This page is an attempt to clean up some of the admin options.
 *
 * For the most part it's successful... 
 *
 * Everything else is straight forward but the function jdt_form_header might be confusing. Returns string if tab active, else false. Also echos header if tab is active, so a two-birds-with-one-stone approach.
 *
 * To change tab order use jdt_create_admin_tabs, the $tabs array is what sets the order
 *
 * To set the default tab, modify jdt_form_header's $default setting AND jdt_creat_admin_tabs' $current var
 *
**/


/**
 * Creates admin menu tabs and manages current tab
 * @param type $current, manually fed - set below in this page
 * 
 */

function jdt_create_admin_tabs($current){
    $tabs = array(  'general-settings' => 'General', 'column-settings' => 'Columns', 'link-settings' => 'Links',  );//'advanced-settings' => 'Advanced'
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=dev_tool_options&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}
function check_user_permission($die = false){
	if(!user_can( get_current_user_id(), 'activate_plugins' )){
		// Joan Cusack
		wp_die( 'Permission denied dude. WTF are you doing...? You shouldn\'t even be here.', 'Error 1408' ); 
	}
	return;
}


/**
 * Begins a form section by echoing, in other words a tabbed section. Also returns a boolean value based on whether or not the tab is active. 
 * @param type $name 
 * @param type $action 
 * @return Boolean, true if tab is active
 */

function jdt_form_header($name, $action = ''){
	// if current tab, return true and echo the header.
	$default = 'general-settings';
	$tab = ( isset($_GET['tab']) ) ? $_GET['tab'] : "$default";
	$return = '';
	if( $tab == $name ){
		$return .= "<form action='$action' method='post'>";
		$return .= "<div class='$name jdt-admin-tab '>";
		echo $return;
		return true;
	} else { return false; }
}


/**
 * Form footer/end section. Same as a tabbed section. Echos
 * @param type $name 
 * @param type $label 
 */

function jdt_form_footer($name = 'Submit', $label = "Save Changes"){
	//wpnonce
	$referer = true;
	$echo = true;
	$return = '';
	//submit
	$type = 'primary';
	$submit_name = $name;
	$wrap = true;
	$other_attributes = null;
	$return .= '<input type="hidden" name="post_saved" value="1">';
	wp_nonce_field( 'jdt_to_update_option', 'jdt_update_option_nonce', $referer, $echo ); 
	submit_button( $label, $type, $submit_name, $wrap, $other_attributes );
	$return .= '</form>';
	$return .= '</div><!-- end of $name -->';
	echo $return;
}
function jdt_options_page(){
global $wpdb;
global $jdt_urls; //jdt_urls.php object, used for stuff
// print_r($_POST);
/**
 * need to extract($_POST) and use cleaner values
 * NEED to validate input a little more
**/
if(isset($_POST['post_saved'])){
	if (isset($_POST['jdt_update_option_nonce']) && wp_verify_nonce( $_POST['jdt_update_option_nonce'], 'jdt_to_update_option')){
		check_user_permission();/*admin*/
		/**

		 *  Start column settings
		**/
		if( isset( $_POST['column-settings'] ) ){
			//The url options save was clicked.
			if( isset($_POST['jdt_default_colwidth']) && is_numeric($_POST['jdt_default_colwidth']) ){
				if($_POST['jdt_default_colwidth'] > 12 ){
					$jdt_default_colwidth = 12;
				}
				if($_POST['jdt_default_colwidth'] < 1 ){
					$jdt_default_colwidth = 1;
				}
				update_option( 'jdt_default_colwidth', $_POST['jdt_default_colwidth'] );
			}
			if ( isset($_POST['use_column_css']) && $_POST['use_column_css'] == true ){
				update_option('use_column_css', 'on');
			} else {
				update_option('use_column_css', 'off');
			}
		}
		/**
		 *  ...End column settings

		 *  Start general settings...
		**/
		if( isset( $_POST['general-settings'] ) ){

			if ( isset($_POST['preview_homeurl_shortcode']) && $_POST['preview_homeurl_shortcode'] == true ){
				update_option('preview_homeurl_shortcode', 'on');
			} else {
				update_option('preview_homeurl_shortcode', 'off');
			}
			if( isset($_POST['wpautop_content']) && $_POST['wpautop_content'] == true ){
				update_option( 'wpautop_content', 'on' );
			} else {
				update_option( 'wpautop_content', 'off' );
			}
			if( isset($_POST['wpautop_excerpt']) && $_POST['wpautop_excerpt'] == true ){
				update_option( 'wpautop_excerpt', 'on' );
			} else {
				update_option( 'wpautop_excerpt', 'off' );
			}

		}
		/**
		 *  ...End general settings

		 *  Start link settings...
		**/
		if( isset($_POST['link-settings']) ){
			if (isset($_POST['find_and_save'])  ){
			// echo "column-settings";
				$source = $_POST['source_value'];
				$replace = ($_POST['replace_value']);
				$jdt_urls->find_and_replace_urls($source, $replace);
				if ( $jdt_urls->have_results ){
					$jdt_urls->update_results();
				}
			} else if ( isset( $_POST['finalize_matches'] ) ){
				echo $jdt_urls->finalize_results();
			}
		}
		/**
		 *  ...End link settings

		 *  Start Advanced settings...
		**/
		if( isset($_POST['advanced-settings']) ){
		}
		/**
		 *  ...End Advanced settings
		 
		 **/

	}
}
// checkboxes need this:
$wpautop_content = get_option( 'wpautop_content', 'off' );
$wpautop_excerpt = get_option( 'wpautop_excerpt', 'off' );
$preview_homeurl_shortcode = get_option( 'preview_homeurl_shortcode', 'off' );
$use_column_css = get_option( 'use_column_css', 'off' );


?>
<div class="wrap">
	<div class="jdt-admin-wrap">
		<?php
		 $default =  'general-settings';
		 $current = isset($_GET['tab']) ? $_GET['tab'] : $default  ?>
		<?php jdt_create_admin_tabs($current) ?>
		<!-- <h1>Jameel's Dev Tools - Options</h1> -->
		<?php // jdt_form will output the <form> and other necessary stuff if true ?>
		<?php if ( jdt_form_header('column-settings') ) : ?>
			<h2>Columns</h2>
			<h3>How to use columns.</h3>
			<p><strong>1) Columns use <em>shortcode</em>...</strong></p>
			<p>Paste <code>[col]</code>...<code>[/col]</code><code>[col]</code>...<code>[/col]</code> into your post or page for two even columns. Of course replace ... with your content.</p>
			<p><strong>2) Columns use <em>blocks</em>...</strong></p>
			<div class="jdt-column-wrapper">
				<div class="jdt-one-col first"><code>[1]</code></div>
				<div class="jdt-one-col"><code>[2]</code></div>
				<div class="jdt-one-col"><code>[3]</code></div>
				<div class="jdt-one-col"><code>[4]</code></div>
				<div class="jdt-one-col"><code>[5]</code></div>
				<div class="jdt-one-col"><code>[6]</code></div>
				<div class="jdt-one-col"><code>[7]</code></div>
				<div class="jdt-one-col"><code>[8]</code></div>
				<div class="jdt-one-col"><code>[9]</code></div>
				<div class="jdt-one-col"><code>[10]</code></div>
				<div class="jdt-one-col"><code>[11]</code></div>
				<div class="jdt-one-col last"><code>[12]</code></div>
			</div>
			<p>By default your columns span from blocks 1-6 and then 7-12.</p>
			<p><strong>3) Columns can have a <em>custom width</em>...</strong></p>
			<p>Ideally, you can change your default width below to a number like 3, 4 or 6 for a four column, three column, or two column layout, respectively.</p>
			<p>Set width in the shortcode.</p>
			<p><code>[col w="4"]</code>...<code>[/col]</code>
				<code>[col w="5"]</code>...<code>[/col]</code>
				<code>[col w="3"]</code>...<code>[/col]</code>
			</p>
			<p>See how they add up to twelve [4 + 5 + 3]? They must to work correctly. You can have two different column groups on one page using this.</p>
			<p><strong>4) Columns <em>should</em> be on the same line...</strong></p>
			<p>Wordpress has a funky thing about it where it adds space automatically (by adding <code>&lt;br&gt;</code> and <code>&lt;p&gt;</code> tags), so keep your columns on one line or turn off wpautop in the general settings of this plugin.
			<h3>Column Settings</h3>
			<div class="jdt-column-wrapper">
				<div class="jdt-three-col first">
					Default column width
				</div>
				<div class="jdt-nine-col last"><input min="1" max="12" type="number" name="jdt_default_colwidth" value="<?php echo get_option( 'jdt_default_colwidth', '6' ) ?>">
					<p class="description">Enter value 1 - 12. Using 3, 4 or 6 is recomended.</p>
				</div>
			</div>
			<h3>Use this plugin's <acronym title="Cascading Style Sheets">CSS</acronym> for columns</h3>
			<p><input type="checkbox" <?php if($use_column_css === 'on') {echo 'checked';} ?> name="use_column_css" value="1"> Uncheck this if you want to style your own custom columns or if your theme already has the classes.</p>
		<?php jdt_form_footer('column-settings') ?>
		<?php endif; ?>

<!-- [||||||||||||||||||||||||||||||||||||||||||||||||||||||||] -->

		<?php if ( jdt_form_header('general-settings') ) : ?>
			<h2>General Settings</h2>
				<h3>Preview [home_url] on Visual Editor</h3>
				<p><input type="checkbox" <?php if($preview_homeurl_shortcode === 'on') {echo 'checked';} ?> name="preview_homeurl_shortcode" value="1"> Convert [home_url] on the Tiny MCE visual tab &#8212; useful for pictures.</p>

			<h3>Turn OFF wpautop filter</h3>
			<div class="jdt-column-wrapper">
				<div class="jdt-three-col first">
					For content &#8212; <code>the_content</code>
				</div>
				<div class="jdt-nine-col last"><input type="checkbox" <?php if($wpautop_content === 'on') {echo 'checked';} ?> name="wpautop_content" value="1"> 
				</div>
			</div>
			<div class="jdt-column-wrapper">
				<div class="jdt-three-col first">
					For excerpts &#8212; <code>the_excerpt</code>
				</div>
				<div class="jdt-nine-col last">
					<input type="checkbox" <?php if($wpautop_excerpt === 'on') {echo 'checked';} ?> name="wpautop_excerpt" value="1"> 
				</div>
			</div>
			<br>
			<p class="description">The <a href="http://codex.wordpress.org/Function_Reference/wpautop">wpautop</a> filter is built into wordpress to add line breaks and paragraph tags to your posts. This can be frustrating when trying to organize html content properly. This will not making any permanent changes, but turning it off may affect the way olds posts appear, so beware.
			</p>
		<?php jdt_form_footer('general-settings') ?>
		<?php endif; ?>

<!-- [||||||||||||||||||||||||||||||||||||||||||||||||||||||||] -->

		<?php if ( jdt_form_header('link-settings') ) : ?>
			<h2>Post content and dev URLs</h2>
<!-- 			<p>The [homeurl] shortcode will work so long as this plugin is active, if you deactivate it, add this to your functions.php:</p>
			<pre>
	function custom876_home_url_shortcode(){
		$siteurl = site_url();
		return $siteurl;
	}
	add_shortcode( 'homeurl', 'custom876_home_url_shortcode' );
			</pre> -->
			First, do a search using the settings below. You will be shown the number of results and posts that will be affected, and can then choose to finalize the results.
			<?php if ( !$jdt_urls->have_results ) { ?>
			<?php $submitbtnlabel = "Find matches"; ?>
			<p>
				Change all instances of <input name="source_value" value="<?php echo home_url() ?>"> to <input type="text" value="[homeurl]" name="replace_value">
				<input type="hidden" name="find_and_save" value="1">
			</p>
			<?php } ?>
			
			<?php if ( $jdt_urls->have_results ){ ?>
			<?php $submitbtnlabel = "Replace matches"; ?>
			<p>
				<input type="checkbox" name="finalize_matches"> <?php echo $jdt_urls->results_for_humans; ?>
				<p>Will change posts and pages, both published and not. <strong>Probably</strong> not custom posts, but this has not been tested.</p>
			</p>
			<p class="description">Hold on buddy! think about what you're going to do here. Did you back up your database? Would you? Pretty please.</p>
			<p>You can only undo by doing the opposite replace, which could have other consequences.</p>
			<?php } ?>

			<?php jdt_form_footer('link-settings', $submitbtnlabel) ?>
		<?php endif; ?>
		<?php if ( jdt_form_header('advanced-settings') ) : ?>
			<h2>Advanced Settings</h2>





			<?php jdt_form_footer('advanced-settings', "Save Settings") ?>
		<?php endif; ?>
	</div><!-- eof of wrap -->
</div><!-- jdt-admin-wrap -->
<?php } ?>