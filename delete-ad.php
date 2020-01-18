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

// query and delete Chats relative to this Ad (if any)
try {
	$query = new ParseQuery($CHATS_CLASS_NAME);
	$query->equalTo($CHATS_AD_POINTER, $adObj);
	
	// perform query
	$chatsArray = $query->find();
	if (count($chatsArray) != 0){    
		for ($i = 0;  $i<count($chatsArray); $i++) {
	  	$cObj = $chatsArray[$i];
		  $cObj->destroy();
		}// ./ For
	}// ./ If

	// save...
	try { 
		$adObj->destroy();
		echo 'Your item has been successfully deleted!';
	// error
	} catch (ParseException $e){ echo $e->getMessage(); }

// error on saving 
} catch (ParseException $ex) { echo $ex->getMessage(); }
?>