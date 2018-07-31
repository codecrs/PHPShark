<?php 

namespace perform{
    use \Statickidz\GoogleTranslate;
    use app\lib\utilities as utils;
    
    class Language{
        public static function _t($content,$set_target = false){
            $source = utils\Config::get('language/base');
            if ($set_target === true) {
                $target = $set_target;
            } else {
                $target = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            }
        
            $target = strtolower($target);
            $text = $content;
        
            $trans = new GoogleTranslate();
            $result = $trans->translate($source, $target, $text);
            return $result;
        }

        public static function _h($string, $flags = ENT_COMPAT, $characterset = 'UTF-8', $double_encode = 'TRUE'){
            return htmlentities($string, $flags, $characterset, $double_encode);
        }
    }
}
