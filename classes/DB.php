<?php
class DB {
    private static $_instance = null;
    
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0,
            $_options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
); 
    private function __construct(){
        try{
           $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'),
            $this->_options); 
            
        }   catch(PDOExcpetion $e){
            die($e->getMessage());
        }
        
        
    }     
    public static function getInstance(){
        if(!isset($_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
}