<?php 
use core\lib\files as files;
function set_folder($member_folder){
    $path = path("upload/members/{$member_folder}");
    if(!file_exists($path)){
        $file = new files\Files;
        $file->createFolder($path);
    }
    return $path;
}