<?php

// edit image details
if(isset($_POST['editImage'])) {
	$image_id = $_POST['id'];
	$title = strip_tags($_POST['title']);
	$description = strip_tags($_POST['description']);

	$image_query = mysqli_query($con, "SELECT * FROM images WHERE id='$image_id'");
	$row = mysqli_fetch_array($image_query);	
	$added_by = $row['added_by'];

	if($userLoggedIn == $added_by) {

	$update_title = mysqli_query($con, "UPDATE images SET title='$title', description='$description' WHERE id='$image_id'");
	$message = "Your details have been changed.";
    }
    else {
    	$message = "Details could not be updated";
    }
    header("Location: edit-images.php");
}

// delete image
if(isset($_POST['delete_image'])) {
	$image_id = $_POST['id'];

	$image_query = mysqli_query($con, "SELECT * FROM images WHERE id='$image_id'");
	$row = mysqli_fetch_array($image_query);
	$added_by = $row['added_by'];

	if($userLoggedIn == $added_by) {

		$delete_query = mysqli_query($con, "UPDATE images SET deleted='yes' WHERE id='$image_id'");
	}
	else {
		$message = "Photo could not be deleted";
	}
	header("Location: edit-images.php");
}

?>