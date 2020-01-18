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

// Login
if(isset($_POST['username']) && isset($_POST['password']) ) {
    $username = $_POST['username'];
    $password = $_POST['password'];
        
    try {
        $user = ParseUser::logIn($username, $password); 

        // Go to index.php
        header('Refresh:1; url=index.php'); ?>

        <div class="alert alert-success text-center">
            You have successfully logged in. <br>
            Please wait...
        </div>
        <div class="text-center">
            <p>In case you will not be redirected to the Home page within 5 seconds, click the botton below.</p>
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
        
    <?php 
    // error 
    } catch (ParseException $error) { $e = $error->getMessage(); ?>
        <div class="alert alert-danger text-center">
            <em class="fa fa-exclamation"> </em><?php echo $e ?>
        </div>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div> 
    <?php }
}

// Reset Password
if( isset($_POST['email']) ) {
    $email = $_POST['email'];
    try {
        ParseUser::requestPasswordReset($email); ?>

        <div class="alert alert-success text-center">
            You will soon get email with a link to reset your password.
        </div>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
    <?php  
    // error
    } catch (ParseException $error) { $e = $error->getMessage(); ?>
        <div class="alert alert-danger text-center">
            <em class="fa fa-exclamation"></em> <?php echo $e ?>
        </div>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>   
    <?php }
}
?>
<!-- header -->
<?php include 'header.php' ?>
    
    <!-- main container -->
    <div class="container">
        <div class="text-center">
            <div class="row">
                <div class="col-lg-12">
                    <img src="assets/images/favicon.png" width="80">
                    	<br><br>
                    	<h3>Log in</h3>
                    	<br>

    					<!-- form -->
    					<form method="post" action="login.php">
    						<!-- username input -->
    						<input class="login-input" id="username" type="text" name="username" placeholder="USERNAME">
    						<br>
                            <!-- password input -->
                            <input class="login-input" id="password" type="password" name="password" placeholder="PASSWORD">
    						<br>

    						<!-- Log in button -->
    						<input type="submit" value="Log in" class="btn btn-primary">
    					</form>
                        
                        <br><br>

                        <div class="text-center">
                            <!-- reset password btn -->
                            <a href="#" data-toggle="modal" data-target="#resetModal" style="text-decoration: none; font-weight: 700">Reset password</a>
                            &nbsp;&nbsp;
                            <!-- sign up with email -->
                            <a href="signup.php" style="text-decoration: none; font-weight: 700">Sign up with email</a>
                        </div>
                    </div>
                </div>
            </div>

        <hr>
            
        <!-- app store buttons -->
        <h5 class="text-center">
            <strong>Get the mobile versions here:</strong>
            <br><br>
            <a href="<?php echo $IOS_APPSTORE_LINK ?>" target="_blank" class="btn btn-appstore" style="text-decoration: none;"><i class="fab fa-apple"></i> Download from the App Store</a>
            &nbsp; 
            <a href="<?php echo $ANDROID_PLAYSTORE_LINK ?>" target="_blank" class="btn btn-appstore" style="text-decoration: none;"><i class="fab fa-android"></i> Download from the Play Store</a>
        </h5>

    </div><!-- ./ container -->


        <!-- reset modal -->
        <div id="resetModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reset Password</h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <p>Please type the email address you have used to sign up.</p>
                            <form method="post" action="login.php">
                                <input class="login-input" type="email" name="email" placeholder="Your email address">
                                <br>
                                <input type="submit" value="Reset Password" class="btn btn-primary">
                            </form>
                        </div>
                    </div>

                    <!-- cancel btn -->
                    <div class="modal-footer"><button type="button" class="btn btn-cancel" data-dismiss="modal">Close</button></div>
                </div>
            </div>
        </div><!-- ./ reset modal -->

<!-- footer -->
<?php include 'footer.php' ?>

</body>
</html>