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

// Parse Obj
$adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
$adObj->fetch();

// prepare data
$adObj->set($ADS_IS_REPORTED, true);
// save...
try { $adObj->save();
  echo "reported";
// error
} catch (ParseException $e){ echo $e->getMessage(); }
?>