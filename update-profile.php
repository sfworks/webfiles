<?php include 'Configurations.php';
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
use Parse\ParseGeoPoint;
use Parse\ParseClient;
use Parse\ParseSessionStorage;
// session_start();

/*--- VARIABLES ---*/
// currentUser
$currentUser = ParseUser::getCurrentUser();
$cuObjectID = $currentUser->getObjectId();

$username = $_GET['username'];
$fullName = $_GET['fullName'];
$email = $_GET['email'];
$bio = $_GET['bio'];
$fileURL = $_GET['fileURL'];

// prepare data
$currentUser->set($USER_USERNAME, $username);
$currentUser->set($USER_FULLNAME, $fullName);
$currentUser->set($USER_EMAIL, $email);
$currentUser->set($USER_BIO, $bio);

// avatar
$file = ParseFile::createFromFile($fileURL, "avatar.jpg");
$file->save();
$currentUser->set($USER_AVATAR, $file);
$currentUser->save();

// save...
try { $currentUser->save();
  echo 'Your Profile has been updated!';
// error 
} catch (ParseException $ex) { echo $ex->getMessage(); }
?>