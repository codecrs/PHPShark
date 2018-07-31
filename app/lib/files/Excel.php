<?php 
namespace core\lib\files {
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

	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	class Excel extends \PHPExcel
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function initExcelLibrary($pathName)
		{
			$execlLibPath = APP_PATH . 'vendors' . DS . 'php-excel' . DS . 'PHPExcel' . DS . $pathName . '.php';
			if (is_file($execlLibPath)) {
				require_once ($execlLibPath);
			}
		}
	}
}