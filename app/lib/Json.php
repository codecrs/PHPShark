<?php 
namespace core\lib\json{
	if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  Qmvc - Quick PHP MVC 
 *  Developed by Contempative Radical Solutions Consulting Private Ltd. 
 *  Project Initiative By Ankit Kumar
 *  http://www.contemplativeradicals.com
 *  
 *  @copyright  Contempative Radical Solutions Consulting Private Ltd. 
 *  @link
 *  @since      1.0.0
 *  @license
 *  
 * Change Logs
 * *******************************************************************
 * 
 * *******************************************************************
 **/

	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\pages as pages;
	//use core\lib\json as json;

	 class Json extends files\Files{

		protected static $_messages = array(
			JSON_ERROR_NONE => '0',
			JSON_ERROR_DEPTH => '1',
			JSON_ERROR_STATE_MISMATCH => '2',
			JSON_ERROR_CTRL_CHAR => '3',
			JSON_ERROR_SYNTAX => '4',
			JSON_ERROR_UTF8 => '5'
		);

		public function __construct()
		{
			
		}
		/***************************************************************
         ***************************************************************/
		public function writeJSONFile($rows, $name = null, $obj = true, $path = null)
		{
			if ($path == null) {
				$default_path = PROJECT_PATH . 'warehousing' . DS;
				$path = $default_path;
			}
			if ($name == null) {
				$name = 'default';
			}
			if (file_exists($path . $name)) {
				unlink($path . $name);
			}
			$name .= '.json';
			$fp = fopen($path . $name, 'w');
			if ($obj == true) {
				fwrite($fp, json_encode($rows, JSON_FORCE_OBJECT));
			}
			else {
				fwrite($fp, json_encode($rows));
			}
			fclose($fp);
			return true;
		}
		/***************************************************************
         ***************************************************************/
		public function readJSONFile($name = null, $path = null)
		{
			if ($path == null) {
				$default_path = PROJECT_PATH . 'migrations' . DS;
				$path = $default_path;
			}
			if ($name == null) {
				$name = 'default';
			}
			$name .= '.json';
			$str = file_get_contents($path . $name);
			$json_array = json_decode($str, true);
			return $json_array;
		}
		/***************************************************************
         ***************************************************************/
		public function searchReadJSONFile($key, $equalValue, $name = null, $path = null)
		{
			if ($path == null) {
				$default_path = PROJECT_PATH . 'migrations' . DS;
				$path = $default_path;
			}
			if ($name == null) {
				$name = 'default';
			}
			$name .= '.json';
			$str = file_get_contents($path . $name);
			$json_array = json_decode($str, true);
			for ($idx = 0; $idx < count($json_array); $idx++) {
				if ($json_array[$idx][$key] == $equalValue) {
					return $json_array[$idx];
				}
			}
		}
		/***************************************************************
         ***************************************************************/
		public function JsonAPIValues($url)
		{
			$json = file_get_contents($url);
			$obj = json_decode($json);
			return $obj;
		}

		/***************************************************************
		***************************************************************/
		public function encode($value, $options = 0) {
			header("Content-type: application/json; charset=utf-8");
			$result = json_encode($value, $options);

			if($result)  {
				return $result;
			}

			throw new RuntimeException(static::$_messages[json_last_error()]);
		}
		/***************************************************************
		***************************************************************/
		public function decode($json, $assoc = false) {
			$result = json_decode($json, $assoc);

			if($result) {
				return $result;
			}

			throw new RuntimeException(static::$_messages[json_last_error()]);
		}

		/***************************************************************
		***************************************************************/
		public function output($dados, $charset = 'UTF-8'){
			header('Content-Type: application/json');
			if ($charset <> 'UFT-8') {
				array_walk_recursive($dados, function(&$value, $key) {
					if (is_string($value)) {
						$value = utf8_encode(self::clean($value));
					}
				});
			}
			return json_encode($dados);
		}
	}
}
