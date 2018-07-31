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

 
use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\utilities as utils;
use core\lib\json as json;
use core\lib\pages as pages;

/********************************
Browser Functions 
 *********************************/
 
/***************************************************************
 ***************************************************************/

function check($pattern)
{
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$match = preg_match($pattern, strtolower($agent));
	return !empty($match);
}
/***************************************************************
 ***************************************************************/
function isOpera()
{
	check("/opera/");
}
/***************************************************************
 ***************************************************************/
function isOpera10_5()
{
	return isOpera() && check("/version\/10\.5/");
}
/***************************************************************
/***************************************************************
 ***************************************************************/
function isChrome()
{
	return check("/\bchrome\b/");
}
/***************************************************************
 ***************************************************************/
function isWebKit()
{
	return check("/webkit/");
}
/***************************************************************
 ***************************************************************/
function isAndroid()
{
	return check("/android/");
}
/***************************************************************
 ***************************************************************/
function isSafari()
{
	return !isChrome() && check("/safari/");
}
/***************************************************************
 ***************************************************************/
function isSafari2()
{
	return isSafari() && check("/applewebkit\/4/");
}
/***************************************************************
 ***************************************************************/
// unique to Safari 2
function isSafari3()
{
	return isSafari() && check("/version\/3/");
}
/***************************************************************
 ***************************************************************/
function isSafari4()
{
	return isSafari() && check("/version\/4/");
}
/***************************************************************
 ***************************************************************/
function isSafari5()
{
	return isSafari() && check("/version\/5/");
}
/***************************************************************
 ***************************************************************/
function isiPhone()
{
	return isSafari() && check("/iphone/");
}
/***************************************************************
 ***************************************************************/
function isiPod()
{
	return isSafari() && check("/ipod/");
}
/***************************************************************
 ***************************************************************/
function isiPad()
{
	return isSafari() && check("/ipad/");
}
/***************************************************************
 ***************************************************************/
function isIE()
{
	return !isOpera() && check("/msie/");
}
/***************************************************************
 ***************************************************************/
function isGecko()
{
	return !isWebKit() && check("/gecko/");
}
/***************************************************************
 ***************************************************************/
function isGecko3()
{
	return isGecko() && check("/rv:1\.9/");
}
/***************************************************************
 ***************************************************************/
function isGecko4()
{
	return isGecko() && check("/rv:2\.0/");
}
/***************************************************************
 ***************************************************************/
function isGecko5()
{
	return isGecko() && check("/rv:5\./");
}
/***************************************************************
 ***************************************************************/
function isFF()
{
	return isGecko() && check("/firefox/");
}
/***************************************************************
 ***************************************************************/
function isFF3_0()
{
	return isGecko3() && check("/rv:1\.9\.0/");
}
/***************************************************************
 ***************************************************************/
function isFF3_5()
{
	return isGecko3() && check("/rv:1\.9\.1/");
}
/***************************************************************
 ***************************************************************/
function isFF3_6()
{
	return isGecko3() && check("/rv:1\.9\.2/");
}
/***************************************************************
 ***************************************************************/
function isWindows()
{
	return check("/windows|win32/");
}
/***************************************************************
 ***************************************************************/
function isWindowsCE()
{
	return check("/windows ce/");
}
/***************************************************************
 ***************************************************************/
function isMac()
{
	return check("/macintosh|mac os x/");
}
/***************************************************************
 ***************************************************************/
function isLinux()
{
	return check("/linux/");
}
/***************************************************************
 ***************************************************************/
function isiOS()
{
	return isiPhone() || isiPod() || isiPad();
}
/***************************************************************
 ***************************************************************/
function isMobile()
{
	return isiOS() || isAndroid();
}

?>