<?php 
include("includes/header.php"); 

// upload image to post
if(isset($_POST['post'])) {

  $uploadOK = 1;
  // create image name variable with file to name
  $imageName = $_FILES['fileToUpload']['name'];
  // leave error message empty string
  $errorMessage = "";

  if($imageName != "") {
    // if imagename is not empty set directory for image
    $targetDir = "assets/users/" . $userLoggedIn . "/images/";
    // add a unique id to each image to avoid conflicts with duplicate names
    $imageName = $targetDir . uniqid() . basename($imageName);
    // get filetype extension
    $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);
    // set size limit for file to upload
    if($_FILES['fileToUpload']['size'] > 100000000) {
      // give error message if above
      $errorMessage = "Your file is too large.";

      $uploadOK = 0;
    }
    // check if (change file name to lower case) file is not an image type, send error message
    if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg" && strtolower($imageFileType)) {
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
      // new post class
      $post = new Post($con, $userLoggedIn);
      // submit post
      $post->submitPost($_POST['post_text'], 'none', $imageName);
      // refresh to show changes
      header("Location: index.php"); 
    }
    else {
      // else provide error image
      echo "<div style='text-align: center;' class='alert alert-danger'>
            $errorMessage
            </div>";
    }

}
?>    <div class="post_container">

        <form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
            <textarea name="post_text" id="post_text" placeholder="Say something..."></textarea><br>
               <input type="submit" name="post" id="post_button" value="Share">

                    <input type="file" name="fileToUpload" id="fileToUpload">

            <div class="line"></div>   
        </form>

            <div class="posts_area"></div>

        </div><!-- // post container -->
       </div><!-- // wrapper -->

    <script>
    // load posts to post area script

   $(function(){
 
       var userLoggedIn = '<?php echo $userLoggedIn; ?>';
       var inProgress = false;
 
       loadPosts(); //Load first posts
 
       $(window).scroll(function() {
           var bottomElement = $(".status_post").last();
           var noMorePosts = $('.posts_area').find('.noMorePosts').val();
 
           // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
           if (isElementInView(bottomElement[0]) && noMorePosts == 'false') {
               loadPosts();
           }
       });
 
       function loadPosts() {
           if(inProgress) { //If it is already in the process of loading some posts, just return
               return;
           }
          
           inProgress = true;
           $('#loading').show();
 
           var page = $('.posts_area').find('.nextPage').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'
 
           $.ajax({
               url: "includes/handlers/ajax_load_posts.php",
               type: "POST",
               data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
               cache:false,
 
               success: function(response) {
                   $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage
                   $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage
                   $('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage
 
                   $('#loading').hide();
                   $(".posts_area").append(response);
 
                   inProgress = false;
               }
           });
       }
 
       //Check if the element is in view
       function isElementInView (el) {
             if(el == null) {
                return;
            }
 
           var rect = el.getBoundingClientRect();
 
           return (
               rect.top >= 0 &&
               rect.left >= 0 &&
               rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
               rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
           );
       }
   });
 
   </script>

<?php include("includes/footer.php"); ?>