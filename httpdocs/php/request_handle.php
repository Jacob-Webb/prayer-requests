<?php
/******************************************************************************
* request_handle.php takes the input from index.html. It inserts the input
* into the database, creates a confirmation message on the page, and then
* emails the user if he/she gave an email.
******************************************************************************/
require 'server_info.php';
require 'helper_functions.php';
include_once 'message_maker.php';
require_once 'swiftmailer/lib/swift_required.php';

// The request was received from either front-end website (value==False) or back-end admin site (value==True)
$is_admin = $_POST['is-admin'];

// Get all variables from the prayer request form on main page
// If the user checked the anonymous box, insert empty strings for the user info fields
$user_first_name = (isset($_POST['anonymous'])) ? '' : sanitize($_POST['user-first']);
$user_first_name = str_replace(' ', '', $user_first_name);
$user_last_name = (isset($_POST['anonymous'])) ? '' : sanitize($_POST['user-last']);
$email_to = (isset($_POST['anonymous'])) ? '' : sanitize($_POST['email']);
//only admin can set the phone number when adding a request
if($is_admin == "True")
    $phone = (isset($_POST['anonymous'])) ? '' : sanitize($_POST['phone']);
else $phone = '';

// if an email was sent in the form, set $request_contact to true, otherwise false,
// $follow_up if contact requested, otherwise don't
$request_contact = ($email_to == '') ? 0 : 1;
$follow_up = $request_contact;
$email_sent = 0;
$user_responded = 0;
$prayer_answered = 0;
$attend = (isset($_POST['attend'])) ? 1 : 0;
$intercession = (isset($_POST['intercession'])) ? 1 : 0;
$for_first_name = ($intercession) ? sanitize($_POST['for-first']) : $user_first_name;
$for_first_name = str_replace(' ', '', $for_first_name);
$for_last_name = ($intercession) ? sanitize($_POST['for-last']) : $user_last_name;
$prayer_category = sanitize($_POST['category']);
$request = sanitize($_POST['prayer-request']);
$time = date("Y:m:d H:i:s");

//attempt to transfer variables to database
$q = "INSERT INTO web_form (user_first_name, user_last_name, attending, intercession,
        for_first_name, for_last_name, request_contact, phone, email, category,
        prayer_request, prayer_timestamp, follow_up, email_sent, user_responded, prayer_answered)
        VALUES ('$user_first_name', '$user_last_name', '$attend', '$intercession',
        '$for_first_name', '$for_last_name', '$request_contact', '$phone', '$email_to',
        '$prayer_category', '$request', '$time', '$follow_up', '$email_sent', '$user_responded', '$prayer_answered')";

$result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);

// generate hash value: an encrypted, unique value for each prayer based on the id
$id_query = "SELECT id FROM web_form WHERE prayer_timestamp='$time'";
$hash = 0;
$id_result = $mysqli->query($id_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $id_query);
if($id_result->num_rows > 0) {
    $id = $id_result->fetch_assoc()['id'];
    $hash = strtotime($time) + $id;
    $hash_query = "UPDATE web_form SET hash='$hash' WHERE id='$id'";
    $insert_result = $mysqli->query($hash_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $hash_query);
}

//create a confirmation message for user (found in message_maker.php)
echo getConfirmationMessage($user_first_name, $for_first_name, $attend, $intercession, $for_first_name);

// if user left an email address, create an email message and send it
if($email_to) {
    $email_subj = $user_first_name . "'s Prayer Request Receipt";
    $email_message = getConfirmationEmail($user_first_name, $attend, $intercession, $for_first_name);

    // Create the Transport
    $transport = Swift_SmtpTransport::newInstance('mail.therockyouth.org', 25)
      ->setUsername($smtp_user)
      ->setPassword($smtp_pass);

    $mailer = Swift_Mailer::newInstance($transport);

    // Create a message
    $message = Swift_Message::newInstance($email_subj)
      ->setFrom(array('websupport@rockchurch.com' => 'The Rock Church'))
      ->setTo(array($email_to => $user_first_name))
      ->setBody($email_message, 'text/html');

    $mailer->send($message);
}

//He Deserves More
?>
