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
$isFollowing = $_GET['isFollowing'];
$option = $_GET['option'];
$upObjID = $_GET['upObjID'];
$latitude = floatval($_GET['lat']);
$longitude = floatval($_GET['lng']);
$dist = $_GET['distance'];
$distance = (int)$dist;
$category = $_GET['category'];
$sortBy = str_replace(' ', '', $_GET['sortBy']);
if ($_GET['keywords'] != '') {
   $keywords = preg_split ("/[\s,]+/", $_GET['keywords']);
}
$currentUser = ParseUser::getCurrentUser();

/*
echo '<p style="font-size: 12px;">
     LAT: '.$latitude.
'<br>LNG: '. $longitude.
'<br>DISTANCE: '.$distance.
'<br>CATEGORY: '.$category.
'<br>KEYWORDS: ' .json_encode($keywords). " COUNT: " .count($keywords).
'<br>SORT BY: ' .$sortBy.
'<br>FOLLOWING: ' .$isFollowing.
'<br>OPTIONS (FROM ACCOUNT): ' .$option.
'</p><br><br>';
*/

// query Ads
try {
   $query = new ParseQuery($ADS_CLASS_NAME);
   $query->equalTo($ADS_IS_REPORTED, false);

   // it's following.php
   if ($isFollowing == true) {
      $currentUser = ParseUser::getCurrentUser();
      $cuObjIdArr = array(); 
      array_push($cuObjIdArr, $currentUser->getObjectId());
      $query->containedIn($ADS_FOLLOWED_BY, $cuObjIdArr);
   
   // it's User profile page
   } else if ($upObjID != null) {
      $userPointer = new ParseUser($USER_CLASS_NAME, $upObjID);
      $userPointer->fetch();
      $query->equalTo($ADS_SELLER_POINTER, $userPointer);

      if ($option == 'selling'){ $query->equalTo($ADS_IS_SOLD, false);
      } else if ($option == 'sold'){ $query->equalTo($ADS_IS_SOLD, true); 
      } else if ($option == 'liked'){ $query->containedIn($ADS_LIKED_BY, array($userPointer->getObjectId())); }

   // it's index.php
   } else {
      
      // nearby Ads
      if ($latitude != null  &&  $longitude != null) {
         $currentLocation = new ParseGeoPoint($latitude, $longitude);

         $query->withinKilometers($ADS_LOCATION, $currentLocation, $distance);

         /* NOTE - UNCOMMENT THE LINE BELOW AND COMMENT THE LINE ABOVE IN CASE YOU WANT TO FILTER FOR MILES INSTEAD OF KILOMETERS: */
         // $query->withinMiles($ADS_LOCATION, $currentLocation, $distance);

      // nearby DEFAULT LOCATION COORDINATES
      } else { 
         $defaultLocation = new ParseGeoPoint($DEFAULT_LOCATION_LATITUDE, $DEFAULT_LOCATION_LONGITUDE);
         
         $query->withinKilometers($ADS_LOCATION, $defaultLocation, $DISTANCE_IN_KM); 

         /* NOTE - UNCOMMENT THE LINE BELOW AND COMMENT THE LINE ABOVE IN CASE YOU WANT TO FILTER FOR MILES INSTEAD OF KILOMETERS: */
         // $query->withinMiles($ADS_LOCATION, $defaultLocation, $DISTANCE_IN_KM);
      }

      // keywords
      if (count($keywords) != 0) { $query->containedIn($ADS_KEYWORDS, $keywords); }

      // category
      if ($category != "All") { $query->equalTo($ADS_CATEGORY, $category); }

      // sort by
      switch ($sortBy) {
         case 'Newest': $query->descending($ADS_CREATED_AT);
            break;
         case 'Price:lowtohigh': $query->ascending($ADS_PRICE);
            break;
         case 'Price:hightolow': $query->descending($ADS_PRICE);
            break;
         case 'MostLiked': $query->descending($ADS_LIKES);
            break;
         
         default: break;
      }// ./ sort by

   }// ./ If
 

   // perform query
   $adsArray = $query->find(); 
   if (count($adsArray) != 0) {
      for ($i = 0;  $i < count($adsArray); $i++) {
         // Parse Obj
         $adObj = $adsArray[$i];

         // image 1
         $adImg = $adObj->get($ADS_IMAGE1);
         
         // title
         $adTitle = $adObj->get($ADS_TITLE);
         $titleLength = strlen($adTitle);
         if ($titleLength > 30) { $adTitle = substr ($adTitle, 0, 30).'...'; } 
         
         // currency
         $adCurrency = $adObj->get($ADS_CURRENCY);

         // price
         $adPrice = $adObj->get($ADS_PRICE); ?>

         <!-- Ad card -->
         <div class="col-lg-3 col-md-5 portfolio-item">
            <div class="card">

               <!-- Sold badge -->
               <?php $isSold = $adObj->get($ADS_IS_SOLD);
               if ($isSold) { ?> <div class="card-sold-badge"><img src="assets/images/sold-badge.png"></div> <?php } ?>
               <a href="ad-info.php?adObjID=<?php echo $adObj->getObjectId() ?>"><img src="<?php echo $adImg->getURL() ?>"></a>
               <div class="card-body">
                  <p class="card-title"><a href="ad-info.php?adObjID=<?php echo $adObj->getObjectId() ?>"><?php echo $adTitle ?></a></p>
                  <p class="card-price"><?php echo $adCurrency ?><?php echo $adPrice ?></p>
               </div>
            </div>
         </div>
      <?php }// ./ For
      
   // no ads
   } else { ?>
      <div class="col-lg-12 col-md-12">
         <div class="text-center" style="margin-top: 40px; font-weight: 600">No Ads found.</div>
      </div>
   <?php }

// error
} catch (ParseException $e){ echo $e->getMessage(); }
?>



