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
		<div class="title-header">Messages</div>
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

	<!-- right-sidebar -->
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
	</div><!-- ./ right-sidebar -->

	<!-- container -->
	<div class="container">
		<div class="row">
			<?php 
	            // variables 
	            $adObjID = $_GET['adObjID'];
	            $upObjID = $_GET['upObjID']; 
	            $cuObjID = $currentUser->getObjectId();

	            // adObj
	            $adObj = new ParseObject($ADS_CLASS_NAME, $adObjID);
	            $adObj->fetch();
	            // ad title
	            $adTitle = $adObj->get($ADS_TITLE);
	            // ad image
	            $adFile = $adObj->get($ADS_IMAGE1);
	            $adImageURL = $adFile->getURL();
	            
	            // userObj
	            $userObj = new ParseUser($USER_CLASS_NAME, $upObjID);
	            $userObj->fetch();
	            $uFullname = $userObj->get($USER_FULLNAME);
	            // avatar
	            $avFile = $userObj->get($USER_AVATAR);
	            $avImageURL = $avFile->getURL();

	            $unblock = 'Unblock User';
	            $block = 'Block User';
	            $hasBlocked = $currentUser->get($USER_HAS_BLOCKED); ?>

	            <div class="messages-top">
	            	<a href="ad-info.php?adObjID=<?php echo $adObjID ?>">
	            		<img src="<?php echo $adImageURL ?>">
	            		<?php if (in_array($upObjID, $hasBlocked)) {?>
	            			<span><?php echo $adTitle ?><a class="btn btn-option-user" href="#" onclick="fireOptionsAlert('<?php echo $unblock?>')">&nbsp; ••• &nbsp;</a> 
	                    <?php } else { ?>
	                    	<span><?php echo $adTitle ?><a class="btn btn-option-user" href="#" onclick="fireOptionsAlert('<?php echo $block ?>')">&nbsp; ••• &nbsp;</a> 
	                    <?php } ?>
	                </a>
	                <div class="messages-user">
		                <a href="user-profile.php?upObjID=<?php echo $upObjID ?>">
		                	<img src="<?php echo $avImageURL ?>">
		                	<span><?php echo $uFullname ?></span>
		                </a>
		            </div>
		        </div><!-- ./ messages-top -->
	    </div><!-- ./ row -->


        <div class="row">
       		<!-- messaging container -->
        	<div class="messaging-container">
        		<div class="inbox_msg">
        			<div class="mesgs">
        				<div class="msg_history">
        					<!-- messagesList -->
        					<div id="messagesList"></div>
        				</div><!-- ./ message history -->

        				<!-- write message container -->
        				<div class="type_msg">
        					<div class="input_msg_write">
        						<!-- message text input -->
        						<input id="messageInput" type="text" class="write_msg" placeholder="Type your message..." />
        						<!-- send message button -->
        						<div class="messages-buttons">
	        						<button id="sendMessageButton" class="msg_send_btn" onclick="sendMessage()"><i class="far fa-paper-plane"></i></button>
	        						<!-- send image button -->
	        						<button data-toggle="modal" href="#uploadImageModal" class="msg_attach_img_btn"><i class="fas fa-paperclip"></i></button>
        						</div>
        					</div>
        				</div>
        			</div>
        		</div>
       		</div><!-- ./ messaging container -->
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
    /*--- variables --*/
    var cuObjID = '<?php echo $cuObjID ?>';
    var adObjID = '<?php echo $adObjID ?>';
    var upObjID = '<?php echo $upObjID ?>';  
    // console.log('CURR. USER ID: ' + cuObjID);

    //---------------------------------
    // MARK - QUERY MESSAGES
    //---------------------------------
    function queryMessages() {
      console.log('QUERY MESSAGES CALLED!');

      $.ajax({
        url:'query-messages.php',
        data: 'adObjID=' + adObjID + '&upObjID=' + upObjID,
        type: 'GET',
        success:function(data) {
          document.getElementById("messagesList").innerHTML = data;
          
        // error
        }, error: function(xhr, status, error) {
          var err = eval("(" + xhr.responseText + ")");
          swal(err.Message);
      }});
    }
    queryMessages();


    
    //---------------------------------
    // MARK - RECALL QUERY INTERVAL
    //---------------------------------
    window.setInterval(function(){
      // call function
      queryMessages();
    }, 20000); // -> 20 seconds 
    


    //---------------------------------
    // MARK - SEND CHAT MESSAGE
    //---------------------------------
    function sendMessage(imageURL) {
      var messInput = document.getElementById('messageInput').value;
      if (imageURL == null) { imageURL = "NONE"; }
      console.log('MESSAGE TXT: ' + messInput);
      console.log('IMAGE URL: ' + imageURL);
      
      $.ajax({
        url:'send-message.php',
        data: 'adObjID=' + adObjID + '&upObjID=' + upObjID + '&fileURL=' + imageURL + '&messTxt=' + messInput,
        type: 'GET',
        success:function(data) {
          // var results = data.replace(/\s+/, "");
          console.log(data);

          document.getElementById('messageInput').value = "";

          // recall query
          queryMessages();

        // error
        },error: function(xhr, status, error) {
          hideLoadingModal();
          var err = eval("(" + xhr.responseText + ")");
          swal(err.Message);
      }});
    }



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
                deleteChat: {
                    text: 'Delete Chat',
                    value: "deleteChat",
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
                            data: 'upObjID=' + upObjID + '&option=' + blockText,
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
                                        location.reload();
                                    }
                                });

                            // error
                            },error: function(xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");
                                swal(err.getMessage);
                        }});// ./ ajax
                    break;


                    //---------------------------------
                    // MARK - DELETE CHAT
                    //---------------------------------
                    case "deleteChat":
                        swal({ text: 'Loading...', buttons: false });
                        
                        $.ajax({
                            url:'delete-chat.php',
                            data: 'adObjID=' + adObjID + '&upObjID=' + upObjID,
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
                                        location.reload();
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
                var fileURL = websitePath + data;
                console.log('UPLOADED TO: ' + fileURL);
                
                // call function
                sendMessage(fileURL);
                
            // error
            }, error: function(e) { 
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