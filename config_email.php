<?php 
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require '../../../php/pear/PHPMailer-master/src/Exception.php';
  require '../../../php/pear/PHPMailer-master/src/PHPMailer.php';
  require '../../../php/pear/PHPMailer-master/src/SMTP.php';

  //email settings for production

  $mail = new PHPMailer(TRUE);
  $mail->isSMTP();
  $mail->SMTPDebug = 0;
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;//2525 - 587
  $mail->Host = 'mail.south.tours';
  $mail->Username = 'noreply@south.tours';
  $mail->Password = 'BwJ+Y0yC[_2{';
?>