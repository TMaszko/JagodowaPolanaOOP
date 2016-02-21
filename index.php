<?php

/*
--------------------------------------------------
To do :
verify user email and add it to database (done)
send an email with activation link (done),
Change Password functionality
Update Name and Phone Number functionality (done)
Forgot username or password functionality
--------------------------------------------
*/







require_once 'core/init.php';
if (Session::exists('home')){
    echo '<p>' . Session::flash('home') . '</p>';
}
$user = new User();
if ($user->isLoggedIn() && !$user->isRecoveredPass()){
    echo 'Logged in';
    ?>

    <ul>
        <li><a href="logout.php">Log out!</a></li>
        <li><a href="changepassword.php">Change Password</a></li>
        <li><a href="update.php">Update details</a></li>
    </ul>

<?php } else if($user->isLoggedIn() && $user->isRecoveredPass()){
        Redirect::to('changepassword.php?force=true');

      } else {
?>
  <p> You need to <a href="login.php">Log in</a> or <a href="register.php">Register!</a>
    <p> Forgotten your <a href="recover.php?mode=username">username</a> or <a href="recover.php?mode=password">password</a>?</p>
<?php
      }
?>
