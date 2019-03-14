<?php  

// edit post
if(isset($_POST['update_post'])) {
	$post_body = strip_tags($_POST['post_body']);
	$id = $_GET['id'];

	$query = mysqli_query($con, "UPDATE posts SET body='$post_body' WHERE id='$id'");
}

?>