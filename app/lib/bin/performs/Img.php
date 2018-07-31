<?php
namespace perform{
    class Img{
        public static function saveUrl($url){
            $image = file_get_contents($url);
            file_put_contents('/images/image.jpg', $image); //save the image on your server
        }
    }
}
