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
use Parse\ParseClient;
use Parse\ParseSessionStorage;
use Parse\ParseGeoPoint;

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
   $query->notContainedIn($MESSAGES_DELETED_BY, array($cuObjID) );
   $query->ascending('createdAt');;

   // perform query
   $messagesArray = $query->find();

   // no messages yet...
   if (count($messagesArray) == 0) { ?>
      <div class="text-center">No messages yet.</div>

   <?php 
   // messages are present!
   } else {
      for ($i = 0;  $i<count($messagesArray); $i++) {
         // Parse Obj
         $mObj = $messagesArray[$i];
                           
         // userPointer
         $userPointer = $mObj->get($MESSAGES_SENDER);
         $userPointer->fetch();
         $upObjID = $userPointer->getObjectId(); 

         // message
         $messageText = $mObj->get($MESSAGES_MESSAGE);

         // date
         $date = $mObj->getCreatedAt();
         $dateStr = date_format($date,"Y/m/d H:i:s");

         // ------------------------------------------------
         // MARK: - SENDER MESSAGE (CURRENT USER)
         // ------------------------------------------------
         if ($upObjID == $cuObjID ){ ?>
            <!-- sender message -->
            <div class="outgoing_msg">
               <div class="sent_msg">
               <?php // image
                  $sfile = $mObj->get($MESSAGES_IMAGE);
                  if ($sfile != null){ ?> <a href="<?php echo $sfile->getURL(); ?>" data-lightbox="gallery">
                     <img class="chat_img" src="<?php echo $sfile->getURL(); ?>"></a>  
                  <?php } else { ?> <p><?php echo $messageText ?></p> 
                  <?php } ?>
                  <div class="time_date"><?php echo time_ago($dateStr) ?></div>
               </div>
            </div>

            <?php 
            // ------------------------------------------------
            // MARK: - RECEIVER MESSAGE
            // ------------------------------------------------
            } else { ?>
               <!-- receiver message -->
               <div class="incoming_msg">
                  <div class="received_msg">
                     <div class="received_withd_msg">
                        <?php 
                           // image
                           $rfile = $mObj->get($MESSAGES_IMAGE);
                           if ($rfile != null){ ?> <a href="<?php echo $rfile->getURL() ?>" data-lightbox="gallery">
                              <img class="chat_img" src="<?php echo $rfile->getURL() ?>"></a>  
                           <?php } else { ?> <p><?php echo $messageText ?></p>
                           <?php } ?>
                           <div class="time_date"><?php echo time_ago($dateStr) ?></div>
                           </div>
                           </div>
                        </div>
            <?php }// ./ If     
         }// ./ For
   }// ./ If

// error
} catch (ParseException $e){ echo $e->getMessage(); }


// format date in time ago
function time_ago($date) {
    if (empty($date)) {
        return "No date provided";
    }
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
    $now = time();
    $unix_date = strtotime($date);
    // check validity of date
    if (empty($unix_date)) {
        return "Bad date";
    }
    // is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if ($difference != 1) {
        $periods[$j].= "s";
    }
    return "$difference $periods[$j] {$tense}";
}
?>



