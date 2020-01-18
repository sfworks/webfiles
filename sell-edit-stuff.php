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

// currentUser
$currentUser = ParseUser::getCurrentUser();
if ($currentUser) {
} else { header("Refresh:0; url=intro.php"); }

// ad objectId
$adObjID = $_GET['adObjID'];
?>
<!-- header -->
<?php include 'header.php' ?>
<body>
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar fixed-top">
        <!-- navbar title -->
        <a id="navbar-brand" class="navbar-brand" href="index.php"><?php echo $WEBSITE_NAME ?></a>
        <!-- title header -->
        <?php 
            if ($adObjID != null) { ?> <div class="title-header">EDIT AN ITEM</div>
            <?php } else { ?> <div class="title-header">SELL AN ITEM</div>
        <?php } ?>
        <!-- right menu button -->
        <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

   <!-- bottom navbar -->
   <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
        <?php 
            $currentUser = ParseUser::getCurrentUser();
            if ($currentUser) { ?> <a href="following.php">
            <?php } else { ?> <a href="intro.php">
        <?php } ?>
        <img src="assets/images/tab_following.png" style="width: 44px; margin-left: 20px;"></a>
        <?php 
            if ($currentUser) { ?> <a href="notifications.php"> 
            <?php } else { ?> <a href="intro.php"> <?php }
        ?>
        <img src="assets/images/tab_notifications.png" style="width: 44px; margin-left: 20px;"></a>
        <?php 
            if ($currentUser) { ?> <a href="chats.php"> 
            <?php } else { ?> <a href="intro.php"> <?php }
        ?>
        <img src="assets/images/tab_chats.png" style="width: 44px; margin-left: 20px;"></a>
        <?php 
            if ($currentUser) { ?> <a href="account.php">
            <?php } else { ?> <a href="intro.php"> <?php }
        ?>
        <img src="assets/images/tab_account.png" style="width: 44px; margin-left: 20px;"></a>
    </div><!-- ./ bottom navbar -->


    <!-- right sidebar menu -->
    <div id="right-sidebar" class="right-sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
        
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"> Home</a>
        <?php 
            if ($currentUser) { ?> <a href="following.php">
            <?php } else { ?> <a href="intro.php"> <?php }
        ?>
        <img src="assets/images/tab_following.png" style="width: 44px;"> Following</a>
        <?php 
            if ($currentUser) { ?> <a href="notifications.php">
            <?php } else { ?> <a href="intro.php"> <?php }
        ?>
        <img src="assets/images/tab_notifications.png" style="width: 44px;"> Notifications</a>
        <?php 
            if ($currentUser) { ?> <a href="chats.php"> 
            <?php } else { ?> <a href="intro.php"> <?php }
        ?>
        <img src="assets/images/tab_chats.png" style="width: 44px;"> Chats</a>
        <?php 
            if ($currentUser) { ?> <a href="account.php">
            <?php } else { ?> <a href="intro.php"> <?php }
        ?>
        <img src="assets/images/tab_account.png" style="width: 44px;"> Account</a>
    </div>

    <!-- container -->
    <div class="container">
        <div class="text-center">

            <input type="hidden" id="adObjID" value="<?php echo $adObjID ?>">

            <?php 
                if ($adObjID != null) {
                    $adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
                    $adObj->fetch();

                    // title 
                    $adTitle = $adObj->get($ADS_TITLE);
                    
                    // category
                    $category = $adObj->get($ADS_CATEGORY); ?>
                    <input type="hidden" id="adObjID" value="<?php echo $category ?>">
            <?php 
                    // geopoint
                    $gp = $adObj->get($ADS_LOCATION);
                    $latitude = $gp->getLatitude();
                    $longitude = $gp->getLongitude();
                    
                    // image1
                    if ($adObj->get($ADS_IMAGE1) != null) {
                        $file1 = $adObj->get($ADS_IMAGE1);
                        $imageURL1 = $file1->getURL();
                    }
                    
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

                    // price
                    $adPrice = $adObj->get($ADS_PRICE);

                    // negotiable
                    $isNegotiable = $adObj->get($ADS_IS_NEGOTIABLE);
                    
                    // description
                    $adDescription = $adObj->get($ADS_DESCRIPTION);

                    // date
                    $date = $adObj->getCreatedAt();
                    $dateStr = date_format($date,"Y/m/d H:i:s");
                            
                    // userPointer
                    $sellerPointer = $adObj->get($ADS_SELLER_POINTER);
                    $sellerPointer->fetch();
                } ?>


                <!-- edit an item -->
                <?php if ($adObjID != null) { ?>
                    <strong>TITLE</strong>
                    <?php // title
                    if ($adObj->get($ADS_TITLE) != null) { ?> <input type="text" id="title" class="ad-title-input" value="<?php echo $adTitle ?>">
                    <?php } else { ?> <input type="text" id="title" class="ad-title-input" placeholder="Type a title for your ad"> 
                    <?php } ?>

                    <div class="sell-images">
                        <!-- image1 -->
                        <a data-id="1" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal">
                            <img id="image1" class="sell-item-img"
                            <?php if ($adObj->get($ADS_IMAGE1) != null) { ?> src="<?php echo $imageURL1 ?>">
                            <?php } else { ?> src="assets/images/add_photo.png"> <?php } ?>
                        </a>

                        <!-- image2 -->
                        <a data-id="2" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal">
                            <img id="image2" class="sell-item-img"
                            <?php if ($adObj->get($ADS_IMAGE2) != null) { ?> src="<?php echo $imageURL2 ?>">
                        <?php } else { ?> src="assets/images/add_photo.png"> <?php } ?>
                        </a>

                        <!-- image3 -->
                        <a data-id="3" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal">
                            <img id="image3" class="sell-item-img"
                            <?php if ($adObj->get($ADS_IMAGE3) != null) { ?> src="<?php echo $imageURL3 ?>">
                            <?php } else { ?> src="assets/images/add_photo.png"> <?php } ?>
                        </a>
                        
                        <!-- image4 -->
                        <a data-id="4" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal">
                            <img id="image4" class="sell-item-img"
                            <?php if ($adObj->get($ADS_IMAGE4) != null) { ?> src="<?php echo $imageURL4 ?>">
                            <?php } else { ?> src="assets/images/add_photo.png"> <?php } ?>
                        </a>

                        <!-- image5 -->
                        <a data-id="5" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal">
                            <img id="image5" class="sell-item-img"
                            <?php if ($adObj->get($ADS_IMAGE5) != null) { ?> src="<?php echo $imageURL5 ?>">
                            <?php } else { ?> src="assets/images/add_photo.png"> <?php } ?>
                        </a>
                    </div>


                <!-- sell an item -->
                <?php } else { ?>
                    <strong>TITLE</strong>
                    <input id="title" type="text" id="title" class="ad-title-input" placeholder="Type a title for your ad">
                    
                    <div class="sell-images">
                        <a data-id="1" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal"><img id="image1" class="sell-item-img" src="assets/images/add_photo.png"></a>
                        <a data-id="2" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal"><img id="image2" class="sell-item-img" src="assets/images/add_photo.png"></a>
                        <a data-id="3" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal"><img id="image3" class="sell-item-img" src="assets/images/add_photo.png"></a>
                        <a data-id="4" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal"><img id="image4" class="sell-item-img" src="assets/images/add_photo.png"></a>
                        <a data-id="3" data-toggle="modal" href="#uploadImageModal" class="uploadImageModal"><img id="image5" class="sell-item-img" src="assets/images/add_photo.png"></a>
                    </div> 
                <?php } ?>
        </div>

        <!-- hidden inputs -->
        <input type="hidden" id="fileURL1" value="<?php echo $imageURL1 ?>">
        <input type="hidden" id="fileURL2" value="<?php echo $imageURL2 ?>">
        <input type="hidden" id="fileURL3" value="<?php echo $imageURL3 ?>">
        <input type="hidden" id="fileURL4" value="<?php echo $imageURL4 ?>">
        <input type="hidden" id="fileURL5" value="<?php echo $imageURL5 ?>">
        <br>

        <!-- price -->
        <div class="text-center">
            <strong>PRICE</strong> - <?php echo $CURRENCY_CODE ?> 
            <div class="col-sm-4 offset-md-4">
                <input type="number" id="price" class="ad-title-input" placeholder="type a price" value="<?php echo $adPrice ?>">
            </div>
        </div>
        <br>

        <!-- negotiable switch -->
        <div class="text-center">
            <strong>NEGOTIABLE</strong> 
            	<label class="switch">
                    <?php if ($isNegotiable == 0) { ?>
                        <input type="checkbox" onclick="checkIsNegotiableSwitch(this)"><span class="slider round"></span>
                        </label>
                        <input id="is_negotiable" type="hidden" value="false">
               		<?php } else { ?>
                        <input type="checkbox" checked onclick="checkIsNegotiableSwitch(this)"><span class="slider round"></span>
                        </label>
                        <input id="is_negotiable" type="hidden" value="true">
                    <?php } ?>
        </div>
        <br>

        <!-- description -->
        <div class="text-center">
            <strong>DESCRIPTION</strong> 
            <div class="col-sm-4 offset-md-4">
            	<textarea class="descr-textarea" id="description" rows="5" cols="30"><?php echo $adDescription ?></textarea>
            </div>
        </div>
        <br>


        <!-- choose location btn -->
        <div class="text-center">
            <a id="cityState" data-toggle="modal" href="#chooseLocationModal" class="btn btn-location" style="margin-top: 20px;" onclick="getAddress()"> 
                <i class="fas fa-location-arrow"></i> Choose location
            </a>
        </div>
        <br><br>

        <!-- categories -->
    	<div class="text-center">
    		<!-- category name -->
    		<?php 
    		if ($category != null) { ?> <h5 id="categoryName"><strong><?php echo $category ?></strong></h5>    			
    		<?php } else { ?> <h5 id="categoryName"><strong>Select a category below:</strong></h5>
            <?php } ?>
    		<div class="home-categories">
                <div class="owl-carousel">
    	    		<?php 
                        array_shift($categoriesArray);
                        foreach($categoriesArray as $key => $catName) { ?>
    			    		<a class="btn btn-category-sell" onclick="setCategory('<?php echo $catName?>')">
    			    			<img src="assets/images/categories/<?php echo strtolower($catName) ?>.png">
    			    			<p class="text-center"><?php echo $catName ?></p>
    			    		</a>
    	    		<?php } ?>
                </div>
	    	</div>
		</div>
	    <br><br>
        
        <!-- mark as sold button -->
        <div class="text-center">
            <?php if ($adObjID != null) {
                if (!$adObj->get($ADS_IS_SOLD)) { ?>
                    <a class="btn btn-sell" href="#" onclick="markAsSold()">MARK AS SOLD</a>
                <?php }
            } ?>
        </div>
        <br>

        <!-- delete ad button -->
        <div class="text-center">
            <?php if ($adObjID != null) { ?>
                <a class="btn btn-delete-ad" href="#" onclick="deleteAd()">DELETE ITEM</a>
            <?php } ?>
        </div>
        <br>

        <!-- sell/update button -->
        <div class="text-center">
        	<?php if ($adObjID != null) { ?>
                <a href="#" class="btn btn-sell-update" onclick="saveAd()"><i class="fas fa-check"></i> &nbsp; Update </a>
        	<?php } else { ?>
                <a href="#" class="btn btn-sell-update" onclick="saveAd()"><i class="fas fa-check"></i> &nbsp; Sell </a> 
            <?php } ?>
            <br><br><br>
        </div>

    </div><!-- /.container -->



    <!-- choose location modal -->
    <div id="chooseLocationModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false"  aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="myModalLabel">Choose a location</h5>
                </div>
                <div class="modal-body">

                	<!-- map search input -->
                	<form class="search-location-form" action="javascript:getCoordsFromAddress(address.value)">
                		<div class="search-location-icon"><i class="fas fa-search" style="font-size: 16px; margin-top: 4px"></i></div>
                		<input id="address" class="form-control search-location-input" type="text" name="address" placeholder=" Type an address or city name">
                	</form>
                	<br>

                    <!-- current location button -->
                    <div class="curr-location-div">
                        <a href="#" class="btn btn-curr-location" onclick="getCurrentLocation()"><i class="fas fa-location-arrow" style="font-size: 24px;"></i></a>
                    </div>
                    <!-- google map -->
                	<div id="map"></div>
                	<br>
                    
					<!-- choose location button -->
                    <div class="text-center">
                        <a href="#" class="btn btn-primary" data-dismiss="modal" onclick="getAddress()"><strong>Choose location</strong></a>
                    </div>

                </div><!-- ./ modal body -->

                <!-- cancel button -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                </div>
    
            </div>
        </div>
    </div><!-- ./ choose location modal -->




    <!-- upload image modal -->
    <div id="uploadImageModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false"  aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="myModalLabel">Upload images</h5>
                </div>
                <div class="modal-body">
                    
                    <!-- drag and drop image area -->
                    <div id="drop-area">
                        <form class="drop-form">
                            <p>Drop your image here, or click <strong>Select Image</strong> to upload your Ad's image</p>
                            <input type="file" id="fileElem" multiple accept="image/*" onchange="handleFiles(this.files)">
                            <label class="btn btn-select-img" for="fileElem">Select Image</label>
                          </form>
                          <progress id="progress-bar" max=100 value=0 style="display: none;"></progress>
                            <div id="gallery" /></div>
                    </div>
                    <br>
                </div><!-- ./ modal body -->

                <!-- cancel button -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                </div>
    
            </div>
        </div>
    </div><!-- ./ upload image modal -->


    <!-- Footer -->
    <?php include 'footer.php' ?>

    <!-- javascript functions -->
    
    <!-- google maps API call -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLE_MAP_API_KEY ?>&callback=initMap"></script>

    <script>
   	var cityStateButton = document.getElementById("cityState");

    /*--- variables --*/
    var latitude = <?php if($latitude != null) { echo $latitude; } else { echo $DEFAULT_LOCATION_LATITUDE; } ?>;
    var longitude = <?php if($longitude != null) { echo $longitude; } else { echo $DEFAULT_LOCATION_LONGITUDE; } ?>;
    var aCategory = '<?php if($category != null) { echo $category; } ?>';
    var map;
    var markers = [];
    var geocoder;
    var adObjID = document.getElementById('adObjID').value;
    console.log('AD OBJ ID: ' + adObjID);

    // Call functions
    if (latitude == null) { getCurrentLocation();
    } else { getAddress(); }


    // ------------------------------------------------
    // MARK: - GET CURRENT LOCATION
    // ------------------------------------------------
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(getPosition, showError);

        } else { 
            alert("Geolocation is not supported by this browser.");
            
            // set default location coordinates
            latitude =  <?php echo $DEFAULT_LOCATION_LATITUDE ?>;
            longitude = <?php echo $DEFAULT_LOCATION_LONGITUDE ?>;
            getAddress();
        }
    }

    function getPosition(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        getAddress();
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("You have denied your current Location detection.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;            
            case error.TIMEOUT:
                alert("The request to get your current location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }

        // set default location
        latitude = <?php echo $DEFAULT_LOCATION_LATITUDE ?>;
        longitude = <?php echo $DEFAULT_LOCATION_LONGITUDE ?>;
        getAddress();
    }


	//---------------------------------
	// MARK - INIT GOOGLE MAP
	//---------------------------------
    var mapZoom = 12;
	function initMap() {  
        var location = new google.maps.LatLng(latitude, longitude);
        map = new google.maps.Map(document.getElementById('map'), {
			zoom: mapZoom,
			center: location,
			mapTypeId: 'roadmap',
			disableDefaultUI: true,
		    mapTypeControl: false,
		    scaleControl: false,
		    zoomControl: false
		});

		// call addMarker() when the map is clicked.
		map.addListener('click', function(event) {
			addMarker(event.latLng);
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



    function getAddress() {   
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
                cityStateButton.innerHTML = '<i class="fas fa-location-arrow"></i> &nbsp;' + city + ', ' + state;

                console.log("LAT (getAddress): " + latitude + " -- LNG (getAddress): " + longitude);

                // call function
                initMap();

            }// ./ If
        });
    }

	//---------------------------------
	// MARK - GET GPS COORDS FROM ADDRESS
	//---------------------------------
	function getCoordsFromAddress(address) {   
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == 'OK') {
	        	map.setCenter(results[0].geometry.location);
	        	var marker = new google.maps.Marker({
	            	map: map,
	            	position: results[0].geometry.location
	        	});
	        	markers.push(marker);

	        	// set coordinates
	        	latitude = results[0].geometry.location.lat();
	        	longitude = results[0].geometry.location.lng();                
	        	console.log("SEARCH LOCATION LAT: " + latitude + " - SEARCH LOCATION LNG: " + longitude);

                initMap();

	        // error
	        } else { alert('Geocode was not successful for the following reason: ' + status); 
	    }});
	}


    //---------------------------------
    // MARK - NEGOTIABLE SWITCH
    //---------------------------------
    function checkIsNegotiableSwitch(checkbox){
        if (checkbox.checked){
            console.log('true');
            $('#is_negotiable').val('true');
        } else {
            console.log('false');
            $('#is_negotiable').val('false');
        }
    }


	//---------------------------------
	// MARK - SET CATEGORY
	//---------------------------------
	function setCategory(categ) {
		aCategory = categ;
		document.getElementById("categoryName").innerHTML = '<h5 id="categoryName"><strong>' + aCategory + '</strong></h5>';
	}



    //---------------------------------
    // MARK - SAVE AD
    //---------------------------------
    function saveAd() {
    	var title = document.getElementById("title").value;
    	console.log(title);

    	var fileURL1 = document.getElementById("fileURL1").value;
    	console.log(fileURL1);
    	var fileURL2 = document.getElementById("fileURL2").value;
    	console.log(fileURL2);
		var fileURL3 = document.getElementById("fileURL3").value;
    	console.log(fileURL3);
    	var fileURL4 = document.getElementById("fileURL4").value;
    	console.log(fileURL4);
    	var fileURL5 = document.getElementById("fileURL5").value;
    	console.log(fileURL5);
    	
    	var price = document.getElementById("price").value;
    	console.log(price);
		
		var isNegotiable = document.getElementById("is_negotiable").value;
    	console.log(isNegotiable);
    	
    	var description = document.getElementById("description").value;
    	console.log(description);

    	console.log(latitude + ' -- ' + longitude);
    	console.log('SELECTED CATEGORY: ' + aCategory);
    	
        if (title == '' || fileURL1 == '' || price == '' || description == '' || aCategory == '') {
            swal('You must fill the following fields to post your Ad:\n- Title\n- First image\n- Price\n- Description\n- Select a Category');

        } else {
            swal({ text:'Loading...', buttons: false });

        	$.ajax({
                url:'save-ad.php',
                data: 'adObjID=' + adObjID + '&title=' + title + '&category=' + aCategory + '&price=' + price + '&isNegotiable=' + isNegotiable + '&description=' + description + '&latitude=' + latitude + '&longitude=' + longitude + '&fileURL1=' + fileURL1 + '&fileURL2=' + fileURL2 + '&fileURL3=' + fileURL3 + '&fileURL4=' + fileURL4 + '&fileURL5=' + fileURL5,
                type: 'GET',
                success:function(data) {
                    swal({
                        text: 'Congratulations, your stuff has been successfully posted!',
                        icon: "success",
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                    }).then((action) => { 
                        if (action) {
                            document.location.href = 'index.php';
                        }
                    });

                // error
                }, error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    swal(err.Message);
            }});

        }// ./ If
    }




    //---------------------------------
    // MARK - MARK AD AS SOLD
    //---------------------------------
    function markAsSold() {
        swal({
            text: 'Are you sure you want to mark this Item as Sold?\nYou will not be able to change it later.',
            buttons: {
                cancel: "Cancel",
                markSold: {
                    text: "Mark as Sold",
                    value: "markSold",
                },
            },
            }).then((value) => {
                switch (value) {

                    case "markSold":
                        swal({text: 'Loading...', buttons: false});

                        $.ajax({
                          url:'mark-as-sold.php',
                          data: 'adObjID=' + adObjID,
                          type: 'GET',
                          success:function(data) {
                            console.log(data);

                            swal({ 
                                text: 'Your item as been marked as Sold!',
                                icon: 'success',
                                closeOnClickOutside: false,
                                closeOnEsc: false,
                            }).then((action) => {
                                if (action) { location.reload(); }
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
    // MARK - DELETE AD
    //---------------------------------
    function deleteAd() {
        swal({
            text: 'Are you sure you want to delete this Item?',
            buttons: {
                cancel: "Cancel",
                deleteAdButt: {
                    text: "Delete",
                    value: "delete",
                },
            },
            }).then((value) => {
                switch (value) {

                    case "delete":
                        swal({text: 'Loading...', buttons: false});
                        
                        $.ajax({
                          url:'delete-ad.php',
                          data: 'adObjID=' + adObjID,
                          type: 'GET',
                          success:function(data) {
                            console.log(data);
                            
                            swal({ 
                                text: data,
                                icon: 'success',
                                closeOnClickOutside: false,
                                closeOnEsc: false,
                            }).then((action) => {
                                if (action) { window.location.href = 'index.php'; }
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
    // MARK - OPEN/CLOSE RIGHT SIDEBAR
    //---------------------------------
    function openSidebar() {
        document.getElementById("right-sidebar").style.width = "250px";
    }

    function closeSidebar() {
        document.getElementById("right-sidebar").style.width = "0";
    }


    

    //---------------------------------
    // MARK - DROP IMAGES AREA
    //---------------------------------
    // get fileID for image upload
    var fileID = 0;
    $(document).on("click", ".uploadImageModal", function () {
        fileID = $(this).data('id');
        console.log(fileID);
    });


    let dropArea = document.getElementById("drop-area")

    // Prevent default drag behaviors
    ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, preventDefaults, false)   
      document.body.addEventListener(eventName, preventDefaults, false)
    })

    // Highlight drop area when item is dragged over it
    ;['dragenter', 'dragover'].forEach(eventName => {
      dropArea.addEventListener(eventName, highlight, false)
    })

    ;['dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, unhighlight, false)
    })

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false)

    function preventDefaults (e) {
      e.preventDefault()
      e.stopPropagation()
    }

    function highlight(e) {
      dropArea.classList.add('highlight')
    }

    function unhighlight(e) {
      dropArea.classList.remove('active')
    }

    function handleDrop(e) {
      var dt = e.dataTransfer;
      var files = dt.files;
      if (files.length == 1) {
        handleFiles(files);
      } else { alert('1 image only!'); }
    }

    let uploadProgress = []
    let progressBar = document.getElementById('progress-bar')


    function handleFiles(files) {
      files = [...files]
      initializeProgress(files.length)
      files.forEach(uploadFile)
      files.forEach(previewFile)
    }

    function initializeProgress(numFiles) {
      progressBar.value = 0
      uploadProgress = []

      for(let i = numFiles; i > 0; i--) {
        uploadProgress.push(0)
      }
    }

    function updateProgress(fileNumber, percent) {
      uploadProgress[fileNumber] = percent
      let total = uploadProgress.reduce((tot, curr) => tot + curr, 0) / uploadProgress.length
      console.log('UPDATE: ', fileNumber, percent, total)
      progressBar.value = total
    }


    function previewFile(file) {
      let reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onloadend = function() {
        let img = document.createElement('img')
        img.src = reader.result
        document.getElementById('gallery').appendChild(img)
      }
    }
    
    //---------------------------------
    // MARK - UPLOAD FILE
    //---------------------------------
    function uploadFile(file) {
        $('#uploadImageModal').modal('hide');

        swal({ text: 'Loading...', buttons: false });
           
        var filename = "image.jpg";
        var data = new FormData();
        data.append('file', file);
        var websitePath = '<?php echo $WEBSITE_PATH ?>';
        $.ajax({
            url : "upload-image.php?imageWidth=600",
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(data) {
                swal.close();
                
                var fileURL = websitePath + data;
                console.log('UPLOADED TO: ' + fileURL);
                document.getElementById("fileURL" + fileID).value = fileURL;
                $('#image' + fileID).attr("src", fileURL);

            // error
            }, error: function(e) {  
                swal("Something went wrong: " + e); 
        }});
    }
    </script>

    <!-- google maps API call --
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLE_MAP_API_KEY ?>&callback=initMap"></script>
    -->
  </body>
</html>
