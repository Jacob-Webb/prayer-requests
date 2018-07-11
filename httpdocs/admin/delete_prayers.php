<?php
/******************************************************************************
* Place GET and POSTed variables here
******************************************************************************/
require_once('../php/server_info.php');
$checked_prayers = $_POST['checked_prayer_array'];

$delete_query = 'DELETE FROM web_form WHERE hash IN ( ' . implode( ',', $checked_prayers ) . ' );';

$delete_result = $mysqli->query($delete_query) or die ("Query failed: " . $mysqli->error. " Actual query: " . $delete_query);
$return_response = "Deleted " . count($checked_prayers) . " prayers";
echo json_encode($return_response);

?>