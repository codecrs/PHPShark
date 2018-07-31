<?php 

namespace view{

    use core\lib\files as files;
    use core\lib\imgs as imgs;
    use core\lib\errors as errs;
    use core\lib\forms as forms;
    use core\lib\utilities as utils;
    use core\lib\json as json;
    use core\lib\pages as pages;
    use core\view\input as input;
    use core\view\tags as tags;

    class ListBuilder{

        private static $_list = '';
        private static $_type;

        public function __construct(){

        }

        public function __destruct(){

        }

        public static function build($param, $type = 'UL'){
            self::$_list = "<{$type} {$param}>";
            self::$_type = $type;
            return new self;
        }

        public function li($param){
            $this->_list .= "<LI {$param}>";
            return $this;  
        }

        public function _li(){
            $this->_list .= "</LI>";
            return $this;  
        }

        public function render($param){
            return $this->_list .= "</{$this->_type}>";
        }

        public function content($param){
            $this->_table .= _t($param); 
            return $this;
        }

        public function html($param){
            $this->_table .= "$param"; 
            return $this;
        }

    }
}