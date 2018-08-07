<?php
/*
Refactored 7-12-18 admin/index.php
*/
require 'admin_controller.php';
require 'admin_view.php';
//require_once '../access_database.php';

// getDateParameters found in admin_controller.php
// Set values based on parameters to index.php from date-picker-form on index.php
$selected_dates = getDateParameters();
$start_date = $selected_dates['start_date'];
$end_date = $selected_dates['end_date'];
$time_period = $selected_dates['time_period'];

//From ../access_database.php
//create one large multi-dimensional array to hold healing, provision, salvation, and circumstances prayers
$prayers_by_category_array = getCategorizedPrayers($mysqli, $start_date, $end_date);

//From admin_controller.php
$total_prayer_count = getTotalPrayerCount($prayers_by_category_array);
$prayer_percentages_array = getCategoryPercentages($total_prayer_count, $prayers_by_category_array);

//special dates used for date selector form
$today = date("Y-m-d");
$implementation_date = "2018-01-01";
$print_only_start_date = date('m/d/Y',strtotime($start_date));
$print_only_end_date = date('m/d/Y', strtotime($end_date));
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

    <!-- Display date range selector -->
    <form name="date-picker-form" action="_index.php">
    <h2 align="center" style="margin:20px 33%">Prayer Requests
        <!-- this.parentNode.parentNode points to this page -->
        <select name="date-range" class="web-only" id="date-range" onchange="this.parentNode.parentNode.submit()">
            <!-- option value is select on page load depending on $time-period's value -->
            <option value='week' <?php echo $time_period == "week" ? "selected" : "";?>>This Week</option>
            <option value='two-weeks' <?php echo $time_period == "two-weeks" ? "selected" : "";?>>For Two Weeks</option>
            <option value='month' <?php echo $time_period == "month" ? "selected" : "" ?>>This Month</option>
            <option value='year' <?php echo $time_period == "year" ? "selected" : ""?>>This Year</option>
            <option value='range' <?php echo $time_period == "range" ? "selected" : ""?>>From</option>
        </select>
    </h2>
    </form>

    <!-- Display the date ranges -->
    <?php if($time_period == 'range') : ?>
    <form name='date-range-form' action='index_2.php'>
        <h4 class='web-only' align='center'>
            <input type='date' id'begin-date' name='begin-date' onchange='this.parentNode.parentNode.submit()'
                value=<?php echo $start_date ?>
                min=<?php echo $implementation_date ?>
                max=<?php echo $end_date ?>/>
            to
            <input type='date' id='end-date' name='end-date' onchange='this.parentNode.parentNode.submit()'
                value=<?php echo $end_date ?>
                min=<?php echo $start_date ?>
                max=<?php echo $today ?> />
        </h4>
        <h4 class='print-only-dates' align='center'><?php $print_only_start_date . " - " . $print_only_end_date ?></h4>
      </form>
    <?php else : ?>
    <h4 align='center'><?php echo $start_date . " - " . $end_date ?></h4>
    <?php endif; ?>

    <h3 style="float: left"><?php echo $total_prayer_count . "<br />" . "Requests" ?></h3>

    <div id="percentages">
        Healing: <?php  echo $prayer_percentages_array['healing'] . "%" ?>
        <br />
        Provision: <?php echo $prayer_percentages_array['provision'] . "%"?>
        <br />
        Salvation: <?php echo $prayer_percentages_array['salvation'] . "%"?>
        <br />
        Circumstances: <?php echo $prayer_percentages_array['circumstances'] . "%"?>
    </div>

    <div class="chart-container" style="margin:0 auto; height: 30vh; width: 30vw">
        <canvas id="my_chart"></canvas>
    </div>

    <br />
    <br />

    <div class="prayer-tables">
        <?php displayPrayersAsTables($prayers_by_category_array); ?>
    </div> <!-- /.prayer-tables -->

    <button onclick="deletePrayers()" class="btn btn-warning" id="delete-button" style="width: auto; margin:0 0 0 85%; color:black">Delete Selected Prayers</button>

<?php /*
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
                    <form id="admin-request-form" action="../php/request_handle.php" method="post">
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
*/
?>


    <!-- **********************************************************************
        End modal button
        *********************************************************************** -->

    <!-- get javascript variables from php to pass to the charts -->
    <script>
        var healing_percentage = <?php echo $prayer_percentages_array['healing']; ?>;
        var provision_percentage = <?php echo $prayer_percentages_array['provision']; ?>;
        var salvation_percentage = <?php echo $prayer_percentages_array['salvation']; ?>;
        var circumstance_percentage = <?php echo $prayer_percentages_array['circumstances']; ?>;
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="node_modules/chart.js/dist/Chart.bundle.js"></script>
    <script src="../js/functionality.js"></script>
    <script src="//rock.church/assets/js/build/production.min.js?rel=e81611a50c"></script>

</body>
</html>
