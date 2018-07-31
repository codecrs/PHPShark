<?php
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

namespace core\orm\mysql {
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    /**
     * QMVC Interface
     *
     */

    interface Execute{
        public static function buildQuery();
    }
}
