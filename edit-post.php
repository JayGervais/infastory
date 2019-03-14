<?php  
include("includes/header.php");
include("includes/form_handlers/update_post.php");

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

	</div><!-- // col -->

	<div class="col-sm-12">

		<div class="posts_area">

			<form action="edit-post.php?id=<?php echo $id; ?>" method="POST">
				
				<h4>Update post:</h4>
				<textarea name="post_body" class="descriptionfield" id="settings_input" value="<?php echo $post_body; ?>"><?php echo $post_body; ?></textarea><br>

				<input type="submit" name="update_post" id="update_post" value="Update Post" class="info setting_submit"><br>	
			</form><br>

			<?php 
			echo $post_body . '<br><br>';
			if(isset($row['image'])) {
				echo '<img src="' . $image . '" width="100%;">';
			}
			else {
				$image = "";
			}  

			?>
		</div>	
	</div>
 </div>
</div>
 <?php include("includes/footer.php"); ?>