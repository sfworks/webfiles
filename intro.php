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
// session_start();
require_once 'fb-autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// Logout automatically
ParseUser::logOut();
?>
<!-- header -->
<?php include 'header.php' ?>
    <body style="background-image: url('assets/images/bg01.jpg');">

    <!-- main container -->
    <div class="container">
        <div class="text-center">
            <!-- logo -->
            <img src="assets/images/favicon.png" width="100">
            <div class="intro">

	            <h6>
	            	Buy and sell stuff quickly, safely and locally.<br>
	            	Welcome to Bazaar! 
	        	</h6>
	            
	            <p class="intro-p" style="color: #fff">QUICKLY CONNECT WITH</p>
			        	
	            <!-- facebook login button -->                  
	            <?php 
	                $fb = new Facebook\Facebook([
	                    'app_id'                => $FACEBOOK_APP_ID,
	                    'app_secret'            => $FACEBOOK_APP_SECRET,
	                    'default_graph_version' => 'v3.2',
	                ]);
	                $helper = $fb->getRedirectLoginHelper();
	                $permissions = ['email'];
	                $loginUrl = $helper->getLoginUrl($FACEBOOK_CALLBACK_URL, $permissions); ?>
	                <a class="btn btn-facebook" href="<?php echo htmlspecialchars($loginUrl) ?>"><i class="fab fa-facebook-f"></i>  &nbsp; Facebook</a>
	            <br>
	            
	            <p class="intro-p" style="color: #fff">OR USE YOUR EMAIL</p>

	            <!-- sign up button -->
	            <a href="signup.php" class="btn btn-signup">Sign Up</a>
	            <br><br>

	            <p style="color: #fff">Already have an account? <a href="login.php" style="color: var(--main-color);"><strong style="color: #fff">Sign in</strong></a></p>
        		</div>
		        
		        <div class="intro-line"></div>
		            
		        <!-- app store buttons -->
		        <h5 class="text-center">
		            <strong style="color: #fff">Get the mobile versions here:</strong>
		            <br><br>
		            <a href="<?php echo $IOS_APPSTORE_LINK ?>" target="_blank" class="btn btn-appstore" style="text-decoration: none;"><i class="fab fa-apple"></i> Download from the App Store</a>
		            <a href="<?php echo $ANDROID_PLAYSTORE_LINK ?>" target="_blank" class="btn btn-appstore" style="text-decoration: none;"><i class="fab fa-android"></i> Download from the Play Store</a>
		        </h5>
		        <br><br>

		    </div><!-- ./ intro -->

		</div><!-- ./ container -->

<!-- footer -->
<?php include 'footer.php' ?>

</body>
</html>