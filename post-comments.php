<?php  
include("includes/header.php");

// check id from post is set
if(isset($_GET['id'])) {
	$id = $_GET['id'];
}
else {
	// if no id, set to 0
	$id = 0;
}

$post_data_query = mysqli_query($con, "SELECT * FROM posts WHERE id='$id'");
	$row = mysqli_fetch_array($post_data_query);
	$post_body = $row['body'];
	$id = $row['id'];
	$image = $row['image'];
?>
<div class="post_container">
<style>
	.backButton {
		margin-top: -2px;
    	margin-bottom: 29px;
    	border: 2px solid #000;
	}
</style>

<button onclick="history.go(-1);" class="backButton"><i class="fas fa-arrow-left"></i> Back</button>
<div class="row">
	
<div class="col-sm-12">
<div class="user_details column">
		<a href="<?php echo $userLoggedIn; ?>">
			<img src="<?php echo $user['profile_pic']; ?>" /></a>
			
		<div class="user_details_left_right">

			<a href="<?php echo $userLoggedIn; ?>"><h4><?php echo $user['first_name'] . " " . $user['last_name']; ?></h4></a>
			
			<?php echo "Posts: " . $user['num_posts'] . "<br>"; 
			echo "Likes: " . $user['num_likes'];
			?>

		</div><!-- // user_details_left_right -->
	</div><!-- // user_details -->
	</div><!-- // col -->

	<div class="col-sm-12">
	
		<div class="posts_area">
			<?php 
			echo '<br>' . $post_body . '<br>';
			if(isset($row['image'])) {
				echo '<img src="' . $image . '" width="100%;">';
			}
			else {
				$image = "";
			}  
			echo "<div class='loaded_messages' id='scroll_comments'>";
			include("comments.php");
			echo "</div>";
			?>
			<script>
				var div = document.getElementById("scroll_comments");
				div.scrollTop = div.scrollHeight;
			</script>
		</div>	
	</div>
 </div>
</div>
 <?php include("includes/footer.php"); ?>