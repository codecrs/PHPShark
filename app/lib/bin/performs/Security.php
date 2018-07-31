<?php

namespace perform{
    class Security{
        public static function inputClean($input){
            if (is_array($input)) {
                foreach ($input as $key => $val) {
                    $output[$key] = clean($val);
                    // $output[$key] = $this->clean($val);
                }
            } else {
                $output = (string) $input;
                // if magic quotes is on then use strip slashes
                if (get_magic_quotes_gpc()) {
                    $output = stripslashes($output);
                }
                // $output = strip_tags($output);
                $output = htmlentities($output, ENT_QUOTES, 'UTF-8');
            }
            // return the clean text
            return $output;
        }
    }
}
