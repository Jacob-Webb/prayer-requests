<!doctype html>
<?php 
    include ("includes/display_modals.php"); 
    ?>

<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Prayer Request</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">

    <link rel="stylesheet" type="text/css" href="//rock.church/assets/styles/build/base.css?rel=6d7cc76622">
    <link rel="stylesheet" href="css/main.css">

</head>
<body>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        Request Prayer
    </button>

<p style="color:grey;"></p>

   <?php display_prayer_form(); ?>

    <script src="//rock.church/assets/js/build/production.min.js?rel=e81611a50c"></script>

    <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
    <script>
        window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
        ga('create','UA-XXXXX-Y','auto');ga('send','pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/form.js"></script>
    <script src="js/character-count.js"></script>
</body>
<!-- He deserves more -->
</html>