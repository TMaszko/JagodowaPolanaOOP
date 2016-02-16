<?php

/*
--------------------------------------------------
To do :
verify user email and add it to database (done)
send an email with activation link (done),
Change Password functionality
Update Name and Phone Number functionality
Forgot username or password functionality
--------------------------------------------
*/







require_once 'core/init.php';
if (Session::exists('home')){
    echo Session::flash('home');
}
$user = new User();
if ($user->isLoggedIn()){
    echo 'Logged in';
    ?>

    <ul>
        <li><a href="logout.php">Log out!</a></li>
        <li><a href="changepassword.php"></a></li>
    </ul>

<?php } else{
?>
  <p> You need to <a href="login.php">Log in</a> or <a href="register.php">Register!</a>
<?php
}
?>
