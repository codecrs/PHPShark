<?php 

namespace perform{
	class SMS{
		public static function send($mobile,$msg){
			$authKey = "XXXXXXXXXXX";
			date_default_timezone_set("Asia/Kolkata");
				$date = strftime("%Y-%m-%d %H:%M:%S");
			//Multiple mobiles numbers separated by comma
				$mobileNumber = $mobile;
			
			//Sender ID,While using route4 sender id should be 6 characters long.
				$senderId = "IKOONK";
			
			//Your message to send, Add URL encoding here.
				$message = urlencode($msg);
			
			//Define route 
				$route = "template";
			//Prepare you post parameters
				$postData = array(
				'authkey' => $authKey,
				'mobiles' => $mobileNumber,
				'message' => $message,
				'sender' => $senderId,
				'route' => $route
			);
			
			//API URL
				$url="https://control.msg91.com/sendhttp.php";
			
			// init the resource
				$ch = curl_init();
				curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $postData
				//,CURLOPT_FOLLOWLOCATION =&gt; true
			));
			
			
			//Ignore SSL certificate verification
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
			
			//get response
				$output = curl_exec($ch);
				
				//Print error if any
				if(curl_errno($ch))
				{
					echo 'error:' . curl_error($ch);
				}
			
				curl_close($ch);
		}
	}
}
