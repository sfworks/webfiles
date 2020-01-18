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
$adObjID = $_GET['adObjID'];
$adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
$adObj->fetch();

// currentUser
$currentUser = ParseUser::getCurrentUser();

// userPointer
$userPointer = $adObj->get($ADS_SELLER_POINTER);
$userPointer->fetch();

// prepare data
$adObj->set($ADS_IS_SOLD, true);

// save...
try { $adObj->save();
// error on saving 
} catch (ParseException $ex) { echo $ex->getMessage(); }
?>