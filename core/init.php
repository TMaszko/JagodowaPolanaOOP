<?php
session_start();
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

require_once 'functions/sanitize.php';