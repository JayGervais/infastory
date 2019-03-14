<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>infastory</title>
	<!--<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">-->
	<link rel="stylesheet" type="text/css" href="assets/css/register_style_form.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	<script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script>
	<!--<script type="text/javascript" src="assets/js/register.js"></script>-->
</head>
<body>
<?php
	// hides error messages on opposite pages for login and signup
	if(isset($_POST['register_button'])) {
		echo '
		<script>
		$(document).ready(function() {
			$("#first").hide();
			$("#second").show();
			});
		</script>
		';
	}

?>
		<div class="login-page">		
		<div class="form">
		<h1>infastory</h1>
		<p>Login or sign up below.</p>	
		<form action="register.php" method="POST" class="register-form">
			<input type="text" name="reg_fname" placeholder="First Name" value="<?php 
			if(isset($_SESSION['reg_fname'])) {
				echo $_SESSION['reg_fname'];
			} ?>" required>
			<br>
			<?php if(in_array("First name must be between 2 and 25 characters.<br>", $error_array)) { echo "First name must be between 2 and 25 characters.<br>"; } ?>

			<input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
			if(isset($_SESSION['reg_lname'])) {
				echo $_SESSION['reg_lname'];
			} ?>" required>
			<br>
			<?php if(in_array("Last name must be between 2 and 25 characters.<br>", $error_array)) { echo "Last name must be between 2 and 25 characters.<br>"; } ?>

			<input type="email" name="reg_email" placeholder="Email" value="<?php 
			if(isset($_SESSION['reg_email'])) {
				echo $_SESSION['reg_email'];
			} ?>" required>
			<br>
			<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
			if(isset($_SESSION['reg_email2'])) {
				echo $_SESSION['reg_email2'];
			} ?>" required>
			<br>
			<?php if(in_array("Invalid email.<br>", $error_array)) { echo "Invalid email.<br>"; }
			else if(in_array("Email already in use.<br>", $error_array)) { echo "Email already in use.<br>"; }
			else if(in_array("Emails do not match.<br>", $error_array)) { echo "Emails do not match.<br>"; } ?>
			<br>
			<input type="password" name="reg_password" placeholder="Password" required>
			<br>
			<input type="password" name="reg_password2" placeholder="Confirm Password" required>
			<br>
			<?php if(in_array("Your passwords do not match.<br>", $error_array)) { echo "Your passwords do not match.<br>"; }
			else if(in_array("Your password can only contain English characters or numbers.<br>", $error_array)) { echo "Your password can only contain English characters or numbers.<br>"; }
			else if(in_array("Your password must be between 5 and 30 characters.<br>", $error_array)) { echo "Your password must be between 5 and 30 characters.<br>"; } ?>

			<input type="submit" name="register_button" value="Register">
			<br>

			<?php if(in_array("<span style='color: #14C800;'>You're all set! Go ahead and login.</span><br>", $error_array)) { echo "<span style='color: #14C800;'>You're all set! Go ahead and login.</span><br>"; } ?>
			<br>
		  <p class="message">Already registered? <a href="#" id="signin" class="signin">Sign In</a></p>

		</form>


		<form class="login-form" action="register.php" method="POST">
			<input type="email" name="log_email" placeholder="Email" value="<?php 
			if(isset($_SESSION['log_email'])) {
				echo $_SESSION['reg_email'];
			} ?>" required>
			
			<input type="password" name="log_password" placeholder="Password">
			<br>
			<?php if(in_array("Email or password is incorrect.<br>", $error_array)) { echo "Email or password is incorrect.<br>"; } ?>

			<input type="checkbox" name="check_box"><label style="font-family: Roboto, sans-serif; font-size: 14px;">Remember me</label>

			<input type="submit" name="login_button" value="Login">

		  <p class="message">Not registered? <a href="#">Create an account</a></p>

		  <p class="message"><a href="password-reset.php" id="forgotpass" class="forgotpass">Forgot password?</a></p>
		  
		</form>

		</div>
		</div>

<script>
	$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
</script>
</body>
</html>