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

	//Technical Details
	//Msg Class J000

	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \orm as orm;
	use \PDO;

	class Join
	{
		public static function setQuery(array $declarion, array $joinSet, $querySet = '*', string $joinType = 'left', $extra)
		{
			$query = 'SELECT ';
			if (is_array($querySet)) {
				foreach ($querySet as $fields) {
					$query .= str_replace('-', '.', $fields) . ', ';
				}
			}
			else {
				$query .= " {$querySet} ";
			}

			$query = rtrim($query, ", ");
			$rev_arr = array_reverse($joinSet);
			$pop = array_pop($rev_arr);
			$get_from = explode('-', $pop);
			$from = $get_from[0];
			$query .= ' FROM ' . array_search($get_from[0], $declarion) . ' ' . $get_from[0];
			$removal_key = array_search($get_from[0], $declarion);
			$new_set = array();
			$new_set = $declarion;
			unset($new_set[$removal_key]);
			foreach ($new_set as $key => $sudoName) {
				$query .= ' ' . $joinType . ' JOIN ' . array_search($sudoName, $declarion) . ' ' . $sudoName . ' ';
				if (str_replace('&', 'AND', $joinSet[$sudoName])) {
					$on = ' ON ' . str_replace('&', 'AND', $joinSet[$sudoName]);
					$query .= str_replace('-', '.', $on);
				}
				else if (str_replace('|', 'OR', $joinSet[$sudoName])) {
					$query .= ' ON ' . str_replace('|', 'OR', $joinSet[$sudoName]);
					$query .= str_replace('-', '.', $on);
				}

			}
			if ($extra !== null) {
				$query .= $extra;
			}
			return $query;
		}
	}
}
