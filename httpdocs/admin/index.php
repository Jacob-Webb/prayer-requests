<?php
require "dashboard_logic.php";

$date_select = isset($_GET['date-range']) ? $_GET['date-range'] : "";
?>
<html>
<head>
    <title>Prayer Admin</title>
    <link rel="stylesheet" type="text/css" href="//rock.church/assets/styles/build/base.css?rel=6d7cc76622">
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
    <!-- send parameter from form to this page -->
    <form name="dashboard-form" action="index.php">
    <h2 align="center">Prayer Requests
        <!-- The parentNode property returns the parent node of the specified
            node, as a Node object.
            Note: In HTML, the document itself is the parent node of the HTML
            element, HEAD and BODY are child nodes of the HTML element.
            Need to get parentNode.parentNode of this because it's wrapped in a body tag
            onchange calls the function when the select value is changed -->
        <select name="date-range" id="date-range" onchange="this.parentNode.parentNode.submit()">
            <!-- when the $select parameter is set to a value set the corresponding option to "selected" -->
            <option value='week' <?php echo $date_select == "week" ? "selected" : "";?>>This Week</option>
            <option value='two-weeks' <?php echo $date_select == "two-weeks" ? "selected" : "";?>>For Two Weeks</option>
            <option value='month' <?php echo $date_select == "month" ? "selected" : "" ?>>This Month</option>
            <option value='year' <?php echo $date_select == "year" ? "selected" : ""?>>This Year</option>
        </select>
    </h2>
    </form>

<?php

//pull any prayer requests withing these ranges
$begin_date_range = getBeginDate($date_select);
$end_date_range = getEndDate($date_select);

//get all of the information from the database that we'll need to use
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //If the request was made within the date ranges, add to the prayer category arrays
        $row_date_time = strtotime($row['prayer_timestamp']);
        if($row_date_time >= strtotime($begin_date_range)){
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
?>
<?php
/*
    <div id='date-table'>
        <div class='table-cell'>
            <!--<label for='begin-date'>Begin: </label>-->
            <input type='date' class='date-input' id='begin-date' name='begin-date'>
        </div> <!-- /.table-cell -->
        <h4 style='margin: 0 10px'>To</h4>
        <div class='table-cell'>
            <!--<label for='end-date'>End: </label>-->
            <input type='date' class='date-input' id='end-date' name='end-date'>
        </div> <!-- /.table-cell -->
    </div> <!-- /.date-table -->
*/
?>
    <h4 align="center"><?php echo $begin_date_range . " - " . $end_date_range ?></h4>

    <h3 style="float: left"><?php echo $total_count . "<br />" . "Requests" ?></h3>

    <div id="percentages">
        <?php echo "Healing: " . $healing_percentage . "%"?>
        <br />
        <?php echo "Provision: " . $provision_percentage . "%"?>
        <br />
        <?php echo "Salvation: " . $salvation_percentage . "%"?>
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
        <?php
            displayRequestsInTable($healing_prayers, "Healing");
        ?>
    </table>

    <br />
    <br />

    <!--
    Creates a table for all of the information for provisional prayers
    -->
    <table id="provision-table" style="width: 100%">
        <?php
            displayRequestsInTable($provision_prayers, "Provision");
        ?>
    </table>

    <br />
    <br />

    <!--
    Creates a table for all of the information for salvation prayers
    -->
    <table id="salvation-table" style="width: 100%">
        <?php
            displayRequestsInTable($salvation_prayers, "Salvation");
        ?>
    </table>

    <br />
    <br />

    <!-- get javascript variables from php to pass to the charts -->
    <script>
        var healing_percentage = <?php echo $healing_percentage; ?>;
        var provision_percentage = <?php echo $provision_percentage; ?>;
        var salvation_percentage = <?php echo $salvation_percentage; ?>;
    </script>
    <script src="node_modules/chart.js/dist/Chart.bundle.js"></script>
    <script src="../js/piechart.js"></script>
    <script src="//rock.church/assets/js/build/production.min.js?rel=e81611a50c"></script>
</body>
</html>
