<?php 
include("includes/header.php"); 
include("includes/form_handlers/image_edit.php");
include("upload_images.php");
?>
<div class="post_container">

<style>
.photos img {
	width: 150px;
}
.editPhoto {
	border: 2px solid #408000;
}
.photos {
	display: block;
	float: left;
}
.modal-backdrop {
    /* bug fix - no overlay */    
    display: none;    
}
</style>	
		
<div class="page-header">
	<h1>Upload images</h1>
</div>
	<div class="panel-body">
		<form method="post" enctype="multipart/form-data" name="formUploadFile" id="uploadForm" action="edit-images.php">
			<div class="form-group">
				<label for="exampleInputFile">Select file to upload:</label>
				<input type="file" id="exampleInputFile" name="files[]" multiple="multiple">
				<p class="help-block"><span class="label label-info">Note:</span> Please, Select the only images (.jpg, .jpeg, .png, .gif)</p>
			</div>			
			<button type="submit" class="btn btn-primary" name="btnSubmit" >Start Upload</button>
		</form>
	</div>
<br>
<p>Click on any image to update details.</p>

<?php
$image_query = mysqli_query($con, "SELECT * FROM images WHERE added_by='$userLoggedIn' AND deleted='no' ORDER BY date_added DESC");

	if($image_query->num_rows > 0) {
		while($row = $image_query->fetch_assoc()) {
			$image_id = $row['id'];
			$date_added = $row['date_added'];
			$title = $row['title'];
			$description = $row['description'];
		    $imageThumbURL = $row['image'];
			$imageURL = $row['image'];
			$deleted = $row['deleted'];
		?>

<!-- Button trigger modal -->
<button type="button" class="btn" data-toggle="modal" data-target="#editImage<?php echo $image_id; ?>">
<div class="photos">
	<img src="assets/images/icons/spinner.gif" data-src="<?php echo $imageURL; ?>">
</div>
</button>

<!-- Modal -->
<div class="modal fade" id="editImage<?php echo $image_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Image Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

      	<form action="edit-images.php" method="POST">
      		<input type="hidden" name="id" value="<?php echo $image_id; ?>">
      		<input type="submit" name="delete_image" id="deleteImage<?php echo $image_id; ?>" class="delete-image" onclick="return confirm('Are you sure you want to delete this item?');" value="Delete">
      	</form>

      	<img src="<?php echo $imageURL; ?>" width="100%">
      	<br><br>

      	<form action="edit-images.php" method="POST">

      		<input type="hidden" name="id" value="<?php echo $image_id; ?>">

	        <div class="col">
	            <p>Title:
	                <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
	            </p>
	        </div>
	        <div class="col">
	            <p>Description:
	                <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
	            </p>
	        </div>

      </div>

      <div class="modal-footer">
        <button type="button" name="close" class="btn btn-secondary" data-dismiss="modal">Close</button>

        <input type="submit" name="editImage" class="btn btn-primary" value="Save Changes">
        
    </form>
        
      </div>
    </div>
  </div>
</div>

<?php } 
} 
?>
</div>
<script>
window.addEventListener('load', function(){
    var allimages= document.getElementsByTagName('img');
    for (var i=0; i<allimages.length; i++) {
        if (allimages[i].getAttribute('data-src')) {
            allimages[i].setAttribute('src', allimages[i].getAttribute('data-src'));
        }
    }
}, false)
</script>
<?php include("includes/footer.php"); ?>