<?php 
namespace core {
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


	final class Template{
		/***************************************************************
Description: get_template($name) is used for templating the mail body.
		 ***************************************************************/
		public function get_template(string $loc, string $name){
			$loc = str_replace(".",DS, trim($loc));
			$msg = file_get_contents(TEMPLATE_PATH.$loc.DS."tpl_{$name}.php");
			return $msg;
		}
		/***************************************************************
Description: DEMO (internal use) - under testing
Probably Depricated after 3rd party plugin.
		 ***************************************************************/
		public function mail_header(array $array){
			if (!empty($array)) {
				$header = '';
				$from = '';
				$bcc = '';
				$cc = '';

				foreach ($array as $key => $value) {
					switch (strtolower($key)) {
						case 'from' :
							$from = $value;
							break;

						case 'cc' :
							if (is_array($value)) {
								$email = '';
								foreach ($value as $email) {
									$cc .= $email.'; ';
								}
							}
							else {
								$cc .= $value.'; ';
							}
							break;

						case 'bcc' :
							if (is_array($value)) {
								$email = '';
								foreach ($value as $email) {
									$bcc .= $email.'; ';
								}
							}
							else {
								$bcc .= $value.'; ';
							}
							break;
					}
				}

				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				// More headers
				if ($from != '') {
					$headers .= 'From: '.$from."\r\n";
				}
				else {
					echo 'error';
				}
				if ($cc != '') {
					$headers .= 'Cc: '.$cc."\r\n";
				}

				if ($bcc != '') {
					$headers .= 'Bcc: '.$bcc."\r\n";
				}
			}
			return $headers;
		}
		/***************************************************************
Description: DEMO (internal use) - under testing 
Probably Depricated after 3rd party plugin.
		 ***************************************************************/
		public function send_mail(array $array){
			$to = '';
			$subject = '';
			$header = '';
			$message = '';

			//Check Required Keywords
			if (array_key_exists('to', $array) == false) {
				echo 'error';
				exit;
			}

			if (array_key_exists('subject', $array) == false) {
				echo 'error';
				exit;
			}

			if (array_key_exists('message', $array) == false) {
				echo 'error';
				exit;
			}

			if (array_key_exists('header', $array) == false) {
				echo 'error';
				exit;
			}

			foreach ($array as $key => $value) {
				switch (strtolower($key)) {
					case 'to' :
						if (is_array($value)) {
							$email = '';
							foreach ($value as $email) {
								$to .= $email.'; ';
							}
						}
						else {
							$to .= $value.'; ';
						}
						break;
					case 'subject' :
						$subject = $value;
						break;
					case 'header' :
						$header = $value;
						break;
					case 'message' :
						$message = $value;
						break;

				}
			}
			if ($to != '') {
				$i_to = $to;
			}
			else {
				echo 'error';
				exit;
			}

			if ($subject != '') {
				$i_subject = $subject;
			}
			else {
				echo 'error';
				exit;
			}

			if ($header != '') {
				$i_header = $header;
			}
			else {
				echo 'error';
				exit;
			}

			if ($message != '') {
				$i_message = $message;
			}
			else {
				echo 'error';
				exit;
			}

			try {
				mail($i_to, $i_subject, $i_message, $i_header);
			} catch (exception $e) {
				var_dump($e);
			}
		}
	}
}