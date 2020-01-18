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
      <a id="navbar-brand" class="navbar-brand" href="index.php"><?php echo $WEBSITE_NAME ?></a>
      <!-- title header -->
      <div class="title-header">User Profile</div>
      <!-- right menu button -->
      <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

    <!-- bottom navbar -->
    <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
        <?php $currentUser = ParseUser::getCurrentUser();
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
        <img src="assets/images/tab_account.png" style="width: 44px; margin-left: 20px;"></a>
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
        <img src="assets/images/tab_account.png" style="width: 44px;"> Account</a>
	</div>

    <!-- container -->
    <div class="container">

        <?php
            // get user info
            $upObjID = $_GET['upObjID'];
            $userObj = new ParseUser($USER_CLASS_NAME, $upObjID);
            $userObj->fetch();

            // full name
            $fullname = $userObj->get($USER_FULLNAME);
            // avatar
            $avatarImg = $userObj->get($USER_AVATAR);
            // bio
            $bio = $userObj->get($USER_BIO);
            // verified
            $verified = $userObj->get('emailVerified');

        ?>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="user-box">
                    <div class="text-center">
                        <!-- avatar -->
                        <img style="margin-top: 10px; margin-left: 70px;" src="<?php echo $avatarImg->getURL(); ?>">
                        
                        <!-- options button -->
                        <?php 
                            // currentuser IS LOGGED IN!
                            if ($currentUser) {
                                $unblock = 'Unblock User';
                                $block = 'Block User';
                                $hasBlocked = $currentUser->get($USER_HAS_BLOCKED);
                                
                                if (in_array($userObj->getObjectId(), $hasBlocked)) { ?>
                                    <a class="btn btn-option-user" href="#" onclick="fireOptionsAlert('<?php echo $unblock ?>')">&nbsp; ••• &nbsp;</a>
                                <?php } else { ?>
                                    <a class="btn btn-option-user" href="#" onclick="fireOptionsAlert('<?php  echo $block ?>')">&nbsp; ••• &nbsp;</a>
                                <?php }
                            // currentUser IS NOT LOGGED IN...                            
                            } else { ?> <a href="intro.php" class="btn btn-option-user"> ••• </a> 
                            <?php } ?>
                        
                        <!-- full name -->
                        <div class="account-fullname"><?php echo $fullname ?></div>
                        
                        <!-- verified -->
                        <img src="assets/images/email_icon.png" style="width: 50px; height: 50px;"><span style="font-size: 12px; margin-left: -12px;"><?php if ($verified != null) { ?> Verified <?php } else { ?> Not Verified yet <?php } ?>
                        </span>
                        
                        <!-- bio -->
                        <p><?php if ($bio != null) { echo $bio; } ?></p>
                        <?php
                        	// blocked user
        					if ($currentUser) {
            					$hasBlocked = $currentUser->get($USER_HAS_BLOCKED);
            					if (in_array($userObj->getObjectId(), $hasBlocked) ){ ?>
                                    <div style="font-size: 14px; color: red; margin-bottom: 10px;">
                                        <i class="fas fa-exclamation-circle"></i> You have blocked this user
                                    </div>				
					            <?php }
					        }
                        ?>

                        <!-- follow button -->
                        <?php 
                        	try {
                        		$query = new ParseQuery($FOLLOW_CLASS_NAME);
							    $query->equalTo($FOLLOW_IS_FOLLOWING, $userObj);
							    $query->equalTo($FOLLOW_CURRENT_USER, $currentUser);
							    // perform query
							    $fArray = $query->find();
                                $follow = 'follow';
                                $following = 'following';
                                if ($currentUser) {   
    								if (count($fArray) != 0) { ?>
    									<a href="#" id="followButton" class="btn btn-following" onclick="followButton('<?php echo $following ?>')">Following</a>
    								<?php } else { ?>
    									<a href="#" id="followButton" class="btn btn-follow" onclick="followButton('<?php echo $follow ?>')">Follow</a>
    								<?php }
                                }// ./ if
						    // error
						    } catch (ParseException $e){ echo $e->getMessage(); }
						?>
                    </div>
                </div>
            </div>
        </div><!-- ./ row -->

        <!-- option filters buttons -->
        <div class="text-center">
        	<a href="#" id="sellingButton" onclick="queryAds('selling')" style="border-bottom: 2px solid var(--main-color);">Selling</a> &nbsp; &nbsp;&nbsp; &nbsp; 
        	<a href="#" id="soldButton" onclick="queryAds('sold')" style="text-decoration: none;">Sold</a> &nbsp; &nbsp; &nbsp; &nbsp;  
        	<a href="#" id="likedButton" onclick="queryAds('liked')" style="text-decoration: none;">Liked</a>
        </div>
        <br>

        <!-- adsGrid -->
        <div class="row" id="adsGrid"></div>

    </div><!-- /.container -->


    <!-- Footer -->
    <?php include 'footer.php' ?>

    <!-- javascript functions -->
    <script>
    var upObjID = '<?php echo $upObjID ?>';
    console.log('USER OBJID: ' + upObjID);

    //---------------------------------
    // MARK - SET OPTIONS
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
            data: 'upObjID=' + upObjID + '&option=' + option,
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
    queryAds('selling');




    //---------------------------------
    // MARK - FIRE OPTIONS ALERT
    //---------------------------------
    function fireOptionsAlert(blockText) { 
        console.log(blockText);

        swal({
            text: "Select option:",
            buttons: {
                cancel: "Cancel",
                reportUser: {
                    text: "Report User",
                    value: "reportUser",
                },
                blockUnblockUser: {
                    text: blockText,
                    value: "blockUnblockUser",
                },
            },
            }).then((value) => {
                switch (value) {

                    //---------------------------------
                    // MARK - REPORT USER
                    //---------------------------------
                    case "reportUser":
                        swal({
                          text: "Are you sure you want to report this User to the Admin?",
                          icon: "warning",
                          buttons: ["Cancel", "Report User"],
                          dangerMode: true,
                        }).then((action) => {
                          if (action) {
                            swal({ text: 'Loading...', buttons: false });

                            $.ajax({
                                url:'report-user.php',
                                data: 'upObjID=' + upObjID,
                                type: 'GET',
                                success:function(data) {
                                    console.log(data);

                                    swal({
                                        text: 'Thanks for reporting this User. We will take action for it within 24h.',
                                        icon: "success",
                                        closeOnClickOutside: false,
                                        closeOnEsc: false,
                                        // buttons: ["Cancel", "OK"],
                                        dangerMode: false,
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
                          }
                        });
                    break;



                    //---------------------------------
                    // MARK - BLOCK/UNBLOCK USER
                    //---------------------------------
                    case "blockUnblockUser":
                        swal({ text: 'Loading...', buttons: false });
                        
                        $.ajax({
                            url:'block-unblock-user.php',
                            data: 'upObjID=' + '<?php echo $upObjID ?>' + '&option=' + blockText,
                            type: 'GET',
                            success:function(data) {
                                swal({
                                    text: data,
                                    icon: "success",
                                    closeOnClickOutside: false,
                                    closeOnEsc: false,
                                }).then((action) => { 
                                    if (action) {
                                        // Reload
                                        setTimeout(function(){ location.reload(); }, 200);
                                    }
                                });

                            // error
                            },error: function(xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");
                                swal(err.getMessage);
                        }});// ./ ajax
                    break;

                }// ./ switch
        });
    }



    //---------------------------------
    // MARK - FOLLOW BUTTON
    //---------------------------------
    function followButton(followTxt) {
        $.ajax({
            url:'follow-user.php',
            data: 'upObjID=' + upObjID + '&followTxt=' + followTxt,
            type: 'GET',
            success:function(data) {
				console.log(data);
                // Reload
                setTimeout(function(){ location.reload(); }, 200);
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

  </body>
</html>
