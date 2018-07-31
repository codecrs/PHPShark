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
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \PDO;


	class Update extends Execute
	{

		private static $_table = '';
		private $_query = '';
		private $_data = array();

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

		public static function into(string $into)
		{
			self::$_table = $into;
			return new self;
		}

		public function set(array $data, string  $DATABASE = null)
		{
			$db = new Database();
			$table = self::$_table;
			$db->checkCreateFields($table);
			$data['modified'] = utils\Timestamp::setTime();
			ksort($data);
			$fieldDetails = NULL;
			if ($DATABASE == null)
				$DATABASE = utils\Config::get('database_credits/database');

			foreach ($data as $key => $value) {
				$fieldDetails .= "`$key`='$value',";
			}

			$table = self::$_table;
			$this->_data = $data;
			$this->_query = "UPDATE `{$DATABASE}`.`{$table}` SET {$fieldDetails} ";
			$db = null;
			return this;
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
					return true;
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