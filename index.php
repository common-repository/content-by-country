<?php

/*
Plugin Name: Content by Country
Plugin URI: http://www.simonemery.co.uk/wp-plugins/content-by-country/
Description: Adds the ability to target post content by a visitor's country location
Version: 1.00
Author: Simon Emery
Author URI: http://www.simonemery.co.uk
*/

class cbc_geo_content {

	var $location;

	function location() {
		global $cbc_visitor_location;
		if(!is_admin()) {
			if($_SERVER['HTTP_HOST'] != "localhost") {
				$dir = str_replace("\\", "/", dirname(__FILE__));
				if(!function_exists('geoip_open')) {
					include_once($dir.'/database/geoip.php');
				}
				$ip = strip_tags($_SERVER['REMOTE_ADDR']);
				$gi = @geoip_open($dir.'/database/GeoIP.dat', GEOIP_STANDARD);
				$this->location = @geoip_country_code_by_addr($gi, $ip);
				$cbc_visitor_location = $this->location;
				@geoip_close($gi);
			} else {
				$this->location = strip_tags($_GET['cbc']);
				$cbc_visitor_location = $this->location;
			}
			return $this->location;
		}
		return false;
	}

	function get_string($string, $start, $end) {
		$string = " " . $string;
		$ini = strpos($string, $start);
		if($ini == 0) return "";
		$ini += strlen($start);   
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string,$ini,$len);
	}

	function parse_content($content) {
		if(!empty($this->location)) {
			$this->location = strtolower($this->location);
			$finish = 1;
			for($z=1; $z <= $finish; $z++) {
				$loc = $this->get_string($content, "[cbc", "]");
				if(!empty($loc)) {
					$text = $this->get_string($content, "[cbc".$loc."]", "[/cbc]");
					$from = "[cbc" . $loc . "]" . $text . "[/cbc]";
					if(strpos($loc, ":+".$this->location) !== false) {
						$to = $text;
					} elseif(strpos($loc, ":".$this->location) !== false) {
						$to = $text;
					} elseif(strpos($loc, ":-".$this->location) !== false) {
						$to = '';
					} elseif(strpos($loc, ":-") !== false) {
						$to = $text;
					} else {
						$to = '';
					}
					$content = str_replace($from, $to, $content);
					$finish++;
				}
			}
			$content = str_replace("<p><br />\n", "<p>", $content);
			$content = str_replace("<p></p>\n", "", $content);
		}
		return $content;
	}

	function admin_options() {
		if(is_admin()) {
			echo "<style type=\"text/css\">.example { color: #675D1C; border: 1px solid #FFE95A; background: #FFFBE0; padding: 10px; overflow: visible; }</style>\n";
			echo "<div class='wrap'>\n";
			echo "<h2>Content by Country</h2>\n";
			echo "Below is a full list of countries and codes to use for content targeting, as well as some usage examples.\n";
			echo "<br /><br /><br />\n";
			echo "<b>Usage Examples:</b>\n";
			echo "<br /><br />\n";
			echo "<div class='example'>\n";
			echo htmlentities('[cbc:+usa:+gb]My example text goes here[/cbc]') . " &nbsp;&nbsp;<i>displays the text to visitors from the US and UK only</i>\n";
			echo "</div>\n";
			echo "<br />\n";
			echo "<div class='example'>\n";
			echo htmlentities('[cbc:-usa]My example text goes here[/cbc]') . " &nbsp;&nbsp;<i>excludes users from the US from viewing the text</i>\n";
			echo "</div>\n";
			echo "<br /><br /><br />\n";
			echo "<b>Country List:</b>\n";
			echo "<br /><br />\n";
			if(!function_exists('geoip_open')) {
				$dir = str_replace("\\", "/", dirname(__FILE__));
				include_once($dir.'/database/geoip.php');
			}
			$geo_class = new GeoIP();
			$geo = $geo_class->merge_arrays();
			$geo_count = count($geo);
			$geo_counter_half = round($geo_count / 2);
			if(!empty($geo) && $geo_count > 0) {
				ksort($geo);
				$counter = 0;
				echo "<table width='100%'>\n";
				echo "<tr><td valign='top'>\n";
				foreach($geo as $key => $val) {
					if($counter == $geo_counter_half) {
						echo "</td><td valign='top'>\n";
					}
					echo $val . " --> " . strtolower($key) . "\n";
					echo "<br />\n";
					$counter++;
				}
				echo "</td></tr>\n";
				echo "</table>\n";
			} else {
				echo "<br /><br />\n";
				echo "Oops, there seems to have been a problem loading the database. Please refresh the page!\n";
			}
			echo "<br />\n";
			echo "</div>\n";
		}
	}

	function admin_page() {
		add_management_page('CBC Content', 'CBC Content', 8, 'cbc-content', array(&$this, 'admin_options'));
	}

	function activate() {
		header("Location: edit.php?page=cbc-content");
		exit();
	}
	
	function init() {
		add_action('wp', array(&$this, 'location'), 101);
		add_action('admin_menu', array(&$this, 'admin_page'));
		add_filter('the_content', array(&$this, 'parse_content'));
	}
	
}

//init class
$cbc = new cbc_geo_content;
$cbc->init();

//activation
register_activation_hook(__FILE__, array(&$cbc, 'activate'));

?>