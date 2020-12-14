<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Vuosipaikat_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// self::debug();		
	}
	
	private static function debug(){
		
		$filename = plugin_dir_path(__FILE__).'../debug.txt';

		$myfile = fopen($filename, "w") or die("Unable to open file!");
		$txt = "activate() ajettu \n";
		fwrite($myfile, $txt);
		fclose($myfile);			 
	}	

}
