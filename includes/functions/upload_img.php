<?php
// upload image to post
if(isset($_POST['post'])) {

  $uploadOK = 1;
  $imageName = $_FILES['fileToUpload']['name'];
  $errorMessage = "";

  if($imageName != "") {
    $targetDir = "assets/images/posts/";
    $imageName = $targetDir . uniqid() . basename($imageName);
    $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

    if($_FILES['fileToUpload']['size'] > 100000000) {
      $errorMessage = "Your file is too large.";
      $uploadOK = 0;
    }

    if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg") {
      $errorMessage = "Only images allowed.";
      $uploadOK = 0;
    }

    if($uploadOK) {
      if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)) {
        // image uploaded
      }
      else {
        // image did not upload
        $uploadOK = 0;
      }
    }
  }

    if($uploadOK) {
      $post = new Post($con, $userLoggedIn);
      $post->submitPost($_POST['post_text'], 'none', $imageName);
      header("Location: index.php"); 
    }
    else {
      echo "<div style='text-align: center;' class='alert alert-danger'>
            $errorMessage
            </div>";
    }

} ?>