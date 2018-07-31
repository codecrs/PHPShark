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

 
/********************************
Hash Functions
 *********************************/
/***************************************************************
 ***************************************************************/
function hash_encrypt(string $data)
{
	$context = hash_init(
		utils\config::get('database_credits/security_function'),
		HASH_HMAC,
		utils\config::get('database_credits/security_salt')
	);
	hash_update($context, $data);
	return hash_final($context);
}
/***************************************************************
 ***************************************************************/
function hash_token(string $type = 'md5')
{
	$data1 = rand();
	$data2 = rand();
	$context = hash_init($type, HASH_HMAC, $data2);

	hash_update($context, $data1);
	return hash_final($context);
}
/***************************************************************
 ***************************************************************/
function sha_token($str, $opt = FALSE){
	return sha1($str, $opt);
}	

function decrypt($string, $key){
	$result = '';
	$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
	return $result;
}
/***************************************************************
 ***************************************************************/
function encrypt($string, $key){
	$result = '';
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return base64_encode($result);
}