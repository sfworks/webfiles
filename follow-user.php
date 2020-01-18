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
$followTxt = $_GET['followTxt'];

// currentUser
$currentUser = ParseUser::getCurrentUser();

// userObj
$userObj = new ParseUser($USER_CLASS_NAME, $upObjID);
$userObj->fetch();


//---------------------------------
// MARK - FOLLOW USER
//---------------------------------
if ($followTxt == 'follow') {  
    $fObj = new ParseObject($FOLLOW_CLASS_NAME);   
    $fObj->set($FOLLOW_CURRENT_USER, $currentUser);
    $fObj->set($FOLLOW_IS_FOLLOWING, $userObj);

    // save...
    try { $fObj->save();
      echo 'following';

      try {
        // query Ads and add currentUser as follower
        $query = new ParseQuery($ADS_CLASS_NAME);
        $query->equalTo($ADS_SELLER_POINTER, $userObj);
        // perform query
        $adsArray = $query->find(); 
        for ($i=0;  $i<count($adsArray); $i++) {
          // Parse Obj
          $adObj = $adsArray[$i];
          $followedBy = $adObj->get($ADS_FOLLOWED_BY);
          array_push($followedBy, $currentUser->getObjectId());
          $adObj->setArray($ADS_FOLLOWED_BY, $followedBy);
          // save
          try { $adObj->save(); } catch (ParseException $ex) { echo $ex->getMessage(); }
        }// ./ for

      // error 
      } catch (ParseException $ex) { echo $ex->getMessage(); }

    // error 
    } catch (ParseException $ex) { echo $ex->getMessage(); }


//---------------------------------
// MARK - UNFOLLOW USER
//---------------------------------
} else {
  try {
    // query Follow
    $query = new ParseQuery($FOLLOW_CLASS_NAME);
    $query->equalTo($FOLLOW_IS_FOLLOWING, $userObj);
    $query->equalTo($FOLLOW_CURRENT_USER, $currentUser);
    // perform query
    $followArray = $query->find(); 
    // Parse Obj
    $fObj = $followArray[0];
    
    // delete follow
    try { $fObj->destroy();
      echo 'follow';

      // query Ads and rmove currentUser as follower      
      try {
        $query = new ParseQuery($ADS_CLASS_NAME);
        $query->equalTo($ADS_SELLER_POINTER, $userObj);
        // perform query
        $adsArray = $query->find(); 
        for ($i=0;  $i<count($adsArray); $i++) {
          // Parse Obj
          $adObj = $adsArray[$i];
          $followedBy = $adObj->get($ADS_FOLLOWED_BY);
          unset($followedBy[array_search($currentUser->getObjectId(),$followedBy)]);
          $adObj->setArray($ADS_FOLLOWED_BY, $followedBy);
          // save
          try { $adObj->save(); } catch (ParseException $ex) { echo $ex->getMessage(); }
        }// ./ for

      // error 
      } catch (ParseException $ex) { echo $ex->getMessage(); }

    // error 
    } catch (ParseException $ex) { echo $ex->getMessage(); }
    
  // error 
  } catch (ParseException $ex) { echo $ex->getMessage(); }

}
?>