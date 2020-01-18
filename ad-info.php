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
//session_start();
?>
<!-- header -->
<?php include 'header.php' ?>
<body>
	<!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar fixed-top">
      <!-- navbar title -->
      <a id="navbar-brand"  class="navbar-brand" href="index.php"><?php echo $WEBSITE_NAME ?></a>
      <!-- title header -->
      <div class="title-header">Ad info</div>
      <!-- right menu button -->
      <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

    <!-- bottom navbar -->
    <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
        <a href="following.php"><img src="assets/images/tab_following.png" style="width: 44px; margin-left: 20px;"></a>
        <a href="notifications.php"><img src="assets/images/tab_notifications.png" style="width: 44px; margin-left: 20px;"></a>
        <a href="chats.php"><img src="assets/images/tab_chats.png" style="width: 44px; margin-left: 20px;"></a>
        <a href="account.php?option=selling"><img src="assets/images/tab_account.png" style="width: 44px; margin-left: 20px;"></a>
    </div><!-- ./ bottom navbar -->

    <!-- right sidebar menu -->
    <div id="right-sidebar" class="right-sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
        
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"> Home</a>
        <a href="following.php"><img src="assets/images/tab_following.png" style="width: 44px;"> Following</a>
        <a href="notifications.php"><img src="assets/images/tab_notifications.png" style="width: 44px;"> Notifications</a>
        <a href="chats.php"><img src="assets/images/tab_chats.png" style="width: 44px;"> Chats</a>
        <a href="account.php?option=selling"><img src="assets/images/tab_account.png" style="width: 44px;"> Account</a>
    </div>

    <!-- container -->
    <div class="container">
        <div class="row">
            <?php 
                $adObjID = $_GET['adObjID'];

                // currentUser
                $currentUser = ParseUser::getCurrentUser();

                // Parse Obj
                $adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
                $adObj->fetch();

                // title
                $title = $adObj->get($ADS_TITLE);

                // price
                $price = $adObj->get($ADS_PRICE);

                // negotiable
                $isNegotiable = $adObj->get($ADS_IS_NEGOTIABLE);

                // isSold
                $isSold = $adObj->get($ADS_IS_SOLD);

                // description
                $description = $adObj->get($ADS_DESCRIPTION);

                // views
                $views = $adObj->get($ADS_VIEWS);

                // location
                $aLocation = $adObj->get($ADS_LOCATION);
                $latitude = $aLocation->getLatitude();
                $longitude = $aLocation->getLongitude();

                // image1
                $file1 = $adObj->get($ADS_IMAGE1);
                $imageURL = $file1->getURL();
                                    
                // image2
                if ($adObj->get($ADS_IMAGE2) != null) {
                    $file2 = $adObj->get($ADS_IMAGE2);
                    $imageURL2 = $file2->getURL();
                }
                // image3
                if ($adObj->get($ADS_IMAGE3) != null) {
                    $file3 = $adObj->get($ADS_IMAGE3);
                    $imageURL3 = $file3->getURL();
                }
                // image4
                if ($adObj->get($ADS_IMAGE4) != null) {
                    $file4 = $adObj->get($ADS_IMAGE4);
                    $imageURL4 = $file4->getURL();
                }
                // image5
                if ($adObj->get($ADS_IMAGE5) != null) {
                    $file5 = $adObj->get($ADS_IMAGE5);
                    $imageURL5 = $file5->getURL();
                }

                // date
                $date = $adObj->getCreatedAt();
                $dateStr = date_format($date,"Y/m/d H:i:s");

                // userPointer
                $userPointer = $adObj->get($ADS_SELLER_POINTER);
                $userPointer->fetch();

                // avatar
                $avFile = $userPointer->get($USER_AVATAR);
                $avatarURL = $avFile->getURL();

                // fullname
                $upName = $userPointer->get($USER_FULLNAME);

                // hasBlocked
                $hasBlocked = $userPointer->get($USER_HAS_BLOCKED);

                // increment views
                $adObj->increment($ADS_VIEWS, 1);
                try { $adObj->save();
                // error
                } catch (ParseException $e){ echo $e->getMessage(); }
            ?>

            <!-- left column -->
            <div class="col-md-5">
                <div id="images" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#images" data-slide-to="0"></li>
                        <?php if ($adObj->get($ADS_IMAGE2) != null) { ?><li data-target="#images" data-slide-to="1"></li>
                        <?php } if ($adObj->get($ADS_IMAGE3) != null) { ?><li data-target="#images" data-slide-to="2"></li>
                        <?php } if ($adObj->get($ADS_IMAGE4) != null) { ?><li data-target="#images" data-slide-to="3"></li>
                        <?php } if ($adObj->get($ADS_IMAGE5) != null) { ?><li data-target="#images" data-slide-to="4"></li>
                        <?php } ?>
                    </ol>
                    <!-- images -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <a href="<?php echo $imageURL ?>" data-lightbox="gallery"><img class="d-block w-100" src="<?php echo $imageURL ?>"></a>
                        </div>
                        <?php if ($adObj->get($ADS_IMAGE2) != null) { ?>
                            <div class="carousel-item"><a href="<?php echo $imageURL2 ?>" data-lightbox="gallery"><img class="d-block w-100" src="<?php echo $imageURL2 ?>"></a></div>
                        <?php } if ($adObj->get($ADS_IMAGE3) != null) { ?>
                            <div class="carousel-item"><a href="<?php echo $imageURL3 ?>" data-lightbox="gallery"><img class="d-block w-100" src="<?php echo $imageURL3 ?>"></a></div>
                        <?php } if ($adObj->get($ADS_IMAGE4) != null) { ?>
                            <div class="carousel-item"><a href="<?php echo $imageURL4 ?>" data-lightbox="gallery"><img class="d-block w-100" src="<?php echo $imageURL4 ?>"></a></div>
                        <?php } if ($adObj->get($ADS_IMAGE5) != null) { ?>
                            <div class="carousel-item"><a href="<?php echo $imageURL5 ?>" data-lightbox="gallery"><img class="d-block w-100" src="<?php echo $imageURL5 ?>"></a></div>
                        <?php } ?>
                    </div>
                    <!-- Controls -->
                    <a class="carousel-control-prev" href="#images" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#images" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div><!-- ./ left column -->

            <!-- right column -->
            <div class="col-md-5" style="margin-top: 10px;">
                <!-- avatar -->
                <?php 
                    if ($currentUser) {
                	   if ($currentUser->getObjectId() == $userPointer->getObjectId()) { ?>
                        <a href="account.php">
                        <?php } else { ?> <a href="user-profile.php?upObjID=<?php echo $userPointer->getObjectId() ?>"> 
                        <?php }
                	} else { ?> <a href="user-profile.php?upObjID=<?php echo $userPointer->getObjectId() ?>">
                <?php } ?>
                <img class="center-cropped-60" src="<?php echo $avatarURL ?>"></a>

                <!-- fullname -->
                <?php 
                    if ($currentUser) {
                	   if ($currentUser->getObjectId() == $userPointer->getObjectId()) { ?>
                        <a href="account.php" style="text-decoration: none; margin-left: 10px; color: #333;">
            		  <?php } else { ?>
                        <a href="user-profile.php?upObjID=<?php echo $userPointer->getObjectId() ?>" style="text-decoration: none; margin-left: 10px; color: #333;">
            		  <?php } 
                    } else { ?>
                        <a href="user-profile.php?upObjID=<?php echo $userPointer->getObjectId() ?>" style="text-decoration: none; margin-left: 10px; color: #333;">
                <?php } ?>
            	<strong><?php echo $upName ?></strong></a>

                <!-- options button -->
                <?php 
                    if ($currentUser) {
                    	if ($currentUser->getObjectId() == $userPointer->getObjectId()) { ?>
                            <a href="#" onclick="fireCurrentUserOptionsAlert()"><div style="float: right; font-size: 24px; color: #777;"> &nbsp; ••• &nbsp; </div></a>
                    	<?php } else { ?>
                            <a href="#" onclick="fireOtherUserOptionsAlert()"><div style="float: right; font-size: 24px; color: #777;"> &nbsp; ••• &nbsp; </div></a>
                    	<?php }
                    } else { ?>
                        <a href="intro.php"><div style="float: right; font-size: 24px; color: #777;"> &nbsp; ••• &nbsp; </div></a>
                <?php } ?>
                
                <!-- like button -->
                <a href="#" onclick="likeAd('<?php echo $adObjID ?>')">
                <?php
                    $likedBy = $adObj->get($ADS_LIKED_BY);
                    // currentUser IS LOGGED IN!
                    if ($currentUser) {
                        if (in_array($currentUser->getObjectId(), $likedBy)) { ?>
                            <div id="likeButton" style="float: right; font-size: 26px; color: var(--red);"> <i class="fas fa-heart"></i> </div>
                        <?php } else { ?>
                            <div id="likeButton" style="float: right; font-size: 26px; color: #777;"> <i class="fas fa-heart"></i> </div>
                        <?php }
                    // currentUser IS NOT LOGGED IN...
                    } else { ?>
                        <div id="likeButton" style="float: right; font-size: 26px; color: #777;"> <a href="intro.php" style="color: #777;"><i class="fas fa-heart"></i></a> </div>
                <?php } ?>
                </a>
                
                <!-- line -->
                <hr style="margin-top: 10px; margin-bottom: 10px">
                
                <!-- price -->
                <h4 style="font-weight: 800; font-size: 30px; color: var(--main-color);"><?php echo $CURRENCY_CODE. ' ' .$price; if ($isNegotiable) { ?> - Negotiable 
                    <?php } ?>
                </h4>
                <br>

                <!-- title -->
                <h5 style="font-weight: 600"><?php echo $title; ?></h5>
                <br>

                <!-- item sold -->
                <?php if($isSold) { ?> 
                    <div class="text-center item-sold">THIS ITEM HAS BEEN SOLD</h4></div> 
                <?php } ?>

                <!-- description -->
                <p><?php echo $description ?></p>
                
                <!-- chat button -->
                <?php 
                    // currentUser IS LOGGED IN!
                    if ($currentUser) {
                        if ($currentUser->getObjectId() != $userPointer->getObjectId()) {
                            if (!$isSold) { ?>
                                <a href="messages.php?adObjID=<?php echo $adObjID ?>&upObjID=<?php echo $userPointer->getObjectId() ?>" class="btn btn-primary" style="width: 100%; font-weight: 800; margin-bottom: 10px;">Chat now</a>
                            <?php }
                        }

                    // $currentuser IS NOT LOGGED IN...
                    } else { ?>
                        <a href="intro.php" class="btn btn-primary" style="width: 100%; font-weight: 800; margin-bottom: 10px;">Chat now</a>
                <?php } ?>

                <div>
                    <!-- date -->
                    <img src="assets/images/date_icon.png" width="30"><span style="font-size: 13px;"><?php echo time_ago($dateStr); ?></span>
                    <!-- views -->
                    <span style="float: right; font-size: 13px;"><img src="assets/images/views_icon.png" width="30"><?php echo $views ?></span>
                </div>

                <!-- social share buttons -->
                <br>
                <h6>Share this Ad</h6>
                <a class="btn btn-fb-share" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $WEBSITE_PATH ?>ad-info.php?adObjID=<?php echo $adObjID ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-tw-share" href="https://twitter.com/intent/tweet?text=Check%20out%20this%20product%20on%20%23bazaar%20<?php echo $WEBSITE_PATH ?>ad-info.php?adObjID=<?php echo $adObjID ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-pin-share" href="https://pinterest.com/pin/create/button/?url=<?php echo $WEBSITE_PATH ?>ad-info.php?adObjID=<?php echo $adObjID ?>&media=<?php echo $imageURL ?>&description=Check%20out%20this%20product%20on%20%23bazaar" target="_blank"><i class="fab fa-pinterest-p"></i></a>
                <a class="btn btn-email-share" href="mailto:?subject=Check%20out%20this%20product%20on%20%23bazaar&body=Check%20out%20this%20product%20on%20Bazaar%3A%20<?php echo $WEBSITE_PATH ?>ad-info.php?adObjID=<?php echo $adObjID ?>" target="_blank"><i class="fas fa-envelope"></i></a>

            </div><!-- ./ right column -->
            
            <!-- location & map area -->
            <div class="col-lg-12 col-md-12">
                <br>
                <img src="assets/images/location_icon.png" width="30"><span id="locationText" style="font-size: 13px; font-weight: 600"></span>
                <br>
                <!-- google map -->
                <div id="map-ad-info"></div>
            </div>

        </div><!-- ./ row -->
    </div><!-- /.container -->

    <!-- footer -->
    <?php include 'footer.php' ?>

<!-- javascript functions -->
<script>
    // localStorage.clear();
    var latitude = <?php echo $latitude ?>;
    var longitude = <?php echo $longitude ?>;
    var map;
    var markers = [];
    var geocoder;
    var adObjID = "<?php echo $adObjID ?>";

    console.log(latitude + ' - ' + longitude);
    console.log('AD OBJ ID: ' + adObjID);

    // images carousel
    $('.carousel').carousel({
      interval: 0,
      pause: "false"
    });


    function getAddress () {   
        // geocoding API
        var geocodingAPI = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + latitude + "," + longitude + "&key=<?php echo $GOOGLE_MAP_API_KEY ?>";
        $.getJSON(geocodingAPI, function (json) {
            if (json.status == "OK") {
                var result = json.results[0];
                var city = "";
                var state = "";
                for (var i = 0, len = result.address_components.length; i < len; i++) {
                    var ac = result.address_components[i];
                    if (ac.types.indexOf("locality") >= 0) { city = ac.short_name; }
                    if (ac.types.indexOf("country") >= 0) { state = ac.short_name; }
                }// ./ For
                
                // show city, state
                document.getElementById("locationText").innerHTML = city + ', ' + state;

                // save gps coordinates
                localStorage.setItem('latitude', latitude);
                localStorage.setItem('longitude', longitude);
                // console.log("LAT (getAddress): " + latitude + " -- LNG (getAddress): " + longitude);

                // call function
                initMap();

            }// ./ If
        });
    }
    getAddress();



	//---------------------------------
	// MARK - INIT GOOGLE MAP
	//---------------------------------
    var mapZoom = 12;
	function initMap() {  
        var location = new google.maps.LatLng(latitude, longitude);
        map = new google.maps.Map(document.getElementById('map-ad-info'), {
			zoom: mapZoom,
			center: location,
			mapTypeId: 'roadmap',
			disableDefaultUI: true,
		    mapTypeControl: false,
		    scaleControl: false,
		    zoomControl: false
		});

		// Add a marker in the center of the map.
		addMarker(location);
	}


	function addMarker(location) {
        clearMarkers();
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
        markers.push(marker);
        
        // set lat & lng based on marker's coordinates
        latitude = marker.getPosition().lat();
        longitude = marker.getPosition().lng();
        console.log("MARKER LAT: " + latitude + " - MARKER LNG: " + longitude);

        // zoom & center map based on pin
        metersPerPx = 156543.03392 * Math.cos(latitude * Math.PI / 180) / Math.pow(2, mapZoom)
        map.setZoom(metersPerPx/2.6);
        map.setCenter(marker.getPosition());        
    }

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }// ./ For
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() { 
        setMapOnAll(null);
        markers = [];
    }



	//---------------------------------
	// MARK -  CURRENT USER OPTIONS ALERT
	//---------------------------------
	function fireCurrentUserOptionsAlert() {
		swal({
			text: "Select options:",
			closeOnClickOutside: false,
            closeOnEsc: false,
			buttons: {
	    		cancel: "Cancel",
	    		editAd: {
	      			text: "Edit Ad",
	      			value: "editAd",
	    		},
	    		deleteAd: {
	      			text: "Delete Ad",
	      			value: "deleteAd",
	    		},
	  		},
		}).then((value) => {
	  		switch (value) {

                //---------------------------------
                // MARK - EDIT AD
                //---------------------------------
		    	case "editAd":
		      		document.location.href = "sell-edit-stuff.php?adObjID=" + adObjID;
		      	break;


		        //---------------------------------
                // MARK - DELETE AD
                //---------------------------------
		    	case "deleteAd":
                    swal({ text:'Loading...', buttons: false });

                    $.ajax({
                      url:'delete-ad.php',
                      data: 'adObjID=' + adObjID,
                      type: 'GET',
                      success:function(data) {
                        console.log(data);
                        
                        swal({
                            text: data,
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            icon: 'success',
                            }).then((action) => { 
                                if (action) {
                                    document.location.href = 'index.php';
                                }
                        });

                      // error
                      },error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        swal(err.Message);
                    }});

		      	break;
	   		}// ./ switch
		});
	}


	//---------------------------------
	// MARK - OTHER USER OPTIONS ALERT
	//---------------------------------
	function fireOtherUserOptionsAlert() {
		swal({
			text: "Select options:",
			closeOnClickOutside: false,
            closeOnEsc: false,
			buttons: {
    			cancel: "Cancel",
    			reportAd: {
      				text: "Report Ad",
      				value: "reportAd",
    			},
  			},
		}).then((value) => {
  			switch (value) {

                //---------------------------------
                // MARK - REPORT AD
                //---------------------------------
		    	case "reportAd":
		    		swal({
		    			text: "Are you sure you want to report this Ad to the Admin?",
		    			closeOnClickOutside: false,
            			closeOnEsc: false,
                        dangerMode: true,
						buttons: {
				    		cancel: "Cancel",
				    		reportAdButt: {
				      			text: "Yes, Report it",
				      			value: "reportAdButt",
				    		},
				  		},
					}).then((value) => {
				  		switch (value) {
                            
					    	case "reportAdButt":
                                swal({ text:'Loading...', buttons: false });

                                $.ajax({
                                    url:'report-ad.php',
                                    data: 'adObjID=' + adObjID,
                                    type: 'GET',
                                    success:function(data) {
                                        console.log(data);
                                        
                                        swal({
                                            text: 'Thanks for reporting this Ad. We will take action for it within 24h.',
                                            closeOnClickOutside: false,
                                            closeOnEsc: false,
                                            icon: 'success',
                                            }).then((action) => { 
                                                if (action) {
                                                    document.location.href = 'index.php';
                                                }
                                        });

                                    // error
                                    },error: function(xhr, status, error) {
                                        var err = eval("(" + xhr.responseText + ")");
                                        swal(err.Message);
                                }});
					      	break;
				   		}
					});
		      	break;

	   		}// ./ switch
		});
	}


    //---------------------------------
    // MARK - LIKE/UNLIKE AD
    //---------------------------------
    function likeAd(adObjID) {
        $.ajax({
            url:'like-ad.php',
            data: 'adObjID=' + adObjID,
            type: 'GET',
            success:function(data) {
                console.log(data);
                if (data == 'liked') {
                    document.getElementById("likeButton").style.color = 'var(--red)';
                } else if (data == "unliked") {
                    document.getElementById("likeButton").style.color = '#777';
                }

            // error
            },error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert('ERROR: ' + err.Message);
        }});
    }



    //---------------------------------
    // MARK - OPEN/CLOSE RIGHT SIDEBAR
    //---------------------------------
    function openSidebar() {
        document.getElementById("right-sidebar").style.width = "250px";
    }

    function closeSidebar() {
        document.getElementById("right-sidebar").style.width = "0";
    }

</script>
<!-- google maps API call -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLE_MAP_API_KEY ?>&callback=initMap"></script>
</body>
</html>