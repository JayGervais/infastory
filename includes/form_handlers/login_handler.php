<?php 

if(isset($_POST['login_button'])) {
	
	$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); // sanitize email
	
	$_SESSION['log_email'] = $email; // store email into session
	$password = md5($_POST['log_password']); // get password

	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
	$check_login_query = mysqli_num_rows($check_database_query);

	// login re-activates user account if set to closed
	if($check_login_query == 1) {
		// check if remember me box is checked
		if(isset($_POST['check_box'])) {
			// set cookie to remember email
		     setcookie('email', $email, time() + 86400, "/");
		     // set cookie to remember password
		     setcookie('password', $password, time() + 86400, "/");
		}

		$row = mysqli_fetch_array($check_database_query);
		$username = $row['username'];

		$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
		if(mysqli_num_rows($user_closed_query) == 1) {
			$reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
		}

		$_SESSION['username'] = $username;
		header("Location: index.php");
		exit();
	} else {
		array_push($error_array, "Email or password is incorrect.<br>");
	}

}

?>