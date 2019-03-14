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
<!DOCTYPE html>
<html>
<head>
	<title>Social Club</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style_form.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	<script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script>
</head>
<body>
		<div class="login-page">		
		<div class="form">
		<p>Forgot password?</p>	
		<form class="forgot-password" action="send_link.php" method="POST">
			<input type="text" name="email" placeholder="Email">
     	 	<input type="submit" name="submit_email">
		</form>

		</div>
		</div>
</body>
</html>