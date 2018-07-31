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

    class TableBuilder{
        
        private $_table = '';

        public function __construct(){

        }

        public function __destruct(){

        }

        public function build($param){
            $this->_table = "<TABLE {$param}>";
            return $this;
        }

        public function render($param){
            return $this->_table .= "</TABLE>";
        }

        public function thead($param){
            $this->_table .= "<THEAD {$param}>"; 
            return $this;
        }

        public function _thead($param){
            $this->_table .= "</THEAD>"; 
            return $this;
        }

        public function tfoot($param){
            $this->_table .= "<TFOOT {$param}>"; 
            return $this;
        }

        public function _tfoot($param){
            $this->_table .= "</TFOOT>"; 
            return $this;
        }

        public function tr($param){
            $this->_table .= "<TR {$param}>"; 
            return $this;
        }

        public function _tr($param){
            $this->_table .= "</TR>"; 
            return $this;
        }

        public function td($param){
            $this->_table .= "<TD {$param}>";
            return $this; 
        }

        public function _td($param){
            $this->_table .= "</TD>"; 
            return $this;
        }

        public function th($param){
            $this->_table .= "<TH {$param}>"; 
            return $this;
        }

        public function _th($param){
            $this->_table .= "</TH>"; 
            return $this;
        }

        public function content($param){
            $this->_table .= filter($param); 
            return $this;
        }

        public function html($param){
            $this->_table .= "$param"; 
            return $this;
        }
    }

}