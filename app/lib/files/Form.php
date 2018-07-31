<?php
namespace core\lib\forms {
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

/***************************************************************/
	/****************************** 
	 * Form Functions
	 ******************************/
	/*
	 *  Fill out the form
	 *  - POST to PHP
	 *  - Sanitize 
	 *  - Validate 
	 *  - Return Data
	 *  - Write to Database
	 *
	 */
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	class Form
	{
		/** @var array $_currentItem the immidiately posted data**/
		private $_currentItem = null;
		/** @var array $_postData stores the posted data **/
		private $_postData = array();
		/** @var object $_validator The validator object **/
		private $_validator = array();
		/** @var object $_error Holds the current forms errors  **/
		private $_error = array();
		/***************************************************************
		 ***************************************************************/
		/*
		 *  The Constructor
		 */
		public function __construct()
		{
			$this->_validation = new Validator(new errs\ErrorHandler);
		}
		/***************************************************************
		 ***************************************************************/
		/*
		 *  post - This is to run $_POST 
		 *  @param string $field - The HTML fieldName to post 
		 */
		public function post($field)
		{
			$this->_postData[$field] = $_POST[$field];
			$this->_currentItem = $field;
			return $this;
		}
		/***************************************************************
		 ***************************************************************/
		/*
		 *	fetch - Return the posted data 
		 * 
		 *  @param mixed $fieldName
		 *  @return mixed string or Array 
		 */
		public function fetch($fieldName = false)
		{
			if ($fieldName) {
				if (isset($this->_postData[$fieldName])) {
					return $this->_postData[$fieldName];
				}
				else {
					return false;
				}
			}

			else {
				return $this->_postData;
			}
		}
		/***************************************************************
		 ***************************************************************/
		/*
		 *  validation - This is to validate
		 *  @param string $typeOfValidator A method from the Form/Validation Class
		 *  @param string arg A property to validate against
		 */
		public function validate($typeOfValidator, $arg = null)
		{

			if ($arg == null)
				$error = $this->_validation->{$typeOfValidator}($this->_postData[$this->_currentItem]);
			else
				$error = $this->_validation->{$typeOfValidator}($this->_postData[$this->_currentItem], $arg);

			if ($error)
				$this->_error[$this->_currentItem] = $error;

			return $this;

		}
		/***************************************************************
		 ***************************************************************/
		/*
		 *  submit - Handles the form, and throws an exception upon error. 
		 *  @param string $typeOfValidator A method from the Form/Validation Class
		 *  @param string arg A property to validate against
		 */

		public function submit()
		{
			if (empty($this->_error)) {
				return true;
			}
			else {
				$str = '';
				foreach ($this->_error as $key => $value) {
					$str .= $key . ' => ' . $value . "\n";
				}
				throw new Exception($str);
			}
		}
	}
}