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

function write_log($log_type, $error_desc, $filename = null)
{
	$files = new Files;
	switch (strtolower(trim($log_type))) {
		case 'db' :
			$location = loc_file_log($log_type, $filename);
			$files->putFileContent($location, date("Y-m-d", $t) . ":" . date("h:i:sa") . ">" . $error_desc);
		case 'audit' :
			$location = loc_file_log($log_type, $filename);
			$files->putFileContent($location, date("Y-m-d", $t) . ":" . date("h:i:sa") . ">" . $error_desc);
		case 'application' :
			$location = loc_file_log($log_type, $filename);
			$files->putFileContent($location, date("Y-m-d", $t) . ":" . date("h:i:sa") . ">" . $error_desc);
	}
}

function loc_file_log($log_type, $filename = null)
{
	switch (strtolower(trim($log_type))) {
		case 'db' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('logs/db')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "file-error-log" with your log Folder!');
				}
			}
			if ($filename == null) {
				return $path . utils\Config::get('logs/dbfile') . '.txt';
			}
			else {
				return $path . $filename . '.txt';
			}

			break;

		case 'audit' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('logs/audit')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "file-error-log" with your log Folder!');
				}
			}
			if ($filename == null) {
				return $path . utils\Config::get('logs/auditfile') . '.txt';
			}
			else {
				return $path . $filename . '.txt';
			}
			break;

		case 'application' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('logs/app')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "file-error-log" with your log Folder!');
				}
			}
			if ($filename == null) {
				return $path . utils\Config::get('logs/appfile') . '.txt';
			}
			else {
				return $path . $filename . '.txt';
			}
			break;

		case 'php' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('logs/php')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "file-error-log" with your log Folder!');
				}
			}
			if ($filename == null) {
				return $path . utils\Config::get('logs/phpfile') . '.txt';
			}
			else {
				return $path . $filename . '.txt';
			}
			break;
	}
}

function loc_file_import($folder, $file)
{
	switch (strtolower(trim($folder))) {
		case 'xml' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('imports/xml')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "application-log" with your log Folder!');
				}
			}
			return $path . $file . '.xml';
			break;

		case 'csv' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('imports/csv')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "application-log" with your log Folder!');
				}
			}
			return $path . $file . '.csv';
			break;

		case 'text' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('imports/txt')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "application-log" with your log Folder!');
				}
			}
			return $path . $file . '.txt';
			break;

		case 'warehouse' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('imports/warehouse')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "application-log" with your log Folder!');
				}
			}
			return $path . $file . '.txt';
			break;

		case 'archive' :
			$path = PROJECT_PATH . str_replace('.', DS, utils\Config::get('imports/archive')) . DS;
			if (!file_exists($path)) {
				if (!mkdir($path, 0777, true)) {
					//die('Failed to create folders...');
					write_log('application', 'ERROR: Failed to create folders...');
					die(date("Y-m-d", $t) . ":" . date("h:i:sa") . 'check "application-log" with your log Folder!');
				}
			}
			return $path . $file . '.txt';
			break;
	}
}

