<?php
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

//Clean Array
use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\utilities as utils;
use core\lib\json as json;
use core\lib\pages as pages;

function array_clean(array $arr)
{
	if (!empty($arr)) {
			$arr = array_filter($arr);
			$arr = array_values($arr);
			return $arr;
	}
	else {
		setError( "Array Object cannot be empty!" );	
	}
}
/***************************************************************
 ***************************************************************/
function array_sort(array $array, string $opt = null, string $by = null)
{
	$ret = array();
	if (isAssoc($array) == true) {
		switch (strtolower($opt)) {
			case 'ascending' :
				if ($by == 'value') {
					$ret = asort($array);
				}
				else if ($by == 'key') {
					$ret = ksort($array);
				}
				else {
					$ret = ksort($array);
				}
				break;
			case 'descending' :
				if ($by == 'value') {
					$ret = arsort($array);
				}
				else if ($by == 'key') {
					$ret = krsort($array);
				}
				else {
					$ret = krsort($array);
				}
				break;
			default :
				$ret = ksort($array);
		}
	}
	else {
		switch (strtolower($opt)) {
			case 'ascending' :
				$ret = sort($array);
				break;
			case 'descending' :
				$ret = rsort($array);
				break;
			default :
				$ret = sort($array);
		}
	}
}
/***************************************************************
 ***************************************************************/
function isAssoc(array $arr)
{
	if (array() === $arr) return false;
	return array_keys($arr) !== range(0, count($arr) - 1);
}
/***************************************************************
 ***************************************************************/
function arfind(array $array, string $where, string $value)
{
	if (isAssoc($array)) {
		$key = array_search($where, array_column($array, $value));
		return $key;
	}
	else {
		return null;
	}
}
/***************************************************************
 ***************************************************************/
function arintersect(array $array1, array $array2)
{
	$r = array_intersect($array1, $array2);
	return $r;
}
/***************************************************************
 ***************************************************************/
function ardifference(array $array1, array $array2)
{
	$r = array_diff($array1, $array2);
	return $r;
}
/***************************************************************
 ***************************************************************/
function arunique(array $array1, array $array2)
{
	$r = array_unique(array_merge($array1, $array2));
	return $r;
}
/***************************************************************
 ***************************************************************/
function array_concatanate(array $arr, string $using)
{
	$str = join($using, $arr);
	return $r;
}
/***************************************************************
 ***************************************************************/
 function m_array(array $myArray, string $find) {
    $res = array();
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($myArray), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $k => $v) {
        if($k === $find) {
            $res[] = $v;
        }
    }
    return $res;
}

function array_has(Array $array, String $index){
	return array_key_exists($index,$array);
}	
