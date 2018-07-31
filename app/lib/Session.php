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

/********************************
Session Functions
	*********************************/
use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\json as json;
use core\lib\pages as pages;

class Session
{
	/***************************************************************
	 ***************************************************************/
	public static function init()
	{
		if(phpversion() >= "5.4.0"){
			if (session_status() == PHP_SESSION_NONE) {
				@session_start();
			}
		}
		if(phpversion() < "5.4.0"){
			if(session_id() == '') {
				session_start();
			}
		}
	}
	/***************************************************************
	 ***************************************************************/
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	/***************************************************************
	 ***************************************************************/
	public static function get($key)
	{
		if (isset($_SESSION[$key]))
			return $_SESSION[$key];
	}
	/***************************************************************
	 ***************************************************************/
	public static function exists($key)
	{
		if (isset($_SESSION[$key])) {
			return true;
		}
		else {
			return false;
		}
	}
	/***************************************************************
	 ***************************************************************/
	public static function destroy()
	{
		//unset($_SESSION);
		session_destroy();
	}
	/***************************************************************
	 ***************************************************************/
	public static function delete(string $key)
	{
		if (self::exists($key)) {
			unset($_SESSION[$key]);
		}
	}
	/***************************************************************
	 ***************************************************************/
	public static function flash(string $key, string $string = '')
	{
		if (self::exists($key)) {
			$session = self::get($key);
			self::delete($key);
			return $session;
		}
		else {
			self::put($name, $string);
		}
	}
}
