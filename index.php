<?php 
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
    </ul>
    
<?php }
?>