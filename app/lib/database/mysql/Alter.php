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

	use core\lib\files as files;
	use core\lib\errors as errs;
	use core\lib\imgs as imgs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \orm as orm;
	use \PDO;

	//Technical Details
	//Msg Class A000.

	class Alter implements Execute
	{
		private static $_table;
		private static $_gtable;
		private static $_field;
		private static $_opr;
		private static $_type;
		private static $_length;
		private static $_add;
		private static $_drop;
		private static $_after;
		private static $_decimal;
		private static $_query;
		private static $_null;
		private static $_queue = array();
		private static $_sym = array(',', '"');
		/***************************************************************
		 ***************************************************************/
		public static function setQuery(array $param)
		{
			if (!empty($param)){
				$i = 0;
				$queue = array();
				foreach ($param as $key => $value) {
					$key = strtolower($key);

					switch ($key) {
						case 'field' :
							self::_field($param['field']);
							break;
						case 'operation' :
							self::_opr($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'table' :
							self::_table($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'after' :
							self::_after($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'drop' :
							self::_after($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'add' :
							self::_after($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'type' :
							self::_type($value);
							$queue[$i] = $key;
							$i = $i + 1;
							break;
						case 'key' :
							self::_key($param['key']);
							break;
						case 'null' :
							self::_null($value);
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

			Alter::$_queue = $queue;
			$q = self::buildQuery();
			return $q;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _table(string $table)
		{
			if (orm\Query::is_table($table) == false) {
				set_error( $table." :is not a existing table in the database. ".  __CLASS__ . "/". __FUNCTION__ );
			}

			if ($table !== NULL) {
				$table = trim($table);
				self::$_table = "{$table} ";
				self::$_gtable = trim($table);
			}
			else {
				set_error( "Incorrect table definition in: " . __CLASS__ . "/". __FUNCTION__ );
			}
		}
		/***************************************************************
		 ***************************************************************/
		private static function _opr(string $opr)
		{
			$oprBuild = '';
			$field = self::$_field;
			switch (strtolower(trim($opr))) {
				case 'add' :
					if (isset(self::$_key)) {
						$key = strtolower(self::$_key);
						switch ($key) {
							case 'primary' :
								$oprBuild = "ADD PRIMARY KEY ({$field}) ";
								break;
							case 'unique' :
								$oprBuild = "ADD UNIQUE KEY ({$field}) ";
								break;
							case 'index' :
								$oprBuild = "ADD INDEX KEY ({$field}) ";
								break;
						}
					}
					else {
						$oprBuild = "ADD {$field} ";
					}
					break;
				case 'drop' :
					$oprBuild = "DROP {$field} ";
					break;
				case 'change' :
					$oprBuild = "CHANGE {$field} ";
					break;
				default :
			}
			Alter::$_opr = $oprBuild;
		}
		/***************************************************************
		 ***************************************************************/
		public static function _type(string $type)
		{
			$type = new orm\Type($type);
			self::$_type = $type->var;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _field(string $field)
		{
			self::$_field = " `{$field}` ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _after(string $after)
		{
			self::$_after = "AFTER `{$after}` ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _key(string $key)
		{
			self::$_key = $key;
		}
		/***************************************************************
		 ***************************************************************/
		private static function _add(string $add)
		{
			$iadd = explode('.', $add);
			self::$_add = "{$iadd[0]} {$iadd[1]} ";

		}
		/***************************************************************
		 ***************************************************************/
		private static function _drop(string $drop)
		{
			self::$_drop = "DROP {$drop} ";
		}
		/***************************************************************
		 ***************************************************************/
		private static function _null(bool $null)
		{
			if ($null == false) {
				self::$_null = 'NOT NULL ';
			}
			else {
				self::$_null = 'NULL ';
			}
		}
		/***************************************************************
		 ***************************************************************/
		public static function buildQuery()
		{
			$query = 'ALTER TABLE ';
			$queue = array();
			$queue = self::$_queue;
			foreach ($queue as $setOrder) {
				$order = strtolower(trim($setOrder));
				switch ($setOrder) {
					case 'table' :
						$query .= self::$_table;
						break;
					case 'field' :
						$query .= self::$_field;
						break;
					case 'operation' :
						$query .= self::$_opr;
						break;
					case 'type' :
						$query .= self::$_type;
						break;
					case 'null' :
						$query .= self::$_null;
						break;
					case 'after' :
						$query .= self::$_after;
						break;
					case 'add' :
						$query .= self::$_add;
						break;
					case 'drop' :
						$query .= self::$_drop;
						break;
				}
			}
			return $query;
		}
	}
}