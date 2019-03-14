<?php  
include_once("includes/header.php");
include("includes/form_handlers/settings_handler.php");
?>
<div class="post_container">
<div class="main_column column">
	<h1>Account Settings</h1>
	<?php 
	echo "<img src='" . $user['profile_pic'] . "' id='small_profile_pic'>";
	?>
	<br>
	<a href="upload.php">Upload new picture.</a><br><br><br>

	<h4>Edit your account details:</h4>

	<?php  
	$user_data_query = mysqli_query($con, "SELECT first_name, last_name, email, description FROM users WHERE username='$userLoggedIn'");
	$row = mysqli_fetch_array($user_data_query);

	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$email = $row['email'];
	$description = $row['description'];

	?>

	<form action="settings.php" method="POST">
		First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>" id="settings_input"><br><br>
		Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>" id="settings_input"><br><br>
		Email: <input type="text" name="email" value="<?php echo $email; ?>" id="settings_input"><br><br>

		Description:<br>
		<textarea name="description" class="descriptionfield" value="<?php echo $description; ?>" id="settings_input" maxlength="1500"><?php echo $description; ?></textarea> <br><br>

		<?php echo $message; ?>

		<input type="submit" name="update_details" id="save_details" value="Update Details" class="info setting_submit"><br>	
	</form>
	<br><br>
	<h4>Change Password</h4>
	<form action="settings.php" method="POST">
		Current Password: <input type="password" name="old_password" id="settings_input"><br><br>
		New Password: <input type="password" name="new_password_1" id="settings_input"><br><br>
		New Password Again: <input type="password" name="new_password_2" id="settings_input"><br><br>

		<?php echo $password_message; ?>

		<input type="submit" name="update_password" id="save_details" value="Update Password" class="info setting_submit"><br>	
	</form>
	<br><br>
	<h4>Close Account</h4>
	<form action="settings.php" method="POST">
		<input type="submit" name="close_account" id="close_account" value="Close Account" class="danger setting_submit">		
	</form>
</div>
</div><!-- // post_container -->
<?php include("includes/footer.php"); ?>