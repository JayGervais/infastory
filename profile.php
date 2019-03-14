<?php 
include("includes/header.php");

// create new message object from class
$message_obj = new Message($con, $userLoggedIn);
 
// check if profile username is set 
if(isset($_GET['profile_username'])) {
  // assign profile username to variable
  $username = $_GET['profile_username'];
  // create query selecting users where username is username
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
  // fetch user details array in user_array
  $user_array = mysqli_fetch_array($user_details_query);
  // create number of friends variable selecting friend array
  $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}

if(isset($_POST["post_button"])) {
 
  if($userLoggedIn == $username) {
    $userTo = "none";
  }
  else {
    $userTo = $username;
  }
  
// upload image to post
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
      $post = new Post($con, $userLoggedIn);
      $post->submitPost($_POST['post_body'], $userTo, $imageName);
    }
    else {
      // else provide error image
      echo "<div style='text-align: center;' class='alert alert-danger'>
            $errorMessage
            </div>";
    }
} 

// remove friend handler
if(isset($_POST['remove_friend'])) {
  $user = new User($con, $userLoggedIn);
  $user->removeFriend($username);
}
 
// add friend handler
if(isset($_POST['add_friend'])) {
  $user = new User($con, $userLoggedIn);
  $user->sendRequest($username);
}
 
if(isset($_POST['respond_request'])) {
  header("Location: requests.php");
}
 
if(isset($_POST['post_message'])) {
  if(isset($_POST['message_body'])) {
    $body = mysqli_real_escape_string($con, $_POST['message_body']);
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($username, $body, $date);
  }

  $link = '#profileTabs a[href="#messages_div"]';
  echo "<script> 
          $(function() {
              $('" . $link ."').tab('show');
          });
        </script>";
}

?>
<style>
  .post_form {
    width: 100%;
    padding-bottom: 20px;
  }
</style>

<div class="post_container">
<div class="row">

<div class="col-sm-12">
  <h4><a href="<?php echo $user_array['username'] ?>"><?php echo $user_array['first_name'] . " " . $user_array['last_name']; ?></a></h4>
</div>
<div class="col-sm-5">

  <div class="profile_img">
    <img src="<?php echo $user_array['profile_pic']; ?>" width="100%">
  </div>

    <div class="profile_info">
      
      <p><?php echo "Posts: " . $user_array['num_posts']; ?><br>
      <?php echo "Likes: " . $user_array['num_likes']; ?><br>
      <?php echo "Friends: " . $num_friends; ?><br>
      <?php  
      $mutual_friends_obj = new User($con, $username); 
        if($userLoggedIn != $username) {
          echo $mutual_friends_obj->getMutualFriends($username) . " Mutual friends";
    } 
    ?>
    </p>
  </div>
</div><!-- // col-3 -->

<div class="col-sm-7">

    <div class="profile_description"><?php echo $user_array['description']; ?>
    </div>

</div><!-- // col-6 -->
</div> <!-- // row -->

<div class="profile_row">
  <div class="row">
  <div class="col-6">
  <?php  
  if($username == $userLoggedIn) {
    echo '';
  }
  else {
    echo "<form action='messages.php'>
              <button type='submit' class='btnSendMessage'><i class='fas fa-comment'></i> Send Message</button>
              <input type='hidden' name='u' value='$username' />
          </form>";
  }

  ?>

  </div>
  <div class="col-6">

  <div class="friend_request_button">
    <form action="<?php echo $username; ?>" method="POST">
      <?php 
 
      // create new instance of User class
      $profile_user_obj = new User($con, $username); 
      // if profile is closed, send user to account closed notification page.
      if($profile_user_obj->isClosed()) {
        header("Location: user_closed.php");
      }
      // set user object for User as logged in
      $logged_in_user_obj = new User($con, $userLoggedIn);
      // check to see if user logged in is not = to own account
      if($userLoggedIn != $username) {
        // if logged in user is a friend, show removed friend button
        if($logged_in_user_obj->isFriend($username)) {
          echo '<button type="submit" name="remove_friend"><i class="fas fa-user-minus"></i> Remove Friend</button><br>';
        } 
        // else if they have received a request to be friends, show response button
        else if($logged_in_user_obj->didReceiveRequest($username)) {
          echo '<button type="submit" name="respond_request"><i class="fas fa-reply"></i> Repond to Request</button><br>';
        } 
        // else if user did send request to user, show request sent button
        else if($logged_in_user_obj->didSendRequest($username)) {
          echo '<button type="submit" name=""><i class="fas fa-user-check"></i> Request Sent<br>';
        }
        else {
          // echo add friend button
          echo '<button type="submit" name="add_friend"><i class="fas fa-user-plus"></i> Add Friend<br>';
        }
      }
 
      ?>
    </form>
    </div><!-- // friend_request_button -->

  </div>
</div>
  </div><!-- // profile_row -->

  <hr class="aboveProfile">

  <div class="profile_main_column column">
 
 
    <ul class="nav nav-tabs" role="tablist" id="profileTabs">
      <li class="nav-item"><a class="nav-link show active" href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a></li>

      <li class="nav-item"><a class="nav-link" href="#images_div" aria-controls="about_div" role="tab" data-toggle="tab">Images</a></li>
    </ul>
 
    <div class="tab-content">
 
      <div role="tabpanel" class="tab-pane fade in show active" id="newsfeed_div">

        
 
        <form class="post_form" action="" method="POST" enctype="multipart/form-data">
            <textarea name="post_body" id="post_text" placeholder="Say something..."></textarea>  
            <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
            <input type="hidden" name="user_to" value="<?php echo $username; ?>">
            <input type="submit" name="post_button" id="post_button" value="Post">
            <input type="file" name="fileToUpload" id="fileToUpload">
        </form>

          <div class="posts_area"></div>
          <img id="loading" src="assets/images/icons/loading.gif">
      </div>

        <div role="tabpanel" class="tab-pane fade in active" id="images_div">

          <?php include("images.php"); ?>

  </div><!-- // main column -->
  </div><!-- // tab content -->

<script>
   $(function(){
 
       var userLoggedIn = '<?php echo $userLoggedIn; ?>';
 
       var profileUsername = '<?php echo $username; ?>';
 
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
               url: "includes/handlers/ajax_load_profile_posts.php",
               type: "POST",
               data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
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
 
</div><!-- // post_container -->
<?php include("includes/footer.php"); ?>