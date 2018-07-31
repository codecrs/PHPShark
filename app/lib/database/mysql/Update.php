<?php
namespace core\orm\mysql {
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

	//Technical Details
	//Msg Class U000.
	/*
	Wild Card Searches.
	WHERE City LIKE 'ber%';
	WHERE City LIKE '%es%';
	WHERE City LIKE '_erlin';
	WHERE City LIKE 'L_n_on';
	WHERE City LIKE '[bsp]%';
	WHERE City LIKE '[a-c]%';
	WHERE City LIKE '[!bsp]%';
	WHERE City NOT LIKE '[bsp]%';
	 */
	use core\lib\files as files;
	use core\lib\errors as errs;
	use core\lib\imgs as imgs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \orm as orm;
	use \PDO;

	class Update implements Execute
	{
		private static $_into;
		private static $_where;
		private static $_and;
		private static $_or;
		private static $_set;
		private static $_query;
		private static $_table;
		private static $_queue = array();
		private static $_sym = array(',', '"');
		private static $isWherePresent = '0';
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
						case 'set' :
							self::_set($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'where' :
							self::_where($value);
							$queue[$i] = $key;
							$i = $i + 1;
							self::$isWherePresent = '1';
							break;
						case 'and' :
							self::_and($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'or' :
							self::_or($value);
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
				set_error( $table." :is not a existing table in the database.". __CLASS__ . "/". __FUNCTION__ );
			}

			if ($into !== NULL) {
				self::$_into = "`{$into}`";
				self::$_table = trim($into);
			}
			else {
				set_error( "Incorrect table definition : " . __CLASS__ . "/". __FUNCTION__ );
			}
		}
		/***************************************************************
		 ***************************************************************/
		private static function _set(array $set)
		{
			$elements = '';
			$set['modified'] = utils\Timestamp::setTime();
				foreach ($set as $key => $value) {
					$setKey = "`{$key}`";
					$setValue = "'{$value}'";
					$elements .= "{$setKey} = {$setValue}, ";
				}
				$elements = rtrim($elements, ', ');
				$query = "SET {$elements} ";
			self::$_set = $query;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _where(string $where)
		{		
			self::$_where = Expr::logic(self::$_table,$where,'WHERE');
		}
		/***************************************************************
		 ***************************************************************/
		private static function _and(string $and)
		{
			self::$_and = Expr::logic(self::$_table,$and,'AND');
		}
		/***************************************************************
		 ***************************************************************/
		private static function _or(string $or)
		{
			self::$_or = Expr::logic(self::$_table,$or,'OR');
		}
		/***************************************************************
		 ***************************************************************/
		public static function buildQuery()
		{
			$query = 'UPDATE ';
			$queue = array();
			$queue = self::$_queue;
			foreach ($queue as $setOrder) {
				$order = strtolower(trim($setOrder));
				switch ($setOrder) {
					case 'into' :
						$query .= self::$_into;
						break;
					case 'set' :
						$query .= self::$_set;
						break;
					case 'where' :
						$query .= self::$_where;
						break;
					case 'and' :
						$query .= self::$_and;
						break;
					case 'or' :
						$query .= self::$_or;
						break;
				}
			}
			return $query;
		}
	}
}