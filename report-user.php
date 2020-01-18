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
$upObjID = $_GET['upObjID'];

// Parse User
$userObj = new ParseUser($USER_CLASS_NAME, $upObjID);
$userObj->fetch();

// Run ParseCloud function
ParseCloud::run( "reportUser", array("userId" => $upObjID, "reportMessage" => 'Inappropriate/offensive User') );

// Query and Report all Ads of this user (if any)
try {
	$query = new ParseQuery($ADS_CLASS_NAME);
    $query->equalTo($ADS_SELLER_POINTER, $userObj);
    // find objects
    $adsArray = $query->find();      
    for ($i = 0;  $i < count($adsArray); $i++) {
    	$adObj = $adsArray[$i];
        $adObj->set($ADS_IS_REPORTED, true);
        $adObj->save();
    }
// error
} catch (ParseException $e){ echo $e->getMessage(); }
?>