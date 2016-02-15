<?php
class Validate{
    private $_passed =false,
            $_errors = array(),
			$_db = null;
    
    
    public function __construct(){
		$this->_db = DB::getInstance();
	}
    
    public function chooseSet($num){
        switch($num){
            case 2:
                $fields = array(
                    'username' => array(
                        'required'	=> true,
                        'min' 		=> 2,
                        'max' 		=> 20,
                        'unique' 	=> 'users' // unique to users table
                ),
                    'password' => array(
                        'required' 	=> true,
                        'min'		=> 6

                ),
                    'password_again'=> array(
                        'required' 	=> true,
                        'matches'  	=> 'password'
                ),
                    'name' => array(
                        'required' 	=> true,
                        'min' 		=> 2,
                        'max' 		=> 50
                ),
                    'phone_num' => array(
                        'required' =>true,
                        'max' > 9
                )
            );
                return $fields;
                break;
            
            case 1:
                $fields = array(
                    'username' => array(
                        'required'	=> true,
                        'min' 		=> 2,
                        'max' 		=> 20,
                        'unique' 	=> 'users' // unique to users table
                ),
                    'password' => array(
                        'required' 	=> true,
                        'min'		=> 6

                ),
                    'password_again'=> array(
                        'required' 	=> true,
                        'matches'  	=> 'password'
                ),
                    'name' => array(
                        'required' 	=> true,
                        'min' 		=> 2,
                        'max' 		=> 50
                ),
                    'phone_num' => array(
                        'max' > 9
                )
            );
                return $fields;
                break;
            default: 
                $fields = array(
                'user_group' => array(
                    'required' => true
                )); 
                return $fields;
                break;
        }    
        
    }
    
    public function check($source, $items=array()){
        foreach($items as $item=>$rules){
             (isset($source[$item]))? $value=$source[$item] : $value = null;
            $item = escape($item);
            
            foreach($rules as $rule=>$rule_value){        
                if($rule === 'required' && empty($value)){
                    $this->addError("{$item} is required");

                } else if(!empty($value)) {
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$item} must be a minimum of {$rule_value} characters");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$item} must be a maximum of {$rule_value} characters");
                            }

                        break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} must match {$item}");
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value ,array($item, '=', $value));
                                if($check ->count()){
                                    $this->addError("{$item} already exists.");
                                }
                        break;
                    }

                }
            }
        }
        if(empty($this->_errors)){
			$this->_passed = true;
		} 
		return $this;
	}
    
    private function addError($error){
		$this->_errors[] = $error;
	}
    
	public function errors(){
		return $this->_errors;
	}
	
	public function passed(){
		return $this->_passed;
	}
                
}