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

	use core\lib\files as files;
	use core\lib\errors as errs;
	use core\lib\imgs as imgs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \orm as orm;	
	use \PDO;

	//Technical Details
	//Msg Class D000.
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

	class Delete implements Execute
	{
		private static $_from;
		private static $_where;
		private static $_and;
		private static $_or;
		private static $_query;
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
						case 'from' :
							self::_from($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'where' :
							self::_where($value);
							$queue[$i] = $key;
							$i = $i + 1;
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
		private static function _from(string $from)
		{
			$form = trim($form);
			if (!orm\Query::is_table($from)) {

			}

			if ($from !== NULL) {
				self::$_from = "FROM `{$from}` ";
				self::$_table = trim($from);
			}
			else {
				set_error( "Arguments for FROM cannot be NULL: " . __CLASS__ . "/". __FUNCTION__ );
			}
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
			self::$_where = Expr::logic(self::$_table,$and,'AND');
		}
		/***************************************************************
		 ***************************************************************/
		private static function _or(string $or)
		{
			self::$_where = Expr::logic(self::$_table,$or,'OR');
		}
		/***************************************************************
		 ***************************************************************/
		protected static function buildQuery()
		{
			$query = 'DELETE ';
			$queue = array();
			$queue = self::$_queue;
			foreach ($queue as $setOrder) {
				$order = strtolower(trim($setOrder));
				switch ($setOrder) {
					case 'from' :
						$query .= self::$_from;
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