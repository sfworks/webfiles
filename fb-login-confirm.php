<?php
require 'autoload.php';
include 'Configurations.php';

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
use Parse\ParseSessionStorage;
// session_start();
?>
<?php if ($_SESSION['FBID']): ?>
<?php 

$userID = $_SESSION['FBID'];

ParseUser::logInWithFacebook($userID, $_SESSION['TOKEN']);
     
$currentUser = ParseUser::getCurrentUser();

// Get user data
$fullname = $_SESSION['FULLNAME'];
$email = $_SESSION['EMAIL'];

// Make username out of full name
$nameLowercase = strtolower($fullname);
$arr =  explode(" ", $nameLowercase);
$username = '';
foreach($arr as $w){ $username .= $w; }

$currentUser->set("username", $username);
if($email == null) {
    $email = $userID.'@facebook.com';
    $currentUser->set("email", $email);
} else {
    $currentUser->set("email", $email);
}


// Get Avatar
$avatarPath = "https://graph.facebook.com/$userID/picture?type=large";
$file = ParseFile::createFromFile($avatarPath, "avatar.jpg");
$file->save();
$url = $file->getURL();
$currentUser->set("avatar", $file);
       
// Save other data
$currentUser->set("isReported", false);
$currentUser->set("fullName", $fullname);
$currentUser->setArray("hasBlocked", array());

/*
print_r($username.'<br>');
print_r($email.'<br>');
// print_r($fullname.'<br>');
echo '<img src="https://graph.facebook.com/'.$userID.'/picture?type=large">';
*/

try {
    $currentUser->save();
    // Go back to index.php
    header("Refresh:1; url=index.php");
	
    echo '
        <div class="text-center">
            <div class="alert alert-success">You have logged in with Facebook, please wait...</div>
        </div>
    ';
    // error
    } catch (ParseException $ex) {  
		// Go back to index.php
    	header("Refresh:1; url=index.php");
				
		echo '
            <div class="text-center">
                <div class="alert alert-danger">
                    <em class="fa fa-exclamation"></em> '.$ex->getMessage().'
                </div>
             </div>
        ';				 
}
?>
<?php else: ?> 
<?php endif ?>

<!-- header -->
<?php include 'header.php' ?>

<body>
    <!-- container -->
    <div class="container">

        <div class="row">
            <div class="text-center"></div>
        </div><!-- ./ row -->
    </div><!-- /.container -->

<!-- footer -->
<?php include 'footer.php' ?>

</body>
</html>