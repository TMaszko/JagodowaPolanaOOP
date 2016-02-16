<?php
require_once 'libs/mail/PHPMailerAutoload.php';
class Mail {
  function email($to,$subject,$body){
  $m = new PHPMailer;
  $m -> isSMTP();
  $m -> SMTPAuth = true;
  //$m -> SMTPDebug = 2;
  $m -> Host =  'smtp.gmail.com';
  $m -> Username = 't.krzyzowski96@gmail.com';
  $m -> Password = '!Tomekon96#';
  $m -> SMTPSecure = 'ssl';
  $m -> Port = 465;
  $m -> From = 't.krzyzowski96@gmail.com';
  $m -> FromName = 'Tomasz Krzyzowski';
  $m -> addAddress($to,'Tomasz');
  $m -> Subject = $subject;
  $m -> Body = $body;
  $m -> AltBody = 'This is the body of an email!';
  $m ->send();
  }
}
