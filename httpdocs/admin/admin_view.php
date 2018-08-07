<?php
/******************************************************************************
* admin_view.php contains helper functions for the "view" portion of the admin site
******************************************************************************/

// displayTableHeader displays the header for each prayer category
// @param $category_heading should be a string of the title of the table 
function displayPrayerTableHeader($category_heading) {
    ?>
    <tr>
        <td colspan='8'><h3><?php echo $category_heading . ' Requests' ?></h3></td>
    </tr>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th class='print-only'>Phone</th>
        <th class='print-only'>Attend</th>
        <th>Prayer Request</th>
        <th class='print-only'>Testimony</th>
        <th class='web-only'>Follow Up</th>
        <th class='web-only'>Prayer Answered</th>
        <th class='web-only'>Prayer Information</th>
        <th class='web-only'>Delete</th>
    </tr>
    <?php
}

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
    $prayer_info['request_contact'] = ($prayer['request_contact'] == 1) ? "Yes" : "No";
    $prayer_info['for_first'] = $prayer['for_first_name'];
    $prayer_info['for_last'] = $prayer['for_last_name'];
    $prayer_info['request'] = $prayer['prayer_request'];
    $prayer_info['follow_up'] = $prayer['follow_up'];
    $prayer_info['update_request'] = $prayer['update_request'];
    $prayer_info['testimony'] = ($prayer['testimony']) ? $prayer_array[$index]['testimony'] : "No testimony yet";
    $prayer_info['timestamp'] = date('m/d/Y', strtotime($prayer['prayer_timestamp']));

    // set follow_up status as:
    //   not sent: waiting
    //   sent, not responded to: pending
    //   sent, responded to: responded
    if($prayer['request_contact'] == 0) {
        $prayer_info['follow_up_status'] = "None Requested";
    } elseif(($prayer['request_contact'] == 1) && ($prayer['email_sent'] == 0)) {
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
    if($prayer['request_contact'] == 0) {
        $prayer_info['answered'] = "N/A";
    } elseif($prayer['request_contact'] == 1 && $prayer['prayer_answered'] == 1) {
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

// displayModalBody sets a prayer's information to be accessible through a modal screen
// @return $information is simply a string containing prayer inforamtion and layout tags
function displayModalBody($prayer_info) {
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
            "<strong>For :</strong> " . $prayer_info['for_first'] . " " . $prayer_info['for_last'] . "<br><br>";
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

// displayPrayerTableBody is the template for all prayers. Each prayer in a certain category
// will be displayed in a row of that categories table.
// @param $prayer_info is an array of information taken from the rows of mysql
function displayPrayerTableBody($prayer_info) {?>
    <tr>
        <td><?php echo $prayer_info['first_name'] ?></td>
        <td><?php echo $prayer_info['last_name'] ?></td>
        <td class='print-only'><?php echo $prayer_info['phone'] ?></td>
        <td class='print-only'><?php echo $prayer_info['attending'] ?></td>
        <td style='max-width:200px'><?php echo $prayer_info['request'] ?></td>
        <td class='print-only' style='max-width:100px'><?php echo $prayer_info['testimony'] ?></td>
        <td class='web-only'><?php echo $prayer_info['follow_up_status'] ?></td>
        <td class='web-only'><?php echo $prayer_info['answered'] ?></td>
        <td class='web-only'>
            <button type='button'class='btn btn-primary' data-toggle='modal' data-target='<?php echo "#" .$prayer_info['hash'] . "Modal" ?>' style='color:black; width:auto'>See More</button>

            <div class='modal fade' id='<?php echo $prayer_info['hash'] . "Modal" ?>' tabindex='1' role='dialog'>
                <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h4 class='modal-title'>Prayer Request: <?php echo $prayer_info['first_name'] ?></h4>
                        </div>
                        <div class='modal-body'>
                            <?php echo displayModalBody($prayer_info) . $prayer_info['link'] ?>
                        </div> <!-- /.modal-body -->
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-default' data-dismiss='modal' style='margin:0 36%'>Close</button>
                        </div> <!-- /.modal-footer -->
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </td>
        <td>
            <input type='checkbox' class='web-only' id='delete-prayer-checkbox' name='delete-prayer-checkbox' data-pid=<?php echo $prayer_info['hash'] ?>>
        </td>
    </tr>
<?php
}

// displayPrayersAsTables takes in a group of prayers. For each category a new table will be made with a header.
// Each prayer in the category will be a row, and each piece of prayer's data will be a column.
// @param $all_prayer_array should be a multi-dimensional array containing: categories => prayers => prayer information
function displayPrayersAsTables($all_prayer_array) {
    //for each category
        //create a table
        //give it a header with the category name
        //for each prayer in the category
            //create a row of important information

    $categories = array_keys($all_prayer_array);

    foreach($categories as $category) {
        // capitalize the first letter of each category. Used for table title
        $heading = ucwords($category)?>
        <table>
            <?php
            displayPrayerTableHeader($heading);
            if(count($all_prayer_array[$category]) > 0) {
                foreach($all_prayer_array[$category] as $prayer) {
                    $prayer_information = getPrayerVariables($prayer);
                    displayPrayerTableBody($prayer_information);
                }
            // if there are no prayers to display
            } else { ?>
                <tr>
                    <td colspan='8' align='center'> No Requests </td>
                </tr>
          <?php
            }
          ?>
        </table>
<?php
    }
}
?>
