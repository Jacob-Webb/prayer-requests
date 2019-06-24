<?php
require_once 'server_info.php';
require_once 'keep_secure.php';

/******************************************************************************
~~~~~~~~~~~~~ Functions for receive_prayer_request.php  ~~~~~~~~~~~~~~~
******************************************************************************/
//insert a new prayer into the database with all of the fields from the prayer request form
function setNewPrayerInDatabase($mysqli, $user_first_name, $user_last_name, $attend, $intercession,
        $prayer_is_for, $contact_requested, $phone, $email_to, $prayer_category, $request, $time,   
        $follow_up_needed, $email_sent, $user_responded, $prayer_answered) {

$q = "INSERT INTO web_form (user_first_name, user_last_name, attending, intercession,
        prayer_is_for, contact_requested, phone, email, category,
        prayer_request, prayer_timestamp, follow_up_needed, email_sent, user_responded, prayer_answered)
        VALUES ('$user_first_name', '$user_last_name', '$attend', '$intercession',
        '$prayer_is_for', '$contact_requested', '$phone', '$email_to',
        '$prayer_category', '$request', '$time', '$follow_up_needed', '$email_sent', '$user_responded', '$prayer_answered')";

	$result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);
}

/******************************************************************************
~~~~~~~~~~~~~  Functions for admin/index.php  ~~~~~~~~~~~~~~~~~~~~
******************************************************************************/
function getCategorizedPrayers($mysqli, $begin_date, $end_date) {
    $q = "SELECT hash, user_first_name, user_last_name, attending, intercession,
            prayer_is_for, contact_requested, phone, email, category,
            prayer_request, prayer_timestamp, follow_up_needed, email_sent, user_responded,
            prayer_answered, update_request, testimony FROM web_form";

    $result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);

    $table_columns = array('hash', 'user_first_name', 'user_last_name', 'attending',
                          'intercession', 'prayer_is_for','contact_requested', 'phone', 'email', 'category',
                          'prayer_request', 'prayer_timestamp', 'follow_up_needed', 'email_sent',
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

/******************************************************************************
~~~~~~~~~~~~~  Functions for admin/follow_up_email.php  ~~~~~~~~~~~~~~~~~~~~
******************************************************************************/
function getPrayersInRange($mysqli, $begin_time_range) {
    $sql = "SELECT id, hash, user_first_name, email, prayer_timestamp, email_sent FROM web_form
        WHERE follow_up_needed = 1 and prayer_timestamp >= '$begin_time_range'";
    $result = $mysqli->query($sql) or die ("Query failed: " . $mysqli->error . " Actual query: " . $sql);

    return $result;
}

function updateDBAfterEmail($mysqli, $hash) {
    $email_sent_query = "UPDATE web_form SET follow_up_needed=0, email_sent=1
            WHERE hash='$hash'";

    $email_sent_result = $mysqli->query($email_sent_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $email_sent_query);
}

/******************************************************************************
~~~~~~~~~~~~~  Functions for admin/follow_up_form.php  ~~~~~~~~~~~~~~~~~~~~
******************************************************************************/
function getUserName($mysqli) {
    $sql = "SELECT user_first_name FROM web_form WHERE hash='$hash'";
    $request = $mysqli->query($sql);
    if($request->num_rows > 0) {
        return $request->fetch_assoc()['user_first_name'];
    } else 
        return "";
}

/******************************************************************************
~~~~~~~~~~~~~  Functions for admin/follow_up_handler.php  ~~~~~~~~~~~~~~~~~~~~
******************************************************************************/
function updatePrayer($mysqli, $prayer_answered, $testimony, $prayer_update, $phone, $hash_value) {
    // add all of the new information to the original prayer in the database
    // only attempt to add information if it exists
    if($phone && $testimony) {
        $follow_up_query = "UPDATE web_form SET phone='$phone', testimony='$testimony', user_responded=1, prayer_answered=1 WHERE hash='$hash_value'";
    } elseif($phone && $prayer_update) {
        $follow_up_query = "UPDATE web_form SET phone='$phone', update_request='$prayer_update', user_responded=1 WHERE hash='$hash_value'";
    } elseif(!$phone && $testimony) {
        $follow_up_query = "UPDATE web_form SET testimony='$testimony', user_responded=1, prayer_answered=1 WHERE hash='$hash_value'";
    } elseif(!$phone && $prayer_update) {
        $follow_up_query = "UPDATE web_form SET update_request='$prayer_update', user_responded=1 WHERE hash='$hash_value'";
    } else echo "Issue";

    $follow_up_result = $mysqli->query($follow_up_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $follow_up_query);

    // Manaully set the follow up variable in case the administrator has bypassed the follow up email to update the request 
    $set_follow_up = "UPDATE web_form SET follow_up_needed=0 WHERE hash='$hash_value'";
    $set_result = $mysqli->query($set_follow_up) or die ("Query failed: " . $mysqli->error . " Actual query: " . $set_follow_up);
}
?>
