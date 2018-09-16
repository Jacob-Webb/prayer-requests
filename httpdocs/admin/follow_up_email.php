<?php
/*******************************************************************************
* follow_up_email.php will be run by the server on a schedule every day. It should
* find every prayer request that hasn't been followed up on (using the follow_up
* field) and every request greater than 5 days ago. It will send an email with
* a link to a response form and set the follow_up variable to false.
* Update log database.
* HDM
*******************************************************************************/
require_once('../server_info.php');
include '../message_creator.php';
require_once('../emailer.php');
require_once('../access_database.php');
//db info pulled from access_database.php
//$mysqli = new MySQLi($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error());

// Get the date of x days ago
$days_ago = 5;
$begin_time_range = date('Y:m:d H:i:s',
        mktime(0, 0, 0, date('m'), date('d') - $days_ago, date('Y')));

// found in ../access_database.php
$result = getPrayersInRange($mysqli, $begin_time_range);

// Create an email and send to person with matching id's or hash values
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hash = ($row['hash']) ? $row['hash'] : $row['id'];
        $user_name = $row['user_first_name'];
        $email_to = $row['email'];

        // Found in ../emailer.php
        sendFollowUpEmail($smtp_user, $smtp_pass, $user_name, $hash, $email_to);

        // Found in ../access_database.php
        updateDBAfterEmail($mysqli, $hash);
    }
}
?>
