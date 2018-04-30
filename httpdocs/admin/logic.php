<?php
require_once($_SERVER['DOCUMENT_ROOT'] . 'php/server_info.php');

$q = "SELECT user_first_name, user_last_name, attending, intercession, for_first_name,
        for_last_name, request_contact, phone, email, category, prayer_request,
        prayer_timestamp FROM web_form";

$result = $mysqli->query($q);

//multidimensional arrays for all values of each category request
$healing_prayers = array();
$provision_prayers = array();
$salvation_prayers = array();

//keep track of prayers in category for a given time period
$healing_count = 0;
$provision_count = 0;
$salvation_count = 0;
$total_count = 0;

//pull any prayer requests withing these ranges
$begin_time_range = date('m/d/Y', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y')));
$end_time_range = date('m/d/Y');

//useful to get a string from an associative array using an index 0 == 'user_first_name' and so on
$table_values = array('user_first_name', 'user_lsdfast_name', 'attending', 'intercession', 'for_first_name',
                      'for_last_name', 'request_contact', 'phone', 'email', 'category', 'prayer_request',
                      'prayer_timestamp');

//get all of the information from the database that we'll need to use
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //If the request was made within the date ranges, add to the prayer category arrays
        $row_date_time = strtotime($row['prayer_timestamp']);
        //if(($row_date_time >= strtotime($begin_time_range)) && ($row_date_time <= strtotime($end_time_range))) {
        if($row_date_time >= strtotime($begin_time_range)){
            //group all "healing" prayers in an array
            if($row["category"] == "physical") {
                foreach($table_values as $column) {
                    $healing_prayers[$healing_count][$column] = $row[$column];
                }
                ++$healing_count;
            }
            //group all "provision" prayers in an array
            elseif($row["category"] == "provision") {
                foreach($table_values as $column) {
                    $provision_prayers[$provision_count][$column] = $row[$column];
                }
                ++$provision_count;
            }
            //group all "salvation" prayers in an array
            elseif($row["category"] == "salvation") {
                foreach($table_values as $column) {
                    $salvation_prayers[$salvation_count][$column] = $row[$column];
                }
                ++$salvation_count;
            }
        }
    }
} else {
    echo "0 results";
}

$total_count = $healing_count + $provision_count + $salvation_count;

// category percentages in ints
$healing_percentage = round($healing_count / $total_count * 100);
$provision_percentage = round($provision_count / $total_count * 100);
$salvation_percentage = round($salvation_count / $total_count * 100);

/*******************************************************************************
* displayRequestsInTable takes in all prayers in a category and displays in
* the form of an html table.
* param $prayer_array is a grouping of prayers based on category
* return void
*******************************************************************************/
function displayRequestsInTable($prayer_array){
    $index = 0;
    if(count($prayer_array) > 0) {
        foreach($prayer_array as $row){
            // convert int type 0 or 1 to string 'No' or 'Yes', respectively
            if($prayer_array[$index]['attending'] == 0) {
                $attending = "No";
            }
            else {
                $attending = "Yes";
            }

            echo  "<tr>" .
                    "<td>" . $prayer_array[$index]['user_first_name'] . "</td>".
                    "<td>" . $prayer_array[$index]['user_last_name'] . "</td>" .
                    "<td>" . $attending . "</td>" .
                    "<td>" . $prayer_array[$index]['for_first_name'] . "</td>" .
                    "<td>" . $prayer_array[$index]['for_last_name'] . "</td>" .
                    "<td>" . $prayer_array[$index]['phone'] .  "</td>" .
                    "<td>" . $prayer_array[$index]['email'] . "</td>" .
                    "<td>" . $prayer_array[$index]['prayer_request'] . "</td>" .
                  "</tr>";
            ++$index;
        }
    } else {
        echo "<tr>" .
                "<td colspan=\"8\" align=\"center\"> No Requests </td>" .
             "</tr>";
    }
}

?>
