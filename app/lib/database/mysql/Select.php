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
	//Msg Class S000.
	/*
	Wild Card Searches.
	SELECT * FROM Customers WHERE City LIKE 'ber%';
	SELECT * FROM Customers WHERE City LIKE '%es%';
	SELECT * FROM Customers WHERE City LIKE '_erlin';
	SELECT * FROM Customers WHERE City LIKE 'L_n_on';
	SELECT * FROM Customers	WHERE City LIKE '[bsp]%';
	SELECT * FROM Customers WHERE City LIKE '[a-c]%';
	SELECT * FROM Customers WHERE City LIKE '[!bsp]%';
	SELECT * FROM Customers WHERE City NOT LIKE '[bsp]%';
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

	class Select implements Execute
	{
		private static $_columns;
		private static $_from;
		private static $_where;
		private static $_and;
		private static $_or;
		private static $_query;
		private static $_table;
		private static $_limit;
		private static $_order;
		private static $_offset;
		private static $_top;
		private static $_between;
		private static $_queue = array();
		private static $_sym = array(',', '"');
		//Other Functions
		private static $_max;
		private static $_min;
		private static $_round;
		private static $_avg;
		private static $_sum;
		private static $_count;
		private static $_groupBy;
		private static $_having;
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
						case 'columns' :
							self::_column($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'top' :
							self::_top($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'from' :
							self::_from($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'where' :
							self::_where($value);
							$queue[$i] = $key;
							$i = $i + 1;
							Select::$isWherePresent = '1';
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
						case 'limit' :
							self::_limit($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'offset' :
							self::_offset($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'order' :
							self::_order($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'between' :
							self::_between($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
							// Other Functions
						case 'max' :
							self::_max($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'min' :
							self::_min($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'avg' :
							self::_avg($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'round' :
							self::_round($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'sum' :
							self::_sum($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'count' :
							self::_count($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'groupBy' :
							self::_groupBy($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'having' :
							self::_having($value);
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
		private static function _column(mixed $columns)
		{

			$selectColumn = '';
			if (isset($columns) == true) {
				if (is_array($columns)) {
					foreach ($columns as $column) {
						$selectColumn .= "`$column`, ";
					}
					self::$_columns = rtrim($selectColumn, ', ');
				}
				elseif (isset($columns) == true && $columns == '*') {
					self::$_columns = '*';
				}
				elseif (is_string($columns)) {
					self::$_columns = " {$columns} ";
				}
				else {
					set_error( "Incorrect column definition in function:" . __CLASS__ . "/". __FUNCTION__ );
				}
			}
		}

		/***************************************************************
		 ***************************************************************/
		private static function _from(string $from)
		{
			$from = trim($from);

			if (!orm\Query::is_table($from)) {            
				set_error( "Incorrect table definition: " . __CLASS__ . "/". __FUNCTION__ );
			}
			if ($from !== NULL) {
				self::$_from = " FROM `{$from}`";
				self::$_table = trim($from);
			}
			else {
				set_error( "Incorrect table definition: " . __CLASS__ . "/". __FUNCTION__ );
			}
		}
		/***************************************************************
		 ***************************************************************/
		private static function _where(string $where)
		{
			self::$_where = Expr::logic(self::$_table,$where,' WHERE');
		}
		/***************************************************************
		 ***************************************************************/
		private static function _and(string $and)
		{
			self::$_and = Expr::logic(self::$_table,$and,' AND');	
		}
		/***************************************************************
		 ***************************************************************/
		private static function _or(string $or)
		{
			self::$_or = Expr::logic(self::$_table,$or,' OR');	
		}
		/***************************************************************
		 ***************************************************************/
		private static function _limit(int $limit)
		{
			if (!empty($limit)) {
				$query = 'LIMIT ';
					$query .= "{$limit} ";
			}
			self::$_limit = $query;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _offset(int $offset)
		{
			if (!empty($offset)) {
				$query = 'OFFSET ';
					$query .= "{$offset} ";
			}
			self::$_offset = $query;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _top($top)
		{

			if (!empty($top)) {
				$query = 'TOP ';
				if (is_numeric($top)) {
					$query .= "{$top} * ";
				}
				else if (strpos($top, '%')) {
					$query .= "{$top} PERCENT ";
				}
				else {
					set_error( "Arguments for TOP has to be Numeric Value:" . __CLASS__ . "/". __FUNCTION__ );
				}
			}
			self::$_top = $query;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _between($between)
		{
			if (!empty($between)) {
				if (Select::$isWherePresent == '1') {
					$query = 'AND ';
				}
				else {
					$query = 'WHERE ';
				}

				if (is_array($between) && count($between) == '3') {
					$query .= "`{$between[0]}` BETWEEN '{$between[1]}' AND '{$between[2]}'";
				}
				else {
					set_error( $between. " :is not a valid Argument. BETWEEN Requires Array".
					 __CLASS__ . "/". __FUNCTION__ );
				}
			}
			self::$_between = $query;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _order(array $order)
		{
			$query = ' ORDER BY ';
			foreach ($order as $elements) {
				$element = explode(' ', $elements);
				$element = array_clean($element);

				switch ($element[1]) {
					case 'ASC' :
						if (orm\Query::is_field(select::$_table, $element[0])) {
							$query .= "{$element[0]} ASC ";
						}
						else {
							set_error( $table." :is not a existing table-field. ". 
							__CLASS__ . "/". __FUNCTION__ );
						}
						break;

					case 'DESC' :
						if (orm\Query::is_field(select::$_table, $element[0])) {
							$query .= "{$element[0]} DESC ";
						}
						else {
							set_error( $table." :is not a existing table-field. ". 
							__CLASS__ . "/". __FUNCTION__ );
						}
						break;
				}
			}
			$query .= ', ';
			$query .= rtrim($query, ', ');
			self::$_order = $query;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _max(string $max)
		{
			self::$_max = "MAX({$max}) ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _min(string $min)
		{
			self::$_min = "MIN({$min}) ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _sum(string $sum)
		{
			self::$_sum = "SUM({$sum}) ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _avg(string $avg)
		{
			self::$_avg = "AVG({$avg}) ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _round(string $round)
		{
			if ($round = explode(',', $round)) {
				self::$_round = "ROUND({$round[0]},{$round[1]})";
			}
			else {
			set_error( "Invalid Argument Given to ROUND. ROUND required comma separated values with Decimal Palces". 
				__CLASS__ . "/". __FUNCTION__ );
			}
		}
		/***************************************************************
		 ***************************************************************/
		private static function _count(string $count)
		{
			self::$_count = "COUNT({$count}) ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _groupBy(string $groupby)
		{
			self::$_groupBy = "GROUP BY({$groupby}) ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _having(string $having)
		{
			self::$_having = "HAVING {$having}";
		}
		/***************************************************************
		 ***************************************************************/
		public static function buildQuery()
		{
			$query = 'SELECT ';
			$queue = array();
			$queue = self::$_queue;

			foreach ($queue as $setOrder) {
				$order = strtolower(trim($setOrder));

				switch ($setOrder) {
					case 'columns' :
						$query .= self::$_columns;
						break;
					case 'top' :
						$query .= self::$_top;
						break;
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
					case 'limit' :
						$query .= self::$_limit;
						break;
					case 'offset' :
						$query .= self::$_offset;
						break;
					case 'order' :
						$query .= self::$_order;
						break;
					case 'between' :
						$query .= self::$_between;
						break;
						//Other Functions 
					case 'max' :
						$query .= self::$_max;
						break;
					case 'min' :
						$query .= self::$_min;
						break;
					case 'sum' :
						$query .= self::$_sum;
						break;
					case 'avg' :
						$query .= self::$_avg;
						break;
					case 'round' :
						$query .= self::$_round;
						break;
					case 'count' :
						$query .= self::$_count;
						break;
					case 'groupby' :
						$query .= self::$_groupBy;
						break;
					case 'having' :
						$query .= self::$_having;
						break;
				}
			}
			return $query;
		}
	}
}