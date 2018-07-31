<?php 
namespace plugin {
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

	use core\orm\mysql as mysql;
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \PDO;

	class AppPlugin extends \AppModel
	{
		/***************************************************************
Description: extends the functions from \core\Request
methods available to us in model class are as follows: 

require 'vendors/php-mailer/PHPMailerAutoload.php';
$this->request   = new utils\Request();
$this->variable  = new utils\Variable($this->request);
$this->data      = $this->variable->getViewVariables();
$this->buffer    = new utils\Buffer();
$this->cookie    = new utils\Cookie(utils\Config::get('remember/cookie_expiry'));
$this->files     = new files\Files();
$this->image     = new imageUtils\Image();
$this->upload    = new fupload\Upload();
$this->session   = new utils\Session();
$this->template  = new app\Template();
$this->warehouse = new wh\Warehouse();
$this->slim      = new slim\Slim();
$this->json      = new json\Json();
$this->sms       = new smsCon\SMS();
$this->form      = new forms\Form();
$this->validator = new val\Validator(new errhndlr\ErrorHandler);
$this->phpMailer = new PHPMailer;
         ***************************************************************/
		public function __construct()
		{
			parent::__construct();
		}
	}
}