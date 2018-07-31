<?php 
namespace core\view\tags{
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

	class Tag{
		public function __construct(){}
		public static function arributes(){
			$attr = array();
			$function = func_get_arg(0);
			$id   = func_get_arg(1);
			$attr = func_get_arg(2);
			$bt   = func_get_arg(3);
			$caller   = array_shift($bt);
			$class = '';
			$attrib = '';
			if(!is_initial($attr)){
				(is_initial($attr,'class')) ? $class  = $attr['class'] : $class = '';
				(is_initial($attr,'attr'))  ? $attrib = $attr['attr'] : $attrib = '';
			}
			$attributes = "id=\"{$id}\" class=\"{$class}\" {$attrib}";
			return $attributes;
		}
		
		public static function arributes2(){
			$attr = array();
			$function = func_get_arg(0);
			$attr = func_get_arg(1);
			$bt   = func_get_arg(2);
			$caller   = array_shift($bt);
			$class = '';
			$attrib = '';
			
			if(!is_initial($attr)){
				(is_initial($attr,'class')) ? $class  = $attr['class'] : $class = '';
				(is_initial($attr,'attr'))  ? $attrib = $attr['attr'] : $attrib = '';
			}
			$attributes = "class=\"{$class}\" {$attrib}";
			return $attributes;
		}
	}

}

