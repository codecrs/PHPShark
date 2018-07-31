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

/*
<!-- 
    LOGS  
-->
<APP_ERROR_LOG>'logs'.DS.'application'.DS.'core-error-log.txt'</APP_ERROR_LOG>
<PHP_LOG>'logs'.DS.'php'.DS.'php-log.txt'</PHP_LOG>
 ***************************************************************/
/****************************** 
 * Exception Functions
 ******************************/
/***************************************************************
 ***************************************************************/
function writeDBLog($e, $query = null)
{
	$file = new files\Files;
	$t = time();
	$ms = ($query != null) ? $query : " ";
	$location = loc_file_log('db');
	$file->putFileContent($location, ">" . date("Y-m-d", $t) . ":" . date("h:i:sa") . " " . $ms . " ");
	$file->putFileContent($location, "- " . $e->getMessage());
}
/***************************************************************
 ***************************************************************/
function writeFiles($e, $msg)
{
	$file = new files\Files;
	$t = time();
	$ms = ($msg != null) ? $msg : " ";
	$location = loc_file_log('application');
	$file->putFileContent($location, "> " . date("Y-m-d", $t) . ":" . date("h:i:sa") . " " . $ms . " ");
	$file->putFileContent($location, "- " . $e->getMessage());
}
/***************************************************************
 ***************************************************************/
function applicationLog($msg)
{
	$file = new files\Files;
	$t = time();
	$ms = ($msg != null) ? $msg : " ";
	$location = loc_file_log('application');
	$file->putFileContent($location, "> " . date("Y-m-d", $t) . ":" . date("h:i:sa") . " " . $ms . " ");
}
/***************************************************************
 ***************************************************************/
function deleteLog($query = null, $record)
{
	$file = new files\Files;
	$t = time();
	$ms = ($query != null) ? $query : " ";
	$location = loc_file_log('audit');
	$file->putFileContent($location, "> " . date("Y-m-d", $t) . ":" . date("h:i:sa") . " " . $ms . " ");
	$file->putFileContent($location, "- " . 'Existing Old Deleted Record Was ' . $record);
}
/***************************************************************
 ***************************************************************/
 function stackTrace() {
	$stack = debug_backtrace();
    $output = '';

    $stackLen = count($stack);
    for ($i = 1; $i < $stackLen; $i++) {
        $entry = $stack[$i];

        $func = $entry['function'] . '(';
        $argsLen = count($entry['args']);
        for ($j = 0; $j < $argsLen; $j++) {
            $my_entry = $entry['args'][$j];
            if (is_string($my_entry)) {
                $func .= $my_entry;
            }
            if ($j < $argsLen - 1) $func .= ', ';
        }
        $func .= ')';

        $entry_file = 'NO_FILE';
        if (array_key_exists('file', $entry)) {
            $entry_file = $entry['file'];               
        }
        $entry_line = 'NO_LINE';
        if (array_key_exists('line', $entry)) {
            $entry_line = $entry['line'];
        }           
        $output .= $entry_file . ':' . $entry_line . ' - ' . $func . PHP_EOL;
    }
    return $output;
}

function setError($error, $page = "exception", $type = E_USER_NOTICE ){
	$env = strtolower(ENVIRONMENT);
	if($env == 'production' || $env == 'testing'){
		ob_start();
		debug_print_backtrace();
		$log = ob_get_clean();
		$log .= $error;
		applicationLog($log);
		request_page($page);
	}else{
		trigger_error($error, $type);
		echo "<br/>";
		echo "<pre>".stackTrace()."</pre>";
	}
}