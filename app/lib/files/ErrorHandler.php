<?php 
namespace core\lib\errors {
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
Error Functions
	 *********************************/
	use core\lib\imgs as imgs;
	use core\lib\forms as forms;
	use core\lib\files as files;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	class ErrorHandler
	{
		protected $errors = [];
		/***************************************************************
		 ***************************************************************/
		public function addError($error, $key = null)
		{
			if ($key) {
				$this->errors[$key][] = $error;
			}
			else {
				$this->errors[] = $error;
			}
		}
		/***************************************************************
		 ***************************************************************/
		public function all($key = null)
		{
			return isset($this->errors[$key]) ? $this->errors[$key] : $this->errors;
		}
		/***************************************************************
		 ***************************************************************/
		public function hasErrors()
		{
			return count($this->all()) ? true : false;
		}
		/***************************************************************
		 ***************************************************************/
		public function first($key)
		{
			return isset($this->all()[$key][0]) ? $this->all()[$key][0] : false;
		}
	}
}