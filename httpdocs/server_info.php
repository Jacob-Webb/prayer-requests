<?php
/******************************************************************************
* server_info.php takes in the server name. If it is related to the development
* environment use the information for the development database. Otherwise,
* use the information for the production database. Access the database with
* this information.
******************************************************************************/
session_start();
$server = $_SERVER['SERVER_NAME'];
($server == "prayer.rock.church") ? $env = "production" : $env = "dev";
$http = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';

// Set SMTP auth
$smtp_user = 'info@therockyouth.org';
$smtp_pass = 'X;8D(YVjAutGJgQ7ke';

// Setup Database Connection
if ($env == "production") {
    $db_server = "prayer.rock.church";
    $db_user = "prayeradmin";
    $db_pass = "69YIDmnjPR+yg8}7(=";
    $db_name = "prayerreq";
}

if ($env == "dev") {
  $db_name = "prayer";
  $db_user = "root";
  $db_pass = "root";
  $db_server = "localhost";
}

//db info pulled from access_database.php
$mysqli = new MySQLi($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error());


?>
