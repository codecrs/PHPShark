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


	class Drop extends Execute
	{
		public $_condition;
		private $_drop;
		private $_name;

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

		public static function table(string $name)
		{
			$name = trim($name);
			$query = "DROP TABLE {$name}";
			$self::execute($query);
		}

		public static function db(string $name)
		{
			$name = trim($name);
			$query = "DROP DATABASE {$name}";
			$self::execute($query);
		}

		public function execute(string $query)
		{
			try {
				$query = trim($this->_query);
				if ($this->_db->exec($query)) {
					//Audit Log

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