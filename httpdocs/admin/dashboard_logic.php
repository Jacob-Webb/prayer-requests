<?php
/******************************************************************************
* dashboard_logic.php contains all of the backend information gathering and
* processing for the admin/index.php.
******************************************************************************/
require_once('../php/server_info.php');

$q = "SELECT hash, user_first_name, user_last_name, attending, intercession,
        for_first_name, for_last_name, request_contact, phone, email, category,
        prayer_request, prayer_timestamp, follow_up, user_responded, prayer_answered,
        update_request, testimony
        FROM web_form";

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
$table_values = array('hash', 'user_first_name', 'user_last_name', 'attending',
                      'intercession', 'for_first_name', 'for_last_name',
                      'request_contact', 'phone', 'email', 'category',
                      'prayer_request', 'prayer_timestamp', 'follow_up', 'user_responded',
                      'prayer_answered', 'update_request', 'testimony');

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

// Make sure we aren't dividing by zero.
if($total_count > 0) {
    $healing_percentage = round($healing_count / $total_count * 100);
    $provision_percentage = round($provision_count / $total_count * 100);
    $salvation_percentage = round($salvation_count / $total_count * 100);
} else {
    $healing_percentage = 0;
    $provision_percentage = 0;
    $salvation_percentage = 0;
}

/******************************************************************************
* displayTableHeader displays the Table name and information labels.
* @param string $prayer_category is the category of prayer request for the table
* @return void
******************************************************************************/
function displayTableHeader($prayer_category) {
    echo
        "<tr>
            <td colspan='8'><h3>" . $prayer_category ." Requests</h3></td>
        </tr>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Follow Up</th>
            <th>Prayer Status</th>
            <th>Prayer Information</th>
        </tr>";
}

/******************************************************************************
* displayModalBody is used to display the additional information of the
* prayer requests.
* @param $prayer_request is an element of the $prayer_array representing a
*     prayer request. All information will be on a row in the prayer table in sql
* @return string $information
******************************************************************************/
function displayModalBody($prayer_request) {
    $first_name = ($prayer_request['user_first_name'] == "") ? "anonymous" : $prayer_request['user_first_name'];
    $attending = ($prayer_request['attending'] == 1) ? "Yes" : "No";
    $intercession = ($prayer_request['intercession'] == 1) ? "Yes" : "No";
    $for_first = $prayer_request['for_first_name'];
    $for_last = $prayer_request['for_last_name'];

    $requested_contact = ($prayer_request['request_contact'] == 1) ? "Yes" : "No";
    $phone = ($prayer_request['phone']) ? $prayer_request['phone'] : "None Given";
    $email = ($prayer_request['email']) ? $prayer_request['email'] : "None Given";

    $prayer_request = $prayer_request['prayer_request'];
    $update = $prayer_request['update_request'];
    $testimony = $prayer_request['testimony'];

    // If the person marked the anonymous box and didn't mark the attending box
    // he/she may still attend
    if($first_name == "anonymous" && $attending == "No")
        $attending = "Unknown";

    $information =
        "<strong>Attends:</strong> " . $attending . "<br><br>
        <strong>For Someone Else:</strong> " . $intercession . "<br><br>";

    if($intercession == "Yes") {
        $information .=
            "<strong>For :</strong> " . $for_first . " " . $for_last . "<br><br>";
    }

    $information .=
        "<strong>Requested Contact:</strong> " . $requested_contact . "<br><br>
        <strong>Phone Number:</strong> " . $phone . "<br><br>
        <strong>Email:</strong> " . $email . "<br><br>";

    $information .=
        "<strong>Prayer Request:</strong> " . $prayer_request . "<br><br>";

    if(strlen($update) > 1)
        $information .=
            "<strong>Additional Prayer Info:</strong> " . $update . "<br><br>";

    if(strlen($testimony) > 1)
        $information .=
            "<strong>Testimony:</strong> " . $testimony . "<br><br>";

    return $information;
}

/*******************************************************************************
* displayRequestsInTable takes in all prayers in a category and displays in
* the form of an html table.
* param $prayer_array is a grouping of prayers based on category
* return void
*******************************************************************************/
function displayRequestsInTable($prayer_array, $prayer_category){
    displayTableHeader($prayer_category);

    $index = 0;
    if(count($prayer_array) > 0) {
        foreach($prayer_array as $row){
            $hash = $prayer_array[$index]['hash'];
            $first_name = ($prayer_array[$index]['user_first_name'] == "") ? "anonymous" : $prayer_array[$index]['user_first_name'];
            $last_name = $prayer_array[$index]['user_last_name'];
            $attending = ($prayer_array[$index]['attending'] == 1) ? "Yes" : "No";
            $intercession = ($prayer_array[$index]['intercession'] == 1) ? "Yes" : "No";
            $for_first = $prayer_array[$index]['for_first_name'];
            $for_last = $prayer_array[$index]['for_last_name'];
            //$category = $prayer_array[$index]['category'];
            $prayer_request = $prayer_array[$index]['prayer_request'];
            $follow_up = $prayer_array[$index]['follow_up'];
            $user_responded = $prayer_array[$index]['user_responded'];
            $prayer_answered = $prayer_array[$index]['prayer_answered'];
            $update = $prayer_array[$index]['update_request'];
            $testimony = $prayer_array[$index]['testimony'];

            // set follow_up status as:
            //   not sent: waiting
            //   sent, not responded to: pending
            //   sent, responded to: responded
            if($prayer_array[$index]['follow_up'] == 0) {
                $follow_up_status = "Waiting";
            } else {
                if($prayer_array[$index]['has_responded'] == 0) {
                    $follow_up_status = "Pending";
                } else {
                    $follow_up_status = "Responded";
                }
            }

            // set Prayer Status to "answered" if user_responded == 1, "no" otherwise
            if($prayer_array[$index]['prayer_answered'] == 1) {
                $answered = "Answered";
            } else {
                $answered = "Unanswered";
            }

            // display the info for each request in a given category
            echo
                "<tr>" .
                    "<td>" . $first_name . "</td>".
                    "<td>" . $last_name . "</td>" .
                    "<td>" . $follow_up_status . "</td>" .
                    "<td>" . $answered . "</td>" .
                    "<td>
                        <button type='button'class='btn btn-primary' data-toggle='modal' data-target='#". $hash ."Modal'>
                        See More</button>

                        <div class='modal fade' id='" . $hash . "Modal' tabindex='1' role='dialog'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                        <h4 class='modal-title'>Prayer Request: " . $first_name . "</h4>
                                    </div>
                                    <div class='modal-body'>" .
                                        displayModalBody($prayer_array[$index]) .
                                    "</div> <!-- /.modal-body -->
                                    <div class='modal-footer'>

                                    </div> <!-- /.modal-footer -->
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    </td>
                </tr>";

            ++$index;
        }
    } else {
        echo "<tr>" .
                "<td colspan=\"8\" align=\"center\"> No Requests </td>" .
             "</tr>";
    }
}

?>
