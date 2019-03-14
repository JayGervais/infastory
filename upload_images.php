<?php
	if(isset($_POST["btnSubmit"])){		
		$errors = array();
		
		$extension = array("jpeg","jpg","png","gif");
		
		$bytes = 1000000;
		$allowedKB = 10485760000;
		$totalBytes = $allowedKB * $bytes;

		$imgDir = "assets/users/".$userLoggedIn."/images/";
		
		if(isset($_FILES["files"])==false)
		{
			echo "<b>Please, Select the files to upload!!!</b>";
			return;
		}
		
		// $conn = mysqli_connect("localhost","root","","phpfiles");
		
		foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name)
		{
			$uploadThisFile = true;
			
			$file_name=$_FILES["files"]["name"][$key];
			$file_tmp=$_FILES["files"]["tmp_name"][$key];
			
			$ext=pathinfo($file_name,PATHINFO_EXTENSION);

			if(!in_array(strtolower($ext),$extension))
			{
				array_push($errors, "File type is invalid. Name:- ".$file_name);
				$uploadThisFile = false;
			}				
			
			if($_FILES["files"]["size"][$key] > $totalBytes){
				array_push($errors, "File size is too big. Name:- ".$file_name);
				$uploadThisFile = false;
			}
			
			/* if(file_exists($imgDir.$_FILES["files"]["name"][$key]))
			{
				array_push($errors, "File is already exist. Name:- ". $file_name);
				$uploadThisFile = false;
			} */
			
			if($uploadThisFile){
				$filename = basename($file_name,$ext);
				$newFileName = uniqid().$filename.$ext;

				move_uploaded_file($_FILES["files"]["tmp_name"][$key],$imgDir.$newFileName);

				// current date and time
				$date_added = date("Y-m-d H:i:s");

				$imagePath = $imgDir.$newFileName;
				
				$query = "INSERT INTO images(date_added, added_by, image, deleted) VALUES('$date_added', '$userLoggedIn','$imagePath', 'no')";
				
				mysqli_query($con, $query);		
			}
		}
		
		header("Location: edit-images.php");
		
		$count = count($errors);
		
		if($count != 0){
			foreach($errors as $error){
				echo $error."<br/>";
			}
		}		
	}
?>