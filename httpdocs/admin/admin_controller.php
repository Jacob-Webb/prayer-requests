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
            if($request_contact == 0) {
                $follow_up_status = "None Requested";
            } elseif(($request_contact == 1) && ($email_sent == 0)) {
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
            if($request_contact == 0) {
                $answered = "N/A";
                //$tr_color="background-color:#3399FF";
            } elseif($request_contact == 1 && $prayer_answered == 1) {
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
?>
