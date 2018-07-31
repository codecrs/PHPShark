<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
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

 
use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\utilities as utils;
use core\lib\json as json;
use core\lib\pages as pages;

function filter($data)
{ //Filters data against security risks.
    if (is_array($data)) {
        foreach ($data as $key => $element) {
            $data[$key] = filter($element);
        }
    }
    else {
        $data = trim(htmlentities(strip_tags($data)));
        if (get_magic_quotes_gpc()) $data = stripslashes($data);
        $data = mysql_real_escape_string($data);
    }
    return $data;
}


/**************
*@length - length of random string (must be a multiple of 2)
**************/
function readable_random_string($length = 6){
    $conso=array("b","c","d","f","g","h","j","k","l",
    "m","n","p","r","s","t","v","w","x","y","z");
    $vocal=array("a","e","i","o","u");
    $password="";
    srand ((double)microtime()*1000000);
    $max = $length/2;
        for($i=1; $i<=$max; $i++){
            $password.=$conso[rand(0,19)];
            $password.=$vocal[rand(0,4)];
        }
        return $password;
}

/*************
*@l - length of random string
*/
function generate_rand($l){
    $c= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    srand((double)microtime()*1000000);
    for($i=0; $i<$l; $i++) {
        $rand.= $c[rand()%strlen($c)];
    }
    return $rand;
}

function encode_email($email='info@domain.com', 
         $linkText='Contact Us', $attrs ='class="emailencoder"' ){
             
	// remplazar aroba y puntos
	$email = str_replace('@', '@', $email);
	$email = str_replace('.', '.', $email);
	$email = str_split($email, 5);

	$linkText = str_replace('@', '@', $linkText);
	$linkText = str_replace('.', '.', $linkText);
	$linkText = str_split($linkText, 5);
	
	$part1 = '<a href="ma';
	$part2 = 'ilto:';
	$part3 = '" '. $attrs .' >';
	$part4 = '</a>';

	$encoded = '<script type="text/javascript">';
	$encoded .= "document.write('$part1');";
	$encoded .= "document.write('$part2');";
	foreach($email as $e)
	{
			$encoded .= "document.write('$e');";
	}
	$encoded .= "document.write('$part3');";
	foreach($linkText as $l)
	{
			$encoded .= "document.write('$l');";
	}
	$encoded .= "document.write('$part4');";
	$encoded .= '</script>';

	return $encoded;
}

function is_valid_email($email, $test_mx = false){
	if(eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
		if($test_mx)
		{
			list($username, $domain) = split("@", $email);
			return getmxrr($domain, $mxrecords);
		}
		else
			return true;
	else
		return false;
}