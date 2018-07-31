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
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\msg as msgs;
	use core\lib\pages as pages;
	use \orm as orm;
	use \PDO;
 

	class Expr{
		private static $_sym = array(',', '"');
		public static function logic(string $table, mixed $clause, string $buildType){
			if (!empty($clause)) {
				$buildType = strtoupper($buildType);
				$build = "{$buildType} ";
				$clause = explode(' ', $clause, 3);
				$clause  = array_clean($clause);
				foreach ($clause as $element) {
					$element = strtolower(trim($element));
					switch ($element) {
						case '=' :
							$build .= ' = ';
							break;
						case '<' :
							$build .= ' < ';
							break;
						case '>' :
							$build .= ' > ';
							break;
						case '<=' :
							$build .= ' <= ';
							break;
						case '>=' :
							$build .= ' >= ';
							break;
						case 'lt' :
							$build .= ' < ';
							break;
						case 'gt' :
							$build .= ' > ';
							break;
						case 'le' :
							$build .= ' >= ';
							break;
						case 'ge' :
							$build .= ' <= ';
							break;
						case 'eq' :
							$build .= ' = ';
							break;
						case 'ne' :
							$build .= ' != ';
							break;
						case '<>' :
							$build .= ' != ';
							break;
						case '!=' :
							$build .= ' != ';
							break;
						case 'like' :
							$build .= ' LIKE ';
							break;
						case '!like' :
							$build .= ' NOT LIKE ';
							break;
						case 'in' :
							$build .= ' IN ';
							break;
						case 'not' :
							$build .= ' NOT ';
							break;
						default :
							if (orm\Query::is_field(trim($table), $element)) {
								$build .= "`{$element}`";
							}
							else if (in_array($element, self::$_sym)) {
								$build .= $element;
							}
							else {
								$build .= "'{$element}'";
							}
					}
				}
				return $build;
			}
		}

	}
}