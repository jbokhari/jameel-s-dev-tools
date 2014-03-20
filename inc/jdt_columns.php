<?php
class JDT_Columns{
	/**
	 *
	 * To do column width using px (regex if px is included)
	 * Column predefined styles
	 *
	**/
	function __construct(){
		$this->totalwidth = 0;
		$this->errors = array();
		$this->logs = array(); //used for debugging
		$this->numbers = array('one','two','three','four','five','six','seven','eight','nine','ten','eleven','twelve');
		add_shortcode('column_wrap', array($this, 'jdt_columns_wrap'));
		add_shortcode('column', array($this, 'jdt_column'));
		add_shortcode('col', array($this, 'jdt_column'));
		add_shortcode('showerrors', array($this, 'show_errors'));
		add_shortcode('showlogs', array($this, 'show_logs'));
		add_action( 'wp_enqueue_scripts', array($this, 'do_enqueue_stuff') );
	}
	function show_errors(){
		$return = "";
		foreach ($this->errors as $i => $e) {
			$i++;
			$return .= "<!-- " . $i . ") " .$e . " -->";
		}
		return $return;
	}
	function show_logs(){
		$return = "";
		foreach ($this->logs as $i => $l) {
			$i++;
			$return .= "<!-- " . $i . ") " .$l . " -->";
		}
		return $return;
	}
	function _error($error){
		$this->errors[] = $error;
	}
	function _log($log){
		$this->logs[] = $log;
	}
	function do_enqueue_stuff(){
		if(get_option('use_column_css', 'on') === 'on'){
			wp_enqueue_style( 'jdt_columns', plugin_dir_url( __FILE__ ) . '../css/jdt_columns.css', null, false, 'all' );
		}
	}
	function jdt_columns_wrap($atts, $content = null){
		$content = do_shortcode( $content );
		// $content = str_replace('<br>', '', $content); this didn't work :(
		return $content;
	}
	function jdt_column( $atts, $content = null ){
		extract( shortcode_atts( 
			array(
				'w' => null,
				'width' => get_option( 'jdt_default_colwidth', 6 ),
				'pos' => null
				), $atts )
		);
		if ( !is_null($w) ){ $width = $w; } //$w takes priority
		if( is_numeric($width) ){
			$strwidth = $this->convert_integer_to_string($width);
			$intwidth = $width;
			// $this->_log("Integer width = $intwidth");
			// $this->_log("String width = $strwidth");
		} else if( is_string($width) ) {
			$strwidth = $width;
			$intwidth = $this->convert_string_to_integer($width);
			// $this->_log("Integer width = $intwidth");
			// $this->_log("String width = $strwidth");
		}

		$newcontent = '';
		$pos = $this->column_position($intwidth, $pos); //$pos should be null unless override is set
		if ( $pos  === 'first'){
			$newcontent .= "<div class='column-wrapper'>" . PHP_EOL;
		}
		$class = '';
		$class .= $pos . ' '; //add space
		$class .= "{$strwidth}col";
		$newcontent .= "<div class='$class'>$content</div>" . PHP_EOL;
		$newcontent .= "<!-- End Column -->" . PHP_EOL;
		if ( $pos === 'last' ){
			$newcontent .= '</div><!-- End Column Group -->' . PHP_EOL;
		}
		$newcontent = do_shortcode( $newcontent );
		return $newcontent;
	}
	function column_position($width, $override){
		/**
		 * Determine what position the column is in
		 * Also sets the total width appropriately
		 * Lastly, uses $this->wasover to determing, essentially a bool that is set if
		 * the last column wasn (probably?) not closed
		**/
		$c = $width;
		if ( !is_null($override) ){
			if ($override === 'first'){
				$this->totalwidth = $c;
				return 'first';
			}
			if ($override === 'last'){
				$this->totalwidth = 0;
				return 'last';
			}
			$this->_error('Postition (pos) option can only be set to first or last');
		}
		$t = $this->totalwidth;
		$this->_log("$t");
		/**
		 * if total width is already 12 or 0 ... OR ... if the new total exceeds twelve
		 * set class to first
		 * if first set, new total is current column width 
		**/
		if ($t === 0 ){
			$t = 0;
			$this->totalwidth = $c;
			// $this->_log('Existing total was 0; Set first, and total set to current column width.');
			return 'first';
		}
		$newtw = $c + $t;
		if ( $newtw > 12 ){
			$this->wasover = true;
			$this->totalwidth = $c;
			// $this->_log('New total exceeds 12; Was over, set first, and total set to current column width.');
			return 'first';
		}
		//only use last if the newwidth is excactly 12
		//
		if ( $newtw === 12 ){
			$this->totalwidth = 0;
			// $this->_log('New total was exactly 12; Set last and total reset.');
			return 'last';
		}
		$this->totalwidth = $newtw;
		return;
		/*new, exactly 0 new, less than twelve */
	}
	function convert_integer_to_string( $input ){
		$input--;
		return $this->numbers[ intval($input) ];
	}
	function convert_string_to_integer( $input ){
		$key = array_search( strval($input), $this->numbers );
		return $key;
	}
}
$columns = new JDT_Columns();