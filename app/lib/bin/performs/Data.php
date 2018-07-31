<?php

namespace perform{
    class Data{
        public static function uri(){
            $contents=file_get_contents($file);
            $base64=base64_encode($contents);
            echo "data:$mime;base64,$base64";
        }
    }
}
