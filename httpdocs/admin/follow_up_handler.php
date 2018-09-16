<?php
/******************************************************************************
* follow_up_handler.php takes in the information from follow_up_form.php. It
* updates the original prayer request in the database.
******************************************************************************/
require_once('../server_info.php');
require_once('../clean_io.php');
require_once('../access_database');

//prayer-answered is required. Should be no need to check it
//take in the testimony and request-update strings even if the are blank
$prayer_answered = ($_POST['prayer-answered'] == 'yes') ? 1 : 0;
$testimony = sanitize($_POST['testimony']);
$prayer_update = sanitize($_POST['request-update']);

//get the phone number if it was given

$phone = sanitize($_POST['phone-num']);

//get the original prayer request's hash value to retrieve the request from the db
$hash_value = $_POST['hash-value'];

// Found in ../access_database.php 
updatePrayer($mysqli, $prayer_answered, $testimony, $prayer_update, $phone, $hash_value);
?>
<html>
<body>
    <h4>Thanks for following up with us!</h4>
</body>
</html>
