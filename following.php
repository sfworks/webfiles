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
      <div class="title-header">Following</div>
      <!-- right menu button -->
      <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

   <!-- bottom navbar -->
   <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
        <?php $currentUser = ParseUser::getCurrentUser(); ?>
            <?php if ($currentUser) { ?><a href="following.php">
            <?php } else { ?><a href="intro.php"> <?php } ?>
        <img src="assets/images/tab_following_active.png" style="width: 44px; margin-left: 20px;"></a>
        
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
        
        <a href="index.php""><img src="assets/images/tab_home.png" style="width: 44px;"> Home</a>
        <?php if ($currentUser) { ?> <a href="following.php" style="color: var(--main-color);">
        <?php } else { ?> <a href="intro.php" style="color: var(--main-color);"> <?php } ?>
        <img src="assets/images/tab_following_active.png" style="width: 44px;"> Following</a>
        
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
        <!-- adsGrid -->
        <div class="row" id="adsGrid"></div>
    </div><!-- /.container -->


    <!-- Footer -->
    <?php include 'footer.php' ?>

    <!-- javascript functions -->
    <script>
    //---------------------------------
    // MARK - QUERY ADS
    //---------------------------------
    function queryAds() {        
        $.ajax({
            url:'query-ads.php',
            data: 'isFollowing=' + true,
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
    queryAds()


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
