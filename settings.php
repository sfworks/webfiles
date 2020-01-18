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
      <div class="title-header">Settings</div>
      <!-- right menu button -->
      <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
   </nav>

    <!-- bottom navbar -->
    <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
        <?php $currentUser = ParseUser::getCurrentUser();
        if (!$currentUser) { header("Refresh:0; url=intro.php"); }
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
	</div>

    <!-- container -->
    <div class="container">
        <?php
            // currentUser
            $currentUser = ParseUser::getCurrentUser();

            // username
            $username = $currentUser->get($USER_USERNAME);
            // full name
            $fullname = $currentUser->get($USER_FULLNAME);
            // avatar
            $avatarImg = $currentUser->get($USER_AVATAR);
            $avatarURL = $avatarImg->getURL();
            // bio
            $bio = $currentUser->get($USER_BIO);
            // email
            $email = $currentUser->get($USER_EMAIL);
        ?>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- avatar -->
                <input type="hidden" id="fileURL" value="<?php echo $avatarURL; ?>">
                <img id="avatarImg" class="center-cropped-40" style="margin-right: 10px;" src="<?php echo $avatarURL; ?>"> Photo
                <a data-toggle="modal" href="#uploadImageModal" class="btn" style="float: right">Change</a>
                <br><br><div class="separator"></div>

                <!-- username -->
                <div>
		            <i class="fas fa-user" style="font-size: 22px; color: var(--main-color); margin-right: 10px; float: left;"></i> Username: 
		            <input type="text" id="username" class="settings-input" placeholder="Your username" value="<?php echo $username ?>">
	        	</div>
	        	<br><br><div class="separator"></div>

                <!-- name -->
                <div>
	                <i class="fas fa-user" style="font-size: 22px; color: var(--main-color); margin-right: 10px;"></i> Name: 
	                <input type="text" id="fullName" class="settings-input" placeholder="Your full name" value="<?php echo $fullname ?>">
            	</div>
                <br><br><div class="separator"></div>

                <!-- email -->
                <div>
                <i class="fas fa-envelope" style="font-size: 22px; color: var(--main-color); margin-right: 10px;"></i> Email:
                <input type="text" id="email" class="settings-input" placeholder="Your valid email address" value="<?php echo $email ?>">
            	</div>
                <br><br><div class="separator"></div>

                <!-- bio -->
                <div>
                	<i class="fas fa-pen-nib" style="font-size: 22px; color: var(--main-color); margin-right: 10px;"></i> Bio/About You: 
                	<textarea id="bio" class="settings-textarea" placeholder="Type something about you"><?php if ($bio != null) { echo $bio; } ?></textarea>
                	<br><br><br><br>
            	</div>
                <br><br><div class="separator"></div>

                <!-- update profile button -->
                <br><br>
                <div class="text-center"><a href="#" class="btn btn-primary" style="width: 280px" onclick="updateProfile()">Update Profile</a></div>
                <!-- logout button -->
                <br>
                <div class="text-center"><a href="logout.php" class="btn btn-logout">Logout</a></div>
               
            </div>
        </div><!-- ./ row -->

    </div><!-- /.container -->


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
                            <p>Drop your image here, or click <strong>Select Image</strong> to upload your Avatar picture</p>
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
    <script>
    var cuObjID = '<?php echo $cuObjID ?>';
    // console.log('CURRENT USER ID: ' + cuObjID);

	//---------------------------------
	// MARK - UPDATE PROFILE 
	//---------------------------------
    function updateProfile() {
    	var username = document.getElementById('username').value;
    	var fullName = document.getElementById('fullName').value;
    	var email = document.getElementById('email').value;
    	var bio = document.getElementById('bio').value;
    	var fileURL = document.getElementById('fileURL').value;

    	console.log('USERNAME: ' + username);
    	console.log('FULL NAME: ' + fullName);
    	console.log('EMAIL: ' + email);
    	console.log('BIO: ' + bio);
    	console.log('FILE URL: ' + fileURL);

    	// ajax call
    	document.getElementById('loadingText').innerHTML = " Updating your Profile...";
    	$('#loadingModal').modal('show');

    	$.ajax({
    		url:'update-profile.php',
    		data: 'username=' + username + '&fullName=' + fullName + '&email=' + email + '&bio=' + bio + '&fileURL=' + fileURL,
    		type: 'GET',
    		success:function(data) {
    			// var results = data.replace(/\s+/, "");
    	    	console.log(data);
                
    			$('#loadingModal').modal('hide');

                swal({
                        title: '<?php echo $WEBSITE_NAME ?>',
                        text: data,
                        icon: "success",
                        dangerMode: false,
                });

    		// error
    	  	},error: function(xhr, status, error) {
    	    	$('#loadingModal').modal('hide');
    	    	var err = eval("(" + xhr.responseText + ")");
    	    	swal(err.Message);
    	}});
    }




    //---------------------------------
    // MARK - DROP IMAGES AREA
    //---------------------------------
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

        // show loading modal
        document.getElementById('loadingText').innerHTML = " Please wait...";
        $('#loadingModal').modal('show');
           
        var filename = "image.jpg";
        var data = new FormData();
        data.append('file', file);
        var websitePath = '<?php echo $WEBSITE_PATH ?>';
        $.ajax({
            url : "upload-image.php?imageWidth=300",
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function(data) {
                var fileURL = websitePath + data;
                console.log('UPLOADED TO: ' + fileURL);
                document.getElementById("fileURL").value = fileURL;
                $('#avatarImg').attr("src", fileURL);
                
                $('#loadingModal').modal('hide');
            // error
            }, error: function(e) { 
                 $('#loadingModal').modal('hide');
                 swal("Something went wrong: " + e); 
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
