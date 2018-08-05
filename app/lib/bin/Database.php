<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\utilities as utils;
use core\lib\json as json;
use core\lib\pages as pages;

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


/***************************************************************/
/***************************************************************
 ***************************************************************/
function pdo()
{
	try {
		$obj = new PDO(
			DNS(),
			utils\Config::get('database_credits/login'),
			utils\Config::get('database_credits/password')
		);
		return $obj;
	} catch (PDOException $e) {
		writeDBLog($e);
	}
}

function db_condition(string $condition)
{
	$condition = trim($condition);
	str_replace('lt', '<', $condition);
	str_replace('LT', '<', $condition);
	str_replace('le', '<=', $condition);
	str_replace('Le', '<=', $condition);
	str_replace('gt', '>', $condition);
	str_replace('GT', '>', $condition);
	str_replace('ge', '>=', $condition);
	str_replace('GE', '>=', $condition);
	str_replace('eq', '=', $condition);
	str_replace('EQ', '=', $condition);
	str_replace('ne', '!=', $condition);
	str_replace('NE', '!=', $condition);
	str_replace('<>', '!=', $condition);
	str_replace('!like', 'NOT LIKE', $condition);
	str_replace('!LIKE', 'NOT LIKE', $condition);
	str_replace('like', 'LIKE', $condition);
	str_replace('in', 'IN', $condition);
	str_replace('not', 'NOT', $condition);
	str_replace('&&', 'AND', $condition);
	str_replace('||', 'OR', $condition);
	str_replace('-', '.', $condition);
	str_replace('is', 'IS', $condition);
	str_replace('null', 'NULL', $condition);
	str_replace('!null', 'NOT NULL', $condition);

	$condition = trim($condition);
	return $condition;
}

function  view_clear(string $view_name)
{
	$con = pdo();
	$query = "DROP VIEW [{$view_name}]";
	$con->exec($query);
	$con = null;
}

/***************************************************************
 ***************************************************************/
function dbcount(array $arr)
{
    if (is_array($arr)) {
        $num = count($arr);
        return $num;
    }
    else {
        return null;
    }
}

