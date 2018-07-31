<?php
namespace core\lib\utilities {
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

	/********************************
Config Functions
	 *********************************/
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\json as json;
	use core\lib\pages as pages;

	final class Config
	{
		/***************************************************************
		 ***************************************************************/
		public static function get($path = null)
		{
			if ($path) {
				$config = $GLOBALS['config'];
				$path = explode('/', $path);

				foreach ($path as $bit) {
					if (isset($config[$bit])) {
						$config = $config[$bit];
					}
				}
				return $config;
			}
			return false;
		}
	}

}