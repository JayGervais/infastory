<?php 
include("includes/header.php");

// set messages to viewed query
$set_viewed_query = mysqli_query($con, "UPDATE messages SET viewed='yes' WHERE user_to='$userLoggedIn'");

$secret = md5(uniqid(rand(), true));
$_SESSION['FORM_SECRET'] = $secret;

// create new message class variable with database connection to user
$message_obj = new Message($con, $userLoggedIn);

// if get is set to u, set variable 
if(isset($_GET['u'])) {
	$user_to = $_GET['u'];
}
else {
	// of if other user is most recent user to
	$user_to = $message_obj->getMostRecentUser();
	// set false and create new user to
	if($user_to == false)
		$user_to = 'new';
}
// if user to is not new
if($user_to != "new")
	// set variable to new user class with database connection and and user_to obect variable
	$user_to_obj = new User($con, $user_to);

// send message
$form_secret = isset($_POST["form_secret"])?$_POST["form_secret"]:'';

if(isset($_SESSION["FORM_SECRET"])) {
    if(strcasecmp($form_secret, $_SESSION["FORM_SECRET"]) === 0) {
	// if post message was sent.
	if(isset($_POST['post_message'])) {
		// if message has text, escape strings to prevent code injections, add date, and sendMessage class.

			if(isset($_POST['message_body'])) {
				$body = mysqli_real_escape_string($con, $_POST['message_body']);
				$date = date("Y-m-d H:i:s");
				$message_obj->sendMessage($user_to, $body, $date);
				unset($_SESSION["FORM_SECRET"]);
			}
		}
	}
}

?>

<div class="row">

	<div class="col-md-8 message_div">

		<?php 
		if($user_to != "new"){
			echo "<h4> You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName()
 . "</a></h4><hr>";
 			echo "<div class='loaded_messages' id='scroll_messages'>";
 			echo $message_obj->getMessages($user_to);
 			echo "</div>";
 		} else {
 			echo "<h4>New Message</h4>";
 		}
		?>
		<div class="message_post">
			<form action="" method="POST">
				<input type="hidden" name="form_secret" id="form_secret" value="<?php echo $_SESSION['FORM_SECRET'] ?>">
				<?php 
				if($user_to == "new") {
					echo "Select the friend you would like to message <br><br>";
					?> 
					To: <input type='text' onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'> 

					<?php

					echo "<div class='results'></div>";
					} 
					else {
						echo "<textarea name='message_body' id='message_textarea' placeholder='Write a message...'></textarea>";
						echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
					}		
				?>
						<script>
						// send message when pressing enter
						var input = document.getElementById("message_textarea");
						input.addEventListener("keyup", function(event) {
						    event.preventDefault();
						    if (event.keyCode === 13) {
						        document.getElementById("message_submit").click();
						    }
						});
						</script>
			</form>
			<script>
				var div = document.getElementById("scroll_messages");
				div.scrollTop = div.scrollHeight;
			</script>
		</div><!-- // messages_post -->
	</div><!-- // col -->
	<div class="col-md-4">

	<div class="user_details column" id="conversations">
			<h4>Conversations</h4>
			<div class="loaded_conversations">
				<?php echo $message_obj->getConvos(); ?>
			</div>
			<br>
			<a class="new_message_btn" href="messages.php?u=new">New Message</a>
		</div>
</div><!-- // row -->		
<?php include("includes/footer.php"); ?>