<?php
require_once 'core/init.php';
protect_page_notLogin();
if(Input::exists()){
  if(Token::check(Input::get('token'))){
    $validate = new Validate();
    $validation = $validate->check($_POST,array(
      'password_current' => array(
        'required' => true,
        'min' => 6
      ),
      'new_password' => array(
        'required' => true,
        'min' => 6
      ),
      'new_password_again' => array(
        'required' => true,
        'min' => 6,
        'matches' => 'new_password'
      )
    ));


    if($validation->passed()){
      $user = new User();
      if($user->data()->password !== Hash::make(Input::get('password_current'),$user->data()->salt)){
        echo 'Your current password is wrong';
      } else {
        $salt = Hash::salt(32);
        $user->update(array(
          'password' => Hash::make(Input::get('new_password'),$salt),
          'salt' => $salt
        ));
        Session::flash('home','Your password has been changed!');
        Redirect::to('index.php');
      }
    } else{
      foreach($validation->errors() as $error){
        echo $error,'<br>';
      }
    }
  }
}

?>
<form action="" method="post">
  <div class="field">
    <label for="password_current">Current password</label>
    <input type="password" name="password_current" id="password">
  </div>
  <div class="field">
    <label for="new_password">Enter a new password</label>
    <input type="password" name="new_password" id="new_password">
  </div>
  <div class="field">
    <label for="new_password_again">Enter a new password again</label>
    <input type="password" name="new_password_again" id="new_password_again">
  </div>
  <input type="hidden" name="token" value="<?php echo Token::generate();?>">
  <input type="submit" value="Change password">
</form>
