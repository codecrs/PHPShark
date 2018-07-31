<?php
namespace perform{
    class Zip{
        public static function create($files = array(), $destination = '', $overwrite = false){
            //if the zip file already exists and overwrite is false, return false
            if (file_exists($destination) && !$overwrite) {
                return false;
            }
            //vars
            $valid_files = array();
            //if files were passed in...
            if (is_array($files)) {
                //cycle through each file
                foreach ($files as $file) {
                    //make sure the file exists
                    if (file_exists($file)) {
                        $valid_files[] = $file;
                    }
                }
            }
            //if we have good files...
            if (count($valid_files)) {
                //create the archive
                $zip = new ZipArchive();
                if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                    return false;
                }
                //add the files
                foreach ($valid_files as $file) {
                    $zip->addFile($file, $file);
                }
                //debug
                //echo 'The zip archive contains ',$zip-&gt;numFiles,' files with a status of ',$zip-&gt;status;
                
                //close the zip -- done!
                $zip->close();
                
                //check to make sure the file exists
                return file_exists($destination);
            } else {
                return false;
            }
        }

        public static function unzip($location, $newLocation){
            if (exec("unzip $location", $arr)) {
                mkdir($newLocation);
                for ($i = 1; $i<count($arr); $i++) {
                    $file = trim(preg_replace("~inflating: ~", "", $arr[$i]));
                    copy($location.'/'.$file, $newLocation.'/'.$file);
                    unlink($location.'/'.$file);
                }
                return true;
            } else {
                return false;
            }
        }
    }
}