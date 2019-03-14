<?php
include("includes/header.php"); 
include("includes/form_handlers/image_edit.php");



?>
<div class="post_container">

<p>Select the photos you want in a gallery</p>
<form action="gallery.php" method="post">

<input type="submit" name="createGallery" value="Create Gallery"><br><br>

<?php

$image_query = mysqli_query($con, "SELECT * FROM images WHERE added_by='$userLoggedIn' AND deleted='no' ORDER BY date_added DESC");

	if($image_query->num_rows > 0) {
		while($row = $image_query->fetch_assoc()) {
			$image_id = $row['id'];
			$date_added = $row['date_added'];
			$title = $row['title'];
			$description = $row['description'];
			$tags = $row['tags'];
		    $imageThumbURL = $row['image'];
			$imageURL = $row['image'];
			$deleted = $row['deleted'];
		?>
	
  <input type="checkbox" name="image_<?php echo $image_id; ?>" value="<?php echo $image_id; ?>"> <img src="<?php echo $imageURL; ?>" width="200px"> <?php echo $description; ?><hr>
</form>
 
 











	<?php } 
} 
?>






</div>
<?php include("includes/footer.php"); ?>