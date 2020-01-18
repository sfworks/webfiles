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

/*--- variables ---*/
$adObjID = $_GET['adObjID'];
$upObjID = $_GET['upObjID']; 
$messTxt = $_GET['messTxt']; 
$fileURL = $_GET['fileURL']; 
// currentUser
$currentUser = ParseUser::getCurrentUser();
$cuObjID = $currentUser->getObjectId();
// adObj
$adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
$adObj->fetch();
// userObj
$userObj = new ParseUser($USER_CLASS_NAME, $upObjID);
$userObj->fetch();
// users ID's
$uId1 = $cuObjID . $upObjID;
$uId2 = $upObjID . $cuObjID;
$messID = array($uId1, $uId2); 

$messTxtStr = "";

// new Parse Obj
$mObj = new ParseObject($MESSAGES_CLASS_NAME);

// prepare data
$mObj->set($MESSAGES_SENDER, $currentUser);
$mObj->set($MESSAGES_RECEIVER, $userObj);
$mObj->set($MESSAGES_AD_POINTER, $adObj);
$mObj->set($MESSAGES_MESSAGE_ID, $cuObjID.$upObjID);
$mObj->setArray($MESSAGES_DELETED_BY, array());

if ($fileURL != "NONE"){
  $messTxtStr = '[Photo]';
  $file = ParseFile::createFromFile($fileURL, "image.jpg");
  $file->save();
  $mObj->set($MESSAGES_IMAGE, $file);
  $mObj->set($MESSAGES_MESSAGE, $messTxtStr);
  $mObj->save();
} else {
  $messTxtStr = $messTxt;
  $mObj->set($MESSAGES_MESSAGE, $messTxtStr);
}

// save...  
try { $mObj->save();
  echo 'Message sent!';
  
  // Send Push Notification
  $pushMessage = $currentUser->getUsername().' | ' .$adObj->get($ADS_TITLE). ': "'.$messTxtStr.'"';
  $alert = array("alert" => $pushMessage);

  // Send Push to iOS and Android devices
  ParseCloud::run( "pushiOS", array("userObjectID" => $upObjID, "data" => $alert) );
  ParseCloud::run( "pushAndroid", array("userObjectID" => $upObjID, "data" => $alert) );


  // query Chats
  try {
    $query = new ParseQuery($CHATS_CLASS_NAME);
    $query->equalTo($CHATS_AD_POINTER, $adObj);
    $query->containedIn($CHATS_ID, $messID);
     
    // perform query
    $chatsArray = $query->find();    

    // new Parse Obj
    $cObj = new ParseObject($CHATS_CLASS_NAME);
    // existing Parse obj
    if (count($chatsArray) != 0){ $cObj = $chatsArray[0]; }

    // save last message in Chats
    $cObj->set($CHATS_LAST_MESSAGE, $messTxt);
    $cObj->set($CHATS_SENDER, $currentUser);
    $cObj->set($CHATS_RECEIVER, $userObj);
    $cObj->set($CHATS_ID, $cuObjID.$upObjID);
    $cObj->set($CHATS_AD_POINTER, $adObj);
    $cObj->setArray($CHATS_DELETED_BY, array());

    // save...  
    try { $cObj->save();
      echo '\nChats last message saved/updated!';
    // error
    } catch (ParseException $e) { echo $e->getMessage(); }


  // error
  } catch (ParseException $e) { echo $e->getMessage(); }

// error
} catch (ParseException $e) { echo $e->getMessage(); }
?>