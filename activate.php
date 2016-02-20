<?php
require_once 'core/init.php';
if(Input::exists('get')){
  if(isset($_GET['email']) && isset($_GET['email_code'])){
    $email = Input::get('email');
    $email_code = Input::get('email_code');
    $check = DB::getInstance()->get('users',array(array('email','active'),'=',array($email,0),'AND'));
    if(!$check->count()){

      ?>

      <h1>Oops.... something went wrong!</h1>

    <?php

    } else if($check->first()->email_code == $email_code){

      DB::getInstance()->update('users',$check->first()->id,array('active'=> 1));

      ?>

      <h1>Huraayyy! You can now log in!</h1>
      <?php

    }
  }
}



?>
