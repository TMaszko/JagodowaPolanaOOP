<?php
require_once 'core/init.php';
if(Input::exists()){
    $validate = new Validate();
    $first_validate = new Validate();
    $validation = $first_validate->check($_POST,array(
            'user_group' => array(
                'required' => true
            
            )    
    ));
    if ($validation->passed()){
        if(isset($_POST["typeOfUser"])){
            if($_POST["typeOfUser"] == "hurtownik"){
                $validation_hurt = $validate->check($_POST,array(
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
		));
                if($validation_hurt->passed()){
                 echo 'Tak jest szmato hurtownik da rade';
                } else{
                    foreach($validation_hurt->errors() as $error){
                        echo $error,'<br>';
                    }
                }
            } else if($_POST["typeOfUser"] == "robotnik"){
                   $validation_work = $validate->check($_POST,array(
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
		));
            if($validation_work->passed()){
                echo 'Tak jest szmato robotnik da rade';
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
}
?>
<form action="" method="post">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="" autocomplete="off">
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
		<label for="name">Name</label>
		<input type="text" name="name" value="" id="name">
	</div>
	    <div class="field">
        <label>Robotnik<input type="radio" name="user_group" class="usergroup" group="robotnik" ></label>
        <label>Hurtownik<input type="radio" name="user_group" class="usergroup" group="hurtownik"></label>
    </div>
    <div class="field">
	    <label id="phone" style="display:none;"></label>
	</div>
	    <input type="hidden" name="typeOfUser" id="typeOfUser" value="">
		<input type= "hidden" name="token" value="">
		<input type="submit"  value="Register">
</form>
<script>
var regBtn = document.querySelector("#registerBtn");
var inputGroup =document.getElementsByName("user_group");
var targetLabel = document.querySelector("#phone");
     targetLabel.style.transition = "all 1s ease";
    console.log(targetLabel);
var typeOfUser =   document.querySelector("#typeOfUser");
function checkGroup(){
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
        inputGroup[i].addEventListener('click',checkGroup);
}
</script>



