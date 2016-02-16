<?php
function protect_page_login(){
    $user = new User();
    if($user->isLoggedIn()){
      Redirect::to('index.php');
      exit();
    }
}
function protect_page_notLogin(){
  $user = new User();
  if(!$user->isLoggedIn()){
    Redirect::to('protect.php');
    exit();
  }
}
