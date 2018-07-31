<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\utilities as utils;
use core\lib\json as json;
use core\lib\pages as pages;

/**
 *  Qmvc - Quick PHP MVC 
 *  Developed by Contempative Radical Solutions Consulting Private Ltd. 
 *  Project Initiative By Ankit Kumar
 *  http://www.contemplativeradicals.com
 *  
 *  @copyright  Contempative Radical Solutions Consulting Private Ltd. 
 *  @link
 *  @since      1.0.0
 *  @license
 *  
 * Change Logs
 * *******************************************************************
 * 
 * *******************************************************************
 **/

function getFileStructure($location, $resource = false)
{
    $result = array();
    if ($handle = opendir($location)) {
        if ($resource == true) {
            array_push($result, $handle);
        }

        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            array_push($result, $entry);
        }

        closedir($handle);
    }
    return $result;
}
/***************************************************************
 ***************************************************************/
function readFileStructure($location, $reverse = false)
{
    $result = array();
    if (!empty($location) == true) {
        if ($result == true) {
            $result = scandir($location, 1);
        }
        else {
            $result = scandir($location);
        }
    }
    return $result;
}
/***************************************************************
 ***************************************************************/
function list_directory($location)
{
    $result = array();
    $dir = array();
    if (!empty($location) == true) {
        if ($result == true) {
            $result = scandir($location, 1);
        }
        else {
            $result = scandir($location);
        }

        foreach ($result as $res) {
            if (is_dir($location . DS . $res)) {
                array_push($dir, $res);
            }
        }
    }

    return $dir;
}


/***************************************************************
 ***************************************************************/
function list_files($location){
    // $result = array();
    // $files = array();
    // if (!empty($location) == true) {
    //     if ($result == true) {
    //         $result = scandir($location, 1);
    //     }
    //     else {
    //         $result = scandir($location);
    //     }

    //     foreach ($result as $res) {
    //         if (is_file($location . DS . $res)) {
    //             array_push($files, $res);
    //         }
    //     }
    // }
    // return $files;

    if(is_dir($dir)){
        if($handle = opendir($dir)){
            while(($file = readdir($handle)) !== false){
                if($file != "." && $file != ".." && $file != "Thumbs.db"){
                    echo ''.$file.''."\n";
                }
            }
            closedir($handle);
        }
    }
}
/***************************************************************
 ***************************************************************/
function read_file($location)
{
    $file_content = file($location);
    return $file_content;
}
/***************************************************************
 ***************************************************************/
function read_file_content($location)
{
    $file_content = file_get_contents($location);
    return $file_content;
}
/***************************************************************
***************************************************************/
/*****
*@dir - Directory to destroy
*@virtual[optional]- whether a virtual directory
*/
function destroyDir($dir, $virtual = false)
{
	$ds = DIRECTORY_SEPARATOR;
	$dir = $virtual ? realpath($dir) : $dir;
	$dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
	if (is_dir($dir) && $handle = opendir($dir))
	{
		while ($file = readdir($handle))
		{
			if ($file == '.' || $file == '..')
			{
				continue;
			}
			elseif (is_dir($dir.$ds.$file))
			{
				destroyDir($dir.$ds.$file);
			}
			else
			{
				unlink($dir.$ds.$file);
			}
		}
		closedir($handle);
		rmdir($dir);
		return true;
	}
	else
	{
		return false;
	}
}