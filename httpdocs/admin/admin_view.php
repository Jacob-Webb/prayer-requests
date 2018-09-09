<?php
require_once 'admin_controller.php';

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
                            <!-- getModalBody found in admin_controller.php -->
                            <?php echo getModalBody($prayer_info) . $prayer_info['link'] ?>
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
    $categories = array_keys($all_prayer_array);

    // for each prayer category
    foreach($categories as $category) {
        // capitalize the first letter of each category. Used for table title
        $heading = ucwords($category) ?>
        <table>
            <?php
            //create a table and give it a header with the category's name
            displayPrayerTableHeader($heading);
            if(count($all_prayer_array[$category]) > 0) {
                //for each prayer in a category create a row of important information
                foreach($all_prayer_array[$category] as $prayer) {
                    //getPrayerVariables found in admin_controller.php
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

        <br><br>
<?php
    }
}
?>
