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


/***************************************************************
 ***************************************************************/
function sytime($format = "h:i:sa")
{
    $time = date($format);
    return $time;
}
/***************************************************************
 ***************************************************************/
function sydate($format = "Y-m-d")
{
    $t = time();
    $date = date($format, $t);
    return $date;
}
/***************************************************************
 ***************************************************************/
function isDate($month, $day, $year)
{
    return checkdate($month, $day, $year);
}
/***************************************************************
 ***************************************************************/
function current_time()
{
    return mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y"));
}
/***************************************************************
 ***************************************************************/
function current_date()
{
    return date("jS F Y H:i A", current_time());
}
/***************************************************************
 ***************************************************************/
function now()
{
    return date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
}
/***************************************************************
 ***************************************************************/
function get_localtime()
{
    return localtime(time(), true);
}
/***************************************************************
 ***************************************************************/
function word_month($number)
{
    if ($number >= 1 && $number <= 12) :
        $string = '';
    switch ($number) {
        case 1 :
            $string = 'January';
            break;
        case 2 :
            $string = 'February';
            break;
        case 3 :
            $string = 'March';
            break;
        case 4 :
            $string = 'April';
            break;
        case 5 :
            $string = 'May';
            break;
        case 6 :
            $string = 'June';
            break;
        case 7 :
            $string = 'July';
            break;
        case 8 :
            $string = 'August';
            break;
        case 9 :
            $string = 'September';
            break;
        case 10 :
            $string = 'October';
            break;
        case 11 :
            $string = 'November';
            break;
        case 12 :
            $string = 'December';
            break;
    }
    return $string;
    endif;
}
/***************************************************************
 ***************************************************************/
/*
function add_to_date($date, $addValue, $days, $months, $years, $hours, $minutes, $seconds){
    $date=date_create($date);
    date_add($date,date_interval_create_from_date_string($addValue.' '));
    return date_format($date,"Y-m-d");
}*/
/***************************************************************
 ***************************************************************/
function add_to_date($date, $opt = array(), $format = "Y-m-d")
{
    $keywords = array('day', 'month', 'year', 'hour', 'minutes', 'seconds');

    $add_string = '';

    if ($date != null) :
        if (isset($opt['key'])) {
        switch ($opt['key']) {
            case 'day' :
                $add_string .= $opt['key'] . ' days ';
                break;
            case 'month' :
                $add_string .= $opt['key'] . ' months ';
                break;
            case 'year' :
                $add_string .= $opt['key'] . ' years ';
                break;
            case 'hours' :
                $add_string .= $opt['key'] . ' hours ';
                break;
            case 'minutes' :
                $add_string .= $opt['key'] . ' minutes ';
                break;
            case 'seconds' :
                $add_string .= $opt['key'] . ' seconds ';
                break;
        }
    }

    date_add($date, date_interval_create_from_date_string($add_string));
    return date_format($date, $format);
    endif;
}
/***************************************************************
 ***************************************************************/
function substract_to_date($date, $opt = array(), $format = "Y-m-d")
{
    $keywords = array('day', 'month', 'year', 'hour', 'minutes', 'seconds');

    $add_string = '';

    if ($date != null) :
        if (isset($opt['key'])) {
        switch ($opt['key']) {
            case 'day' :
                $add_string .= $opt['key'] . ' days ';
                break;
            case 'month' :
                $add_string .= $opt['key'] . ' months ';
                break;
            case 'year' :
                $add_string .= $opt['key'] . ' years ';
                break;
            case 'hours' :
                $add_string .= $opt['key'] . ' hours ';
                break;
            case 'minutes' :
                $add_string .= $opt['key'] . ' minutes ';
                break;
            case 'seconds' :
                $add_string .= $opt['key'] . ' seconds ';
                break;
        }
    }

    date_sub($date, date_interval_create_from_date_string($add_string));
    return date_format($date, $format);
    endif;
}
/***************************************************************
 ***************************************************************/
function change_date_format($input_date, $rformat, $add_string)
{
    $date = date_create_from_format($input_format, $input_date);
    return date_format($date, $rformat);
}
/***************************************************************
 ***************************************************************/
function format_date($idate, $format = "Y/m/d H:i:s")
{
    $date = date_create($idate);
    return date_format($date, $format);
}
/***************************************************************
 ***************************************************************/
function date_difference($setdate1, $setdate2)
{
    $setdate1 = format_date($setdate1, "Y/m/d");
    $setdate2 = format_date($setdate2, "Y/m/d");
    $date1 = date_create($setdate1);
    $date2 = date_create($setdate2);
    $diff = date_diff($date1, $date2, TRUE);
    return $diff->format("%R%a days");
}
/***************************************************************
 ***************************************************************/
function date_explode($date)
{
    return date_parse($date);
}
/***************************************************************
 ***************************************************************/
function db_date_function($date, $seprator = ' ')
{
    $struct = array();
    if ($i_date = explode(' ', $date)) {
        $int_date = $i_date[0];
        $int_time = $i_date[1];

        $exp_date = explode('-', $int_date);
        $exp_time = explode(':', $int_time);

        $struct['day'] = $exp_date[2];
        $struct['month'] = $exp_date[1];
        $struct['year'] = $exp_date[0];
        $struct['hours'] = $exp_time[2];
        $struct['minutes'] = $exp_time[1];
        $struct['seconds'] = $exp_time[0];
        $struct['month_short'] = date('M', strtotime($date));
        $struct['month_full'] = date('F', strtotime($date));


        $struct['day_name'] = date('l', mktime($struct['hours'], $struct['minutes'], $struct['seconds'], $struct['day'], $struct['month'], $struct['year']));
        $struct['other_format1'] = date('l jS \of F Y h:i:s A', strtotime($date));
        $struct['other_format2'] = date(DATE_ATOM, mktime($struct['hours'], $struct['minutes'], $struct['seconds'], $struct['day'], $struct['month'], $struct['year']));
        $struct['other_format3'] = date('M' . $seprator . 'Y', strtotime($date));
        $struct['other_format4'] = date('F' . $seprator . 'Y', strtotime($date));
    }
    else {
        if ($i_date = explode('-', $date)) {
            $struct['day'] = $i_date[2];
            $struct['month'] = $i_date[2];
            $struct['year'] = $i_date[2];
        }

        if ($i_time = explode(':', $date)) {
            $struct['hours'] = $i_time[2];
            $struct['minutes'] = $i_time[2];
            $struct['seconds'] = $i_time[2];
        }
    }

    if (!empty($struct)) {
        return $struct;
    }

}
/***************************************************************
 ***************************************************************/
function db_first_last_day($query_date = '')
{
    $result = array();
    if ($query_date != '') {
        // First day of the month.
        $result['first_day'] = date('Y-m-01', strtotime($query_date));
        $result['first_day_start_time'] = date('Y-m-01 00:00:00', strtotime($query_date));
        $result['first_day_end_time'] = date('Y-m-01 23:00:00', strtotime($query_date));
        // Last day of the month.
        $result['last_day'] = date('Y-m-t', strtotime($query_date));
        $result['last_day_start_time'] = date('Y-m-t 00:00:00', strtotime($query_date));
        $result['last_day_end_time'] = date('Y-m-t 23:00:00', strtotime($query_date));
    }
    else {
        $query_date = date('Y-m-d H:i:s');
        // First day of the month.
        $result['first_day'] = date('Y-m-01', strtotime($query_date));
        $result['first_day_start_time'] = date('Y-m-01 00:00:00', strtotime($query_date));
        $result['first_day_end_time'] = date('Y-m-01 23:00:00', strtotime($query_date));
        // Last day of the month.
        $result['last_day'] = date('Y-m-t', strtotime($query_date));
        $result['last_day_start_time'] = date('Y-m-t 00:00:00', strtotime($query_date));
        $result['last_day_end_time'] = date('Y-m-t 23:00:00', strtotime($query_date));
    }
    return $result;
}
/***************************************************************
 ***************************************************************/
function db_today()
{
    $today = array();
    $today['start_time'] = date('Y-m-d 00:00:00');
    $today['end_time'] = date('Y-m-d 23:00:00');
    return $today;
}
/***************************************************************
 ***************************************************************/
function db_add_sub_date($number)
{
    $day = array();
    $day['yesterday'] = date('Y-m-d H:i:s', strtotime("{$number} days"));
    $day['start_time'] = date('Y-m-d 00:00:00', strtotime("{$number} days"));
    $day['end_time'] = date('Y-m-d 23:00:00', strtotime("{$number} days"));
    return $day;
}
/***************************************************************
 ***************************************************************/
function db_time_diff($date1, $date2)
{
    $t1 = strtotime($date1);
    $t2 = strtotime($date2);
    $differenceInSeconds = $t2 - $t1;
    $differenceInHours = $differenceInSeconds / 3600;
}
/***************************************************************
 ***************************************************************/
function timeFunction($string, $format = '')
{
    switch ($format) {
        case 'date' :
            return date('Y-m-d', strtotime($string));
            break;

        case 'time' :
            return date('h:i:s', strtotime($string));
            break;

        default :
            return date('Y-m-d h:i:s', strtotime($string));
    }
}
/***************************************************************
***************************************************************/



