<?php
require_once 'core/init.php';
$user = new User();
if(Input::exists()){
  if(Token::check(Input::get('token'))){
    $validate = new Validate();
    if($user->data()->user_group == 1){
      $validation = $validate->check($_POST,array(
        'name'=> array(
          'required' => true,
          'min' 		=> 2,
          'max' 		=> 50

      ),
        'phone_num' => array(
          'max' => 9

      )

    ));
    }else if($user->data()->user_group == 2){
      $validation = $validate->check($_POST,array(
        'name'=> array(
          'required' => true,
          'min' 		=> 2,
          'max' 		=> 50

        ),
        'phone_num' => array(
          'required' => true,
          'max' => 9

        )

      ));
    }
    if($validation->passed()){
      $user->update(array(
        'name'=> Input::get('name'),
        'phone_num' => Input::get('phone_num')

      ));
      Session::flash('home','Your account was successfully updated!');
      Redirect::to('index.php');

    } else {
      foreach($validation->errors() as $error){
          echo $error,'<br>';
      }
    }
  } else{
    echo 'CSRF attack';
  }
}
?>
<form action="" method="post">
  <div class="field">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name);?>">
  </div>
  <div class="field">
    <label for="phone_num">Phone:</label>
    <input type="text" name="phone_num" id="phone_num" value="<?php echo escape($user->data()->phone_num);?>">
  </div>
  <input type="submit" value="Update">
  <input type="hidden" name="token" value="<?php echo Token::generate();?>">
</form>
