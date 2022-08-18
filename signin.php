<?php
function loginn() {
	// Initialize the session
	session_start();
	
	// Check if the user is already logged in, if yes then redirect him to his page
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		if($_SESSION["role"] === 'Admin'){
			header("location: dashboard.php");
		} else if ($_SESSION["role"] === 'Agent') {
			header("location: startCrawling.php");
		}
		exit;
	}
	
	// Include config file
	require_once "config.php";
	
	// Define variables and initialize with empty values
	$email = $password = $company_name = "";
	$email_err = $password_err = $login_err = "";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
		// Check if email is empty
		if(empty(trim($_POST["email"]))){
			$email_err = "Please enter email.";
		} else{
			$email = trim($_POST["email"]);
		}
		
		// Check if password is empty
		if(empty(trim($_POST["password"]))){
			$password_err = "Please enter your password.";
		} else{
			$password = trim($_POST["password"]);
		}
		
		// Validate credentials
		if(empty($email_err) && empty($password_err)){
			// Prepare a select statement
			//$sql = "SELECT id, company_name, email, password FROM a_users WHERE email = ?";
			$sql = "SELECT id, email, password, verification_status, role FROM users WHERE email = ?";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_email);
				
				// Set parameters
				$param_email = $email;
				
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Store result
					mysqli_stmt_store_result($stmt);
					
					// Check if email exists, if yes then verify password
					if(mysqli_stmt_num_rows($stmt) == 1){
						// Bind result variables
						mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $verification_status, $role);

						if(mysqli_stmt_fetch($stmt)){
							
							if($verification_status=='1') {
								//the account has been verified
								

								//use the commented code as if condition ONLY while testing (and hashed password is not implemented yet)
								/*$password===$hashed_password*/
								//password_verify($password, $hashed_password)
								if(password_verify($password, $hashed_password)){
									// Password is correct, so start a new session
									session_start();
									
									// Store data in session variables
									$_SESSION["loggedin"] = true;
									$_SESSION["id"] = $id;
									$_SESSION["email"] = $email; 
									$_SESSION["role"] = $role;
									$_SESSION["currentCoords"] = 'false';//
									//$_SESSION["chosenPubCrawlId"] = '';
									
									//redirect user based on his role
									if($_SESSION["role"] === 'Admin') {
										header("location: dashboard.php");
									} else if ($_SESSION["role"] === 'Agent') {
										header("location: manageCrawling.php");
									} else {
										// If role unknown, redirect to home page
										header("location: index.php");
									}
									
								} else{
									// Password is not valid, display a generic error message
									$login_err = "Invalid email or password.";
								}
								
							} else {
								$login_err = "You have not confirmed your account yet, check your inbox.";
							}
							
						}
					} else{
						// email doesn't exist, display a generic error message
						$login_err = "Invalid email or password.";
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
		// Close connection
		mysqli_close($link);
	}
};

loginn();

	
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<title>Sign in</title>

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
                        Admin Panel Login
                    </ion-title>
                </ion-toolbar>
			</ion-header>
			
			<ion-content [fullscreen]="true">
				<ion-grid id="grid-login">
					<ion-row>
						<ion-col size-md="6" offset-md='3'>
							<ion-card class="card-container">
								
								<!--Header of card-->
								<div class='ion-margin-vertical ion-text-center'>
									<!--Logo-->
										<a id="login-logo" href="index.php">
											<img src="imgs/logo.webp" alt="logo"/>
										</a>
								</div>
								
								<ion-card-content style="width: 60%; margin: auto;">
									<?php 
										if(!empty($login_err)){
											$visible = '';

											if(!empty($email_err)){
												$visible = 'is-invalid';
											}

											echo "<ion-item lines='none' color='main-bg' class='".$visible."'"."><ion-label color='white'>".$login_err."</ion-label></ion-item>";
										}        
									?>
									<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
										<!--email input-->
										<ion-item class="login-form-element">
											<ion-label class='login-label' position='floating'>Email</ion-label>
											<ion-input name="email" type='email' id='input-email' clear-input="true" autocomplete="email" placeholder="E-mail"></ion-input>
										</ion-item>
										<!--password input-->
										<ion-item class="login-form-element">
											<ion-label class='login-label' position='floating'>Password</ion-label>
											<ion-input name='password' type='password' label="Password" id='input-pswrd' clear-input="true" placeholder="Password"></ion-input>
										</ion-item>

										
										<div class='ion-margin-vertical ion-text-center'>
											<!-- login button -->
											<ion-button type="submit" class='sign-in-button' color="main-bg"><!-- onclick="window.location.href='dashboard.php'" -->
												<a class='sign-in-button'>Sign In</a>
											</ion-button>
											</br>
											<!-- cancel Login -->
											<a href='index.php'>
												<ion-button fill='clear' id="cancel-login-button">
													Cancel Login
												</ion-button>
											</a>
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

<?php
echo '<script type = "text/javascript">
document.getElementById("input-pswrd")
	.addEventListener("keyup", function(event) {
		event.preventDefault();
		if(event.keyCode === 13) {
			alert("Enter Key Pressed");
			//document.getElementByClassName("sign-in-button").click();
		}
	});
</script>';
?>