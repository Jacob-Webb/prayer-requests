<?php require "dashboard_logic.php"; ?>
<html>
<head>
    <title>Prayer Admin</title>
    <link rel="stylesheet" type="text/css" href="//rock.church/assets/styles/build/base.css?rel=6d7cc76622">
    <link rel="stylesheet" href="../css/admin_styles.css">

<head>
<body>
    <h2 align="center">Prayer Requests This Week</h2> 

    <h4 align="center"><?php echo $begin_time_range . " - " . $end_time_range ?></h4>

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
