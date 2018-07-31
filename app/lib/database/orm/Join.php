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
/*********************************************************************/
/* JOIN DOCUMENTATION
* *******************************************************************
// \orm\Join::from($this->_users_roles, 't1')
// 	->select('t1.role_id, t2.role_name')
// 	->join($this->_roles, 't2')->match('t1.role_id = t2.role_id')
// 	->where("t1.{$userKey} = {$this->user_id}")
// 	->execute(); 
* *******************************************************************/
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

	class Join extends Execute
	{
		private static $_table = '';
		private static $_as = '';
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

		public static function from(string $from, string $as = null)
		{
			\orm\Join::$_table = $from;
			if($as !== null){
				\orm\Join::$_as = $as;
			}
			return new self;
		}

		public function view(string $viewname, mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns, ' ');
				$columns = str_replace('-', '.', $columns);
			}
			else {
				$columns = implode(',',$columns);
				$columns = str_replace('-', '.', $columns);
			}
			$table = \orm\Join::$_table;
			$this->_query = "CREATE VIEW [{$viewname}] AS 
							 SELECT {$columns} FROM {$table} ";
			
			if(isset(\orm\Join::$_as)){
				$this->_query .= \orm\Join::$_as;
			}
			return $this;
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
			$table = \orm\Join::$_table;
			$this->_query = "SELECT {$columns} FROM {$table} ";

			if(isset(\orm\Join::$_as)){
				$this->_query .= \orm\Join::$_as . " ";
			}
			return $this;
		}

		public function delete(mixed $columns)
		{
			if (!is_array($columns)) {
				$columns = trim($columns, ' ');
				$columns = str_replace('-', '.', $columns);
			}
			else {
				$columns = implode(',',$columns);
				$columns = str_replace('-', '.', $columns);
			}
			$table = \orm\Join::$_table;
			$this->_query = "DELETE {$columns} FROM {$table} ";

			if(isset(\orm\Join::$_as)){
				$this->_query .= \orm\Join::$_as;
			}
			return $this;
		}

		public function join(string $table, string $as = null)
		{
			if($as !== null){
				$this->_query .= "JOIN {$table} AS {$as} ";
			}else{
				$this->_query .= "JOIN {$table} ";
			}
			return $this;
		}

		public function left(string $table)
		{
			$this->_query .= "LEFT JOIN {$table} ";
			return $this;
		}

		public function right(string $table)
		{
			$this->_query .= "RIGHT JOIN {$table} ";
			return $this;
		}

		public function inner(string $table)
		{
			$this->_query .= "INNER JOIN {$table} ";
			return $this;
		}

		public function outer(string $table)
		{
			$this->_query .= "FULL OUTER JOIN {$table} ";
			return $this;
		}

		public function match(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= "ON {$condition} ";
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

		public function execute($fetchMode = PDO::FETCH_ASSOC, string $fetch = "fetchAll")
		{
			try {
				$statement = $this->_db->prepare($this->_query);
				$statement->execute();
				if ($statement->execute()) {
					$result = $statement->{$fetch}($fetchMode);
					return $result;
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $this->_query);
			}
		}
	}
}