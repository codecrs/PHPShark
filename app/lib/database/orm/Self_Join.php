<?php 
namespace orm {
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

 
	use core\orm\mysql as mysql;
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\xml as xml;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\msg as msgs;
	use core\lib\pages as pages;
	use \PDO;


	class Self_Join extends Execute{
		private static $_table = '';
		private $_query = '';

		public function __construct(string $db = null, string $dbname = 'mysql')
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

		public function execute($fetchMode = PDO::FETCH_OBJ, string $fetch = "fetchAll")
		{
			try {
				$statement = $this->_db->prepare($this->_query);
				$statement->execute();
				if ($statement->execute()) {
					$result = $statement->{$fetch}($fetchMode);
					return $result;
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $query);
			}
		}

		public function select(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns, ' ');
				$columns = str_replace('-', '.', $columns);
			}
			else {
				$columns = implode(',',$columns);
				$columns = str_replace('-', '.', $columns);
			}
			$table = self::$_table;
			$this->_query = "SELECT {$columns} FROM {$table} ";
			return $this;
		}

		public static function from(array $from)
		{;
			$from = implode(',',$from);
			self::$_table = str_replace("->"," ",$from);
			return new self;
		}

		public function where(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "WHERE {$condition} ";
			return $this;
		}

		public function and(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "AND {$condition} ";
			return $this;
		}

		public function or(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "OR {$condition} ";
			return $this;
		}

		public function order(mixed $order,string $by = 'ASC')
		{
			if (!is_array($order)) {
				$order = trim($order);
			}
			else {
				$order = implode(',',$order);
				$order = str_replace('-', '.', $order);
			}
			$this->_query .= "ORDER BY {$order} {$by} ";
			return $this;
		}

		public function groupby(string $groupby)
		{
			if (!is_array($groupby)) {
				$order = trim($groupby);
			}
			else {
				$groupby = implode(',',$groupby);
				$groupby = str_replace('-', '.', $groupby);
			}
			$this->_query .= "GROUP BY {$groupby} ";
			return $this;
		}

	}
}
