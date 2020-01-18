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
	<!-- main navigation -->
	<nav class="navbar navbar-expand-lg navbar fixed-top">
      <!-- navbar title -->
      <a id="navbar-brand" class="navbar-brand" href="index.php"><?php echo $WEBSITE_NAME ?></a>
      <!-- title header -->
      <div class="title-header">Account</div>
      <!-- right menu button -->
      <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

    <!-- bottom navbar -->
    <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
        <?php $currentUser = ParseUser::getCurrentUser(); ?>
		  
        <?php if (!$currentUser) { header("Refresh:0; url=intro.php"); }
		  $cuObjID = $currentUser->getObjectId();

        if ($currentUser) { ?> <a href="following.php">
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
        <img src="assets/images/tab_account_active.png" style="width: 44px; margin-left: 20px;"></a>
    </div><!-- ./ bottom navbar -->

    <!-- right sidebar menu -->
    <div id="right-sidebar" class="right-sidebar">
    	<a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
    	
    	<a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"> Home</a>
		
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
      <img src="assets/images/tab_account_active.png" style="width: 44px;"> Account</a>
	</div><!-- ./ right sidebarmenu -->

    <!-- container -->
    <div class="container">
        <?php
            // get currentUser info
            $currentUser = ParseUser::getCurrentUser();

            // full name
            $fullname = $currentUser->get($USER_FULLNAME);
            // avatar
            $avatarImg = $currentUser->get($USER_AVATAR);
            $avatarURL = $avatarImg->getURL();
            // bio
            $bio = $currentUser->get($USER_BIO);
            // verified
            $verified = $currentUser->get('emailVerified');

            $option = 'selling';
        ?>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="user-box">
                    <div class="text-center">
                        <!-- avatar -->
                        <img style="margin-top: 10px; margin-left: 70px;" src="<?php echo $avatarURL; ?>">
                        
                        <!-- options button -->
                        <a href="settings.php" class="btn btn-option-user"><i class="fas fa-cog"></i></a>
                        
                        <!-- full name -->
                        <div class="account-fullname"><?php echo $fullname ?></div>
                        
                        <!-- verified -->
                        <img src="assets/images/email_icon.png" style="width: 50px; height: 50px;">
									<span style="font-size: 12px; margin-left: -12px;">
									<?php if ($verified != null) { ?> Verified <?php } else { ?> Not Verified yet <?php } ?>
                        </span>
                        
                        <!-- bio -->
                        <p><?php if ($bio != null) { echo $bio; } ?></p>
                    </div>
                </div>
            </div>
        </div><!-- ./ row -->

        <!-- options buttons -->
        <div class="text-center">
        	<a href="#" id="sellingButton" onclick="queryAds('selling')" style="border-bottom: 2px solid var(--main-color);">Selling</a> &nbsp; &nbsp;&nbsp; &nbsp; 
        	<a href="#" id="soldButton" onclick="queryAds('sold')" style="text-decoration: none;">Sold</a> &nbsp; &nbsp; &nbsp; &nbsp;  
        	<a href="#" id="likedButton" onclick="queryAds('liked')" style="text-decoration: none;">Liked</a>
        </div>
        <br>

        <!-- adsGrid -->
        <div class="row" id="adsGrid"></div>

    </div><!-- /.container -->

    <!-- sell button -->
    <div class="text-center">
        <div class="btn-sell-container">
            <?php 
            $currentUser = ParseUser::getCurrentUser();
            if ($currentUser) { ?>
					<a class="btn btn-sell" href="sell-edit-stuff.php">
            <?php } else { ?> <a class="btn btn-sell" href="intro.php"> <?php } ?>
            <i class="fas fa-camera"></i> &nbsp; Sell Stuff</a>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php' ?>

    <!-- javascript functions -->
    <script>
   	    var cuObjID = '<?php echo $cuObjID ?>';
    	console.log('CURRENT USER ID: ' + cuObjID);

		//---------------------------------
		// MARK - QUERY ADS
		//---------------------------------
		function queryAds(option) {
			  if (option == 'selling') {
					  document.getElementById("sellingButton").style.borderBottom = '2px solid var(--main-color)';
					  document.getElementById("soldButton").style.borderBottom = '0px';
					  document.getElementById("likedButton").style.borderBottom = '0px';
			  } else if (option == 'sold') {
					  document.getElementById("soldButton").style.borderBottom = '2px solid var(--main-color)';
					  document.getElementById("sellingButton").style.borderBottom = '0px';
					  document.getElementById("likedButton").style.borderBottom = '0px';
			  } else if (option == 'liked') {
					  document.getElementById("likedButton").style.borderBottom = '2px solid var(--main-color)';
					  document.getElementById("soldButton").style.borderBottom = '0px';
					  document.getElementById("sellingButton").style.borderBottom = '0px';
			  }

			  $.ajax({
					url:'query-ads.php',
					data: 'upObjID=' + cuObjID + '&option=' + option,
					type: 'GET',
					success:function(data) {
						document.getElementById("adsGrid").innerHTML = data;				
					}, 
					// error
					error: function(xhr, status, error) {
						 var err = eval("(" + xhr.responseText + ")");
						 alert('ERROR: ' + err.Message);
			  }});
		}
		queryAds('selling');



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

  </body>
</html>
