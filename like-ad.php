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

// currentUser
$currentUser = ParseUser::getCurrentUser();

// userPointer
$userPointer = $adObj->get($ADS_SELLER_POINTER);
$userPointer->fetch();
$upObjID = $userPointer->getObjectId();

// likeBy
$likedBy = $adObj->get($ADS_LIKED_BY);


// UNLIKE AD
if (in_array($currentUser->getObjectId(), $likedBy)) {
  $likedBy = array_diff($likedBy, array($currentUser->getObjectId()));
  $adObj->setArray($ADS_LIKED_BY, $likedBy);
  $adObj->increment($ADS_LIKES, -1);
  // save...
  try { $adObj->save();
    echo "unliked";
  // error
  } catch (ParseException $e){ echo $e->getMessage(); }

// LIKE AD
} else {
  array_push($likedBy, $currentUser->getObjectId());
  $adObj->setArray($ADS_LIKED_BY, $likedBy);
  $adObj->increment($ADS_LIKES, 1);
  // save...
  try { $adObj->save();
    echo "liked";

	
    // Send Push Notification
    $pushMessage = $currentUser->get($USER_FULLNAME)." liked your Ad: '" .$adObj->get($ADS_TITLE). "'";
    $alert = array("alert" => $pushMessage);
    ParseCloud::run( "pushiOS", array("userObjectID" => $upObjID, "data" => $alert) );
    ParseCloud::run( "pushAndroid", array("userObjectID" => $upObjID, "data" => $alert) );
    

    // Save a new Notification 
    $nObj = new ParseObject($NOTIFICATIONS_CLASS_NAME);
    $nObj->set($NOTIFICATIONS_TEXT, $pushMessage);
    $nObj->set($NOTIFICATIONS_CURRENT_USER, $currentUser);
    $nObj->set($NOTIFICATIONS_OTHER_USER, $userPointer);
    try { $nObj->save();
    // error
    } catch (ParseException $e){ echo $e->getMessage(); }


  // error
  } catch (ParseException $e){ echo $e->getMessage(); }
}
?>