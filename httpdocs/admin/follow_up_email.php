<?php
/*******************************************************************************
* follow_up_email.php will be run by the server on a schedule every day. It should
* find every prayer request that hasn't been followed up on (using the follow_up
* field) and every request greater than 5 days ago. It will send an email with
* a link to a response form and set the follow_up variable to false.
* Update log database.
* HDM
*******************************************************************************/
require_once('../server_info.php');
include '../message_creator.php';
require_once('../swiftmailer/lib/swift_required.php');
//db info pulled from access_database.php
$mysqli = new MySQLi($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error());

// Get the date of x days ago
$days_ago = 5;
$begin_time_range = date('Y:m:d H:i:s',
        mktime(0, 0, 0, date('m'), date('d') - $days_ago, date('Y')));

// collect database information
$sql = "SELECT id, hash, user_first_name, email, prayer_timestamp, email_sent FROM web_form
        WHERE follow_up = 1 and prayer_timestamp >= '$begin_time_range'";
$result = $mysqli->query($sql) or die ("Query failed: " . $mysqli->error .
    " Actual query: " . $sql);

// Create an email and send to person with matching id's or hash values
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $hash = ($row['hash']) ? $row['hash'] : $row['id'];
        $user_name = $row['user_first_name'];
        $email_to = $row['email'];

        // Set email subject and message
        $email_subj = "How've you been, " . $user_name . "?";
        $email_message =  getFollowUpEmail($user_name, $hash);

        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('mail.therockyouth.org', 25)
          ->setUsername($smtp_user)
          ->setPassword($smtp_pass);

        $mailer = Swift_Mailer::newInstance($transport);

        // Create a message
        $message = Swift_Message::newInstance($email_subj)
          ->setFrom(array('websupport@rockchurch.com' => 'The Rock Church'))
          ->setTo(array($email_to => $user_name))
          ->setBody($email_message, 'text/html');

        $mailer->send($message);

        $email_sent_query = "UPDATE web_form SET follow_up=0, email_sent=1
            WHERE id='$id'";

        $email_sent_result = $mysqli->query($email_sent_query) or die ("Query failed: " . $mysqli->error . " Actual query: " . $email_sent_query);
    }
}
?>
