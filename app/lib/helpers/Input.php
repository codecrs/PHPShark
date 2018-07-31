<?php 

namespace core\view\input{
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

	class Input{	
		public function __construct(){}
		
		private static function _checkbox($function,$id, $arr = array()){
			$attr  = '';
			$lattr = '';
			$input = '';
			$label = null;
			$type_n = self::_getNameConv($function);
			$attr .= "id=\"{$id}\" name=\"{$type_n}_{$id}\" ";
			if(!is_initial($arr)){
				foreach($arr as $key => $value){
					switch(strtolower(trim($key))){
						case 'label': 
							$label .= $value;
							break;
						case 'for': 
							$lattr .= "for=\"{$value}\" ";
							break;
						case 'class': 
							$attr .= "class=\"{$value}\" ";
							break;
						case 'attr': 
							$attr .= $value;
							break;
						case 'label_attr': 
							$lattr .= $value;
							break;
						case 'value':
							$attr .= "value=\"{$val}\" ";
							break;
						case 'placeholder':
							$attr .= "placeholder=\"{$val}\" ";
							break;
					}
				}
			}
			$input = '';
			$type = strtoupper($function);
			if($label !== null) $input .= "<LABEL for={$id} {$lattr}>$label</LABEL>";
			$input .= "<INPUT type=\"{$function}\" {$attr} /><!-- {$type} field:{$id} -->";
			return $input;	
		}

		private static function _radio($function,$id, $arr = array()){
			$attr  = '';
			$lattr = '';
			$input = '';
			$label = null;
			$type_n = self::_getNameConv($function);
			$attr .= "id=\"{$id}\" name=\"{$type_n}_{$id}\" ";
			if(!is_initial($arr)){
				foreach($arr as $key => $value){
					switch(strtolower(trim($key))){
						case 'label': 
							$label .= $value;
							break;
						case 'for': 
							$lattr .= "for=\"{$value}\" ";
							break;
						case 'class': 
							$attr .= "class=\"{$value}\" ";
							break;
						case 'attr': 
							$attr .= $value;
							break;
						case 'label_attr': 
							$lattr .= $value;
							break;
						case 'value':
							$attr .= "value=\"{$value}\" ";
							break;
						case 'placeholder':
							$attr .= "placeholder=\"{$value}\" ";
							break; 
					}
				}
			}
			$input = '';
			$type = strtoupper($function);
			if($label !== null) $input .= "<LABEL for={$id} {$lattr}>$label</LABEL>";
			$input .= "<INPUT type=\"{$function}\" {$attr} /><!-- {$type} field:{$id} -->";
			return $input;	
		}

		private static function _label($function, $id, $arr = array()){
			$attr = '';
			$lattr = '';
			$label = '';
			$type_n = self::_getNameConv($function);
			$attr .= "id=\"{$id}\" name=\"{$type_n}_{$id}\" ";
			if(!is_initial($arr)){
				foreach($arr as $key => $value){
					switch(strtolower(trim($key))){
						case 'label': 
							$label .= $value;
							break;
						case 'for': 
							$lattr .= "for=\"{$value}\" ";
							break;
						case 'class': 
							$attr .= "class=\"{$value}\" ";
							break;
						case 'attr': 
							$attr .= $value;
							break;
					}
				}
			}
			return "<LABEL {$attr} >{$label}</LABEL><!-- LABEL field:{$id} -->";
		}

		private static function _textarea($function, $id, $arr = array()){
			$attr = '';
			$lattr = '';
			$type_n = self::_getNameConv($function);
			$attr .= "id=\"{$id}\" name=\"{$type_n}_{$id}\" ";
			if(!is_initial($arr)){
				foreach($arr as $key => $value){
					switch(strtolower(trim($key))){
						case 'label': 
							$label .= $value;
							break;
						case 'for': 
							$lattr .= "for=\"{$value}\" ";
							break;
						case 'class': 
							$attr .= "class=\"{$value}\" ";
							break;
						case 'attr': 
							$attr .= $value;
							break;
					}
				}
			}
			return "<TEXTAREA {$attr} {$attrib}>";
		}

		private static function _select($function, $id, $arr = array()){
			$attr = '';
			$type_n = self::_getNameConv($function);
			$attr .= "id=\"{$id}\" name=\"{$type_n}_{$id}\" ";
			if(!is_initial($arr)){
				foreach($arr as $key => $value){
					switch(strtolower(trim($key))){
						case 'class': 
							$attr .= "class=\"{$value}\" ";
							break;
						case 'attr': 
							$attr .= $value;
							break;
					}
				}
			}
			return "<SELECT {$attr}>";
		}

		private static function _default($function, $id, $arr = array()){
			$attr = '';
			$lattr = '';
			$input = '';
			$label = null;
			$type_n = self::_getNameConv($function);
			$attr .= "id=\"{$id}\" name=\"{$type_n}_{$id}\" ";
			if(!is_initial($arr)){
				foreach($arr as $key => $value){
					switch(strtolower(trim($key))){
						case 'label': 
							$label .= $value;
							break;
						case 'for': 
							$lattr .= "for=\"{$value}\" ";
							break;
						case 'class': 
							$attr .= "class=\"{$value}\" ";
							break;
						case 'attr': 
							$attr .= $value;
							break;
						case 'label_attr': 
							$lattr .= $value;
							break;
						case 'value':
							$attr .= "value=\"{$value}\" ";
							break;
						case 'placeholder':
							$attr .= "placeholder=\"{$value}\" ";
							break;
					}
				}
			}
			
			$type = strtoupper($function);
			if($label !== null) $input .= "<LABEL for={$id} {$lattr}>$label</LABEL>";
			$input .= "<INPUT type=\"{$function}\" {$attr} /><!-- {$type} field:{$id} -->";
			return $input;	
		}

		private static function _btn($function, $id, $arr = array()){
			$attr = '';
			$input = '';
			$type_n = self::_getNameConv($function);
			$attr .= "id=\"{$id}\" name=\"{$type_n}_{$id}\" ";
			if(!is_initial($arr)){
				foreach($arr as $key => $value){
					switch(strtolower(trim($key))){
						case 'for': 
							$lattr .= "for=\"{$value}\" ";
							break;
						case 'class': 
							$attr .= "class=\"{$value}\" ";
							break;
						case 'attr': 
							$attr .= $value;
							break;
						case 'value':
							$attr .= "value=\"{$value}\" ";
							break;
					}
				}
			}
			$type = strtolower($function);
			$input .= "<INPUT type=\"{$function}\" {$attr} /><!-- {$type} field:{$id} -->";
			return $input;	
		}
		
		public static function field($function, $id, $arr){
			switch(trim($function)){
				case 'checkbox':
					return self::_checkbox($function,$id, $arr);
					break;
				case 'radio':
					return self::_radio($function, $id, $arr);
					break;
				case 'label':
					return self::_label($function, $id, $arr);
					break;
				case 'textarea':
					return self::_textarea($function, $id, $arr);
					break;
				case 'select':
					return self::_select($function, $id, $arr);
					break;
				case 'button':
					return self::_btn($function, $id, $arr);
					break;
				case 'submit':
					return self::_btn($function, $id, $arr);
					break;
				case 'reset':
					return self::_btn($function, $id, $arr);
					break;
				default:
					return self::_default($function, $id, $arr);
			}
		}
		
		private static function _getNameConv(){
			switch(func_get_arg(0)){
				case 'text':
					return 'txt';
					break;
				case 'radio':
					return 'rb';
					break;
				case 'checkbox':
					return 'cb';
					break;	
				case 'password':
					return 'pas';
					break;	
				case 'button':
					return 'btn';
					break;	
				case 'submit':
					return 'sbt';
					break;	
				case 'reset':
					return 'rst';
					break;	
				case 'color':
					return 'col';
					break;	
				case 'date':
					return 'date';
					break;	
				case 'datetime':
					return 'dt';
					break;
				case 'email':
					return 'email';
					break;
				case 'month':
					return 'mnth';
					break;
				case 'number':
					return 'num';
					break;
				case 'range':
					return 'rng';
					break;
				case 'search':
					return 'srch';
					break;
				case 'tel':
					return 'tel';
					break;
				case 'time':
					return 'time';
					break;
				case 'url':
					return 'url';
					break;
				case 'week':
					return 'week';
					break;
				case 'textarea':
					return 'ta';
					break;
				case 'select':
					return 'so';
					break;
			}
		}
	}
}