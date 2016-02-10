<?php
class Config {
    public static function get($path = null){
        if($path){
            $config = $GLOBALS['config'];
            $path = explode('/',$path);
             // loop through elements of $path i.e 'session/session_name 'session' is array it's like going deeper in array ^^   
            foreach($path as $bit){
                if(isset($config[$bit])) 
                    $config = $config[$bit];
            }
            return $config;
                
        }
    }
}