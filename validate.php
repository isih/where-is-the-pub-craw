<?php

    require_once "config.php";

	$password_err='';
	$confirmPassword_err = '';
	$verificationHash = '';
	$generic_err = '';
	$success_message = '';

	//here after form is submitted
	if($_SERVER['REQUEST_METHOD'] === 'POST'){

		//retrieve verification hash
		$verificationHash=$_POST['verificationHash'];

		/*check if passwords are the same*/
		//validate password
		$password = '';
		if(empty(trim($_POST['password']))){
			$password_err='Please enter a password';
		} else if (strlen(trim($_POST['password']))<6) {
			$password_err = 'Password should have at least 6 characters';
		} else {
			$password = trim($_POST['password']);
		}

		//validate confirm password
		$confirmPassword = '';
		if(empty(trim($_POST['confirm-password']))){
			$password_err='Please enter a password';
		} else {
			$confirmPassword = trim($_POST['confirm-password']);
			if(empty($password_err) && $password != $confirmPassword){
				$confirmPassword_err = 'Password did not match';
			}
		}


		

		//check input errors before inserting in DB
		if(empty($password_err) && empty($confirmPassword_err)) {

			$passwordHash = password_hash($password, PASSWORD_DEFAULT);

			$sql = "UPDATE users SET password = '$passwordHash', verification_status = '1' WHERE verification_hash='$verificationHash'";
			//"UPDATE users SET password = '$passwordHash', verification_status = '1' WHERE password='$verificationHash'";

			if ($link->query($sql) === TRUE) {
				//echo "Record updated successfully";

				$success_message = 'Account verified succesfully';

				//redirect to signin
				header("refresh:5; url=signin.php");
			} else {
				$generic_err = "Oops! Something went wrong. Please try again later.";
			}
		}

	} else {
		//confirmation page. Has to check hash by GET request of the link
		if(isset($_GET['verificationHash'])){
			$verificationHash=mysqli_real_escape_string($link, $_GET['verificationHash']);
		}
	}
	

?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<title>Validate Account</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />-->
		
		<?php
		    include 'common_header.php';
	    ?>
	</head>
	<body>
		<ion-app>
			<ion-header>
				<ion-toolbar id="toolbar" color="white" class="ion-text-center">    
                    <ion-buttons slot="start">
                        <a id="main-logo" href="index.php">
                            <img src="imgs/logo.webp" alt="logo"/>
                        </a>
                    </ion-buttons>

                    <ion-title id='title' size="large" class="ion-text-center">
                        Confirm account
                    </ion-title>
                </ion-toolbar>
			</ion-header>
			
			<ion-content [fullscreen]="true">
				<ion-grid id="grid-login">
					<ion-row>
						<ion-col size-md="6" offset-md='3'>
							<ion-card class="card-container">
								
								<!--Header of card-->
								<!--
								<div class='ion-margin-vertical ion-text-center'>
										<a id="login-logo" href="index.php">
											<img src="imgs/logo.webp" alt="logo"/>
										</a>
								</div>
								-->

								<ion-card-content style="width: 60%; margin: auto;">
									<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
										<ion-input name='verificationHash' type='hidden' value="<?= $verificationHash ?>"></ion-input>
										<!--email input-->
										<ion-item class="login-form-element">
											<ion-label class='login-label' position='floating'>Password</ion-label>
											<ion-input name="password" type='password' id='input-password' clear-input="true" placeholder="Password" required></ion-input>
										</ion-item>
										<span class='confirmationError'><?= $password_err ?></span>
										<!--password input-->
										<ion-item class="login-form-element">
											<ion-label class='login-label' position='floating'>Confirm Password</ion-label>
											<ion-input name='confirm-password' type='password' label="Confirm password" id='input-pswrd' clear-input="true" placeholder="Confirm password" required></ion-input>
										</ion-item>
										<span class='confirmationError'><?= $confirmPassword_err ?></span>
										
										<span class='confirmationError'><?= $generic_err ?></span>
										<span class='confirmationSuccess'><?= $success_message ?></span>

										<div class='ion-margin-vertical ion-text-center'>
											<!-- login button -->
											<ion-button type="submit" class='sign-in-button' color="main-bg"><!-- onclick="window.location.href='dashboard.php'" -->
												<a class='sign-in-button'>Confirm</a>
											</ion-button>
										</div>
									</form>
								</ion-card-content>
							</ion-card>
						</ion-col>
					</ion-row>
				</ion-grid>
			</ion-content>
		</ion-app>
		
	</body>
</html>