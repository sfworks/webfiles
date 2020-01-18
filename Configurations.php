<?php require 'autoload.php';
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
if (session_status() == PHP_SESSION_NONE) { session_start(); }

ParseClient::initialize(

	// REPLACE THIS STRING WITH YOUR OWN 'App Id' FROM back4app
	'TvG8epJnNKZ2bbkdM834tUyaF0dolH04FpalAYwv',

	// REPLACE THIS STRING WITH YOUR OWN 'REST API Key' FROM back4app
	'gqcuzQommBFNLCZ6812NSMEg6dcip5l63zmzpl9L',

	// REPLACE THIS STRING WITH YOUR OWN 'Master Key' FROM back4app
	'p0BK8u798GCHIPq18ymWCkm6dv8u2eV4Y78GyeOS'

);
ParseClient::setServerURL('https://parseapi.back4app.com','/');
ParseClient::setStorage( new ParseSessionStorage() );


// ------------------------------------------------
// MARK: - CHANGE THE NAME BELOW WITH YOUR WEBSITE NAME
// ------------------------------------------------
$WEBSITE_NAME = "SFBazaar";

// ------------------------------------------------
// MARK: - EDIT THE CURRENCY CODE BELOW AS YOU WISH
// ------------------------------------------------
$CURRENCY_CODE = "MZN";


//---------------------------------
// MARK - THIS IS THE DISTANCE RANGE AROUND A LOCATION
//---------------------------------
$DISTANCE_IN_KM = 50;


// ------------------------------------------------
// MARK: - EDIT THE GPS COORDINATES BELOW AS YOU WISH, IN ORDER TO SET THE DEFAULT LOCATION'S COORDINATES
// ------------------------------------------------
$DEFAULT_LOCATION_LATITUDE = 25.9692;
$DEFAULT_LOCATION_LONGITUDE = 32.5732;


// ------------------------------------------------
// MARK: - ARRAY OF STUFF CATEGORIES - YOU CAN EDIT IT AS YOU WISH
// ------------------------------------------------
$categoriesArray = array(
	"All",
    "Cars",
    "Books",
    "Electronics",
    "Home",
    "Fashion",
    "Child",
    "Music",
    "Healthcare",

    /*
    YOU CAN ADD NEW CATEGORIES HERE, JUST MAKE SURE TO CREATE THEIR RELATIVE .png IMAGES INTO THE 'assets/images/categories' FOLDER, ALL LOWERCASE NAMES
    (ex: "Trending" as array's item --> "trending.png" as image name)
    */
);


// ------------------------------------------------
// IMPORTANT: REPLACE THE STRING BELOW WITH THE FULL URL OF THE ROOT OF YOUR WEBSITE:
// ------------------------------------------------
$WEBSITE_PATH = "https://sfbazaar.herokuapp.com/";


// ------------------------------------------------
// IMPORTANT: REPLACE THE STRINGS BELOW WITH THE LINKS TO YOUR IOS AND ANDROID APP VERSIONS:
// ------------------------------------------------
$IOS_APPSTORE_LINK = "YOUR_APPSTORE_LINK";
$ANDROID_PLAYSTORE_LINK	= "YOUR_PLAYSTORE_LINK";



// ------------------------------------------------
// IMPORTANT: REPLACE THE STRING BELOW WITH YOUR OWN FACEBOOK AP ID AND SECRET KEY:
// ------------------------------------------------
$FACEBOOK_APP_ID = "202391853740601";
$FACEBOOK_APP_SECRET = "f972b67f6d35c6f57d35bc93f703628a";


// ------------------------------------------------
// IMPORTANT: REPLACE THE STRING BELOW WITH YOUR OWN GOOGLE MAPS API KEY:
// ------------------------------------------------
$GOOGLE_MAP_API_KEY = "AIzaSyDqoFdzU1hZ5AhyOwqAAjy0GVTcgBDfTFk";





// ------------------------------------------------
// MARK: - PARSE DASHBOARD CLASSES AND COLUMNS
// ------------------------------------------------
$USER_CLASS_NAME = "_User";
$USER_USERNAME = "username";
$USER_EMAIL = "email";
$USER_EMAIL_VERIFIED = "emailVerified";
$USER_FULLNAME = "fullName";
$USER_AVATAR = "avatar";
$USER_LOCATION = "location";
$USER_BIO = "bio";
$USER_IS_REPORTED = "isReported";
$USER_REPORT_MESSAGE = "reportMessage";
$USER_HAS_BLOCKED = "hasBlocked";

$ADS_CLASS_NAME = "Ads";
$ADS_SELLER_POINTER = "sellerPointer";
$ADS_LIKED_BY = "likedBy";
$ADS_KEYWORDS = "keywords";
$ADS_TITLE = "title";
$ADS_PRICE = "price";
$ADS_CURRENCY = "currency";
$ADS_CATEGORY = "category";
$ADS_LOCATION = "location";
$ADS_IMAGE1 = "image1";
$ADS_IMAGE2 = "image2";
$ADS_IMAGE3 = "image3";
$ADS_IMAGE4 = "image4";
$ADS_IMAGE5 = "image5";
$ADS_DESCRIPTION = "description";
$ADS_LIKES = "likes";
$ADS_VIEWS = "views";
$ADS_FOLLOWED_BY = "followedBy";
$ADS_IS_REPORTED = "isReported";
$ADS_IS_SOLD = "isSold";
$ADS_IS_NEGOTIABLE = "isNegotiable";
$ADS_CREATED_AT = "createdAt";

$NOTIFICATIONS_CLASS_NAME = "Notifications";
$NOTIFICATIONS_CURRENT_USER = "currUser";
$NOTIFICATIONS_OTHER_USER = "otherUser";
$NOTIFICATIONS_TEXT = "text";
$NOTIFICATIONS_CREATED_AT = "createdAt";

$FOLLOW_CLASS_NAME = "Follow";
$FOLLOW_CURRENT_USER = "currentUser";
$FOLLOW_IS_FOLLOWING = "isFollowing";

$MESSAGES_CLASS_NAME = "Messages";
$MESSAGES_AD_POINTER = "adPointer";
$MESSAGES_SENDER = "sender";
$MESSAGES_RECEIVER = "receiver";
$MESSAGES_MESSAGE_ID = "messageID";
$MESSAGES_MESSAGE = "message";
$MESSAGES_IMAGE = "image";
$MESSAGES_DELETED_BY = "deletedBy";

$CHATS_CLASS_NAME = "Chats";
$CHATS_LAST_MESSAGE = "lastMessage";
$CHATS_SENDER = "sender";
$CHATS_RECEIVER = "receiver";
$CHATS_ID = "chatID";
$CHATS_AD_POINTER = "adPointer";
$CHATS_CREATED_AT = "createdAt";
$CHATS_DELETED_BY = "deletedBy";

$FACEBOOK_CALLBACK_URL = $WEBSITE_PATH.'fb-callback.php';
?>
