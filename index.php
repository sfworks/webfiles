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
?>
<!-- header -->
<?php include 'header.php' ?>
<body>
	<!-- Main Navigation -->
	<nav class="navbar navbar-expand-lg navbar fixed-top">
      <!-- navbar title -->
      <a class="navbar-brand" href="index.php"><?php echo $WEBSITE_NAME ?></a>

      <!-- navbar search input -->
      <form class="search-form" action="javascript:queryAds('All', keywords.value)">
      	<div class="search-icon"><i class="fas fa-search"></i></div>
      	<input id="keywords" class="form-control navbar-search-input" type="text" name="keywords" placeholder=" Search on <?php echo $WEBSITE_NAME ?>">
  	  </form>
      
      <!-- right menu button -->
      <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

    <!-- bottom navbar -->
    <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home_active.png" style="width: 44px;"></a>
				
        <?php $currentUser = ParseUser::getCurrentUser(); ?>
				
        <?php if ($currentUser) { ?> <a href="following.php">
        <?php } else { ?> <a href="intro.php"> <?php } ?>
        <img src="assets/images/tab_following.png" style="width: 44px; margin-left: 20px;"></a>
				
        <?php if ($currentUser) { ?> <a href="notifications.php"> 
	    	<?php } else { ?> <a href="intro.php"> <?php } ?>
        <img src="assets/images/tab_notifications.png" style="width: 44px; margin-left: 20px;"></a>
				
        <?php if ($currentUser) { ?> <a href="chats.php">
        <?php } else { ?> <a href="intro.php"> <?php } ?>
        <img src="assets/images/tab_chats.png" style="width: 44px; margin-left: 20px;"></a>
				
        <?php if ($currentUser) { ?> <a href="account.php">
        <?php } else { ?> <a href="intro.php"> <?php } ?>
        <img src="assets/images/tab_account.png" style="width: 44px; margin-left: 20px;"></a>
    </div><!-- ./ bottom navbar -->


    <!-- right sidebar menu -->
    <div id="right-sidebar" class="right-sidebar">
	 	<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
    	
    	<a href="index.php" style="color: var(--main-color);"><img src="assets/images/tab_home_active.png" style="width: 44px;"> Home</a>
			
    	<?php if ($currentUser) { ?> <a href="following.php">
	    <?php } else { ?> <a href="intro.php"> <?php } ?>
      <img src="assets/images/tab_following.png" style="width: 44px;"> Following</a>
			
    	<?php if ($currentUser) { ?> <a href="notifications.php"> 
	    <?php } else { ?> <a href="intro.php"> <?php } ?>
			<img src="assets/images/tab_notifications.png" style="width: 44px;"> Notifications</a>
			
    	<?php if ($currentUser) { ?> <a href="chats.php">
	    <?php } else { ?> <a href="intro.php"> <?php } ?>
			<img src="assets/images/tab_chats.png" style="width: 44px;"> Chats</a>
			
    	<?php if ($currentUser) { ?> <a href="account.php">
	    <?php } else { ?> <a href="intro.php"> <?php } ?>
      <img src="assets/images/tab_account.png" style="width: 44px;"> Account</a>
	</div><!-- right sideba menu -->

    <!-- container -->
    <div class="container">
		
		<!-- city/state button -->
		<div class="text-center" style="margin-top: 50px; margin-bottom: 30px;">
			<a id="cityState" data-toggle="modal" href="#chooseLocationModal" class="btn btn-location"> 
				<i class="fas fa-location-arrow"></i> •••
			</a>
		</div>
		
		<!-- categories -->
		<div class="text-center">
            <div class="home-categories">
                <div class="owl-carousel">
    	    		<?php foreach($categoriesArray as $key => $catName) { ?>
    		    		<div class="home-category-item">
                            <a href="#" class="btn btn-category" onclick="queryAds('<?php echo $catName ?>')">
    		    			   <img src="assets/images/categories/<?php echo strtolower($catName) ?>.png"><br>
    		    			   <p class="text-center"><?php echo $catName ?></p>
    		    		    </a>
                        </div>
    	    		<?php } ?>
                </div>
	    	</div>

			<!-- category name -->
			<h5 id="categoryName"><strong>•••</strong></h5>
		</div>
		
		<!-- sort by button -->
		<div class="text-center">
      	<div style="font-size: 12px; margin-top: 20px">Sort By:</div>
        	<a id="sortByBtn" class="btn btn-sortby" data-toggle="modal" href="#sortByModal">Newest</a>
		</div>
		
		<!-- adsGrid -->
		<div class="row" id="adsGrid"></div>
		
	</div><!-- /.container -->

	<!-- sell button -->
   <div class="text-center">
		<div class="btn-sell-container">
			<?php $currentUser = ParseUser::getCurrentUser();
				if ($currentUser) { ?> <a class="btn btn-sell" href="sell-edit-stuff.php">
			<?php } else { ?> <a class="btn btn-sell" href="intro.php"> <?php } ?>
			<i class="fas fa-camera"></i> &nbsp; Sell Stuff</a>
      </div>
	</div>
	
	
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
                    
                    <input type="range" min="5" max="100" value="50" class="distance-slider" id="distance-slider">
                    <p class="text-center"><span id="distance-text"></span></p>

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



    <!-- sort by modal -->
    <div id="sortByModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false"  aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="myModalLabel">Sort By:</h5>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <a href="#" class="btn btn-option" onclick="sortItemsBy('Newest')">Newest</a><br>
                        <a href="#" class="btn btn-option" onclick="sortItemsBy('Price: high to low')">Price: high to low</a><br>
                        <a href="#" class="btn btn-option" onclick="sortItemsBy('Price: low to high')">Price: low to high</a><br>
                        <a href="#" class="btn btn-option" onclick="sortItemsBy('Most Liked')">Most Liked</a>
                    </div>
                </div><!-- ./ modal body -->

                <!-- cancel button -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                </div>
    
            </div>
        </div>
    </div><!-- ./ sort by modal -->


    <!-- Footer -->
    <?php include 'footer.php' ?>
    
    <!-- google maps API call -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLE_MAP_API_KEY ?>"></script>

    <!-- javascript functions -->
    <script>
    var cityStateButton = document.getElementById("cityState");

    /*--- variables --*/
    // localStorage.clear();
    var latitude = localStorage.getItem('latitude');
    var longitude = localStorage.getItem('longitude');
    var distance = localStorage.getItem('distance');
    if (distance == null) { distance = 50; }
    var map;
    var markers = [];
    var geocoder;
    var sortBy = document.getElementById('sortByBtn').innerHTML;
    console.log("1st LATITUDE: " + latitude + " -- 1st LONGITUDE: " + longitude + ' -- 1st DISTANCE: ' + distance + ' -- 1st SORT BY: ' + sortBy);
    

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
            swal("Geolocation is not supported by this browser.");
            
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
                swal("You have denied your current Location detection.");
                break;
            case error.POSITION_UNAVAILABLE:
                swal("Location information is unavailable.");
                break;            
            case error.TIMEOUT:
                swal("The request to get your current location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                swal("An unknown error occurred.");
                break;
        }

        // set default location
        latitude = <?php echo $DEFAULT_LOCATION_LATITUDE ?>;
        longitude = <?php echo $DEFAULT_LOCATION_LONGITUDE ?>;
        getAddress();
    }

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
                cityStateButton.innerHTML = '<i class="fas fa-location-arrow"></i> &nbsp;' + city + ', ' + state;

                // call query
                queryAds();

                // save gps coordinates
                localStorage.setItem('latitude', latitude);
                localStorage.setItem('longitude', longitude);
                // console.log("LAT (getAddress): " + latitude + " -- LNG (getAddress): " + longitude);

                // call function
                initMap();

            }// ./ If
        });
    }

	//---------------------------------
	// MARK - INITIALIZE GOOGLE MAP
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
                
                // save gps coordinates
                localStorage.setItem('latitude', latitude);
                localStorage.setItem('longitude', longitude);

	        	// console.log("SEARCH LOCATION LAT: " + latitude + " - SEARCH LOCATION LNG: " + longitude);

                initMap();

	        // error
	        } else { swal('Geocode was not successful for the following reason: ' + status); 
	    }});
	}


    //---------------------------------
    // MARK - DISTANCE SLIDER
    //---------------------------------
    var slider = document.getElementById("distance-slider");
    var distText = document.getElementById("distance-text");
    slider.value = distance;
    distText.innerHTML = slider.value + ' Km';
    
    slider.oninput = function() { 
        distText.innerHTML = this.value + ' Km';
        distance = slider.value;
        localStorage.setItem('distance', distance);     
    }


    //---------------------------------
    // MARK - SORT ITEMS BY
    //---------------------------------
    function sortItemsBy(sort) {
        // console.log('SELECTED SORT: ' + sort);
        document.getElementById('sortByBtn').innerHTML = sort;
        sortBy = sort;
        
        // call query
        queryAds();
        $('#sortByModal').modal('hide');
    }    


    //---------------------------------
    // MARK - QUERY ADS
    //---------------------------------
    function queryAds(catName, keywords) {
        // category
        if (catName == null) { catName = "All"; }
        document.getElementById("categoryName").innerHTML = '<h5 id="categoryName"><strong>' + catName + '</strong></h5>';
        // keywords
        if (keywords == null) { keywords = ''; }

        console.log('KEYWORDS: ' + keywords);
        console.log('LAT: ' + latitude + ' -- LNG: ' + longitude);
        console.log('DISTANCE: ' + distance + ' Km');
        console.log('SORT BY: ' + sortBy);

        $.ajax({
            url:'query-ads.php',
            data: 'lat=' + latitude + '&lng=' + longitude + '&distance=' + distance + '&category=' + catName + '&keywords=' + keywords + '&sortBy=' + sortBy,
            type: 'GET',
            success:function(data) {
                document.getElementById("adsGrid").innerHTML = data;
            }, 
            // error
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                swal(err.Message);
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

    <!-- cookies alert -->
    <script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>
	 
</body>
</html>