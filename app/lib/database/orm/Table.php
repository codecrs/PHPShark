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
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \PDO;


	class Table extends Execute
	{
		private $_db;
		private static $_action;

		private $_query;
		private static $_check_new_line = '';

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

		public static function create()
		{
			self::$_check_new_line = '';
			self::$_action = 'CREATE TABLE IF NOT EXISTS ';
			return new self;
		}

		public static function alter()
		{
			self::$_check_new_line = '';
			self::$_action = 'ALTER TABLE ';
			return new self;
		}

		public static function truncate()
		{
			self::$_check_new_line = '';
			self::$_action = 'TRUNCATE TABLE ';
			return new self;
		}

		public function table(string $name)
		{
			$acion = self::$_action;
			$this->_query = "{$acion} {$name} ";
			return $this;
		}

		public function field(string $name)
		{
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}
			else {
				self::$_check_new_line = 'X';
				$this->_query .= ' (';
			}
			$this->_query .= $name;
			return $this;
		}

		public function after(string $name)
		{
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}
			$this->_query .= "AFTER {$name} ";
			return $this;
		}

		public function add(string $name)
		{
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}
			else {
				self::$_check_new_line = 'X';
			}
			$this->_query .= "ADD {$name} ";
			return $this;
		}

		public function drop(string $name)
		{
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}
			else {
				self::$_check_new_line = 'X';
			}
			$this->_query .= "DROP COLUMN {$name} ";
			return $this;
		}

		public function modify(string $name)
		{
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}
			else {
				self::$_check_new_line = 'X';
			}
			$this->_query .= "MODIFY COLUMN {$name} ";
			return $this;
		}

		public function type(string $type)
		{
			$type = new Type($type);
			$this->_query .= $type->var . " ";
			return $this;
		}

		public function auto_increment()
		{
			$this->_query .= 'AUTO_INCREMENT';
			return $this;
		}

		public function constraint(string $constraint)
		{
			$this->_query .= "{$constraint} ";
			return $this;
		}

		public function default(string $default)
		{
			$this->_query .= "'{$default}' ";
			return $this;
		}

		public function primary(string $key)
		{
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}
			$this->_query .= "PRIMARY KEY ({$key})";
			return $this;
		}

		public function foreign(string $key, string $reference)
		{
			$iref = array();
			$set_ref = '';
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}

			$iref = explode('.',$reference);
			if(count($iref) > 1)
			$set_ref = "{$iref[0]}($iref[1])";

			$this->_query .= "FOREIGN KEY ({$key}) REFERENCES {$set_ref} ";
			return $this;
		}

		public function unique(string $field)
		{
			if (self::$_check_new_line == 'X') {
				$this->_query .= ', ';
			}
			$this->_query .= "UNIQUE ({$field}) ";
			return $this;
		}

		public function check(string $check)
		{
			$this->_query .= ', ';
			$check = db_condition($check);
			$this->_query .= "CHECK ({$check}) ";
			return $this;
		}

		public function execute()
		{
			if (self::$_action == 'CREATE TABLE IF NOT EXISTS ')
				$this->_query .= ') ';
			try {
				$this->_query;
				$query = trim($this->_query);
				if ($this->_db->exec($query)) {
					//Audit Log
					
				}
				else {
					//Application Log
					return false;
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $query);
			}

			if (self::$_action == 'CREATE TABLE IF NOT EXISTS ') {
				//Audit Log

			}

			if (self::$_action == 'ALTER TABLE ') {
				//Audit Log

			}
		}
	}

}