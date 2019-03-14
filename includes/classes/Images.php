<?php 
class Images {
	private $image_obj;
	private $con;

	public function __construct($con, $user) {
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	// public function to get profile images
	public function getImages($user) {

		$page = $data['photos'];
		$profileUser = $data['profileUsername'];
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1)
			$start = 0;
		else
			$start = ($page - 1) * $limit;


		$image_query = mysqli_query($this->con, "SELECT * FROM images WHERE added_by='$username' AND deleted='no' ORDER BY date_added DESC");

		if($image_query->num_rows > 0) {
			while($row = $image_query->fetch_assoc()) {
				$imageThumbURL = $row['image'];
				$imageURL = $row['image'];
				$deleted = $row['deleted'];
			?>

			<a href="<?php echo $imageURL; ?>" data-fancybox="gallery" data-caption="<span style='font-size: 130%; font-weight: bold;'><?php echo $row["title"]; ?></span><br><?php echo $row['description']; ?>">
				<img src="<?php echo $imageThumbURL; ?>" width="100%" alt="" />

			</a>
			<?php
		}  
	} 
}


}




?>