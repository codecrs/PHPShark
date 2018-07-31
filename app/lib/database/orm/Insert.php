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


	class Insert extends Execute
	{

		private static $_table = '';
		private $_query = '';
		private $_data = array();

		public function __construct(string $db, string $dbname = 'mysql')
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

		public static function into(string $into)
		{
			\orm\Insert::$_table = $into;
			return new self;
		}

		public function values(array $data, string $DATABASE = null)
		{
			$db = new Database();
			$table = \orm\Insert::$_table;
			$db->checkCreateFields($table);
			$data['created'] = utils\Timestamp::setTime();
			$data['modified'] = utils\Timestamp::setTime();
			ksort($data);
			$fieldDetails = NULL;
			if ($DATABASE == null)
				$DATABASE = utils\config::get('database_credits/database');

			$fieldNames = implode('`,`', array_keys($data));
			$fieldValues = ':' . implode(',:', array_keys($data));

			$this->_data = $data;
			$this->_query = "INSERT INTO {$DATABASE}.`{$table}` (`{$fieldNames}`)
												  VALUES ({$fieldValues}) ";
			$db = null;
			return $this;
		}

		public function from(string $table2)
		{
			$db = new Database();
			$table = \orm\Insert::$_table;
			$db->checkCreateFields($table);
			$data['created'] = utils\Timestamp::setTime();
			$data['modified'] = utils\Timestamp::setTime();
			ksort($data);
			$fieldDetails = NULL;
			if ($DATABASE == null)
				$DATABASE = utils\config::get('database_credits/database');

			$fieldNames = implode('`,`', array_keys($data));
			$fieldValues = ':' . implode(',:', array_keys($data));

			$this->_data = $data;
			$this->_query .= "INSERT INTO {$DATABASE}.`{$table}` (`{$fieldNames}`)
							 SELECT {$fieldNames} FROM [{$table2}] ";
			$db = null;
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
			$table = \orm\Insert::$_table;
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
			$table = \orm\Insert::$_table;
			$this->_query .= "UNION SELECT {$columns} FROM {$union_table} ";
			return $this;
		}

		public function where(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= " WHERE {$condition}";
			return $this;
		}

		public function _and(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= " AND {$condition} ";
			return $this;
		}

		public function _or(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= " OR {$condition} ";
			return $this;
		}

		public function having(string $condition)
		{
			$condition = db_condition($condition);
			$this->_query .= " HAVING {$condition} ";
			return $this;
		}

		public function like(string $like)
		{
			$this->_query .= " LIKE {$like} ";
			return $this;
		}

		public function any(string $any)
		{
			$this->_query .= " ANY {$any} ";
			return $this;
		}

		public function all(string $all)
		{
			$this->_query .= " ALL {$all} ";
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
			$table = \orm\Select::$_table;
			$this->_query .= " IN  {$values}";
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
			$this->_query .= " WHERE EXISTS (SELECT {$field} FROM {$table} WHERE {$condition} ";
		}

		public function between(string $value1, string $value2)
		{
			$value1 = trim($value1);
			$value2 = trim($value2);
			$this->_query .= " BETWEEN {$value1} AND {$value2} ";
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
			$this->_query .= " ORDER BY {$order} {$by} ";
			return $this;
		}

		public function groupby(mixed $groupby)
		{
			if (!is_array($groupby)) {
				$order = trim($groupby);
			}
			else {
				$groupby = implode(' ',$groupby);
			}
			$this->_query .= " GROUP BY {$groupby} ";
			return $this;
		}

		public function execute()
		{
			try {
				$statement = $this->_db->prepare($this->_query);
				foreach ($this->_data as $key => $value) {
					$statement->bindValue(":{$key}", $value);
				}
				$statement->execute();
				if ($statement->execute()) {
					//Audit Log
					return $statement->lastInsertId();
				}
				else {
					//Application Log
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $query);
			}
		}
	}

}