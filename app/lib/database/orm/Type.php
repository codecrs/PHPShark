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

	class Type
	{

		public $var;
		private $_type;

		public function __construct(string $type)
		{
			$typeName = explode('(', $type);
			$name = $typeName[0];
			$name = strtolower($name);

			$size = get_string_between($type, '(', ')');
			$f = explode(',', $size);
			$size = $f[0];
			if (isset($f[1])) {
				$decimal = $f[1];
			}

			switch ($name) {
				case 'i' :
					$this->_datatype('int', $size);
					break;
				case 'c' :
					$this->_datatype('char', $size);
					break;
				case 'vc' :
					$this->_datatype('varchar', $size);
					break;
				case 'd' :
					$this->_datatype('date');
					break;
				default :
					if (isset($decimal)) {
						$this->_datatype($name, $size, $decimal);
					}
					else {
						$this->_datatype($name, $size);
					}
			}
			$this->var = $this->_type;
			return $this->var;
		}

		private function _datatype(string $type, $size = null, int $decimal = null)
		{
			switch (strtoupper(trim($type))) {
				case 'CHAR' :	//(size)
					if ($size !== null) {
						$dataType = " CHAR ({$size}) ";
					}
					else {
						set_error("Size Parameter is Mandatory for CHAR datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'VARCHAR' :	//(size)
					if ($size !== null) {
						$dataType = " VARCHAR ({$size})";
					}
					else {
						set_error("Size Parameter is Mandatory for VARCHAR datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'TINYTEXT' :
					$dataType = ' TINYTEXT ';
					break;
				case 'TEXT' :
					$dataType = ' TEXT ';
					break;
				case 'BLOB' :
					$dataType = ' BLOB ';
					break;
				case 'MEDIUMTEXT' :
					$dataType = ' MEDIUMTEXT ';
					break;
				case 'MEDIUMBLOB' :
					$dataType = ' MEDIUMBLOB ';
					break;
				case 'LONGTEXT' :
					$dataType = ' LONGTEXT ';
					break;
				case 'LONGBLOB' :
					$dataType = ' LONGBLOB ';
					break;
				case 'TINYINT' :	//(size)
					if ($size !== null) {
						$dataType = " TINYINT ({$size}) ";
					}
					else {
						set_error("Size Parameter is Mandatory for TINYINT datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'SMALLINT' :	//(size)
					if ($size !== null) {
						$dataType = " SMALLINT ({$size}) ";
					}
					else {
						set_error("Size Parameter is Mandatory for SMALLINT datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'MEDIUMINT' :	//(size)
					if ($size !== null) {
						$dataType = " MEDIUMINT ({$size}) ";
					}
					else {
						set_error("Size Parameter is Mandatory for MEDIUM datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'INT' :  //(size)
					if ($size !== null) {
						$dataType = " INT ({$size}) ";
					}
					else {
						set_error("Size Parameter is Mandatory for INT datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'BIGINT' :	//(size)
					if ($size !== null) {
						$dataType = " BIGINT ({$size}) ";
					}
					else {
						set_error("Size Parameter is Mandatory for BIGINT datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'FLOAT' :	//(size,d)
					if ($size !== null && $decimal !== null) {
						$dataType = " FLOAT ({$size},{$decimal})";
					}
					else {
						set_error("Size & Decimal Parameter is Mandatory for FLOAT datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'DOUBLE' :	//(size,d)
					if ($size !== null && $decimal !== null) {
						$dataType = " DOUBLE ({$size},{$decimal}) ";
					}
					else {
						set_error("Size & Decimal Parameter is Mandatory for DOUBLE datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'DECIMAL' :	//(size,d)
					if ($size !== null && $decimal !== null) {
						$dataType = "DECIMAL ({$size},{$decimal})";
					}
					else {
						set_error("Size & Decimal Parameter is Mandatory for DECIMAL datatype."
						. __CLASS__ . "/". __FUNCTION__ );
					}
					break;
				case 'DATE' :
					$dataType = ' DATE ';
					break;
				case 'DATETIME' :
					$dataType = ' DATETIME ';
					break;
				case 'TIMESTAMP' :
					$dataType = ' TIMESTAMP ';
					break;
				case 'TIME' :
					$dataType = ' TIME ';
					break;
				case 'YEAR' :
					$dataType = ' YEAR ';
					break;
				case 'REAL' :
					$dataType = ' REAL ';
					break;
				default :
				set_error("No data type found as ". $dataType . " : "
				. __CLASS__ . "/". __FUNCTION__ );
			}
			return $this->_type .= $dataType;
		}
	}

}