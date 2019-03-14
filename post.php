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
?>
<style>
	.delete_button {
		margin-top: 0px;
	}

	#post_body {
		padding-top: 35px;
	}
</style>
<div class="post_container">
<div class="row">

<div class="col-sm-8">

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
			// get new post class with connection and user logged in
			$post = new Post($con, $userLoggedIn);
			// get single post class with id that is set from user
			$post->getSinglePost($id);

			?>
		</div>	
	</div>
 </div>
 <?php include("includes/footer.php"); ?>