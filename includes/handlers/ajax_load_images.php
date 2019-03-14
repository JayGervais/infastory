<?php 
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Post.php");

$limit = 7; // number of posts to be loaded per call

$images = new Images($con, $_REQUEST['userLoggedIn']);
$posts->getImages($_REQUEST, $limit);


?>