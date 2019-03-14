<?php

// variables to prevent errors
$fname = ""; // first name
$lname = ""; // last name
$em = ""; // email
$em2 = ""; // email 2
$password = ""; // password
$password2 = ""; // password2
$date = ""; // sign up date
$error_array = array(); // errors signing up

if(isset($_POST['register_button'])){
	// registration form values

	// First name
	$fname = strip_tags($_POST['reg_fname']); // remove html tags
	$fname = str_replace(' ', '', $fname); // remove spaces
	$fname = ucfirst(strtolower($fname)); // lowercase except first letter
	$_SESSION['reg_fname'] = $fname; // stored variables in session

	// Last name
	$lname = strip_tags($_POST['reg_lname']); // remove html tags
	$lname = str_replace(' ', '', $lname); // remove spaces
	$lname = ucfirst(strtolower($lname)); // lowercase except first letter
	$_SESSION['reg_lname'] = $lname; // stored variables in session

	// Email
	$em = strip_tags($_POST['reg_email']); // remove html tags
	$em = str_replace(' ', '', $em); // remove spaces
	$em = ucfirst(strtolower($em)); // lowercase except first letter
	$_SESSION['reg_email'] = $em; // stored variables in session

	// Email 2
	$em2 = strip_tags($_POST['reg_email2']); // remove html tags
	$em2 = str_replace(' ', '', $em2); // remove spaces
	$em2 = ucfirst(strtolower($em2)); // lowercase except first letter
	$_SESSION['reg_email2'] = $em2; // stored variables in session

	// Password
	$password = strip_tags($_POST['reg_password']); // remove html tags
	$password2 = strip_tags($_POST['reg_password2']); // remove html tags

	$date = date("Y-m-d"); // custom date format - current


	if($em == $em2) {
		// check email format
		if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

			// check if email already exists
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

			// count number of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows > 0) {
				array_push($error_array, "Email already in use.<br>");
			}

		} else {
			array_push($error_array, "Invalid email.<br>");
			}

		}
		else {
			array_push($error_array, "Emails do not match.<br>");
		}

		if(strlen($fname) > 25 || strlen($fname) < 2) {
			array_push($error_array, "First name must be between 2 and 25 characters.<br>");
		}

		if(strlen($lname) > 25 || strlen($lname) < 2) {
			array_push($error_array, "Last name must be between 2 and 25 characters.<br>");
		}

		if($password != $password2) {
			array_push($error_array, "Your passwords do not match.<br>");
		}
		else {
			if(preg_match('/[^A-Za-z0-9]/', $password)) {
				array_push($error_array, "Your password can only contain English characters or numbers.<br>");
			}
		}

			if(strlen($password > 30 || strlen($password) < 5)) {
				array_push($error_array, "Your password must be between 5 and 30 characters.<br>");	
		}

		// check to make sure no errors are added to array
		if(empty($error_array)) {
			$password = md5($password); // encrypt password

			// generate username by concotanating first and last name
			$username = strtolower($fname . "_" . $lname);
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

			$i = 0;
			// if username exists add number to username
			$temp_username = $username; //Temporary username variable used to find unique username
 
			//If username already exists, add number to end and check again
			while(mysqli_num_rows($check_username_query) != 0){
			    $temp_username = $username; //Reset temporary username back to original username
			    $i++;
			    $temp_username = $username."_".$i;
			    $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$temp_username'");
			}
			 
			$username = $temp_username; //$temp_username will now contain the unique username
			
			// create user directories
			mkdir('assets/users/' . $username);
			mkdir('assets/users/' . $username . '/profile_pics');
			mkdir('assets/users/' . $username . '/images');
			mkdir('assets/users/' . $username . '/images/thumb');
			mkdir('assets/users/' . $username . '/files');

			// profile picture assignment
			$rand = rand(1, 2); // random number between 1 and 2

			if($rand == 1)
			$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";

			else if($rand == 2)
			$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";

			$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',', '')");

			array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login.</span><br>");

			// Clear session variables
			$_SESSION['reg_fname'] = "";
			$_SESSION['reg_lname'] = "";
			$_SESSION['reg_email'] = "";
			$_SESSION['reg_email2'] = "";

		}

	}

?>