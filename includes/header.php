<?php 
require 'config/config.php'; 
include_once("includes/classes/User.php");
include_once("includes/classes/Post.php");
include_once("includes/classes/Message.php");
include_once("includes/classes/Notification.php");

// set session when user logs in
if(isset($_SESSION['username'])) {
    // set variable to user logged in
    $userLoggedIn = $_SESSION['username'];
    // select username from user logged in query
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    // fetch array for query and set to user
    $user = mysqli_fetch_array($user_details_query);

} else {
    // if not logged in, send to register page
    header("Location: register.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>infastory</title>

    <!-- Javascript -->
    <script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="assets/js/popper.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="assets/js/bootbox.min.js"></script>
    <script type="text/javascript" src="assets/js/social.js"></script>
    <script type="text/javascript" src="assets/js/jquery.Jcrop.js"></script>
    <script type="text/javascript" src="assets/js/jcrop_bits.js"></script>
    <script type="text/javascript" src="assets/js/jquery.fancybox.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/jg_style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.fancybox.min.css">
    
    <?php 
    // check if cookie has been set from checkbox at login
    if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
 
        $email = $_COOKIE['email'];
        $password = $_COOKIE['password'];
       
        $query = mysqli_query($con, "SELECT username FROM users WHERE email='$email' AND password='$password'");
       
        if(mysqli_num_rows($query) > 0) {
       
          $row = mysqli_fetch_array($query);
       
          $_SESSION['username'] = $row['username'];
       
        }       
        else {      
          header("Location: register.php");
        }
      }

     ?>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar" style="background-color: #000;">
            <div class="sidebar-header" style="background-color: #000;">
                <h1><a href="index.php">infastory</a></h1>
            </div>

            <?php  
            // unread messages
            $messages = new Message($con, $userLoggedIn);
            $num_messages = $messages->getUnreadNumber();

            // unread notifications
            $notifications = new Notification($con, $userLoggedIn);
            $num_notifications = $notifications->getUnreadNumber();

            // friend request notifications
            $user_obj = new User($con, $userLoggedIn);
            $num_requests = $user_obj->getNumberOfFriendRequests();
            $num_friends = $user_obj->getFriendNumber();
            ?>

            <div class="search">
              <form action="search.php" method="GET" name="search_form">
                <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">

                <div class="button_holder">
                <img src="assets/images/icons/search.png">
                </div>
              </form>
                <div class="search_results">
                </div>
                <div class="search_results_footer_empty">
                </div>
            </div>

            <ul class="list-unstyled components">
              <div class="sidebarinfo" style="padding: 25px; margin-left: 10px;">
                <h4><a href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name'] . " " . $user['last_name']; ?></a></h4>
                
                <a href="<?php echo $userLoggedIn; ?>">
                <img src="<?php echo $user['profile_pic']; ?>" style="width: 85%"/>
                </a>
                
                <li>
                    <?php echo "Posts: " . $user['num_posts']; ?>
                </li>
                <li>
                    <?php echo "Likes: " . $user['num_likes']; ?>
                </li>
                <li>
                    <?php echo "Friends: " . $num_friends; ?>
                </li>
              </div>
            </ul>

            <!--<ul class="list-unstyled CTAs">
                <li>
                    <a href="#" class="download" style="color: #000;">Download source</a>
                </li>
            </ul>-->

            <ul class="list-unstyled components">
                <li><a href="<?php echo $userLoggedIn; ?>">Profile</a></li>
                <li><a href="edit-images.php">My Photos</a></li>
            </ul>
            
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i></a>

                        <a class="nav-link" href="notifications.php"><i class="fas fa-bell"></i>

                          <?php 
                          if($num_notifications > 0)
                          echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
                          ?></a>

                        <a class="nav-link" href="requests.php"><i class="fas fa-users"></i>
                          <?php 
                          if($num_requests > 0)
                          echo '<span class="notification_badge" id="unread_requests">' . $num_requests . '</span>';
                          ?></a>

                          <?php $message_obj = new Message($con, $userLoggedIn);

                            if(isset($_GET['u']))
                              $user_to = $_GET['u'];
                            else {
                              $user_to = $message_obj->getMostRecentUser();
                              if($user_to == false)
                                $user_to = 'new';
                            }

                            if($user_to != "new")
                              $user_to_obj = new User($con, $user_to);

                            if(isset($_POST['post_message'])) {
                              if(isset($_POST['message_body'])) {
                                $body = mysqli_real_escape_string($con, $_POST['message_body']);
                                $date = date("Y-m-d H:i:s");
                                $message_obj->sendMessage($user_to, $body, $date);
                              }
                            } 
                            ?>

                           <a class='nav-link' href='messages.php?u=<?php echo $user_to; ?>'><i class='fas fa-comments'></i>
                            <?php 
                          if($num_messages > 0)
                          echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
                          ?></a>

                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>
            
                     <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="nav navbar-nav ml-auto">
                           
                            <li class="nav-item">
                                <a class="nav-link" href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="includes/handlers/logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                            </li>
                            <li class="nav-item">
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>  
  <!-- // header area -->