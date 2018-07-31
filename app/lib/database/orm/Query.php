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

 
	//Techincal Information 
	//Msg Class: Q000
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

	class Query
	{
		private static $_sym = array(
			',', '"', '[', ']', '{', '}', '?', '/', '`', ';', ':', '-', '+',
			'_', '|', '\\', '*', '@', '!', '#', '$', '%', '^', '&', '(', ')', '<', '>', '.'
		);

		/***************************************************************
		 ***************************************************************/
		public static function is_field(string $table, string $field, string $dbname = null)
		{
			if ($dbname == null)
				$dbname = utils\Config::get('database_credits/database');

			if (!in_array($field, Query::$_sym))
				$query = "SELECT * FROM 
						 information_schema.COLUMNS 
						 WHERE TABLE_NAME = '{$table}' AND 
						 TABLE_SCHEMA = '{$dbname}' AND COLUMN_NAME = '{$field}'";
			if ($state = self::execute_check($query)) {
				return $state;
			}
		}
		/***************************************************************
		 ***************************************************************/
		public static function is_table(string $table, string $db = null)
		{
			if ($db == null)
			$db = utils\Config::get('database_credits/database');
			$query = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$table}'
					 AND TABLE_SCHEMA = '{$db}'";

			if ($state = self::execute_check($query)) {
				return $state;
			}
			else {
				//Application Log;
			}
		}
		/***************************************************************
		 ***************************************************************/
		public static function is_db(string $db = '')
		{
			if ($db == null)
				$db = utils\Config::get('database_credits/database');
			if (!in_array($db, Query::$_sym))
				$query = "SELECT SCHEMA_NAME 
						  FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$db}'";
			if ($state = self::execute_check($query)) {
				return $state;
			}
			else {
				//Application Log;

			}
		}

		/***************************************************************
		 ***************************************************************/
		public static function keyFields(string $table)
		{
			if (!in_array($db, Query::$_sym))
				$query = "SHOW KEYS FROM '{$table}'";
			if ($state = self::execute_check($query)) {
				return $state;
			}
			else {
				//Application Log;

			}
		}

		/***************************************************************
		 ***************************************************************/
		public static function getAllDBTables(string $db, $fetchMode = PDO::FETCH_ASSOC){
			if (!in_array($db, Query::$_sym)) {
				$query = "SHOW TABLES IN `{$db}`";
				try{
					$db = pdo();
					$stm = $db->prepare($query);
					if($stm->execute()){
						return $stm->fetchAll($fetchMode);
					}
				} catch (PDOException $x){

				}
			} else {
				//Application Log;
			}
		}
		
		/***************************************************************
		 ***************************************************************/
		public static function execute_check(string $query, $fetchMode = PDO::FETCH_ASSOC)
		{
			try {
				$con = pdo();
				$statement = $con->prepare($query);
				$statement->execute();
				$result = $statement->fetchAll($fetchMode);
				$con = null;
				if (count($result) > 0) {
					return true;
				}
				else {
					return false;
				}
			} catch (PDOException $e) {
				writeDBLog($e, $query);
				return false;
			}
		}
		/***************************************************************
		 ***************************************************************/
		public static function checkKeywords(string $query, array $array)
		{
			switch ($query) {
				case 'select' :
					if (array_key_exists('columns', $array) == false) {
						set_error("\SELECT requires obligatory key COLUMNS ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('from', $array) == false) {
						set_error("\SELECT requires obligatory key FROM ". __CLASS__ . "/". __FUNCTION__ );
					}
					break;

				case 'update' :
					if (array_key_exists('set', $array) == false) {
						set_error("\UPDATE requires obligatory key SET ". __CLASS__ . "/". __FUNCTION__ );
					}
					break;

				case 'delete' :
					if (array_key_exists('from', $array) == false) {
						set_error("\DELETE requires obligatory key FROM ". __CLASS__ . "/". __FUNCTION__ );
					}
					break;

				case 'insert' :
					if (array_key_exists('into', $array) == false) {
						set_error("\INSERT requires obligatory key INTO ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('values', $array) == false) {
						set_error("\INSERT requires obligatory key VALUES ". __CLASS__ . "/". __FUNCTION__ );
					}
					break;

				case 'alter' :
					if (array_key_exists('table', $array) == false) {
						set_error("\ALTER requires obligatory key TABLE ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('field', $array) == false) {
						set_error("\ALTER requires obligatory key FIELD ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('operation', $array) == false) {
						set_error("\ALTER requires obligatory key OPERATION ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('type', $array) == false) {
						set_error("\ALTER requires obligatory key TYPE ". __CLASS__ . "/". __FUNCTION__ );
					}
					break;

				case 'join' :
					if (array_key_exists('from', $array) == false) {
						set_error("\JOIN requires obligatory key FROM ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('to', $array) == false) {
						set_error("\JOIN requires obligatory key TO ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('views', $array) == false) {
						set_error("\JOIN requires obligatory key VIEWS ". __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_key_exists('match', $array) == false) {
						set_error("\JOIN requires obligatory key MATCH ". __CLASS__ . "/". __FUNCTION__ );
					}
					break;
			}
		}
		/***************************************************************
		 ***************************************************************/
		public static function checkSyntax(string $query, array $array)
		{
			//$package = 'database/mysql';
			switch ($query) {
				case 'select' :
					if (array_search('columns', array_keys($array)) != '0' || array_search('top', array_keys($array)) != '0') {
						set_error("\SELECT SYNTAX ERROR: COLUMNS not found in proper position in function call.
						True position is 1. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('from', array_keys($array)) != '1') {
						set_error("\SELECT SYNTAX ERROR: FROM not found in proper position in function call.
						True position is 2. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (in_array('where', $array) == true) {
						if (array_search('where', array_keys($array)) != '2') {
							set_error("\SELECT SYNTAX ERROR: WHERE not found in proper position in function call.
							True position is 3. "
							. __CLASS__ . "/". __FUNCTION__ );
						}
					}
					break;

				case 'update' :
					if (array_search('into', array_keys($array)) != '0') {
						set_error("\UPDATE SYNTAX ERROR: INTO not found in proper position in function call.
						True position is 1. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('set', array_keys($array)) != '1') {
						set_error("\UPDATE SYNTAX ERROR: SET not found in proper position in function call.
						True position is 2. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (in_array('where', $array) == true) {
						if (array_search('where', array_keys($array)) != '2') {
							set_error("\UPDATE SYNTAX ERROR: WHERE not found in proper position in function call.
							True position is 3. "
							. __CLASS__ . "/". __FUNCTION__ );
						}
					}
					break;

				case 'delete' :
					if (array_search('from', array_keys($array)) != '0') {
						set_error("\DELETE SYNTAX ERROR: FROM not found in proper position in function call.
						True position is 1. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (in_array('where', $array) == true) {
						if (array_search('where', array_keys($array)) != '2') {
							set_error("\SELECT SYNTAX ERROR: WHERE not found in proper position in function call.
							True position is 2. "
							. __CLASS__ . "/". __FUNCTION__ );
						}
					}
					break;

				case 'insert' :
					if (array_search('into', array_keys($array)) != '0') {
						set_error("\INSERT SYNTAX ERROR: INTO not found in proper position in function call.
						True position is 1. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('set', array_keys($array)) != '0') {
						set_error("\INSERT SYNTAX ERROR: SET not found in proper position in function call.
						True position is 1. "
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;

				case 'alter' :
					if (array_search('table', array_keys($array)) != '0') {
						set_error("\ALTER SYNTAX ERROR: TABLE not found in proper position in function call.
						True position is 1. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('field', array_keys($array)) != '1') {
						set_error("\ALTER SYNTAX ERROR: FIELD not found in proper position in function call.
						True position is 2. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('operation', array_keys($array)) != '2') {
						set_error("\ALTER SYNTAX ERROR: OPERATION not found in proper position in function call.
						True position is 3. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('type', array_keys($array)) != '3') {
						set_error("\ALTER SYNTAX ERROR: TYPE not found in proper position in function call.
						True position is 4. "
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;

				case 'join' :
					if (array_search('from', array_keys($array)) != '0') {
						set_error("\JOIN SYNTAX ERROR: FROM not found in proper position in function call.
						True position is 1. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('to', array_keys($array)) != '1') {
						set_error("\JOIN SYNTAX ERROR: TO not found in proper position in function call.
						True position is 2. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('match', array_keys($array)) != '2') {
						set_error("\JOIN SYNTAX ERROR: MATCH not found in proper position in function call.
						True position is 3. "
						. __CLASS__ . "/". __FUNCTION__ );
					}

					if (array_search('views', array_keys($array)) != '3') {
						set_error("\JOIN SYNTAX ERROR: VIEWS not found in proper position in function call.
						True position is 4. "
						. __CLASS__ . "/". __FUNCTION__ );
					}


					break;
			}
		}
	}
}