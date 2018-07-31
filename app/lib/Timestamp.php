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

	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\json as json;
	use core\lib\pages as pages;

	final class Timestamp
	{
		/*************************************************************** 
		 ***************************************************************/
		public static function setTime($format = null)
		{
			$time = Time();
			if ($format == null) {
				$res = date('Y-m-d H:i:s', $time);
			}
			else {
				$res = date($format, $time);
			}
			return $res;
		}
	}
}