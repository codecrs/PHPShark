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
	
	final class Request
	{

		var $method;
		/***************************************************************
         ***************************************************************/
		public function __construct()
		{
			$this->getServerMethod();
		}
		/***************************************************************
         ***************************************************************/
		public function isPost()
		{
			if ($this->method === 'POST') {
				return true;
			}
			else {
				return false;
			}
		}
		/***************************************************************
         ***************************************************************/
		public function isGet()
		{
			if ($this->method === 'GET') {
				return true;
			}
			else {
				return false;
			}
		}
		/***************************************************************
         ***************************************************************/
		public function getServerMethod()
		{
			$this->method = $_SERVER['REQUEST_METHOD'];
		}

		/***************************************************************
         ***************************************************************/
		public function getUrlVariable($var){
			$parts = parse_url($_SERVER['REQUEST_URI']);
			parse_str($parts['query'], $query);
			return($query[$var]);
		}

	}
}