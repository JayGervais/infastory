<?php  
include("includes/header.php");

if(isset($_POST['cancel'])) {
	header("Location: settings.php");
}

if(isset($_POST['close_account'])) {
	$close_query = mysqli_query($con, "UPDATE users SET user_close='yes' WHERE username='$userLoggedIn'");
	session_destroy();
	header("Location: register.php");
}
?>

<div class="main_column column">
	<h4>Close Account</h4>
	<p>Are you sure you want to close your account?</p>
	<p>Closing your account will hide your profile and all activity.</p>
	<p>You can re-open your account at any time be logging in.</p>
	<form action="close_account.php" method="POST">
		<input type="submit" name="close_account" id="close_account" value="Yes, Close My Account" class="danger setting_submit">
		<input type="submit" name="cancel" id="update_details" value="Keep My Account" class="info setting_submit">
	</form>
</div>