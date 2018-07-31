<?php
namespace perform{
    class Validate{
        public static function email($email){
            $check = 0;
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check = 1;
            }
            return $check;
        }

        public static function url($url){
            $check = 0;
            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                  $check = 1;
            }
            return $check;
        }
    }
}
