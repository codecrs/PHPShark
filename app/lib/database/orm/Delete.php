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
	use core\lib\pages as pages;
	use \PDO;


	class Delete
	{
		private static $_table = '';
		private $_query = '';
		private $_data = array();

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

		public static function from(string $from)
		{
			\orm\Delete::$_table = $from;
			return new self;
		}

		public function where(array $data, string $DATABASE = null){
			$table = \orm\Delete::$_table;
			ksort($data);
			$fieldNames = implode('`,`', array_keys($data));
			$fieldValues = ':' . implode(',:', array_keys($data));

			if ($DATABASE == null)
				$DATABASE = utils\config::get('database_credits/database');

			$this->_query = "DELETE FROM `{$DATABASE}`.`{$table}` WHERE `{$table}`.`{$fieldNames}` = {$fieldValues} ";
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

		public function all(string $DATABASE = null){
			if ($DATABASE == null)
				$DATABASE = utils\config::get('database_credits/database');

			$this->_query = "DELETE * FROM `{$DATABASE}`.`{$table}` ";
			return $this;
		}

		public function execute(string $binder = null)
		{
			try {
				$statement = $this->_db->prepare($this->_query);
				foreach ($data as $key => $value) {
					if($binder !== null){
						$PDO_BINDER = $binder;
					}else{
						if(is_int($value)) $PDO_BINDER = PDO::PARAM_INT;
						else if(is_string($value)) $PDO_BINDER = PDO::PARAM_STR;
						else if(is_null($value)) $PDO_BINDER = PDO::PARAM_NULL;
						else if(is_bool($value)) $PDO_BINDER = PDO::PARAM_BOOL;
						else $PDO_BINDER = PDO::PARAM_INT;
					}
					$statement->bindParam(":$key", $value, $PDO_BINDER);
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