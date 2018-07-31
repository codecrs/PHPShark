<?php

namespace perform{
	class File{
		public static function remote_file_size($url, $user = "", $pw = ""){
			ob_start();
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_NOBODY, 1);
		
			if(!empty($user) && !empty($pw))
			{
				$headers = array('Authorization: Basic ' .  base64_encode("$user:$pw"));
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}
		
			$ok = curl_exec($ch);
			curl_close($ch);
			$head = ob_get_contents();
			ob_end_clean();
		
			$regex = '/Content-Length:\s([0-9].+?)\s/';
			$count = preg_match($regex, $head, $matches);
		
			return isset($matches[1]) ? $matches[1] : "unknown";
		}
	}
}
