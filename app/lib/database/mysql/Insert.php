<?php
namespace core\orm\mysql {
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	/***************************************************************
WARNING: To avoid misbehaviour, please do not touch the files in the APP library. 
	 ***************************************************************
Application Name: Quick MVC PHP APPLICATION
File Version: 2.0
Author: Ankit Kumar
Developer/Designer: Ankit Kumar
Contact Support: mail2ankit85@gmail.com
Company: Contemplative Radical Solutions Consulting Private Limited.  
File/Class Name: Insert.php 
File/Class Version: 1
Type: N/A
	 *******************CHANGE LOGS*********************************/
	/* MAINTANANCE ACTIVITY LOG TO BE MAINTAINED 
   WITH DATES AND CHANGES MADE. */
	/***************************************************************

/***************************************************************/
	//Technical Information
	//Msg Class: I000

	use core\lib\files as files;
	use core\lib\errors as errs;
	use core\lib\imgs as imgs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \orm as orm;
	use \PDO;
	
	class Insert implements Execute
	{
		private static $_into;
		private static $_values;
		private static $_table;
		private static $_queue = array();
		private static $_sym = array(',', '"');
		/***************************************************************
		 ***************************************************************/
		public static function setQuery(array $param)
		{
			if (!empty($param)) {
				$i = 0;
				$queue = array();
				foreach ($param as $key => $value) {
					$key = strtolower($key);
					switch ($key) {
						case 'into' :
							self::_into($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'values' :
							self::_values($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						default :
						set_error("Index/Option Not Found for Class: " . __CLASS__ );
					}
				}
			}else{
				set_error("Empty Array Object Passed " . __CLASS__ );
			}
			self::$_queue = $queue;
			$q = self::buildQuery();
			return $q;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _into(string $into)
		{
			$into = trim($into);
			if (!orm\Query::is_table($into)) {
			}
			if ($into !== NULL) {
				self::$_into = "INTO `{$into}`";
				self::$_table = $into;
			}
			else {
				set_error( $table." :is not a existing table in the database.". __CLASS__ . "/". __FUNCTION__ );
			}
		}
		/***************************************************************
		 ***************************************************************/
		private static function _values(array $values)
		{
			$keys = '';
			$elements = '';
			$values['created'] = utils\Timestamp::setTime();
			$values['modified'] = utils\Timestamp::setTime();
			foreach ($values as $key => $value) {
				$keys .= "`{$key}`,";
				$elements .= "'{$value}', ";
			}
			$keys = rtrim($keys, ', ');
			$elements = rtrim($elements, ', ');
			$query = "({$keys}) VALUES ({$elements}) ";
			self::$_values = $query;
		}
		/***************************************************************
		 ***************************************************************/
		public static function buildQuery()
		{
			$query = 'INSERT ';
			$query .= self::$_into;
			$query .= self::$_values;
			return $query;
		}
	}
}