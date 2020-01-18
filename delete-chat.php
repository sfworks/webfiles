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
$currentUser = ParseUser::getCurrentUser();
$cuObjID = $currentUser->getObjectId();

// adObj
$adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
$adObj->fetch();

// users ID's
$uId1 = $cuObjID . $upObjID;
$uId2 = $upObjID . $cuObjID;
$messID = array($uId1, $uId2); 

// query Messages
try {
   $query = new ParseQuery($MESSAGES_CLASS_NAME);
   $query->equalTo($MESSAGES_AD_POINTER, $adObj);
   $query->containedIn($MESSAGES_MESSAGE_ID, $messID);
   $query->notContainedIn($MESSAGES_DELETED_BY, array($cuObjID));
   
   // perform query
   $messagesArray = $query->find();    
   for ($i = 0;  $i<count($messagesArray); $i++) {
      // Parse Obj
      $mObj = $messagesArray[$i];
                        
      // userPointer
      $userPointer = $mObj->get($MESSAGES_SENDER);
      $userPointer->fetch();
      $upObjID = $userPointer->getObjectId(); 

      // Add currentUser's objectId to all messages of this chat
      $deletedBy = $mObj->get($MESSAGES_DELETED_BY);
      array_push($deletedBy, $cuObjID);
      $mObj->setArray($MESSAGES_DELETED_BY, $deletedBy);
      try { $mObj->save();
      // error
      } catch (ParseException $e){ echo $e->getMessage(); }              
   }// ./ For


   // Query Chats and add currentUser's objectId to the deletedBy column
   try {
   		$query = new ParseQuery($CHATS_CLASS_NAME);
	   	$query->equalTo($CHATS_AD_POINTER, $adObj);
	   	$query->containedIn($CHATS_ID, $messID);
	   	// perform query
   		$chatsArray = $query->find();    
   		for ($i = 0;  $i<count($chatsArray); $i++) {
   			// Parse Obj
      		$cObj = $chatsArray[$i];

      		$cDeletedBy = $cObj->get($CHATS_DELETED_BY);
            array_push($cDeletedBy, $cuObjID);
            $cObj->setArray($CHATS_DELETED_BY, $cDeletedBy);
            try { $cObj->save();
            // error
		    } catch (ParseException $e){ echo $e->getMessage(); }  
                        
   		}// ./ For
   // error
   } catch (ParseException $e){ echo $e->getMessage(); }      

   // echo data
   echo 'Chat messages have been deleted for you.';

// error
} catch (ParseException $e){ echo $e->getMessage(); }
?>

