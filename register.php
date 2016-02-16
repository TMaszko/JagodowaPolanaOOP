<?php
require_once 'core/init.php';
protect_page_login();
if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $first_validate = new Validate();
        $validation = $first_validate->check($_POST,$first_validate->chooseSet(0));
        if ($validation->passed()){
            if(isset($_POST["typeOfUser"])){
                if($_POST["typeOfUser"] == "hurtownik"){
                    $validation_hurt = $validate->check($_POST,$validate->chooseSet(2));
                    if($validation_hurt->passed()){
                        $user = new User();
                        $salt = Hash::salt(32);
                        $email_code = Hash::unique();
                        try{

                            $user->create(array(
                                'username'  => Input::get('username'),
                                'password'  => Hash::make(Input::get('password'), $salt),
                                'salt'      => $salt,
                                'email'     => Input::get('email'),
                                'email_code'=> $email_code,
                                'name'      => Input::get('name'),
                                'phone_num' => Input::get('phone_num'),
                                'user_group'=> 2,
                                'joined'    => date('Y-m-d H:i:s')




                            ));
                            Session::flash('home','You\'ve been registered successfully! <br>
                            The activation link has been send to your email!');
                            Mail::email(Input::get('email'),'Hello'. Input::get('username').'!','Here is your activation link! <br> http://localhost/JagodowaPolanaOOP/activate.php?email='.Input::get('email').'&email_code='.$email_code);
                            Redirect::to('index.php');

                        }catch(Exception $e){
                            die($e->getMessage());
                        }
                    } else{
                        foreach($validation_hurt->errors() as $error){
                            echo $error,'<br>';
                        }
                    }
                } else if($_POST["typeOfUser"] == "robotnik"){
                       $validation_work = $validate->check($_POST,$validate->chooseSet(1));
                if($validation_work->passed()){
                    $user = new User();
                    $salt = Hash::salt(32);
                    $email_code = Hash::unique();
                    try{

                        $user->create(array(
                            'username'  => Input::get('username'),
                            'password'  => Hash::make(Input::get('password'), $salt),
                            'salt'      => $salt,
                            'email'     => Input::get('email'),
                            'email_code'=> $email_code,
                            'name'      => Input::get('name'),
                            'phone_num' => Input::get('phone_num'),
                            'user_group'=> 1,
                            'joined'    => date('Y-m-d H:i:s')




                        ));
                        Session::flash('home','You\'ve been registered successfully! <br>
                        The activation link has been send to your email!');
                        Mail::email(Input::get('email'),'Hello'. Input::get('username').'!',"Here is your activation link! <br> http://localhost/JagodowaPolanaOOP/activate.php?email=".Input::get('email')."&email_code=" . $email_code);
                        Redirect::to('index.php');

                    }catch(Exception $e){
                        die($e->getMessage());
                    }
                } else {
                    foreach($validation_work->errors() as $error){
                        echo $error,'<br>';
                    }
                }
                }
            }
        } else {
            foreach($validation->errors() as $error){
                echo $error,'<br>';
            }
        }
    } else
        echo 'CSRF attack';
}
?>
<form action="" method="post">
  <div class="field">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
  </div>

  <div class="field">
    <label for="password">Choose a password</label>
    <input type="password" name="password" id="password">
  </div>
  <div class="field">
    <label for="password_again">Enter a password again</label>
    <input type="password" name="password_again" id="password_again">
  </div>
  <div class="field">
    <label for="email">Email</label>
    <input type="email" name="email" value="<?php echo escape(Input::get('email'));?>" id="email">
  </div>
  <div class="field">
    <label for="name">Name</label>
    <input type="text" name="name" value="<?php echo escape(Input::get('name'));?>" id="name">
  </div>
      <div class="field">
        <label>Robotnik<input type="radio" name="user_group" class="usergroup" group="robotnik"></label>
        <label>Hurtownik<input type="radio" name="user_group" class="usergroup" group="hurtownik"></label>
    </div>
    <div class="field">
      <label id="phone" style="display:none;"></label>
  </div>
      <input type="hidden" name="typeOfUser" id="typeOfUser" value="">
    <input type="hidden" name="token" value="<?php echo Token::generate();?>">
    <input type="submit"  value="Register">
</form>
<script>
var regBtn = document.querySelector("#registerBtn");
var inputGroup =document.getElementsByName("user_group");
var targetLabel = document.querySelector("#phone");
     targetLabel.style.transition = "all 1s ease";
    console.log(targetLabel);
var typeOfUser =   document.querySelector("#typeOfUser");
    window.addEventListener('load',function(){
        for(var i = 0 ; i<inputGroup.length; i++){
            inputGroup[i].removeAttribute("checked");
        }
    })

function checkGroupClick(){
    var xhttp = new XMLHttpRequest();
    if (this.checked){
        targetLabel.style.opacity = "0";
        targetLabel.style.display = "inline-block";
        var that = this;
        xhttp.onreadystatechange = function(){
            if (xhttp.readyState == 4 && xhttp.status == 200){
                setTimeout(function(){
                    if (that.getAttribute("group") == "hurtownik"){
                        targetLabel.innerHTML = "Phone *: <input type='text' name='phone_num'>";
                        targetLabel.style.opacity = "1";
                    } else if (that.getAttribute("group") == "robotnik"){
                        targetLabel.innerHTML = "Phone :";
                        targetLabel.style.opacity = "1";
                         targetLabel.innerHTML = "Phone : <input type='text' name='phone_num'>";
                        }
                },2);
                typeOfUser.value = xhttp.responseText;
        };

    }
    xhttp.open("GET", "register_ajax.php?q=" + this.getAttribute("group"), true);
    xhttp.send();

    }
}

for(var i = 0 ; i< inputGroup.length; i++){
        inputGroup[i].addEventListener('click',checkGroupClick);
}

</script>
