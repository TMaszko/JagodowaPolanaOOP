<?php
require_once 'core/init.php';
$mode_allowed =array('username','password');
if(Input::exists('get') && in_array(Input::get('mode'),$mode_allowed)){
  $mode = Input::get('mode');
  if(Input::exists()){
    if(Token::check(Input::get('token'))){
      $validate = new Validate();
      $validation = $validate->check($_POST,array(
        'email' => array('required' => true)
      ));
      if($validation->passed()){
        $email = Input::get('email');
        $check =DB::getInstance()->get('users',array('email','=',$email));
        if($check->count()){

            $user = new User($check->first()->id);
            $user->recovery($mode,$email);
            ($mode != 'username') ? $modeMessage = 'password': $modeMessage = 'username';
            if($modeMessage == 'password'){
              Session::flash('home','We\'ve sent an email with your forgotten ' . $modeMessage . ' ! <br>
              Please change your password after log in');
              Redirect::to('index.php');
            } else if($modeMessage =='username'){
              Session::flash('home','We\'ve sent an email with your forgotten ' . $modeMessage . ' !');
            }
          } else {
            echo 'This email doesn\'t exist!';
          }
      } else{
        foreach ($validation->errors as $error) {
          echo $error,'<br>';
        }
      }
    }
  }
  ?>
  <form action="" method="post">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <label> Please enter your email
      <input type="email" name="email" value="<?php echo Input::get('email');?>">
    </label>
    <input type="submit" value="Recover">
  </form>

  <?php
  } else {

    Redirect::to(404);

  ?>

  <?php
  }
?>
