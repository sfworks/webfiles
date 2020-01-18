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
      <div class="title-header">Chats</div>
      <!-- right menu button -->
      <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

   <!-- bottom navbar -->
   <div class="bottom-navbar" id="bottom-navbar">
      <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
      <?php $currentUser = ParseUser::getCurrentUser();
         if (!$currentUser) { header("Refresh:0; url=intro.php"); }

         if ($currentUser) { ?> <a href="following.php">
	      <?php } else { ?> <a href="intro.php"> <?php } ?>
			<img src="assets/images/tab_following.png" style="width: 44px; margin-left: 20px;"></a>

         <?php if ($currentUser) { ?> <a href="notifications.php">
   	   <?php } else { ?> <a href="intro.php"> <?php } ?>
         <img src="assets/images/tab_notifications.png" style="width: 44px; margin-left: 20px;"></a>
           
   		<?php if ($currentUser) { ?> <a href="chats.php">
   	   <?php } else { ?> <a href="intro.php"> <?php } ?>
         <img src="assets/images/tab_chats_active.png" style="width: 44px; margin-left: 20px;"></a>
           
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
    	
		<?php if ($currentUser) { ?> <a href="chats.php" style="color: var(--main-color);">
	   <?php } else { ?> <a href="intro.php"> <?php } ?>
      <img src="assets/images/tab_chats_active.png" style="width: 44px;"> Chats</a>
    	
		<?php if ($currentUser) { ?> <a href="account.php">
	   <?php } else { ?> <a href="intro.php"> <?php } ?>
      <img src="assets/images/tab_account.png" style="width: 44px;"> Account</a>
	</div>

   <!-- container -->
   <div class="container">
	<?php // query Chats
	 	try {
			$query = new ParseQuery($CHATS_CLASS_NAME);
         $query->contains($CHATS_ID, $currentUser->getObjectId());
         $cuObjIDArr = array($currentUser->getObjectId());
         $query->notContainedIn($CHATS_DELETED_BY, $cuObjIDArr);
         $query->descending($CHATS_CREATED_AT);

         $chatsArray = $query->find(); 
         if (count($chatsArray) != 0) {
				for ($i = 0;  $i < count($chatsArray); $i++) {
					// Parse Obj
               $cObj = $chatsArray[$i];
					
					// adPointer
               $adPointer = $cObj->get($CHATS_AD_POINTER);
               $adPointer->fetch();

               // ad photo
               $adFile = $adPointer->get($ADS_IMAGE1);
               $adImageURL = $adFile->getURL();

               // ad title
               $adTitle = $adPointer->get($ADS_TITLE);

               // chat date
               $date = $cObj->getCreatedAt();
               $adDateStr = date_format($date,"Y/m/d H:i:s");
               
					// userPointer
               $adUserPointer = $adPointer->get($ADS_SELLER_POINTER);
               $adUserPointer->fetch();

               // Fullname
               if ($adUserPointer->getObjectId() == $currentUser->getObjectId() ){
                  $fullname = "You";
               } else {
                  $fullname = $adUserPointer->get($USER_FULLNAME);
               }
                    
               // senderUser
               $senderUser = $cObj->get($CHATS_SENDER);
               $senderUser->fetch();
                        
               // receiverUser
               $receiverUser = $cObj->get($CHATS_RECEIVER);
               $receiverUser->fetch();
                        
               $userObjID = '';

               // Avatar Image of the User you're chatting with
               if ($senderUser->getObjectId() == $currentUser->getObjectId() ){
                  $userObjID = $receiverUser->getObjectId();         
                  $avFile = $receiverUser->get($USER_AVATAR);
                  $avatarURL = $avFile->getURL();
               } else {
                  $userObjID = $senderUser->getObjectId();
						      $avFile = $senderUser->get($USER_AVATAR);
                  $avatarURL = $avFile->getURL();
               }
               
               // blocked users array
               $blockedUsers = $receiverUser->get($USER_HAS_BLOCKED); ?>

               <!-- chat cell -->
               <div class="col-lg-6 col-md-6 offset-md-3">
                  <div class="chats-cell">
                     <div class="chats-cell-ad">
                        <?php // receiverUser user has blocked you
                           if (in_array($currentUser->getObjectId(), $blockedUsers) ){ ?>
                           <a href="#" onclick="fireBlockedAlert('<?php echo $receiverUser->get($USER_USERNAME) ?>')">
                        <?php // Chat with receiverUser
                           } else { ?>
                           <a href="messages.php?adObjID=<?php echo $adPointer->getObjectId() ?>&upObjID=<?php echo $userObjID ?>">
                        <?php } ?>
                              <img src="<?php echo $adImageURL ?>">
                              <span><?php echo $fullname?></span>  &nbsp;&nbsp;  <?php echo time_ago($adDateStr); ?> 
                              <p><?php echo $adTitle ?></p>
                           </a>
                     </div>
                     <div class="chats-cell-user"><img style="float:right" src="<?php echo $avatarURL ?>"></div>
                  </div>
               </div>
               <?php }// ./ For
              
         // no notifications
         } else { ?>
            <div class="col-lg-12 col-md-12">
               <div class="text-center" style="margin-top: 40px;">No Chats yet.</div>
            </div>
         <?php }

      // error
      } catch (ParseException $e){ echo $e->getMessage(); }
	?>
   </div><!-- /.container -->


    <!-- Footer -->
    <?php include 'footer.php' ?>

    <!-- javascript functions -->
    <script>
    	function fireBlockedAlert(username) { swal('Ouch, @' + username + ' has blocked you!'); }

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
