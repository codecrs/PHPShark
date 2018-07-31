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


    class Link{
        public static function anchor($content, $target = '#', $param = null){
            $link = "<A HREF=\"{$target}\" {$param}>{$content}</A>";
            echo $link;
        }
    }


}