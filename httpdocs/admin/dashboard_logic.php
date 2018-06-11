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

//keep track of the number of times a name is found in prayer request table
$name_count = array();
$flag_name = array();

//keep track of prayers in category for a given time period
$healing_count = 0;
$provision_count = 0;
$salvation_count = 0;
$total_count = 0;

//useful to get a string from an associative array using an index 0 == 'user_first_name' and so on
$table_values = array('hash', 'user_first_name', 'user_last_name', 'attending',
                      'intercession', 'for_first_name', 'for_last_name',
                      'request_contact', 'phone', 'email', 'category',
                      'prayer_request', 'prayer_timestamp', 'follow_up', 'user_responded',
                      'prayer_answered', 'update_request', 'testimony');

/******************************************************************************
* getBeginDate returns a date that is a certain time before the current date.
* @param string $date_range should be a value of 'week', 'two-weeks', 'month',
* or 'year'.
* @return string $begin_date
******************************************************************************/
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
}

/******************************************************************************
* getEndDate right now returns the current date. Should be made to end at a
* certain range.
******************************************************************************/
function getEndDate($date_range) {
    return date('m/d/Y');
}

/******************************************************************************
* displayTableHeader displays the Table name and information labels.
* print-only is the information to be displayed for printing.
* web-only is the information to be displayed on web page.
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
            <th class='print-only'>Attends</th>
            <th class='print-only'>Prayer Request</th>
            <th class='print-only'>Testimony</th>
            <th class='web-only'>Follow Up</th>
            <th class='web-only'>Prayer Status</th>
            <th class='web-only'>Prayer Information</th>
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

    $request = $prayer_request['prayer_request'];
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
        "<strong>Prayer Request:</strong> " . $request . "<br><br>";

    if($update)
        $information .=
            "<strong>Additional Prayer Info:</strong> " . $update . "<br><br>";

    if($testimony)
        $information .= "<strong>Testimony:</strong> " . $testimony . "<br><br>";


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
            $testimony = ($prayer_array[$index]['testimony']) ? $prayer_array[$index]['testimony'] : "No testimony yet";

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

            echo
                //display info on the website only. The modal will give additional information
                // on the dashboard.
                "<tr>" .
                    "<td>" . $first_name . "</td>".
                    "<td>" . $last_name . "</td>" .
                    "<td class='print-only'>" . $attending . "</td>" .
                    "<td class='print-only'>" . $prayer_request . "</td>" .
                    "<td class='print-only'>" . $testimony . "</td>" .
                    "<td class='web-only'>" . $follow_up_status . "</td>" .
                    "<td class='web-only'>" . $answered . "</td>" .
                    "<td class='web-only'>
                        <button type='button'class='btn btn-primary' data-toggle='modal' data-target='#". $hash ."Modal'>
                        See More</button>

                        <div class='modal fade' id='" . $hash . "Modal' tabindex='1' role='dialog'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h4 class='modal-title'>Prayer Request: " . $first_name . "</h4>
                                    </div>
                                    <div class='modal-body'>" .
                                        displayModalBody($prayer_array[$index]) .
                                    "</div> <!-- /.modal-body -->
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
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
