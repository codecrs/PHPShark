<?php 
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

 
/***************************************************************
WARNING: To avoid misbehaviour, please do not touch the files in the APP library. 
 ***************************************************************
//WHERE CustomerName LIKE 'a%'	Finds any values that starts with "a"
//WHERE CustomerName LIKE '%a'	Finds any values that ends with "a"
//WHERE CustomerName LIKE '%or%'	Finds any values that have "or" in any position
//WHERE CustomerName LIKE '_r%'	Finds any values that have "r" in the second position
//WHERE CustomerName LIKE 'a_%_%'	Finds any values that starts with "a" and are at least 3 characters in length
//WHERE ContactName LIKE 'a%o'	Finds any values that starts with "a" and ends with "o"
 *******************CHANGE LOGS*********************************/
/* MAINTANANCE ACTIVITY LOG TO BE MAINTAINED 
   WITH DATES AND CHANGES MADE. */
/***************************************************************

/***************************************************************/
/***************************************************************
 ***************************************************************/
 
namespace orm {
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	use core\orm\mysql as mysql;
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\xml as xml;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \PDO;

	class Select extends Execute
	{
		private static $_table = '';
		private $_query = '';

		public function __construct($db = null, $dbname = 'mysql')
		{
			try { ($db !== null) ? $this->_db = $db : $this->_db = pdo();
				 $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch (\PDOException $e) {
				//Application Log

			}
		}

		public function __destruct()
		{
			$this->_db = null;
		}

		public static function from(string $table)
		{
			self::$_table = $table;
			return new self;
		}

		public function columns(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT {$columns} FROM {$table} ";
			return $this;
		}

		public function distinct(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT DISTINCT {$columns} FROM {$table} ";
			return $this;
		}

		public function union(string $union_table, mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query .= "UNION SELECT {$columns} FROM {$union_table} ";
			return $this;
		}

		public function union_all(string $union_table, mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query .= "UNION SELECT {$columns} FROM {$union_table} ";
			return $this;
		}

		public function into(mixed $columns, string $into, string $in = null)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			if ($externalDB !== null) {
				$this->_query = "SELECT {$columns} INTO {$into} IN {$in} FROM {$table} ";
			}
			else {
				$this->_query = "SELECT {$columns} INTO {$into} FROM {$table} ";
			}
			return $this;
		}

		public function min(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT MIN({$columns}) FROM {$table} ";
			return $this;
		}

		public function max(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT MAX({$columns}) FROM {$table} ";
			return $this;
		}

		public function count(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT COUNT({$columns}) FROM {$table} ";
			return $this;
		}

		public function avg(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT AVG({$columns}) FROM {$table} ";
			return $this;
		}

		public function sum(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns);
			}
			else {
				$columns = implode(' ',$columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT SUM({$columns}) FROM {$table} ";
			return $this;
		}

		public function top(int $top)
		{
			$this->_query .= "SELECT TOP {$top} * FROM {$table} ";
			return $this;
		}

		public function percent(int $percent)
		{
			$this->_query .= "SELECT TOP {$percent} percent * FROM {$table} ";
			return $this;
		}

		public function where(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "WHERE {$condition} ";
			return $this;
		}

		public function _and(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "AND {$condition} ";
			return $this;
		}

		public function _or(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "OR {$condition} ";
			return $this;
		}

		public function having(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "HAVING {$condition} ";
			return $this;
		}

		public function rownum(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "AND ROWNUM {$condition} ";
			return $this;
		}

		public function like(string $like)
		{
			$this->_query .= "LIKE {$like} ";
			return $this;
		}

		public function any(string $any)
		{
			$this->_query .= "ANY {$any} ";
			return $this;
		}

		public function all(string $all)
		{
			$this->_query .= "ALL {$all} ";
			return $this;
		}

		public function in(mixed $values)
		{
			if (!is_array($values)) {
				$values = trim($values);
			}
			else {
				$values = implode(',',$values);
			}
			$table = self::$_table;
			$this->_query .= "IN  {$values}";
			return $this;
		}

		public function exists(mixed $field, string $table, string $condition)
		{
			if (!is_array($field)) {
				$field = trim($field);
			}
			else {
				$field = implode(' ',$field);
			}

			$table = trim($table);
			$condition = db_condition($condition);
			$this->_query .= "WHERE EXISTS (SELECT {$field} FROM {$table} WHERE {$condition} ";
		}

		public function between(string $value1, string $value2)
		{
			$value1 = trim($value1);
			$value2 = trim($value2);
			$this->_query .= "BETWEEN {$value1} AND {$value2} ";
			return $this;
		}

		public function limit(int $limit)
		{
			$this->_query .= "LIMIT {$limit} ";
			return $this;
		}

		public function offset(int $offset)
		{
			$this->_query .= "OFFSET {$offset} ";
			return $this;
		}

		public function order(mixed $order, string $by = 'ASC')
		{
			if (!is_array($order)) {
				$order = trim($order);
			}
			else {
				$order = implode(',',$order);
			}
			$this->_query .= "ORDER BY {$order} {$by} ";
			return $this;
		}

		public function groupby(mixed $groupby)
		{
			if (!is_array($groupby)) {
				$order = trim($groupby);
			}
			else {
				$groupby = implode(',',$groupby);
			}
			$this->_query .= "GROUP BY {$groupby} ";
			return $this;
		}

		public function execute($fetchMode = PDO::FETCH_ASSOC)
		{
			try {	
				$statement = $this->_db->prepare($this->_query);
				$statement->execute();
				if ($statement->execute()) {
					$result = $statement->fetchAll($fetchMode);
					return $result;
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $this->_query);
			}
		}
	}
}