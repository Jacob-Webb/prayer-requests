<?php
/*
Requires server info and variables
*/
require_once 'server_info.php';
//$mysqli = new MySQLi($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error());

/******************************************************************************
Functions for  **receive_prayer_request.php**
******************************************************************************/
//insert a new prayer into the database with all of the fields from the prayer request form
function setNewPrayerInDatabase($mysqli, $user_first_name, $user_last_name, $attend, $intercession,
        $for_first_name, $for_last_name, $request_contact, $phone, $email_to,
        $prayer_category, $request, $time, $follow_up, $email_sent, $user_responded, $prayer_answered) {

	//attempt to transfer variables to database
	//attempt to transfer variables to database
$q = "INSERT INTO web_form (user_first_name, user_last_name, attending, intercession,
        for_first_name, for_last_name, request_contact, phone, email, category,
        prayer_request, prayer_timestamp, follow_up, email_sent, user_responded, prayer_answered)
        VALUES ('$user_first_name', '$user_last_name', '$attend', '$intercession',
        '$for_first_name', '$for_last_name', '$request_contact', '$phone', '$email_to',
        '$prayer_category', '$request', '$time', '$follow_up', '$email_sent', '$user_responded', '$prayer_answered')";

	$result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);
}

// generate hash value: an encrypted, unique value for each prayer based on the id
function setPrayerHash($mysqli, $time){
	$hash = 0;

	$id_query = "SELECT id FROM web_form WHERE prayer_timestamp='$time'";
	$id_result = $mysqli->query($id_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $id_query);

	if($id_result->num_rows > 0) {
    $id = $id_result->fetch_assoc()['id'];

    // simple hash function
    $hash = ($id * 2) + 300;

    $hash_query = "UPDATE web_form SET hash='$hash' WHERE id='$id'";
    $insert_result = $mysqli->query($hash_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $hash_query);
	}
}
?>