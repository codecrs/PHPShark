<?php
namespace core{
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
Controller Functions
	 *********************************/
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	class Controller{
		public $view = '';
		public $request;

		/***************************************************************
Description: load the View method called from the controller method.
methods available to us in controller class are as follows: 

$this->host 
$this->uri  
$this->request 
$this->route 
$this->url 
		 ***************************************************************/
		public function __construct(){
			$this->view = new View();
			$this->request   = new utils\Request();
		}
		/***************************************************************
Description: Automatically loads the corresponding model for the 
current controller.
		 ***************************************************************/
		public function loadModel(string $class, string $controller, string $path){           
            $modelFile = trim($controller)."_model";
            $modelClass = trim($class)."_Model";
			$v_path = SRC_PATH.$path.DS.$controller.DS.$modelFile.".php";
            if(file_exists($v_path)){
                require($v_path);
                $this->model = new $modelClass();
				$this->view->_setViewPath($path.DS.$controller);
                return;
            }
		}
	}
}
