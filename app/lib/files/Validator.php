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

	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	/****************************** 
	 * Common Functions
	 ******************************/
	/**
	 * Public:: Static Method to converts Objects into an Arraya
	 * @param   object $obj      Object Name
	 * @return  array  $array    Array Output of the given object.
	 **/
	class Validator
	{
		protected $db;
		protected $errorHandler;
		protected $items;
		protected $rules = [];
		public $messages = [];

		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		public function __construct(errs\ErrorHandler $errHndlr)
		{
			$this->errorHandler = $errHndlr;
			$this->db = new \core\Database();
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		public function check($items, $rules)
		{
			$this->items = $items;
			foreach ($items as $item => $value) {
				if (in_array($item, array_keys($rules))) {
					$this->validate([
						'field' => $item,
						'value' => $value,
						'rules' => $rules[$item]
					]);
				}
			}
			return $this;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		public function fails()
		{
			return $this->errorHandler->hasErrors();
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		public function errors()
		{
			return $this->errorHandler;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function validate($item)
		{
			$field = $item['field'];
			foreach ($item['rules'] as $rule => $satisfier) {
				if (in_array($rule, $this->rules)) {
					if (!call_user_func_array([$this, $rule], [$field, $item['value'], $satisfier])) {
						$this->errorHandler->addError(
							str_replace(
							[':field', ':satisfier'],
							[$field, $satisfier],
							$this->messages[$rule]
						),
							$field
						);
					}
				}
			}
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function required($field, $value, $satisfier)
		{
			return !empty($value);
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function minlength($field, $value, $satisfier)
		{
			return mb_strlen($value) >= $satisfier;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function maxlength($field, $value, $satisfier)
		{
			return mb_strlen($value) <= $satisfier;
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function email($field, $value, $satisfier)
		{
			return filter_var($value, FILTER_VALIDATE_EMAIL);
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function alphanum($field, $value, $satisfier)
		{
			return ctype_alnum($value);
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function match($field, $value, $satisfier)
		{
			return $value === $this->items[$satisfier];
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function checked($field, $value, $satisfier)
		{
			return $value === '1';
		}
		/***************************************************************
		 ***************************************************************/
		/**
		 * Public:: Static Method to converts Objects into an Arraya
		 * @param   object $obj      Object Name
		 * @return  array  $array    Array Output of the given object.
		 **/
		protected function unique($field, $value, $satisfier)
		{
			//return !$this->db->exists($satisfier,[$field => $value]);

		}
		/***************************************************************
		 ***************************************************************/
		public function __call($name, $arguments)
		{
			throw new Exception("$name does not exist inside of: " . __CLASS__);
		}
	}
}