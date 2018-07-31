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

/***************************************************************
 ***************************************************************/
function condense($str)
{
	if (is_string($str)) {
		return $str = trim($str);
	}
}
/***************************************************************
 ***************************************************************/
function concatanate($str = '')
{
	if (is_string($str)) {
		$str .= $str;
		return str;
	}
}
/***************************************************************
 ***************************************************************/
function timestamp($dateformat = "Y-m-d", $timeformat = "H:i:sa", $sparator = ":")
{
	$t = time();
	$ts = date($dateformat, $t) . $sparator . date($timeformat);
	return $ts;
}

/***************************************************************
 ***************************************************************/
function echo_json($data)
{
	header('Content-Type: application/json');
	echo json_encode($data);
}
/***************************************************************
 ***************************************************************/
function json_api($data,$extra = "")
{
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json {$extra}");
	echo json_encode($data);
}
/***************************************************************
 ***************************************************************/
function getSessionUserID($userIDKey)
{
	if (isset($_SESSION) && !empty($_SESSION)) {
		return $_SESSION[$userIDKey];
	}
}
/***************************************************************
 ***************************************************************/
function get_configuration($module,$key){
	switch($module){
		case 'db': $module_name = 'database_credits';
			break;
		case 'sms': $module_name = 'sms_settings';
			break;
		default: 
			$module_name = $module;
	}
	return utils\Config::get("{$module_name}/{$key}");
}
/***************************************************************
 ***************************************************************/
function sanitize_output($buffer) {
	$search = array(
		'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
		'/[^\S ]+\</s',     // strip whitespaces before tags, except space
		'/(\s)+/s',         // shorten multiple whitespace sequences 
	);

	if(core\lib\utilities\Config::get('project/remove_comments') == 'on'){
		array_push($search, '/<!--(.|\s)*?-->/'); // Remove HTML comments
	}

	$replace = array(
		'>',
		'<',
		'\\1',
		''
	);

	$buffer = preg_replace($search, $replace, $buffer);
	return $buffer;
}

function is_initial($value,$key = null){
	if(is_array($value) && $key !== null){
		$result = array_key_exists($key,$value);
	}else if(is_array($value)){
		if(empty($value)){
			$result = true;
		}else{
			$result = false;
		}
	}else if(is_array($value) && $key !== null){
		if(empty($value) || !isset($value[$key]) ){
			$result = true;
		}else{
			$result = false;
		}
	}else {
		if($value == null || $value == '' || empty($value) || !isset($value) ){
			$result = true;
		}else{
			$result = false;
		}
	}

	return $result;
}

function click($id, $href = '#',$content, $class = '', $attrib = ''){
	$click .= "<a id=\"{$id}\" href=\"{$href}\" class=\"button {$class}\" {$attrib}>{$content}</a>";
	return $click;
}

function a($id, $content, $herf = '#', $class='', $attrib=''){
	return "<a id=\"{$id}\" class=\"{$class}\" href=\"{$herf}\" {$attrib}>{$content}</a>";
}

function ptext($content, $id = ''){
	return "<span id=\"{$id}\" class=\"ptext\">$content</span>";
}

function thisClass($obj = null){
	if(is_object($obj)){
		return get_class($obj);
	}
}

function create_slug($string){
	$slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return $slug;
}

function getClassFile($class){
	$reflector = new \ReflectionClass($class);
	return $reflector->getFileName();
}

function removeSpaces(String $string){
	return str_replace(' ', '', $string);
}

function removeWhiteSpaces(String $string){
	return str_replace(' ', '', $string);
} 

function createObject(){
	return new stdClass();
}