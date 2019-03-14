<style>
.row img {
	padding-bottom: 20px;
}
</style>


<div class="row text-center text-lg-left">

<?php
$image_query = mysqli_query($con, "SELECT * FROM images WHERE added_by='$username' AND deleted='no' ORDER BY date_added DESC");

	if($image_query->num_rows > 0) {
		while($row = $image_query->fetch_assoc()) {
			$imageThumbURL = $row['image'];
			$imageURL = $row['image'];
			$deleted = $row['deleted'];
		?>
		<div class="col-lg-3 col-md-4 col-6">
		<a href="<?php echo $imageURL; ?>" data-fancybox="gallery" data-caption="<span style='font-size: 130%; font-weight: bold;'><?php echo $row["title"]; ?></span><br><?php echo $row['description']; ?>">
			<img src="assets/images/icons/spinner.gif" data-src="<?php echo $imageThumbURL; ?>" width="100%" alt="" />

		</a>	
	</div>
<?php	}
	}  ?>
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
