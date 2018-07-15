<?php
require_once 'server_info.php';

/******************************************************************************
~~~~~~~~~~~~~ Functions for receive_prayer_request.php  ~~~~~~~~~~~~~~~
******************************************************************************/
//insert a new prayer into the database with all of the fields from the prayer request form
function setNewPrayerInDatabase($mysqli, $user_first_name, $user_last_name, $attend, $intercession,
        $for_first_name, $for_last_name, $request_contact, $phone, $email_to,
        $prayer_category, $request, $time, $follow_up, $email_sent, $user_responded, $prayer_answered) {

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
    $hash = (-1**$id) * (17*$id);

    $hash_query = "UPDATE web_form SET hash='$hash' WHERE id='$id'";
    $insert_result = $mysqli->query($hash_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $hash_query);
	}
}

/******************************************************************************
~~~~~~~~~~~~~  Functions for admin/index.php  ~~~~~~~~~~~~~~~~~~~~
******************************************************************************/
function getCategorizedPrayers($mysqli, $begin_date, $end_date) {
    $q = "SELECT hash, user_first_name, user_last_name, attending, intercession,
            for_first_name, for_last_name, request_contact, phone, email, category,
            prayer_request, prayer_timestamp, follow_up, email_sent, user_responded,
            prayer_answered, update_request, testimony FROM web_form";

    $result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);

    $table_columns = array('hash', 'user_first_name', 'user_last_name', 'attending',
                          'intercession', 'for_first_name', 'for_last_name',
                          'request_contact', 'phone', 'email', 'category',
                          'prayer_request', 'prayer_timestamp', 'follow_up', 'email_sent',
                          'user_responded', 'prayer_answered', 'update_request', 'testimony');

    //To get all of the requests up to $end_date we need to get the all prayers to 23:59 of that day
    $day_after_end_date = date('Y-m-d',strtotime($end_date . "+1 days"));

    $healing_prayers = array();
    $provision_prayers = array();
    $salvation_prayers = array();
    $circumstance_prayers = array();

    $healing_count = 0;
    $provision_count = 0;
    $salvation_count = 0;
    $circumstance_count = 0;

//Place prayers in appropriate arrays by getting categories.
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //If the request was made within the date ranges, add to the prayer category arrays
            $row_date_time = strtotime($row['prayer_timestamp']);
            if($row_date_time >= strtotime($begin_date) && $row_date_time < strtotime($day_after_end_date)){
                //group all "healing" prayers in an array
                if($row["category"] == "healing") {
                    foreach($table_columns as $column) {
                        $healing_prayers[$healing_count][$column] = $row[$column];
                    }
                    ++$healing_count;
                }
                //group all "provision" prayers in an array
                elseif($row["category"] == "provision") {
                    foreach($table_columns as $column) {
                        $provision_prayers[$provision_count][$column] = $row[$column];
                    }
                    ++$provision_count;
                }
                //group all "salvation" prayers in an array
                elseif($row["category"] == "salvation") {
                    foreach($table_columns as $column) {
                        $salvation_prayers[$salvation_count][$column] = $row[$column];
                    }
                    ++$salvation_count;
                }
                //group all "circumstances" prayers in an array
                elseif($row["category"] == "circumstances") {
                    foreach($table_columns as $column) {
                        $circumstance_prayers[$circumstance_count][$column] = $row[$column];
                    }
                    ++$circumstance_count;
                }
            }
        }
    } else {
        //Probably need some sort of catch in case this falls through
    }
    $categorized_prayers['healing'] = $healing_prayers;
    $categorized_prayers['provision'] = $provision_prayers;
    $categorized_prayers['salvation'] = $salvation_prayers;
    $categorized_prayers['circumstances'] = $circumstance_prayers;

    return $categorized_prayers;
}
?>
