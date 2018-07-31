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

 
//Technical Details
//Msg Class: Z000

/***************************************************************
 ***************************************************************/
// Name DNS name for Databse Connection
function DNS()
{
	$server = utils\config::get('database_credits/datasource') . ':host=' . utils\config::get('database_credits/host') .
		';dbname=' . utils\config::get('database_credits/database');
	return $server;
}
/***************************************************************
 ***************************************************************/
// if this is localhost
function isLocalhost()
{
	return $_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1';
}
/***************************************************************
 ***************************************************************/

//Get client IP address
function getClientIP()
{
	$ip = "127.0.0.1";
	if (getenv("HTTP_CLIENT_IP")) {
		$ip = getenv("HTTP_CLIENT_IP");
	}
	else if (getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}
	else if (getenv("REMOTE_ADDR")) {
		$ip = getenv("REMOTE_ADDR");
	}
	else {
		$ip = "UNKNOWN";
	}

	if ($ip == "::1") {
		$ip = "127.0.0.1";
	}
	return $ip;
}
/***************************************************************
 ***************************************************************/
// Check if the action is AJAX request
function isAjax()
{
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
}
/***************************************************************
 ***************************************************************/
//Gets server url path
function getServerUrl()
{
	$port = $_SERVER['SERVER_PORT'];
	$http = "http";

	if ($port == "80") {
		$port = "";
	}

	if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$http = "https";
	}
	if (empty($port)) {
		return $http . "://" . $_SERVER['SERVER_NAME'];
	}
	else {
		return $http . "://" . $_SERVER['SERVER_NAME'] . ":" . $port;
	}
}
/***************************************************************
 ***************************************************************/
/*
function get_current_user(){
	return get_current_user();
}
 */
/***************************************************************
 ***************************************************************/
function get_uid()
{
	return getmyuid();
}
/***************************************************************
 ***************************************************************/
function get_pid()
{
	return getmypid();
}
/***************************************************************
 ***************************************************************/
function UniqueMachineID($salt = "")
{
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		$temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "diskpartscript.txt";
		if (!file_exists($temp) && !is_file($temp)) file_put_contents($temp, "select disk 0\ndetail disk");
		$output = shell_exec("diskpart /s " . $temp);
		$lines = explode("\n", $output);
		$result = array_filter($lines, function ($line) {
			return stripos($line, "ID:") !== false;
		});
		if (count($result) > 0) {
			$result = array_shift(array_values($result));
			$result = explode(":", $result);
			$result = trim(end($result));
		}
		else $result = $output;
	}
	else {
		$result = shell_exec("blkid -o value -s UUID");
		if (stripos($result, "blkid") !== false) {
			$result = $_SERVER['HTTP_HOST'];
		}
	}
	return md5($salt . md5($result));
}
/***************************************************************
 ***************************************************************/
function homepath()
{
	return getenv("HOMEPATH");
}
/***************************************************************
 ***************************************************************/
function homedrive()
{
	return getenv("HOMEDRIVE");
}
/***************************************************************
 ***************************************************************/
function username()
{
	return getenv("username");
}
/***************************************************************
 ***************************************************************/
function get_mac_address()
{
	$ipAddress = $_SERVER['REMOTE_ADDR'];
	$macAddr = false;

	#run the external command, break output into lines
	$arp = `arp -a $ipAddress`;
	$lines = explode("\n", $arp);

	#look for the output line describing our IP address
	foreach ($lines as $line)
		{
		$cols = preg_split('/\s+/', trim($line));
		if ($cols[0] == $ipAddress)
			{
			$macAddr = $cols[1];
		}
	}
}
//to check ip is pass from proxy
function getRealIpAddr(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

/********************
*@file - path to file
*/
function force_download($file){
    if ((isset($file))&&(file_exists($file))) {
       header("Content-length: ".filesize($file));
       header('Content-Type: application/octet-stream');
       header('Content-Disposition: attachment; filename="' . $file . '"');
       readfile("$file");
    } else {
       echo "No file selected";
    }
}
