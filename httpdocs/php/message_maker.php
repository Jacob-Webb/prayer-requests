<?php
/***********************************************************************************
* getConfirmation creates the message displayed on the confirmation page .
* It's contents will be determined by attendance and intercession variables
***********************************************************************************/
function getConfirmation($user, $attend, $intercession, $recipient) {

    // These are messages to add to the confirmation page depending on whether or not the person is praying for someone else
    $intercession_message = "$user, thank you so much for the prayer request for $recipient.";
    $intercession_message .= "<br />\r\nThe Rock Church's pastors will be praying and believeing with you for $recipient!";

    $non_intercession_message = "$user, we're so glad that you were able to reach out to us!";
    $non_intercession_message .= "<br />\r\nPastors at the Rock Church will be praying for you. ";
    $non_intercession_message .= "We believe that God wants to move in your life AND that He's able to. ";

    // Concatenate one of these messages to the conf_message depending on whether or not he/she attends
    $attendee_invite = "<br />\r\nOur prayer teams are available after every service and would love to pray with you about this too. See you in church!";
    $non_attendee_invite = "<br />\r\nIf you live in thea area we'd love to be able to pray with you in person.";
    $non_attendee_invite .= "<br />\r\nWe have prayer teams available after every service. Visit our website for church service times.";

    if($attend) {
        if($intercession) {
            $confirmation_message = $intercession_message;
            $confirmation_message .= $attendee_invite;
        } else {
            $confirmation_message = $non_intercession_message;
            $confirmation_message .= $attendee_invite;
        }
    } else {
        if($intercession) {
            $confirmation_message = $intercession_message;
            $confirmation_message .= $non_attendee_invite;
        } else {
            $confirmation_message = $non_intercession_message;
            $confirmation_message .= $non_attendee_invite;
        }
    }

    return $confirmation_message;
}

/******************************************************************************
* getEmailMessage creates a unique message to email based on the arguments.
*******************************************************************************/
function getEmailMessage($user, $attend, $intercession, $recipient) {
    if($intercession) {
        $person = $recipient;
    } else {
        $person = "you";
    }

    if($attend) {
        $invite = "<br />If you need someone to pray with you in person, we'd love to pray with you at church!";
    } else {
        $invite = "<br />If you live in the area we'd love to be able to pray with you in person. \r\n";
        $invite .= "<br />Check us out at Rock.Church for the service times and address.";
    }

    $message = "Hey, $user, we got your prayer request and we're going to be praying for $person. \r\n";
    $message .= "<br />Keep standing on God's word. We'll contact you again in a few days to see how it's going. \r\n";
    $message .= $invite;

    return $message;
}
?>
