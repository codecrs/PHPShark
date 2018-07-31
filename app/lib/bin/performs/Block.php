<?php
namespace perform{
    class Block{
        public static function IP(){
            if (!file_exists('blocked_ips.txt')) {
                $deny_ips = array(
                 '127.0.0.1',
                 '192.168.1.1',
                 '83.76.27.9',
                 '192.168.1.163'
                );
            } else {
                $deny_ips = file('blocked_ips.txt');
            }
               // read user ip adress:
               $ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';
                
               // search current IP in $deny_ips array
            if ((array_search($ip, $deny_ips))!== false) {
                // address is blocked:
                echo "Your IP adress ('{$ip}') was blocked!";
                exit;
            }
        }
    }
}
