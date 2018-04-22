<?php
/***********************************************************************************
* getConfirmation creates the message displayed on the confirmation page .
* It's contents will be determined by attendance and intercession variables
***********************************************************************************/
function getConfirmation($user, $attend, $intercession, $recipient) {

    // These are messages to add to the confirmation page depending on whether or not the person is praying for someone else
    $intercession_message = "$user, thank you so much for the prayer request for $recipient.";
    $intercession_message .= "<br />\r\nThe Rock Church's pastors will be praying and believeing with you for $recipient!";

    $non_intercession_message = "$user, thank you so much for the prayer request for $recipient.";
    $non_intercession_message .= "<br />\r\nPastors at the Rock Church will be praying for you. We believe, in Jesus' name, that He is both able and willing to move in your life!";

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
            $confirmation_message = $intercession_message;
            $confirmation_message .= $non_attendee_invite;
        }
    }

    return $confirmation_message;
}

/******************************************************************************
* getEmailMessage creates a unique message to email based on the arguments.
* Parameters -
* $user_first_name (String): represents the first name of the requester
* $attend (int): represents whether or not the user attends Church
* $request_contact (int): represents whether or not the user requested contact
* $phone (string): should consist of a string of digits representing the phone number if given
* $email_to (string): represents the user's provided email, if given
* $intercession (int): if 1 the prayer is for someone else, if 0 it's for self
* $for_first_name (string): different than $user_first_name if $intercession, same otherwise
* $prayer_category (string): type of prayer person is requesting
*******************************************************************************/

function getEmailMessage($user, $attend, $intercession, $recipient) {
    if($intercession) {
        $person = $recipient;
    } else {
        $person = "you";
    }

    if($attend) {
        $invite = "<br />If you need to speak with pastor please feel free to call the church. See you at church!";
    } else {
        $invite = "<br />If you live in the area we'd love to be able to pray with you in person. \r\n";
        $invite .= "<br />Check us out at Rock.Church for the address and service times.";
    }

    $message = "Hey, $user, we got your prayer request and we're going to be praying for $person. \r\n";
    $message .= "<br />Keep standing on God's word. We'll contact you again in a few days to see how it's going. \r\n";
    $message .= $invite;

    return $message;
}
?>
