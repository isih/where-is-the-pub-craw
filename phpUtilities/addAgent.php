<?php
  require_once "../config.php";
  require_once "../config_email.php";

  // Initialize the session
	session_start();

 // Check if the user is logged in, if not then redirect him to login page
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: signin.php");
    exit;
  }

  //deleteAgent.php is called by AJAX in Dashboard.js
  $name = '';
  $param_email = '';

  $email_err = '';
  $name_err = '';
  $info = '';


  // Validate email
  if(empty(trim($_POST["email"]))){
    $email_err = "Please enter a email.";
    echo $email_err;
  } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
    $email_err = "Invalid email format.";
    echo $email_err;
  } else {
      // Prepare a select statement
      $sql = "SELECT id FROM users WHERE email = ?";
      
      if($stmt = mysqli_prepare($link, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_email);
          
          // Set parameters
          $param_email = trim($_POST["email"]);
          
          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              /* store result */
              mysqli_stmt_store_result($stmt);
              
              if(mysqli_stmt_num_rows($stmt) == 1){
                  $email_err = "This email is already taken.";
                  echo $email_err;
              } else {
                  $email = trim($_POST["email"]);
              }
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          mysqli_stmt_close($stmt);
      }
  }

  // Validate name
	if(empty(trim($_POST["name"]))){
		$name_err = "Please enter a valid name.";
    echo $name_err;
	} else {
		$param_name = trim($_POST["name"]);
	}

  // Check input errors before inserting in database
  if(empty($email_err) && empty($name_err)){

    // Prepare insert statement
    $sql = "INSERT INTO users (email, name, verification_status, verification_hash, role) VALUES (?,?,?,?,?)"; 

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      //mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_name, $param_message, $param_password);

      $role = 'Agent';//role is always agent

      mysqli_stmt_bind_param($stmt, "sssss", $param_email, $param_name, $param_verification_status, $param_verification_hash, $role);
      
      // Set parameters
      $param_email = $email;
      
      //set hash for email verification
      $param_verification_status = 0;
      $param_verification_hash = password_hash(rand(0, 1000), PASSWORD_DEFAULT);
      
      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
        
        //display message to inform user that verification has been sent
        $msg = "We've just sent a verification link to $param_email</br>".
        "Please click on the link to get started.</br>";
        
        //$email = new PHPMailer(TRUE);
        
        //smtp send email using PHPMailer
        //From email address and name
        $mail->From = "noreply@south.tours";
        $mail->FromName = "South Tours";

        //To address
        $mail->addAddress($param_email);
        
        //add embedded image
        $mail->addEmbeddedImage('../imgs/logo.png', 'logo', 'logo.png');

        //Address to which recipient will reply
        //$mail->addReplyTo("reply@yourdomain.com", "Reply");

        //CC and BCC
        //$mail->addCC("cc@example.com");
        //$mail->addBCC("bcc@example.com");

        $mail->Subject = "Pub Crawl: account verification";
        
        //Send HTML or Plain Text email
        $mail->isHTML(true);
        
        $id = mysqli_insert_id($link);//$stmt
        
        $mail->Body = "
        <p>Welcome to South Tours' Pub Crawl Manager,</br>
        Please confirm your account registration by clicking this 
      <a href='http://".$pageName."/validate.php?verificationHash=$param_verification_hash'>link</a>.</p>
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
          $info = "Mailer Error: " . $mail->ErrorInfo;
          echo $info;
        }
        
      } else{
        echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }


  }

?>