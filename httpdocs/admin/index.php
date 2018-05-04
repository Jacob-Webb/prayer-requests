<?php require "logic.php"; ?>
<html>

<body>
    <link rel="stylesheet" href="admin_styles.css">
    <h1 align="center">Prayer Requests This Week</h1>
    <h4 align="center"><?php echo $begin_time_range . " - " . $end_time_range ?></h4>
    <hr>

    <h3 style="float: left"><?php echo $total_count . "<br />" . "Requests" ?></h3>

    <div id="percentages">
        <?php echo "Healing: " . $healing_percentage . "%"?>
        <br />
        <?php echo "Provision: " . $provision_percentage . "%"?>
        <br />
        <?php echo "Salvation: " . $salvation_percentage . "%"?>
    </div> <!--closes percentages -->

    <div class="chart-container" style="position: relative; float: right; height: 45vh; width: 30vw">
        <canvas id="my_chart"></canvas>
    </div> <!-- closes chart-container -->

    <br />
    <br />

    <!--
    Creates a table for all of the information for healing prayers
    -->
    <table id="heal-table" style="width: 100%">
        <tr>
            <th colspan="8">Healing Requests</th>
        </tr>
        <tr>
            <td>First Name</td>
            <td>Last Name</td>
            <td>Attends</td>
            <td>For: First Name</td>
            <td>For: Last Name</td>
            <td>Phone</td>
            <td>Email</td>
            <td>Prayer Request</td>
        </tr>
        <?php
            displayRequestsInTable($healing_prayers);
        ?>
    </table>

    <br />
    <br />

    <!--
    Creates a table for all of the information for provisional prayers
    -->
    <table id="provision-table" style="width: 100%">
        <tr>
            <th colspan="8">Provision Requests</th>
        </tr>
        <tr>
            <td>First Name</td>
            <td>Last Name</td>
            <td>Attends</td>
            <td>For: First Name</td>
            <td>For: Last Name</td>
            <td>Phone</td>
            <td>Email</td>
            <td>Prayer Request</td>
        </tr>
        <?php
            displayRequestsInTable($provision_prayers);
        ?>
    </table>

    <br />
    <br />

    <!--
    Creates a table for all of the information for salvation prayers
    -->
    <table id="salvation-table" style="width: 100%">
        <tr>
            <th colspan="8">Salvation Requests</th>
        </tr>
        <tr>
            <td>First Name</td>
            <td>Last Name</td>
            <td>Attends</td>
            <td>For: First Name</td>
            <td>For: Last Name</td>
            <td>Phone</td>
            <td>Email</td>
            <td>Prayer Request</td>
        </tr>
        <?php
            displayRequestsInTable($salvation_prayers);
        ?>
    </table>

    <br />
    <br />

    <script>
        var healing_percentage = <?php echo $healing_percentage; ?>;
        var provision_percentage = <?php echo $provision_percentage; ?>;
        var salvation_percentage = <?php echo $salvation_percentage; ?>;
    </script>
    <script src="node_modules/chart.js/dist/Chart.bundle.js"></script>
    <script src="piechart.js"></script>
</body>
</html>
