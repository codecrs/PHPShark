<?php
namespace perform{
    class Download{
        public static function force($file){
            $dir      = "../log/exports/"; 
            if ((isset($file))&&(file_exists($dir.$file))) { 
               header("Content-type: application/force-download"); 
               header('Content-Disposition: inline; filename="' . $dir.$file . '"'); 
               header("Content-Transfer-Encoding: Binary"); 
               header("Content-length: ".filesize($dir.$file)); 
               header('Content-Type: application/octet-stream'); 
               header('Content-Disposition: attachment; filename="' . $file . '"'); 
               readfile("$dir$file"); 
            } else { 
               echo "No file selected"; 
            } 
        }

        public static function imgUrl($image, $rename){
            $ch = curl_init($image);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            $rawdata=curl_exec ($ch);
            curl_close ($ch);
        
            $fp = fopen("$rename", 'w');
            fwrite($fp, $rawdata);
            fclose($fp);
        }

        

    }
}
