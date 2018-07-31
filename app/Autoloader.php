<?php
namespace core {
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
	use core\lib\json as json;
	use core\lib\pages as pages;

	final class Autoloader
	{
		private static $paths = array();
		private static $registered;
		/**
         * Register path to the autoloader
         *
         * @param $path path for autoloading
         */
		public static function addPath($path)
		{
			self::$paths[] = $path;
			// Check if we are already registered
			if (!self::$registered) {
				self::register();
			}
		}
		/**
         * Register myself
         */
		private static function register()
		{
			spl_autoload_register('\core\Autoloader::load');
		}
		/**
         * Autoload a class
         * @param $class Classname
         */
		public static function load(string $class)
		{
			// Namespace escape
			$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
			foreach (self::$paths as $path) {
				if (file_exists($path . DIRECTORY_SEPARATOR . $class . '.php')) {
					require $path . DIRECTORY_SEPARATOR . $class . '.php';
					return;
				}
				if (file_exists($path . DIRECTORY_SEPARATOR . $class . '.class.php')) {
					require $path . DIRECTORY_SEPARATOR . $class . '.class.php';
					return;
				}
			}
		}
	}
}