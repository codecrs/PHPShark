<?php
namespace perform{
    class Folder{
        public static function delete($path){
            if (is_dir($path) === true) {
                $files = array_diff(scandir($path), array('.', '..'));
        
                foreach ($files as $file) {
                    Delete(realpath($path) . '/' . $file);
                }
        
                return rmdir($path);
            } elseif (is_file($path) === true) {
                return unlink($path);
            }
        
            return false;
        }
    }
}
