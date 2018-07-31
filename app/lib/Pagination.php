<?php 
namespace core\lib\pages {
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

	/****************************** 
     * Exception Functions
     ******************************/
	/***************************************************************
     ***************************************************************/
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;

	final class Pagination
	{

		private $_page;
		private $_total;
		private $_limit;

		function getDetails(
			$total,
			$page_number = 1,
			$columns = 3,
			$limit = 10,
			$per_page = 1
		)
		{
			$records = $total;
			$total_pages = ceil( (1 / $limit) * $records);
			$offset = ($page_number - 1) * $limit + 1;
			$rows = ceil($limit / $columns);
			$calc['total'] = $total_pages;
			$calc['offset'] = $offset;
			$calc['limit'] = $limit;
			$calc['pages'] = $per_page;
			$calc['records'] = $total;
			$calc['rows'] = $rows;
			$calc['columns'] = $columns;
			$this->_page = $page_number;
			$this->_total = $total;
			$this->_limit = $limit;
			return $calc;
		}

	}
}
/***************************************************************/