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

// round large numbers into KMGT
function roundNumbersIntoKMGT($n) {
  $n = (0+str_replace(",","",$n));
  if(!is_numeric($n)) return false;
  if($n>1000000000000) return round(($n/1000000000000),1).'T';
  else if($n>1000000000) return round(($n/1000000000),1).'G';
  else if($n>1000000) return round(($n/1000000),1).'M';
  else if($n>1000) return round(($n/1000),1).'K';
  return number_format($n);
}

// format date in time ago
function time_ago($date) {
    if (empty($date)) {
        return "No date provided";
    }
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
    $now = time();
    $unix_date = strtotime($date);
    // check validity of date
    if (empty($unix_date)) {
        return "Bad date";
    }
    // is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if ($difference != 1) {
        $periods[$j].= "s";
    }
    return "$difference $periods[$j] {$tense}";
}

// force HTTPS request
if ($_SERVER['HTTPS'] != "on") {
    $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bazaar | Web PHP Social Listing Theme">
    <meta name="author" content="XScoder">
    <title>Bazaar | Social Listing PHP Template</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png"/>
    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom style -->
    <link href="assets/css/main.css" rel="stylesheet">
    <!-- Lightbox -->
    <link href="assets/vendor/lightbox/css/lightbox.css" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/ba96b2b1ef.js"></script>
    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Owl Carosul -->
    <link rel="stylesheet" href="assets/vendor/owl/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendor/owl/owl.theme.default.min.css">
</head>