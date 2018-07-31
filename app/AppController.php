<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class AppController extends \core\Controller{
	public $request;
	public $cookie;
	public $phpMailer;
	public $paginate;
	public $excel;
	/***************************************************************
Description: extends the functions from \app\Controller
methods available to us in controller class are as follows: 

$this->request     
$this->cookie     
$this->phpMailer
***************************************************************/
	public function __construct(){
		parent::__construct();
		$this->request   = new utils\Request();
		$this->cookie    = new Cookie(utils\Config::get('remember/cookie_expiry'));
		$this->phpMailer = new PHPMailer;  
		$this->paginate  = new pages\pagination();
		\Session::init();
	}    

	/***************************************************************
Description: ifCookieIsEnabled() function inside the controller function 
checks if the cookie is enabled.
***************************************************************/
	protected function ifCookieIsEnabled(){
		return $this->cookie->isCookieEnabled();
	}

	/***************************************************************
Description: isLoggable() function is designed for an exclusive use 
to check if the cookie exist. It is a callback function in controller
in which we pass the model name where we are setting up the cookies. 
This function is used when we are logging into a system. 
***************************************************************/
	protected function isLoggable(string $model_function){
		if(!empty($_COOKIE)){
			$this->model->{$model_function}($_COOKIE);
		} else {
			return false;
		}
	}

	/***************************************************************
Description: redirect($link = null) function is a function inside 
the controller method, which redirects the link to another link.
***************************************************************/
	protected function redirect(string $link = null){
		$url = url($link);
		header('location:'. $url);
	}

	/***************************************************************
Description: phpMailer() function inside the controller method 
provides the settings to the 3rd party plugin integrated into QMVC 
for Mailing into an external Email ID. 

All corresponding settings are available at settings.xml level in the project folder. 
***************************************************************/
	protected function phpMailer(string $addr = null, string $type = null){    
		try {
			$mail = $this->phpMailer;
			// Enable verbose debug output
			//$mail->SMTPDebug = 3;  
			// Set mailer to use SMTP
			$mail->isSMTP();
			// Specify main and backup SMTP servers
			$mail->Host = utils\Config::get('mail/mail_host');    
			// Enable SMTP authentication
			$mail->SMTPAuth = utils\Config::get('mail/mail_smtp_auth');  
			// SMTP username
			$mail->Username = utils\Config::get('mail/mail_user');   
			// SMTP password
			$mail->Password = utils\Config::get('mail/mail_password');  
			// Enable TLS encryption, `ssl` also accepted
			$mail->SMTPSecure = utils\Config::get('mail/mail_smtp_secure');  
			// TCP port to connect to
			$mail->Port = utils\Config::get('mail/mail_port');                                    
			$mail->setFrom(utils\Config::get('mail/mail_set_from'), utils\Config::get('mail/mail_set_from_type'));
			// // Add a recipient
			if($addr == null){
				$addr = utils\Config::get('mail/mail_add_address');
			}
			if($type == null){
				$type = utils\Config::get('mail/mail_recipient_name');
			}
			$mail->addAddress($addr, $type);  
			// Set email format to HTML
			$mail->isHTML(utils\Config::get('mail/mail_is_html'));                                      
			return $mail;
		}catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}

		/***************************************************************
Description: Sets the Title at controller level.
		***************************************************************/
		protected function title($name){
			return trim($name);
		}
		/***************************************************************
Description: Output the JSON format at controller level.
		***************************************************************/
		protected function json_out($data, $variable = null){
			//JSON_PRETTY_PRINT
			$jsonData = json_encode($data);
			if ($variable == null) {
				echo '<script>';
				echo $jsonData;
				echo '</script>';
			}
			else {
				echo '<script>';
				echo "var {$variable} = {$jsonData};";
				echo '</script>';
			}
		}

		//=============================
		// URL/URI FUNCTIONS
		//=============================

		protected function getUrlValues(){
			if (REQUEST == 'GET' && !empty(ROUTE)) {
				$result = explode('&', ROUTE);
				unset($result[0]);
				$result = array_values($result);
				return $result;
			}
			elseif (REQUEST == 'POST') {
				$result = $_POST;
				return $result;
			}
			else {
				return NULL;
			}
		}

		//************************
		//COMMON PAGE FUNCTIONS
		//************************

		//Page Base
		protected function setBase(string $base){
			$base = trim($base);
			$lbase = lcfirst($base);
			$this->_base = $lbase;
		}

		protected function getView(string $viewname){
			$this->view->render($viewname);
		}

		protected function getModel(){
			$param = array();
			if (func_num_args() > 1) {
				for ($i = 1; $i <= (func_num_args() - 1); $i++) {
					array_push($param, func_get_arg($i));
				}
				$this->model->{func_get_arg(0)}($param);
			}
			else {
				$this->model->{func_get_arg(0)}();
			}
		}

		protected function get_list(string $table){
			return $this->model->model_get_list($table);
		}

		protected function get_list_by_id(string $table, string $idFieldName, string $id){
			return $this->model->model_get_list_by_id($table, $idFieldName, $id);
		}
}
