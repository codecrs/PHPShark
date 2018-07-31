<?php

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

 namespace core{
	class Router{	
	protected static $_pathArray = array();
	
		public function __construct(){}
		public static function route(string $urlpath = null, array $folderPath = null){
			if(!is_null($urlpath) && !empty($folderPath))
				self::$_pathArray[$urlpath] = $folderPath;
		}
		
		protected function getrouterfile(string $path = null){
			if($path == null){
				return PROJECT_PATH."router.php";
			}
		}
	}
	
}