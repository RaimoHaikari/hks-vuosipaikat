<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Vuosipaikat_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// self::debug();
	}

	private static function debug(){
		
		$filename = plugin_dir_path(__FILE__).'../debug.txt';

		$myfile = fopen($filename, "w") or die("Unable to open file!");
		$txt = "deactivate() ajettu \n";
		fwrite($myfile, $txt);
		fclose($myfile);	
		
	}
}
