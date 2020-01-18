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
// currentUser
$currentUser = ParseUser::getCurrentUser();
$cuObjectID = $currentUser->getObjectId();

$category = $_GET['category'];
$title = $_GET['title'];
$priceStr = $_GET['price'];
$price = (int)$priceStr;
$isNegotiable = $_GET['isNegotiable'];
$description = $_GET['description'];
$fileURL1 = $_GET['fileURL1'];
$fileURL2 = $_GET['fileURL2'];
$fileURL3 = $_GET['fileURL3'];
$fileURL4 = $_GET['fileURL4'];
$fileURL5 = $_GET['fileURL5'];
// coordinates
$lat = $_GET['latitude'];
$lng = $_GET['longitude'];
$latitude = (float) $lat;
$longitude = (float) $lng;
// set location coordinates
$location = new ParseGeoPoint($latitude, $longitude);
// keywords
$kStr = $description. " " .$title. " " .$currentUser->getUsername();
$keywords = explode( " ", strtolower($kStr) );
array_push($keywords, "");
// sell string
$sellOrUpdate = "";
              
// Parse obj
$adObj = new ParseObject($ADS_CLASS_NAME);

//---------------------------------
// MARK - SAVE AD
//---------------------------------
if ($adObjID == null) {     
    $adObj->setArray($ADS_LIKED_BY, array());
    $adObj->setArray($ADS_FOLLOWED_BY, array());
    $adObj->set($ADS_LIKES, 0);
    $adObj->set($ADS_VIEWS, 0);
    $adObj->set($ADS_IS_REPORTED, false);
    $adObj->set($ADS_IS_SOLD, false);
    
    $sellOrUpdate = " posted a new Item: ";
  } else {
    $adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
    $adObj->fetch();

    $sellOrUpdate = " updated an Item: ";
  }
  
  // prepare data
  $adObj->set($ADS_SELLER_POINTER, $currentUser);
  $adObj->set($ADS_TITLE, $title);
  $adObj->set($ADS_CATEGORY, $category);
  $adObj->set($ADS_PRICE, $price);
  $adObj->set($ADS_CURRENCY, $CURRENCY_CODE);
  $adObj->set($ADS_LOCATION, $location);
  $adObj->set($ADS_DESCRIPTION, $description);
  $adObj->setArray($ADS_KEYWORDS, $keywords);
  if ($isNegotiable == 'true') { $adObj->set($ADS_IS_NEGOTIABLE, true);
  } else { $adObj->set($ADS_IS_NEGOTIABLE, false); }
            
  $file1 = ParseFile::createFromFile($fileURL1, "image1.jpg");
    $file1->save();
    $adObj->set($ADS_IMAGE1, $file1);
    $adObj->save();
  
    if ($fileURL2 != null || $fileURL2 != "") {
        $file2 = ParseFile::createFromFile($fileURL2, "image2.jpg");
        $file2->save();
        $adObj->set($ADS_IMAGE2, $file2);
        $adObj->save();
    }
    if ($fileURL3 != null || $fileURL3 != "") {
        $file3 = ParseFile::createFromFile($fileURL3, "image3.jpg");
        $file3->save();
        $adObj->set($ADS_IMAGE3, $file3);
        $adObj->save();
    }
    if ($fileURL4 != null || $fileURL4 != "") {
        $file4 = ParseFile::createFromFile($fileURL4, "image4.jpg");
        $file4->save();
        $adObj->set($ADS_IMAGE4, $file4);
        $adObj->save();
    }
    if ($fileURL5 != null || $fileURL5 != "") {
        $file5 = ParseFile::createFromFile($fileURL5, "image5.jpg");
        $file5->save();
        $adObj->set($ADS_IMAGE5, $file5);
        $adObj->save();
    }

  // save...
  try { $adObj->save();

    // Store followedBy IDs for this Ad
    try {
      $query = new ParseQuery($FOLLOW_CLASS_NAME);
      $query->equalTo($FOLLOW_IS_FOLLOWING, $currentUser);
    
      // perform query
      $fArray = $query->find();
      if (count($fArray) != 0) {
        $followedBy = $adObj->get($ADS_FOLLOWED_BY);

        for ($i = 0;  $i<count($fArray); $i++) {
          // Parse Obj
          $fObj = $fArray[$i];

          // userPointer
          $userPointer = $fObj->get($FOLLOW_CURRENT_USER);
          $userPointer->fetch();
          $upObjID = $userPointer->getObjectId();

          array_push($followedBy, $userPointer->getObjectId());

          if ($i == count($fArray)-1) { 
            $adObj->setArray($ADS_FOLLOWED_BY, $followedBy);
            try { $adObj->save();            
              
              // Send Push Notification
              $pushMessage = $currentUser->get($USER_FULLNAME). "" .$sellOrUpdate. " '" .$adObj->get($ADS_TITLE). "'";
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

        }// ./ for

      }// ./ if
       
    // error
    } catch (ParseException $e){ echo $e->getMessage(); }

  // error 
  } catch (ParseException $ex) { echo $ex->getMessage(); }
?>