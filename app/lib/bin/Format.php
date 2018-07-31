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

function currency_format($amount, $decimals = null, $decimalpoint = null, $separator = null)
{

    if ($decimals == null) {
        $decimals = \app\utilities\Config::get('currency/decimal');
    }
    if ($decimalpoint == null) {
        $decimalpoint = \app\utilities\Config::get('currency/decimal_places');
    }
    if ($separator == null) {
        $separator = \app\utilities\Config::get('currency/separator');
    }

    return number_format($amount, $decimals, $decimalpoint, $separator);
}