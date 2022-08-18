<?php 
  // Initialize the session
  session_start();
  
  // Check if the user is logged in, if not then redirect him to login page
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: signin.php");
      exit;
  }

    require_once "../config.php";
    require_once "../config_email.php";

    $message = '';

    $verificationHash = password_hash(rand(0, 1000), PASSWORD_DEFAULT);

    //remove old password and set provisional hash
    //$sql = "UPDATE users SET verification_status = '$verificationHash' WHERE role='admin'";
    $cur_email = $_SESSION['email'];
    $cur_role = $_SESSION['role'];

    $sql = "UPDATE users SET verification_hash = '$verificationHash' WHERE email ='$cur_email' AND role='$cur_role'";

    if ($link->query($sql) === TRUE) {

        $email = mysqli_query($link, "SELECT email FROM users WHERE email ='$cur_email' AND role='$cur_role'");

        $param_email = '';

        if ($email->num_rows > 0) {
            while($row = $email->fetch_assoc()) {
                $param_email =  $row['email'];
            }
        }

        $mail->From = "noreply@south.tours";
        $mail->FromName = "South Tours";

        //To address
        $mail->addAddress($param_email);
        
        //add embedded image
        $mail->addEmbeddedImage('../imgs/logo.png', 'logo', 'logo.png');

        $mail->Subject = "Pub Crawl: update password";

        //Send HTML or Plain Text email
        $mail->isHTML(true);
        
        $id = mysqli_insert_id($link);//$stmt
        
        $mail->Body = "
        <p>Welcome to South Tours' Pub Crawl Manager,</br>
        Please choose a new password after clicking this 
      <a href='https://".$pageName."/validate.php?verificationHash=$verificationHash'>link</a>.</p>
        <hr style='border-color:#052049;'>
        <div style='float: center; display: inline-block; margin-bottom:0px; margin-top: 10px;'>
          <img src='cid:logo' />
        </div>";//Change to real URL In production
        //$mail->AltBody = "This is the plain text version of the email content";

        try {
          $mail->send();
          //$info = "Message has been sent successfully";
          //echo $info;
        } catch (Exception $e) {
          $message = "Mailer Error: " . $mail->ErrorInfo;
          echo $message;
        }

        //echo "Record updated successfully";

        //$message = 'password removed succesfully';

        //redirect to validation page
        //header("refresh:5; url=validate.php?verificationHash=$param_verification_status");
    } else {
        $message = "Oops! Something went wrong. Please try again later.";
    }

    

?>