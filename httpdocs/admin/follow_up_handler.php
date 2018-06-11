<?php
/******************************************************************************
* follow_up_handler.php takes in the information from follow_up_form.php. It
* updates the original prayer request in the database.
******************************************************************************/
require '../php/server_info.php';
require '../php/helper_functions.php';

//prayer-answered is required. Should be no need to check it
//take in the testimony and request-update strings even if the are blank
$prayer_answered = ($_POST['prayer-answered'] == 'yes') ? 1 : 0;
$testimony = sanitize($_POST['testimony']);
$prayer_update = sanitize($_POST['request-update']);

//get the phone number if it was given

$phone = sanitize($_POST['phone-num']);

//get the original prayer request's hash value to retrieve the request from the db
$hash_value = $_POST['hash-value'];

// add all of the new information to the original prayer in the database
// only attempt to add information if it exists
if($phone && $testimony) {
    $follow_up_query = "UPDATE web_form SET phone='$phone',
        testimony='$testimony', user_responded=1, prayer_answered=1 WHERE hash='$hash_value'";
} elseif($phone && $prayer_update) {
    $follow_up_query = "UPDATE web_form SET phone='$phone',
        update_request='$prayer_update', user_responded=1 WHERE hash='$hash_value'";
} elseif(!$phone && $testimony) {
    $follow_up_query = "UPDATE web_form SET testimony='$testimony', user_responded=1,
        prayer_answered=1 WHERE hash='$hash_value'";
} elseif(!$phone && $prayer_update) {
    $follow_up_query = "UPDATE web_form SET update_request='$prayer_update',
    user_responded=1 WHERE hash='$hash_value'";
} else echo "Issue";

$follow_up_result = $mysqli->query($follow_up_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $follow_up_query);



?>
<html>
<body>
    <h4>Thanks for following up with us!</h4>
</body>
</html>
