<?php
session_start();
require_once 'functions/sanitize.php';
require_once 'functions/protect.php';
$GLOBALS['config'] = array( //Creates global config array
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'JagodowaPolanaOOP'
    ),
    
    'remember' => array(
        'cookie_name'   => 'hash',
        'cookie_expiry' => 604800 // in seconds
    ),
    
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

spl_autoload_register(function($class){
   require_once 'classes/' . $class . '.php'; 
});

if(Cookie::exists(Config::get('remember/cookie_name'))
   && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session',array('hash','=', $hash));
    
    if($hashCheck->count()){
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}