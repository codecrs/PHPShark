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
Redirect Functions
 *********************************/
/***************************************************************
 ***************************************************************/
function redirect(string $location){
	$link = $location;
	if (headers_sent()) { echo "<meta http-equiv=\"refresh\" content=\"0; URL={$link}\">"; }
	else{	exit(header('Location:' . $link)); }
}

function request_page(string $redirection = ""){
	$link = url($redirection);
	if (headers_sent()) { echo "<meta http-equiv=\"refresh\" content=\"0; URL={$link}\">"; }
	else{ exit(header('Location:' . $link )); }

}


