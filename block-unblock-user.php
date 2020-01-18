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
use Parse\ParseGeoPoint;
use Parse\ParseClient;
use Parse\ParseSessionStorage;
// session_start();

/*--- VARIABLES ---*/
$upObjID = $_GET['upObjID'];
$option = $_GET['option'];

$upObjIdArr = array();
array_push($upObjIdArr, $upObjID);

$currentUser = ParseUser::getCurrentUser();
$hasBlocked = $currentUser->get($USER_HAS_BLOCKED);

// block user
if ($option == 'Block User') {
	array_push($hasBlocked, $upObjID);

// unblock user
} else {
	$unblockID = array_search($upObjID, $hasBlocked);
    unset($hasBlocked[$unblockID]);
}

$currentUser->setArray($USER_HAS_BLOCKED, $hasBlocked);

// save...
try { $currentUser->save();
	if ($option == 'Block User') { echo 'You have blocked this User!';
	} else { echo 'You have unblocked this User!'; }

// error
} catch (ParseException $e){ echo $e->getMessage(); }
?>