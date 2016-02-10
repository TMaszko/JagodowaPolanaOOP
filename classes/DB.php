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
    
    public function query($sql,$params = array()){
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)){
            $x = 1;
            if (count($params)){
                foreach($params as $param){
                    $this->_query->bindValue($x,$param);
                    $x++;
                }
            }
            if($this->_query->execute()){
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count   = $this->_query->rowCount();
            }
            else{
                $this->_error = true;
            }
        }
        return $this;
    }
    public function action($action,$table,$where=array()){
        if(count($where) === 4){
            $operators = array('=','>','<','>=','<=');
            $tie_kinds = array('OR','AND');
            $field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
            $tie        = $where[3];
            
            if(in_array($operator,$operators) && in_array($tie,$tie_kinds)){
                $sql = "{$action} FROM {$table} WHERE ";
                $x = 1;
                foreach($field as $f){
                    $sql .= " {$f} {$operator} ?";
                    if(count($field)>$x){
                        $sql .= " {$tie} ";
                    }
                    $x++;
                }
                if(!$this->query($sql,array($value))->error()){
					return $this;
                }
            } 
        } else if (count($where) === 3){
			$operators = array('=','>','<','>=','<=');
			
			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
			
			if(in_array($operator,$operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this->query($sql,array($value))->error()){
					return $this;
                }
            }
        }
        return false;
    }

    
}