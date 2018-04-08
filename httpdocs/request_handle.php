<?php

$first_name = htmlspecialchars($_POST['first']);
$last_name = htmlspecialchars($_POST['last']);
$prayer_category = htmlspecialchars($_POST['category']);
$request = htmlspecialchars($_POST['prayer_request']);
echo "Hi $first_name $last_name.";
echo "I see you requested prayer for $request";
?>
