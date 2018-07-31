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

	class CSV
	{
		/***************************************************************
         ***************************************************************/
		public function ReadCSV($filePath)
		{
			$csvData = file_get_contents($filePath);
			$lines = explode(PHP_EOL, $csvData);
			$array = array();
			foreach ($lines as $line) {
				$array[] = str_getcsv($line);
			}
			return $array;
		}
		/***************************************************************
         ***************************************************************/
		public function getCSV($path)
		{
			$file = fopen('myCSVFile.csv', 'r');
			while ( ($line = fgetcsv($file)) !== FALSE) {
				//$line is an array of the csv elements
				print_r($line);
			}
			fclose($file);
		}
		/***************************************************************
         ***************************************************************/
		public function WriteCSV($data, $fileName)
		{
			header('Content-Type: application/excel');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			$fp = fopen('php://output', 'w');
			foreach ($data as $line) {
				$val = explode(",", $line);
				fputcsv($fp, $val);
			}
			fclose($fp);
		}
		/***************************************************************
         ***************************************************************/
		public function getCSVStream()
		{
			return stream_get_contents('php://input');
		}

		/***************************************************************
         ***************************************************************/

		public function generateCsv($data, $delimiter = ',', $enclosure = '"') {
			$handle = fopen('php://temp', 'r+');
			foreach ($data as $line) {
				fputcsv($handle, $line, $delimiter, $enclosure);
			}
			rewind($handle);
			while (!feof($handle)) {
				$contents .= fread($handle, 8192);
			}
			fclose($handle);
			return $contents;
		}
	}
}