<?php
/******************************************************************************
* Place GET and POSTed variables here
******************************************************************************/
require '../server_info.php';

// array of hashes associated with Prayer Requests
$checked_prayers = $_POST['checked_prayer_array'];

//format array so that it can be input as a mysql command
$delete_query = 'DELETE FROM web_form WHERE hash IN ( ' . implode( ',', $checked_prayers ) . ' );';

$delete_result = $mysqli->query($delete_query) or die ("Query failed: " . $mysqli->error. " Actual query: " . $delete_query);

//send response back to the jQuery that sent the request
$return_response = "Deleted " . count($checked_prayers) . " prayers";
echo json_encode($return_response);

?>