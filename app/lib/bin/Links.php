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


function url($path = null){
      $base = utils\Config::get('project/base');
      if($path !== null){
        return "{$base}{$path}";
      }else{
        return "{$base}";
      }

    //------------------------------------------------------------
    //FOR DEBUGGING AND REFERENCES - DO NOT DELETE
    //------------------------------------------------------------
    //var_dump($_SERVER['HTTP_HOST']); echo '<br/>';
    //var_dump($_SERVER['PHP_SELF']); echo '<br/>';
    //var_dump($_SERVER['REQUEST_URI']); echo '<br/>';
    //var_dump($_SERVER['QUERY_STRING']); echo '<br/>';
    //var_dump($_SERVER['REQUEST_URI']); echo '<br/>';
    //var_dump($http); echo '<br/>';
    //$base = $_SERVER['REQUEST_URI'];
    //echo $url; echo '<br/>';
    //echo app\utilities\Config::get('url_link/base'); echo '<br/>';
    //echo app\utilities\Config::get('url_link/root');

    //------------------------------------------------------------
    //------------------------------------------------------------
    //------------------------------------------------------------
    //////////////////////////////////////////////////////////////////////////////////
    ////////////////////////DO NOT DELETE - FOR TESTING & REFERENCES//////////////////
    //////////////////////////////////////////////////////////////////////////////////
    /*
    if(app\utilities\Config::get('url_link/root') !== '/'){
        $base_path .= '/'. app\utilities\Config::get('url_link/root') . '/';
    } else if (app\utilities\Config::get('url_link/root') == '/'){
       $base_path .= '/';
    }
    if(app\utilities\Config::get('url_link/base') !== ''){
        $base_path .= app\utilities\Config::get('url_link/base').'/';
    }

    if($path == ''){
        return $base_path;
    } else if($path == '/') {
        return $base_path;
    } else{
        // if url ends with a slash, remove it
        if (substr($path, -1) === '/') {
            $path = substr($path, 0, strlen($path) - 1);
        }
        return $base_path.$path;
    } */

    // $base_path = '';
    // if (isset($_SERVER['HTTPS'])) {
    //     $http = 'https://';
    // }
    // else {
    //     $http = 'http://';
    // }
    //
    // $base_path = $http . $_SERVER['HTTP_HOST'];
    // $base_file = $_SERVER['PHP_SELF'];
    // $base_file = explode('/', $base_file);
    // if ( ($key = array_search('index.php', $base_file)) !== false) {
    //     unset($base_file[$key]);
    // }
    // $folder_link = join('/', $base_file) . '/';
    // return $base_path . $folder_link . $path;
    //
    //////////////////////////////////////////////////////////////////////////////////
    ////////////////////////DO NOT DELETE - FOR TESTING & REFERENCES//////////////////
    //////////////////////////////////////////////////////////////////////////////////


}
/***************************************************************
 ***************************************************************/
function dbase()
{
    return getcwd();
}

/***************************************************************
 ***************************************************************/
function external_url_exist($url)
{
    $c_url = $url;
    $file = 'http://' . $c_url;
    $file_headers = @get_headers($file);
    if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        return $exists = false;
    }
    else {
        return $exists = true;
    }
}

/***************************************************************
 ***************************************************************/
function jsonp_is_valid_callback($subject)
{
    $identifier_syntax
        = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

    $reserved_words = array(
        'break', 'do', 'instanceof', 'typeof', 'case',
        'else', 'new', 'var', 'catch', 'finally', 'return', 'void', 'continue',
        'for', 'switch', 'while', 'debugger', 'function', 'this', 'with',
        'default', 'if', 'throw', 'delete', 'in', 'try', 'class', 'enum',
        'extends', 'super', 'const', 'export', 'import', 'implements', 'let',
        'private', 'public', 'yield', 'interface', 'package', 'protected',
        'static', 'null', 'true', 'false'
    );

    return preg_match($identifier_syntax, $subject)
        && !in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
}


function action_check($action_method){
    if($_SERVER["REQUEST_METHOD"] !== strtoupper($action_method)){
        return false;
    }else{
        return true;
    }
}

/**
 * Suppose, you are browsing in your localhost
 * http://localhost/myproject/index.php?id=8
 */
 function getBaseUrl()
 {
     // output: /myproject/index.php
     $currentPath = $_SERVER['PHP_SELF'];

     // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
     $pathInfo = pathinfo($currentPath);

     // output: localhost
     $hostName = $_SERVER['HTTP_HOST'];

     // output: http://
     $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

     // return: http://localhost/myproject/
     return $protocol.'://'.$hostName.$pathInfo['dirname']."/";
 }
