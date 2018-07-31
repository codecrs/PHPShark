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
Sanitizer Functions 
	 *********************************/
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\json as json;
	use core\lib\pages as pages;
	
	final class Sanitizr
	{
		/***************************************************************
		 ***************************************************************/
		public function __construct()
		{
		}
		/***************************************************************
		 ***************************************************************/
		private static function stripSlashesDeep($value)
		{
			$value = is_array($value) ? array_map(array($self, 'strip_slashes_deep'), $value) : stripslashes($value);
			return $value;
		}
		/***************************************************************
		 ***************************************************************/
		public static function removeMagicQuotes()
		{
			if (get_magic_quotes_gpc()) {
				if (isset($_GET)) {
					$_GET = self::stripSlashesDeep($_GET);
				}

				if (isset($_POST)) {
					$_POST = self::stripSlashesDeep($_POST);
				}

				if (isset($_COOKIE)) {
					$_COOKIE = self::stripSlashesDeep($_COOKIE);
				}
			}
		}
		/***************************************************************
		 ***************************************************************/
		public static function unregisterGlobals()
		{
			if (ini_get('register_globals')) {
				$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
				foreach ($array as $value) {
					foreach ($GLOBALS[$value] as $key => $var) {
						if ($var === $GLOBALS[$key]) {
							unset($GLOBALS[$key]);
						}
					}
				}
			}
		}
		/***************************************************************
		 ***************************************************************/
		public static function cleanBuffer()
		{
			ob_end_clean();
		}
	}
}