<?php
include 'dashboard_logic.php';

// Get the date range passed to this page and set the date_select variable

$given_start_date = isset($_GET['begin-date']) ? $_GET['begin-date'] : "";
$given_end_date = isset($_GET['end-date']) ? $_GET['end-date'] : "";

//If a beginning or ending date were given as parameters, the time period should be a range selection
if($given_start_date || $given_end_date){
    $time_period = 'range';
    $begin_date = $given_start_date;
    $end_date = $given_end_date;
//Otherwise the time period will have been one of the other choices and the beginning and ending dates will reflect that
} else {
    $time_period = isset($_GET['date-range']) ? $_GET['date-range'] : "";
    $begin_date = getBeginDate($time_period);
    $end_date = getEndDate($time_period);
}

?>
<!-- View -->
<html>
<head>
    <title>Prayer Admin</title>
    <link rel="stylesheet" type="text/css" href="//rock.church/assets/styles/build/base.css?rel=6d7cc76622">
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>

    <!-- Button to allow the administrator to fill out a prayer request -->
    <button style="margin:0 auto; float:right; width:auto" type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addRequest">
        New Prayer Request
    </button>

    <!-- send parameter from form to this page -->
    <form name="date-picker-form" action="index.php">

    <h2 align="center" style="margin:20px 33%">Prayer Requests
        <!-- this.parentNode.parentNode points to this page -->
        <select name="date-range" class="web-only" id="date-range" onchange="this.parentNode.parentNode.submit()">
            <!-- when the $select parameter is set to a value set the corresponding option to "selected" -->
            <option value='week' <?php echo $time_period == "week" ? "selected" : "";?>>This Week</option>
            <option value='two-weeks' <?php echo $time_period == "two-weeks" ? "selected" : "";?>>For Two Weeks</option>
            <option value='month' <?php echo $time_period == "month" ? "selected" : "" ?>>This Month</option>
            <option value='year' <?php echo $time_period == "year" ? "selected" : ""?>>This Year</option>
            <option value='range' <?php echo $time_period == "range" ? "selected" : ""?>>From</option>
        </select>
    </h2>
    </form>

    <?php
    //To get all of the requests up to $end_date we need to get the all prayers to 23:59 of that day
    $day_after_end_date = date('Y-m-d',strtotime($end_date . "+1 days"));

    $prayer_count = 0;
    //get all of the information from the database that we'll need to use
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //If the request was made within the date ranges, add to the prayer category arrays
            $row_date_time = strtotime($row['prayer_timestamp']);
            if($row_date_time >= strtotime($begin_date) && $row_date_time < strtotime($day_after_end_date)){
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
                elseif($row["category"] == "circumstances") {
                    foreach($table_values as $column) {
                        $circumstance_prayers[$circumstance_count][$column] = $row[$column];
                    }
                    ++$circumstance_count;
                }
            }
        }
    } else {
    }

    $total_count = $healing_count + $provision_count + $salvation_count + $circumstance_count;

    // Make sure we aren't dividing by zero.
    if($total_count > 0) {
        $healing_percentage = round($healing_count / $total_count * 100);
        $provision_percentage = round($provision_count / $total_count * 100);
        $salvation_percentage = round($salvation_count / $total_count * 100);
        $circumstance_percentage = round($circumstance_count / $total_count * 100);
    } else {
        $healing_percentage = 0;
        $provision_percentage = 0;
        $salvation_percentage = 0;
        $circumstance_percentage = 0;
    }

    //Display the date selection ranges if the user chooses "From" for time_period
    //Date tag takes a date as 'Y-m-d' and displays on Chrome as m/d/Y
    $today = date("Y-m-d");
    $implementation_date = "2018-01-01";
    $print_only_begin_date = date('m/d/Y',strtotime($begin_date));
    $print_only_end_date = date('m/d/Y', strtotime($end_date));
    if($time_period == 'range') {
        echo "<form name='date-range-form' action='index.php'>
                <h4 class='web-only' align='center'>
                    <input type='date' id='begin-date' name='begin-date' onchange='this.parentNode.parentNode.submit()'
                        value='$begin_date'
                        min='$implementation_date'
                        max='$end_date'/>
                    to
                    <input type='date' id='end-date' name='end-date' onchange='this.parentNode.parentNode.submit()'
                        value='$end_date'
                        min='$begin_date'
                        max='$today' />
                </h4>
                <h4 class='print-only-dates' align='center'>" . $print_only_begin_date . " - " . $print_only_end_date . "</h4>
              </form>";
    // Otherwise display the give beginning and ending date ranges
    } else {
        echo "<h4 align='center'>" . $begin_date . " - " . $end_date . "</h4>";
    }
?>

    <h3 style="float: left"><?php echo $total_count . "<br />" . "Requests" ?></h3>

    <div id="percentages">
        <?php echo "Healing: " . $healing_percentage . "%"?>
        <br />
        <?php echo "Provision: " . $provision_percentage . "%"?>
        <br />
        <?php echo "Salvation: " . $salvation_percentage . "%"?>
        <br />
        <?php echo "Circumstances: " . $circumstance_percentage . "%"?>
    </div> <!--closes percentages -->

    <div class="chart-container" style="margin:0 auto; height: 30vh; width: 30vw">
        <canvas id="my_chart"></canvas>
    </div> <!-- closes chart-container -->



    <br />
    <br />

    <!--
    Creates a table for all of the information for healing prayers
    -->
    <table id="heal-table" style="width: 100%">
        <?php displayRequestsInTable($healing_prayers, "Healing"); ?>
    </table>

    <br />
    <br />

    <!--
    Creates a table for all of the information for provisional prayers
    -->
    <table id="provision-table" style="width: 100%">
        <?php displayRequestsInTable($provision_prayers, "Provision"); ?>
    </table>

    <br />
    <br />

    <!--
    Creates a table for all of the information for salvation prayers
    -->
    <table id="salvation-table" style="width: 100%">
        <?php displayRequestsInTable($salvation_prayers, "Salvation"); ?>
    </table>

    <br />
    <br />

    <table id="circumstance-table" style="width: 100%">
        <?php displayRequestsInTable($circumstance_prayers, "Circumstance"); ?>
    </table>

    <button onclick="deletePrayers()" class="btn btn-warning" id="delete-button" style="width: auto; margin:0 0 0 85%; color:black">Delete Selected Prayers</button>


    <!-- ********************************************************************************
        Modal body from prayer request button at top of page
        *********************************************************************************-->
    <div class="modal fade" id="addRequest" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Prayer Request</h4>
                </div>
                <div class="modal-body">
                    <form id="admin-request-form" action="../receive_prayer_request.php" method="post">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous" value="anonymous">
                            <label class="form-check-label" for="anonymous">This prayer is anonymous</label>

                            <div class="hide-if-active">
                                <div id="user-info">
                                    <label class="sr-only" for="user-first">First Name: </label>
                                    <input class="require-if-inactive" type="text" name="user-first" id="user-first"
                                        placeholder="First Name" tabindex="1" data-require-pair="#anonymous">

                                    <label class="sr-only" for="user-last">Last Name: </label>
                                    <input class="require-if-inactive" type="text" name="user-last" id="user-last"
                                        placeholder="Last Name" tabindex="1" data-require-pair="#anonymous">

                                    <label class="sr-only" for="email">Email Address: </label>
                                    <input class="require-if-inactive" type="email" id="email" name="email"
                                        placeholder="Email" tabindex="1" data-require-pair="#anonymous">

                                    <label class="sr-only" for="phone">Phone Number: </label>
                                    <input class="require-if-inactive" type="phone" id="phone" name="phone"
                                        placeholder="Phone" tabindex="1" data-require-pair="#anonymous">
                                </div> <!-- /.user-info -->
                            </div> <!-- /.hide-if-active -->
                            <div class="reveal-if-active">
                                <p>Personal and contact information will not be stored if "anonymous" is checked.</p>
                            </div> <!-- /.reveal-if-active -->
                        </div> <!-- form-check -->

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="attend" name="attend" value="attend">
                            <label class="form-check-label" for="attend">This person attends The Rock Church</label>
                        </div> <!-- /.form-check -->

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="intercession" name="intercession" value="intercession">
                            <label class="form-check-label" for="intercession">This prayer is for someone else </label>

                            <div class="reveal-if-active">
                                <div class="col" id="recipient-name">
                                    <label class="sr-only" for="for-first">First Name: </label>
                                    <input class="require-if-active" type="text" name="for-first" id="for-first"
                                        placeholder="First Name" tabindex="1" data-require-pair="#intercession">

                                    <label class="sr-only" for="for-last">Last Name: </label>
                                    <input class="require-if-active" type="text" name="for-last" id="for-last"
                                        placeholder="Last Name" tabindex="1" data-require-pair="#intercession">
                                </div> <!-- /.recipient-name -->
                            </div> <!-- /.reveal-if-active -->
                        </div> <!-- form-check -->

                        <div class="form-group">
                            <label class="sr-only" for="category">Category:</label>
                            <select class="custom-select custom-select-sm" name="category" id="category">
                                <option value="physical">Healing</option>
                                <option value="provision">Provision</option>
                                <option value="salvation">Salvation</option>
                                <option value="circumstances">Circumstances</option>
                            </select>
                        </div> <!-- /.form-group -->

                        <div class="form-group">
                            <label class="sr-only" for="prayer-request">Request:</label>
                            <textarea class="form-control" name="prayer-request" id="prayer-request" placeholder="Your Prayer Here" rows="5" required></textarea>
                        </div> <!-- /.form-group -->

                </div> <!-- /.modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button><input type="hidden" name="hash-value" value="<?php echo $hash ?>">
                    <!-- Pass is-admin=True when a prayer request is being added through the dashboard -->
                    <input type="hidden" name="is-admin" value="True">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div> <!-- /.modal-footer -->
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- **********************************************************************
        End modal button
        *********************************************************************** -->

    <!-- get javascript variables from php to pass to the charts -->
    <script>
        var healing_percentage = <?php echo $healing_percentage; ?>;
        var provision_percentage = <?php echo $provision_percentage; ?>;
        var salvation_percentage = <?php echo $salvation_percentage; ?>;
        var circumstance_percentage = <?php echo $circumstance_percentage; ?>;
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="node_modules/chart.js/dist/Chart.bundle.js"></script>
    <script src="../js/functionality.js"></script>
    <script src="//rock.church/assets/js/build/production.min.js?rel=e81611a50c"></script>

</body>
</html>
