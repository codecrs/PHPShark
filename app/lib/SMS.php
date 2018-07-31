<?php 
namespace core\lib\sms {
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
	
	final class SMS
	{
		private $_rs;
		public function __construct()
		{
		}

		/***************************************************************
         ***************************************************************/
		public function checkCurl()
		{
			if (!function_exists('curl_version')) {
				die("PHP must have cURL support for this script. 
                Ask hosting for it as it should be a 100% available library.");
			}
		}

		/***************************************************************
         ***************************************************************/
		public function get_option($key)
		{
			$db = new Database;
			$this->_rs = $db->Select([
				'columns' => 'value',
				'from' => 'settings',
				'where' => 'name = ' . $key,
			]);
			return $this->_rs;
		}

		/**
         * Send SMS
         * $gateway = clickatell / experttexting / twilio
         */
		public function send_SMS($number, $message, $gateway = 'twilio', $from = '+13476964579')
		{
			$this->checkCurl();
			$from = urlencode(trim($from));
			$text = urlencode(trim($message));
			$to = urlencode(trim($number));

			if ($gateway == 'clickatell') :
			$baseurl = "http://api.clickatell.com";
			$request_SMS = $baseurl . '/http/sendmsg?user=' . $this->get_option('api_user') . '&password=' . $this->get_option('api_password') . '';
			$request_SMS .= '&api_id=' . $this->get_option('api_id');
			$request_SMS .= '&to=' . $to . '&text=' . $text; elseif ($gateway == 'experttexting') :
			$request_SMS = 'https://www.experttexting.com/exptapi/exptsms.asmx/SendSMS?';
			$request_SMS .= 'UserID=' . $this->get_option('api_user') . '&PWD=' . $this->get_option('api_password') . '&';
			$request_SMS .= 'APIKEY=' . $this->get_option('api_id') . '&FROM=DEFAULT&TO=' . $to . '&MSG=' . $text; elseif ($gateway == 'twilio') :

			//open connection
			$ch = curl_init();

			/*
                //Lje0H3u2iV296HFp490yvybPjKZyODQsv1hxxl14
              //set the url, number of POST vars, POST data
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_USERPWD, $this->get_option('api_id') . ':' . $this->get_option('api_password'));
              curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
              curl_setopt($ch, CURLOPT_URL, sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', $this->get_option('api_id')));
              curl_setopt($ch, CURLOPT_POST, 3);
              curl_setopt($ch, CURLOPT_POSTFIELDS, 'To=' . $to . '&From=' . $from . '&Body=' . $text); */

			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERPWD, 'AC8366ea2bcb01b70ece3ba4eef720a396' . ':' . '528beb0547af39f0d784738dd971eaf4');
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_URL, sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', 'AC8366ea2bcb01b70ece3ba4eef720a396'));
			curl_setopt($ch, CURLOPT_POST, 3);
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'To=' . $to . '&From=' . $from . '&Body=' . $text); 

			//execute post
			$result = curl_exec($ch);
			$result = json_decode($result);

			//close connection
			curl_close($ch);

			if ($result) {
				if (!isset($result->message) AND isset($result->sid))
					var_dump("Sent");
				else
					var_dump($result->message);
			}
			else {
				$result = 'cURL request Failed';
			}
			endif;

			if (isset($request_SMS)) :
			$log = file_get_contents($request_SMS);
			return $log;
			endif;
		}
	}
}