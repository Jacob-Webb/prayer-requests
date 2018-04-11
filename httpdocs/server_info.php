<?php
$server = $_SERVER['SERVER_NAME'];
($server == "prayer.rock.church") ? $env = "production" : $env = "dev";
$http = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';

// Setup Database Connection
if ($env == "production") {
    $db_server = "prayer.rock.church";
    $db_user = "prayeradmin";
    $db_pass = "69YIDmnjPR+yg8}7(=";
    $db_name = "prayerreq";
}

if ($env == "dev") {
  $db_name = "prayer";
  $db_user = "sanctifyd";
  $db_pass = "only-1-King";
  $db_server = "localhost";
}
?>
