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

use core\orm\mysql as mysql;
use core\lib\files as files;
use core\lib\imgs as imgs;
use core\lib\errors as errs;
use core\lib\forms as forms;
use core\lib\utilities as utils;
use core\lib\json as json;
use core\lib\pages as pages;
use \orm as orm;
use \crud as crud;

/***************************************************************
Description: extends the functions from \core\Model
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
$this->template  = new core\Template();
$this->warehouse = new wh\Warehouse();
$this->slim      = new slim\Slim();
$this->json      = new json\Json();
$this->sms       = new smsCon\SMS();
$this->form      = new forms\Form();
$this->validator = new val\Validator(new errhndlr\ErrorHandler);
$this->phpMailer = new PHPMailer;
***************************************************************/
class AppModel extends \core\Model{
	public $files = array();
	public $request;
	public $post;
	public $get;
	public $data;
	public $variable;
	public $buffer;
	public $image;
	public $upload;
	public $template;
	public $json;
	public $form;
	public $validator;
	public $phpMailer;
	public $paginate;
	public $excel;
	public $excelPlug;
	public $query;
	public $token; 
	public $phpform;
	public $db;
	public $pdo;
	public $session;
	/***************************************************************
Description: 
***************************************************************/
	public function __construct(){
		parent::__construct();
		require 'vendors/php-mailer/PHPMailerAutoload.php';
		$this->buffer    = new perform\Buffer();
		$this->cookie    = new Cookie(utils\Config::get('remember/cookie_expiry'));
		$this->files     = new files\Files();
		$this->image     = new imgs\Image();
		$this->upload    = new files\Upload();
		$this->template  = new core\Template();
		$this->json      = new json\Json();
		$this->form      = new forms\Form();
		$this->validator = new forms\Validator(new errs\ErrorHandler);
		$this->phpMailer = new PHPMailer;
		$this->paginate  = new pages\pagination();
		$this->excel     = new files\Excel;
		$this->query     = new orm\Query;
		$this->token     = utils\Token::init();
		$this->pdo       = pdo();
		$this->db		 = new crud\db();
		\Session::init();
	}

	/***************************************************************
Description:  redirect($link = null) function is a function inside 
the model method, which redirects the link to another link.
***************************************************************/
	protected function redirect($link = null){
		$url = url($link);
		header('location:'. $url);
	}

	protected function get_session_by_name(string $name){
		return Session::get($name);
	}

	/***************************************************************
Description: setSessions($session_arr = array()) function is used to 
set the session variables for the login and runtime activity of the 
application.
***************************************************************/
	protected function setSessionsByArray(array $session_arr){
		foreach($session_arr as $key => $value){
			Session::set($key,$value);
		}
	}

	/***************************************************************
Description: phpMailer() function inside the controller method 
provides the settings to the 3rd party plugin integrated into QMVC 
for Mailing into an external Email ID. 

All corresponding settings are available at settings.xml level in the project folder. 
***************************************************************/
	protected function phpMailer($addr = null, $type = null){    
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
Description: 
		***************************************************************/
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

		//=============================
		// Model List Functions
		//=============================
		protected function getlist(string $table){
			$ret_model_seleted = $this->select([
				'columns' => '*',
				'from' => $table
			]);
			return $ret_model_seleted;
		}

		protected function getlist_by_id(string $table,string $idFieldName, string $id){
			$ret_model_seleted = $this->select([
				'columns' => '*',
				'from' => $table,
				'where' => "{$idFieldName} = {$id}"
			]);
			return $ret_model_seleted[0];
		}

		protected function xhr_getlist(string $table){
			$ret_model_seleted = $this->select([
				'columns' => '*',
				'from' => $table
			]);
			echo json_encode($ret_model_seleted);
		}

		protected function xhr_getlist_by_id(string $table, string $idFieldName, string $id){
			$ret_model_seleted = $this->select([
				'columns' => '*',
				'from' => $table,
				'where' => "{$idFieldName} = {$id}"
			]);
			echo json_encode($ret_model_seleted[0]);
		}

		protected function push(string $into, array $fieldListArray){
			$id = $this->insert([
				'into' => $into,
				'values' => $fieldListArray,
			]);
			return $id;
		}

		protected function change(string $into, array $setArray, string $where){
			$upd = $this->update([
				'into' => $into,
				'set' => $setArray,
				'where' => $where
			]);
			return $upd;
		}

		protected function remove(string $table, string $whereCondition){
			$del = $this->delete([
				'from' => $table,
				'where' => $whereCondition
			]);
			return $del;
		}
	
}
