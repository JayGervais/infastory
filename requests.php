<?php  
include("includes/header.php"); 

?>

<div class="post_container">

	<h4>Requests sent</h4>
     <?php
	$pending = mysqli_query($con, "SELECT user_to, id FROM friend_requests WHERE user_from='$userLoggedIn'");
	if(mysqli_num_rows($pending) > 0) {
	   while ($row = mysqli_fetch_array($pending)) {
               $sent = $row['user_to'];

               $req_id = $row['id'];
				$delete_button = "<button class='delete_button delReq' id='$req_id'>Delete</button>";
               
	       $dataQuery = mysqli_query($con, "SELECT * FROM users WHERE username='$sent'");
	        while($name = mysqli_fetch_array($dataQuery)) {
		    echo "<a href='" . $name['username'] . "'><img src='" . $name['profile_pic'] . "' style='height: 50px;'></a>
		      <a href='" . $name['username'] . "'>" . $name['first_name'] . " " . $name['last_name'] . "</a>$delete_button<br>";
	        }
           }
	}
	else {
	    echo "You have no pending requests at this time";
        }	
     ?>
	<hr>


	<h4>Friend Requests</h4>

	<?php  

	$query = mysqli_query($con, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");
	if(mysqli_num_rows($query) == 0)
		echo "You have no friend requests.";
	else {
		while($row = mysqli_fetch_array($query)) {
			$user_from = $row['user_from'];
			$user_from_obj = new User($con, $user_from);

			echo "<a href=" . $user_from . ">" . $user_from_obj->getFirstAndLastName() . '</a>' . " sent you a friend request.";

			$user_from_friend_array = $user_from_obj->getFriendArray();

			if(isset($_POST['accept_request' . $user_from])) {
				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$user_from,') WHERE username='$userLoggedIn'");
				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$userLoggedIn,') WHERE username='$user_from'");

				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
				echo "You are now friends!";
				header("Location: requests.php");
			}

			if(isset($_POST['ignore_request' . $user_from])) {
				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
				echo "Request ignored.";
				header("Location: requests.php");

			} ?>

			<form action="requests.php" method="POST">
				<input type="submit" name="accept_request<?php echo $user_from; ?>" id="accept_button" value="Accept">
				<input type="submit" name="ignore_request<?php echo $user_from; ?>" id="ignore_button" value="Ignore">
			</form>

		<?php
		}
	}
	?>
	<hr>

	<?php
	 $userLoggedIn = new User($con, $userLoggedIn);
	 $rez = array();
	 $rez = $userLoggedIn->getFriendArray();
	 $friend_array_string = trim($rez, ",");
	 
	if ($friend_array_string != "") {
	 
	 $no_commas = explode(",", $friend_array_string);
	 echo '<h4>Friends</h4>';
	 ?>
	 
	 <div class="friends">
	 <?php
	 foreach ($no_commas as $key => $value) {
	 
	 $friend = mysqli_query($con, "SELECT first_name, last_name, username, profile_pic FROM users WHERE username='$value'");
	 $row = mysqli_fetch_assoc($friend);
	 echo "<a href='" . $row['username'] . "'><img src='" . $row['profile_pic'] . "' style='height: 80px;'></a>
	  <a href='" . $row['username'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</a><br>";   
	 }
	}
	 
	else {
	 echo "<br>You have no friends. Please add someone";
	} 
 ?>
	

</div><!-- // post container -->
<script>
 
	$(function(){
	     $(".delReq").on('click', function(e){
	          let id = e.target.id;
	          $.post("includes/handlers/delete_request.php", {id:id}, function(){
	               location.reload();
	          });
	     });
	});
	 
</script>

<?php include("includes/footer.php"); ?>