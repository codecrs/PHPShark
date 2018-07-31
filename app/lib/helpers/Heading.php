<?php 


namespace view {

    use core\lib\files as files;
    use core\lib\imgs as imgs;
    use core\lib\errors as errs;
    use core\lib\forms as forms;
    use core\lib\utilities as utils;
    use core\lib\json as json;
    use core\lib\pages as pages;
    use core\view\input as input;
    use core\view\tags as tags;

    class Heading{
        public static function h($content, $no = '1', $param = null){
            $content = _t($content);
            $link = "<H{$no} {$param}>{$content}</H{$no}>";
            return $link;
        }
    }
}