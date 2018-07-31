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

    class Container{

        private static $_param;
        private static $_content;
        private $content;

        public static function span($param){
            self::$_param = $param;
            self::$_content = __function__;
            return new self;
        }


        public static function div($param){
            self::$_param = $param;
            self::$_content = __function__;
            return new self;
        }

        public function content($content){
            $container = view\Container::$_content;
            $param = view\Container::$_param;
            $this->content = "<{$container} {$param}>{$content}</{$container}>" ;
            return $this;
        }

        public function render(){
            return $this->content;
        }
        
    }

}