<?php 

namespace core{
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;

	final class App extends Router{
		const DEFAULT_FILE_URL = "/";
		const ERROR_FILE_URL   = "404";
		const DEFAULT_FILE 	   = "index";
		const ERROR_FILE 	   = "error";
	
		private $_controller;

		private $_controllerNamespace;
		private $_controllerFile;
		private $_urlArray = array();
		private $_errArray = array();
        private $_routeNode = NULL;
        private $_newURL; 
		//Paths - Plugins   
		private $_pluginPath = 'plugins';
		private $_implementation = 'library';
		//Paths - Vendors
		private $_systemVendor = 'vendors';
		private $_userVendor = 'vendors';
		
		public function __construct(){
			parent::__construct();
			\Session::init();
		}
		
		public function dispatch(){
            utils\Sanitizr::removeMagicQuotes();
			utils\Sanitizr::unregisterGlobals();
			$this->_initialize();
			$router = $this->getrouterfile();
			require_once($router);
			if(!isset($_GET["url"])){	
				//Call Default Controller
				$this->_controllerNamespace = self::DEFAULT_FILE_URL;
			}else{
				$url = $_GET["url"];
                // if url ends with a slash, remove it
                if (substr($url, -1) === '/') {
                    $url = substr($url, 0, strlen($url) - 1);
                }
				$url = filter_var($url, FILTER_SANITIZE_URL); 
				//Call method Controller 
				$this->_urlArray = explode("/",$url);
				if(empty($this->_urlArray)){
					$this->_controllerNamespace = self::DEFAULT_FILE_URL;
				}else{
					$this->_urlArray = array_filter($this->_urlArray, function($value) { return $value !== ""; });
					$this->_urlArray = array_values($this->_urlArray);
					$this->_controllerNamespace = $this->_urlArray[0];
				}
			}
			$this->_getController();
		}
		
		private function _getController(){
			$controllerNamespace = $this->_controllerNamespace;
			if(!array_key_exists($controllerNamespace,Router::$_pathArray)){
				$this->_error();
			}else{
				if(!array_key_exists("path",Router::$_pathArray[$controllerNamespace])){
					$this->_error();
			}else{
					$controllerPath = Router::$_pathArray[$controllerNamespace]["path"];
					if(!isset($this->_urlArray[1])){
						if(!array_key_exists("controller",Router::$_pathArray[$controllerNamespace])){
							$controllerFile = self::DEFAULT_FILE;
							$controller 	= self::DEFAULT_FILE;
						}else{
							$controllerFile = Router::$_pathArray[$controllerNamespace]["controller"];
							$controller 	= Router::$_pathArray[$controllerNamespace]["controller"];
						}
					}else{
						$controllerFile = $this->_urlArray[1];
						$controller = $this->_urlArray[1];
					}
					if(!file_exists(SRC_PATH.$controllerPath.DS.$controller.DS.$controllerFile.".php")){
						$this->_error();
					}else{
						$location = SRC_PATH.$controllerPath.DS.$controller.DS.$controllerFile.".php";
						require($location);		
						$controllerNamespace = $this->_getActualNamespace($controllerNamespace);
						$controllerClass = ucfirst($controller);
						$class = "\\{$controllerNamespace}\\{$controllerClass}";
						if(!class_exists($class)){
							setError("Class: {$class} : Does Not Exist! <br/>" . __CLASS__ . "/". __FUNCTION__ );
							exit;
						}else{
							$this->_controller = new $class;
							$this->_controller->loadModel($class, $controller, $controllerPath); 
							$this->_getControllerMethod();
						}
					}
				}
			}
		}

		private function _getErrorController(){
			 $this->_errArray = array();
			 $error = self::ERROR_FILE;
			 $controllerPath = Router::$_pathArray[self::ERROR_FILE_URL]["path"];
			 if(!array_key_exists("controller",Router::$_pathArray[self::ERROR_FILE_URL])){
				 $err_controllerFile  = self::DEFAULT_FILE;
				 $err_controller	  = self::DEFAULT_FILE;
			 }else{
				 $this->_err_controllerFile = Router::$_pathArray[self::ERROR_FILE_URL]["controller"];
				 $err_controller	  = Router::$_pathArray[self::ERROR_FILE_URL]["controller"];
			 }
			 $err_controllerNamespace = $error;
			
			 $this->_errArray["path"] 		= $controllerPath;
			 $this->_errArray["file"] 		= $err_controllerFile;
			 $this->_errArray["controller"] = $err_controller;
			 $this->_errArray["namespace"]  = $err_controllerNamespace;
		}
		
		private function _getControllerMethod(){
			$length = count($this->_urlArray);
			if ($length > 2) {
				if (!method_exists($this->_controller, $this->_urlArray[2])) {
					$this->_error();
				}
			}
			switch ($length) {  
				case 7 :
					$this->_controller->{$this->_urlArray[2]}($this->_urlArray[3],$this->_urlArray[4],$this->_urlArray[5],$this->_urlArray[6]);
					break;
				case 6 :
					$this->_controller->{$this->_urlArray[2]}($this->_urlArray[3],$this->_urlArray[4],$this->_urlArray[5]);
					break;
				case 5 :
					$this->_controller->{$this->_urlArray[2]}($this->_urlArray[3],$this->_urlArray[4]);
					break;
				case 4 :
					$this->_controller->{$this->_urlArray[2]}($this->_urlArray[3]);
					break;
				case 3 :
					$this->_controller->{$this->_urlArray[2]}();
					break;
				default :
					if (!method_exists($this->_controller,"index")) {
						$this->_error();
					}else{
						$this->_controller->index();
					}
			}
		}

		private function _getActualNamespace($controllerNamespace){
			switch($controllerNamespace){
				case self::DEFAULT_FILE_URL:
					$controllerNamespace = self::DEFAULT_FILE;
					break;
				case self::ERROR_FILE_URL:
					$controllerNamespace = self::ERROR_FILE;
					break;
				default:
			}
			return $controllerNamespace;
		}
		
		private function _error(){
			$this->_getErrorController();
			if(!empty($this->_errArray)){
				$controllerPath = $this->_errArray["path"];
				$controller = $this->_errArray["controller"];
				$controllerFile = $this->_errArray["file"];
				$controllerNamespace = $this->_errArray["namespace"];
				$this->_errArray = "";
			}
			$location = SRC_PATH.$controllerPath.DS.$controller.DS.$controllerFile.".php";
			require($location);
			$controllerClass = ucfirst($controller);
			$class = "\\{$controllerNamespace}\\{$controllerClass}";
			if(!class_exists($class)){
				setError("Class: {$class} : Does Not Exist! <br/>" . __CLASS__ . "/". __FUNCTION__ );
				exit;
			}else{
				$this->_controller = new $class;
				$this->_controller->loadModel($class, $controller, $controllerPath); 
				$this->_controller->index();
			}
			exit;
		}
        
		/***************************************************************
Description: _initialize() function any user plugins or classes written by the customer 
in the project folder. 
         ***************************************************************/
         private function _initialize(){
			//Root Folders
			Defined("SRC_PATH") || Define('SRC_PATH', realpath(dirname(dirname(__file__))) . DS . 'src' . DS);
			Defined("DB_PATH") || Define('DB_PATH', realpath(dirname(__file__)).DS.'database'.DS);
			//$dir = array_filter(glob('*'),'is_dir');
			//print_r($dir);
			spl_autoload_extensions('.php');
			spl_autoload_register(function ($class) {
				$parts = explode('\\', $class);
				$class = end($parts);    

				if (file_exists(PROJECT_PATH.$this->_implementation.DS.$class.'.php'))
						require_once PROJECT_PATH.$this->_implementation.DS.$class.'.php';
				
                //Impliments 
				$folders = scandir(PROJECT_PATH . $this->_implementation);
				$remove = array('.', '..');
				$folders = array_diff($folders, $remove);
				if (!empty($folders)) {
					$folders = array_clean($folders);
					foreach ($folders as $folder) {
						if (file_exists(PROJECT_PATH.$this->_implementation.DS.$folder.DS.$class.'.php'))
							require_once PROJECT_PATH.$this->_implementation.DS.$folder.DS.$class.'.php';
					}
				}

				//Initalize Plugins
				$folders = scandir(PROJECT_PATH . $this->_pluginPath);
				$remove = array('.', '..');
				$folders = array_diff($folders, $remove);
				if (!empty($folders)) {
					$folders = array_clean($folders);
					foreach ($folders as $folder) {
						if (file_exists(PROJECT_PATH.$this->_pluginPath.DS.$folder.DS.$class.'.php'))
							require_once PROJECT_PATH.$this->_pluginPath.DS.$folder.DS.$class.'.php';
					}
				}     
				//System vendor 
				$folders = scandir(APP_PATH . $this->_systemVendor);
				$remove = array('.', '..');
				$folders = array_diff($folders, $remove);
				if (!empty($folders)) {
					$folders = array_clean($folders);
					foreach ($folders as $folder) {
						if (file_exists(APP_PATH.$this->_systemVendor.DS.$folder.DS.$class.'.php'))
							require_once APP_PATH.$this->_systemVendor.DS.$folder.DS.$class.'.php';
					}
				}
				//User Vendor
				$folders = scandir(PROJECT_PATH . $this->_userVendor);
				$remove = array('.', '..');
				$folders = array_diff($folders, $remove);
				if (!empty($folders)) {
					$folders = array_clean($folders);
					foreach ($folders as $folder) {
						if (file_exists(PROJECT_PATH.$this->_userVendor.DS.$folder.DS.$class.'.php'))
							require_once PROJECT_PATH.$this->_userVendor.DS.$folder.DS.$class.'.php';
					}
				}
			});
			if (!empty($_SERVER['QUERY_STRING']))
				$this->_route = preg_replace('/^url=index.php&url=(.*)/', '$1', $_SERVER['QUERY_STRING']);
					
			if(utils\Config::get('role/module_active') == 'YES'){
				//Create All Roles Tables 
				$userTable = utils\Config::get('webadmin/userTable');
				$userKey = utils\Config::get('webadmin/userKey');
				if(isset($userTable) && isset($userKey)){
					if(\orm\Query::is_table($userTable)){
						$roleTables = new roles\tables\RoleTables($userTable,$userKey);
						$roleTables->addRoleTables();
					}
				}
			}
        }

	}
}