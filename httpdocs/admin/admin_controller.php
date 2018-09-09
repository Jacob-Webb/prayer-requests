<?php
require_once '../access_database.php';

/******************************************************************************
        ~~~~~~~~~~~~~ Functions for index.php  ~~~~~~~~~~~~~~~
******************************************************************************/
// Take in parameters passed to index.php by index.php at date-picker-form
function getDateParameters() {
	$dates = [];
	//Get the date range passed to this page and set the date_select variable
	$given_start_date = isset($_GET['begin-date']) ? $_GET['begin-date'] : "";
	$given_end_date = isset($_GET['end-date']) ? $_GET['end-date'] : "";

	//If a beginning or ending date were given as parameters, the time period should be a range selection
	if($given_start_date || $given_end_date){
		$dates['time_period'] = 'range';
		$dates['start_date'] = $given_start_date;
		$dates['end_date'] = $given_end_date;
	//Otherwise the time period will have been one of set time periods and the beginning and ending dates will reflect that
	} else {
		$dates['time_period'] = isset($_GET['date-range']) ? $_GET['date-range'] : "";
		$dates['start_date'] = getBeginDate($dates['time_period']);
		$dates['end_date'] = getEndDate($dates['time_period']);
	}

return $dates;
}

//Return the desired beginning date whether a week ago, two weeks, one month, one year, or some other specified date
function getBeginDate($date_range) {
    // the default range will be one week
    if($date_range == 'week' || $date_range == '')
        return date('m/d/Y', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y')));
    if($date_range == 'two-weeks')
        return date('m/d/Y', mktime(0, 0, 0, date('m'), date('d') - 13, date('Y')));
    if($date_range == 'month')
        return date('m/d/Y', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
    if($date_range == 'year')
        return date('m/d/Y', mktime(0, 0, 0, date('m'), date('d'), date('Y') - 1));
    //Html date tag requires 'Y-m-d' format. Displays 'm/d/Y'
    if($date_range == 'range') {
        return date('Y-m-d');
    }
}

// Return today's date
function getEndDate($date_range) {
    if($date_range == 'range') {
        //Html date tag requires 'Y-m-d' format. Displays 'm/d/Y'
        return date('Y-m-d');
    }
    return date('m/d/Y');
}

// Return the total number of prayers in a given array
// @param $categorized_prayer_array multidimensional array that contains all prayers of a category as values with the key being the category name
// @return $total_prayers
function getTotalPrayerCount($categorized_prayer_array) {
	$total_prayers = 0;
	foreach($categorized_prayer_array as $prayers_in_category) {
		$total_prayers += count($prayers_in_category);
	}
	return $total_prayers;
}

// Return an associative array of percentages by prayer category
function getCategoryPercentages($total_prayers, $categorized_prayer_array) {
	$category_percentages = array();

	$categories = array_keys($categorized_prayer_array);
	foreach($categories as $category) {
		//Make sure we aren't dividing by zero
		if($total_prayers > 0) {
			$category_percentages[$category] = round(count($categorized_prayer_array[$category]) / $total_prayers * 100);
		} else {
			$category_percentages[$category] = 0;
		}
	}
	return $category_percentages;
}

function getDisplayableInfo($prayer) {
	$index = 0;

    $prayer['user_first_name'] = ($prayer['user_first_name'] == "") ? "anonymous" : $prayer['user_first_name'];
	$prayer['attending'] = ($prayer['attending'] == 1) ? "Yes" : "No";
    $prayer['intercession']= ($prayer['intercession'] == 1) ? "Yes" : "No";
    $prayer['testimony'] = ($prayer['testimony']) ? $prayer['testimony'] : "No testimony yet";

    // set follow_up status as:
    //   not sent: waiting
    //   sent, not responded to: pending
    //   sent, responded to: responded
    if($contact_requested == 0) {
       $follow_up_status = "None Requested";
    } elseif(($contact_requested == 1) && ($email_sent == 0)) {
       $follow_up_status = "Waiting";
    } else {
       if($prayer_array[$index]['user_responded'] == 0) {
           $follow_up_status = "Pending";
        } else {
           $follow_up_status = "Responded";
        }
    }

    // set Prayer Status to "answered" if user_responded == 1, "no" otherwise
    //Also, change the color of the modal button depending on whether the prayer has been answered
    if($contact_requested == 0) {
        $answered = "N/A";
        //$tr_color="background-color:#3399FF";
    } elseif($contact_requested == 1 && $prayer_answered == 1) {
        $answered = "Yes";
        //$tr_color="background-color:#19A319";
        //$tr_color="background-color:#3399FF";
    } else {
        $answered = "No";
        //$tr_color="background-color:#FFFF66";
    }

    if($_SERVER['SERVER_NAME'] == 'prayer-rock-church') {
        $link = '<a href="follow_up_form.php?hash=' .
        $hash . '">Update Request</a>';
    } elseif ($_SERVER['SERVER_NAME'] == 'prayer.rock.church') {
        $link = '<a href="https://prayer.rock.church/admin/follow_up_form.php?hash=' .
                  $hash . '">Update Request</a>';
    } else {
        echo "no link";
    }
}

/******************************************************************************
        ~~~~~~~~~~~~~ Functions for admin_view.php  ~~~~~~~~~~~~~~~
******************************************************************************/
// getPrayerVariables grabs all pertinent information from the prayer row dump from mysql and makes it readable
// @param is an array of information equivalent to a mysql row for a prayer
function getPrayerVariables($prayer) {
    $prayer_info = array();

    // grab information from the $prayer array and convert some of it to be more readable
    $prayer_info['hash'] = $prayer['hash'];
    $prayer_info['first_name'] = ($prayer['user_first_name'] == "") ? "anonymous" : $prayer['user_first_name'];
    $prayer_info['last_name'] = $prayer['user_last_name'];
    $prayer_info['phone'] = ($prayer['phone']) ? $prayer['phone'] : "None Given";
    $prayer_info['email'] = ($prayer['email']) ? $prayer['email'] : "None Given";
    $prayer_info['attending'] = ($prayer['attending'] == 1) ? "Yes" : "No";
    $prayer_info['intercession'] = ($prayer['intercession'] == 1) ? "Yes" : "No";
    $prayer_info['contact_requested'] = ($prayer['contact_requested'] == 1) ? "Yes" : "No";
    $prayer_info['prayer_is_for'] = $prayer['prayer_is_for'];
    $prayer_info['request'] = $prayer['prayer_request'];
    $prayer_info['follow_up_needed'] = $prayer['follow_up_needed'];
    $prayer_info['update_request'] = $prayer['update_request'];
    $prayer_info['testimony'] = ($prayer['testimony']) ? $prayer_array[$index]['testimony'] : "No testimony yet";
    $prayer_info['timestamp'] = date('m/d/Y', strtotime($prayer['prayer_timestamp']));

    // set follow_up status as:
    //   not sent: waiting
    //   sent, not responded to: pending
    //   sent, responded to: responded
    if($prayer['contact_requested'] == 0) {
        $prayer_info['follow_up_status'] = "None Requested";
    } elseif(($prayer['contact_requested'] == 1) && ($prayer['email_sent'] == 0)) {
        $prayer_info['follow_up_status'] = "Waiting";
    } else {
        if($prayer['user_responded'] == 0) {
            $prayer_info['follow_up_status'] = "Pending";
        } else {
            $prayer_info['follow_up_status'] = "Responded";
        }
    }

    // set Prayer Status to "answered" if user_responded == 1, "no" otherwise
    //Also, change the color of the modal button depending on whether the prayer has been answered
    if($prayer['contact_requested'] == 0) {
        $prayer_info['answered'] = "N/A";
    } elseif($prayer['contact_requested'] == 1 && $prayer['prayer_answered'] == 1) {
        $prayer_info['answered'] = "Yes";
    } else {
        $prayer_info['answered'] = "No";
    }

    if($_SERVER['SERVER_NAME'] == 'prayer-rock-church') {
        $prayer_info['link'] = '<a href="follow_up_form.php?hash=' .
                $prayer_info['hash'] . '">Update Request</a>';
    } elseif ($_SERVER['SERVER_NAME'] == 'prayer.rock.church') {
        $prayer_info['link'] = '<a href="https://prayer.rock.church/admin/follow_up_form.php?hash=' .
                $prayer_info['hash'] . '">Update Request</a>';
    } else $prayer_info['link'] = '';

    return $prayer_info;
}

// getModalBody sets a prayer's information to be accessible through a modal screen
// @return $information is simply a string containing prayer inforamtion and layout tags
function getModalBody($prayer_info) {
    // If the person marked the anonymous box and didn't mark the attending box
    // he/she may still attend

    if($prayer_info['first_name'] == "anonymous" && $prayer_info['attending'] == "No")
        $prayer_info['attending'] = "Unknown";

    $information =
        "<strong>Date:</strong>" . $prayer_info['timestamp'] . "<br><br>
        <strong>Attend:</strong> " . $prayer_info['attending'] . "<br><br>
        <strong>For Someone Else:</strong> " . $prayer_info['intercession'] . "<br><br>";

    if($prayer_info['intercession'] == "Yes") {
        $information .=
            "<strong>For :</strong> " . $prayer_info['prayer_is_for'] . "<br><br>";
    }

    $information .=
        "<strong>Phone Number:</strong> " . $prayer_info['phone'] . "<br><br>
        <strong>Email:</strong> " . $prayer_info['email'] . "<br><br>";

    if($prayer_info['update_request'])
        $information .=
            "<strong>Additional Prayer Info:</strong> " . $prayer_info['update_request'] . "<br><br>";

    if($prayer_info['testimony'])
        $information .= "<strong>Testimony:</strong> " . $prayer_info['testimony'] . "<br><br>";

    return $information;
}
?>
