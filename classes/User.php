<?php
class User{
    private $_db,
            $_data,
            $_sessionName,
            $_isLoggedIn,
            $_recoveredPass,
            $_cookieName;

    public function __construct($user = null){

        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if(!$user){
            if(Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);
            }
            if($this->find($user)){
                $this->_isLoggedIn = true;
                if($this->data()->password_recover == 1){
                  $this->_recoveredPass = 1;
                }
            } else {
                //process logout
            }
        } else {
            $this->find($user);
            if($this->data()->password_recover == 1){
              $this->_recoveredPass = 1;
            }
        }

    }

    public function create($fields = array()){

        if(!$this->_db->insert('users',$fields)){
            throw new Exception('There was a problem creatiing your account');
        }
    }

    public function find($user = null){
        if($user){
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=',$user));
            if($data->count()){
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    public function update($fields=array(),$id = null){
      if(!$id && $this->isLoggedIn()){
        $id = $this->data()->id;
      }

      if(!$this->_db->update('users',$id,$fields)){
        throw new Exception('There was a problem with updating your account.');
      }

    }
    public function login($username = null,$password = null, $remember = false){
        if(!$username && !$password && $this->exists()){
            Session::put($this->_sessionName,$this->data()->id);



        }else {
            $user = $this->find($username);
            if($user){
                if($this->data()->password === Hash::make($password, $this->data()->salt)){
                    Session::put($this->_sessionName,$this->data()->id);
                    if($remember){
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session',array('user_id','=',$this->data()->id));

                        if($hashCheck->count() === 0){
                            $this->_db->insert('users_session',array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash


                            ));
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookieName,$hash,Config::get('remember/cookie_expiry'));
                    }


                    return true;
                }

            }
        }

        return false;
    }

    public function recovery($type,$email){
      ($type != 'username') ? $typeMessage = 'password': $typeMessage = 'username';
      if($typeMessage == 'password'){
        $salt = Hash::salt(32);
        $hash = Hash::unique();
        $password = substr($hash,1,8);
        $password_db = Hash::make($password,$salt);
        Mail::email($email,'Hello'. $this->data()->username.'!','<br>
        Here is your forgotten '. $typeMessage .': '. $password);
        $this->update(array('password'=>$password_db,'salt'=>$salt,'password_recover' => 1),$this->data()->id);
      } else if($typeMessage == 'username') {
          Mail::email($email,'Hello'. Input::get('username').'!','<br>
          Here is your forgotten '. $typeMessage .': '.$this->data()->username);
      }
    }


    public function exists(){
        return (!empty($this->_data)) ? true : false;
    }

    public function logout(){
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
        $this->_db->delete('users_session', array('user_id', '=',$this->data()->id));
    }

    public function data(){
        return $this->_data;
    }

    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }
    public function isRecoveredPass(){
      return $this->_recoveredPass;
    }

}
