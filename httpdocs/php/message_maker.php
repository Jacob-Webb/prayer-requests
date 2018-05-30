<?php
/*******************************************************************************
* message_maker.php builds the messages that prayer.rock.church utilizes.
* Each message should have a function name that states what it's for and where
* it should be used.
* HDM
*******************************************************************************/

/*******************************************************************************
* getConfirmationMessage creates the message displayed on the confirmation page.
* It's contents will be determined by attendance and intercession variables
*******************************************************************************/
function getConfirmationMessage($user, $attend, $intercession, $recipient) {

    // These are messages to add to the confirmation page depending on whether
    // or not the person is praying for someone else
    if($user) {
        $intercession_message = "$user, thank you so much for the prayer
                request for $recipient.";
        $intercession_message .= "<br />\r\nThe Rock Church's pastors
                will be praying and believeing with you for $recipient!";

        $non_intercession_message = "$user, we're so glad that you reached out to us!";
        $non_intercession_message .= "<br />\r\nPastors at the Rock Church will
                be praying for you. ";
        $non_intercession_message .= "We believe that God wants to move in your
                life AND that He's able to. ";
    } else {
        $intercession_message = "Thank you so much for the prayer
                request for $recipient.";
        $intercession_message .= "<br />\r\nThe Rock Church's pastors
                will be praying and believeing with you for $recipient!";

        $non_intercession_message = "We're so glad that you reached out to us!";
        $non_intercession_message .= "<br />\r\nPastors at the Rock Church will
                be praying for you. ";
        $non_intercession_message .= "We believe that God wants to move in your
                life AND that He's able to. ";
    }


    // Concatenate one of these messages to the confirmation_message depending
    // on whether or not he/she attends the church
    $attendee_invite = "<br />\r\nOur prayer teams are available after every
            service and would love to pray with you about this too. See you
            in church!";
    $non_attendee_invite = "<br />\r\nIf you live in the area we'd love to
            be able to pray with you in person.";
    $non_attendee_invite .= "<br />\r\nWe have prayer teams available after
            every service. Visit our website for church service times.";

    // Build the message
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
* getConfirmationEmail creates a unique message to email based on the arguments.
*******************************************************************************/
function getConfirmationEmail($user, $attend, $intercession, $recipient) {
    if($intercession) {
        $person = $recipient;
    } else {
        $person = "you";
    }

    if($attend) {
        $invite = "If you need someone to pray with you in person, we'd
                love to pray with you at church!";
    } else {
        $invite = "If you live in the area we'd love to be able to pray
                with you in person. <br />Check us out at Rock.Church for our
                address, service times, and online messages.";
    }

    $message = "Hey, $user, we got your prayer request and we're going to be
            praying for $person. <br />Don't give up! Keep believing and God'll answer. <br />";
    $message .= $invite;

    return $message;
}

/*******************************************************************************
* getFollowUpMessage creates a message for following up with requester after a
* number of days. Gets the users first name using the prayer's hash value
*******************************************************************************/
function getFollowUpEmail($user, $hash) {
    $message =  "Hey, $user, we've been praying about your request and we wanted
            to see how everything's going. <br>
            We wanted to let you know that we're here if you'd like to talk to
            someone about it. <br><br>

            The link below will take you to a form that you can use to
            let us know what's been going on. <br><br>

            If you've had a breakthrough we'd love to read your testimony. <br>
            If you're still believing for results, we'd like to get in touch with
            you to give you a little more support. <br /><br />";

    if($_SERVER['SERVER_NAME'] == 'prayer-rock-church') {
        $message .= '<a href="prayer-rock-church/admin/follow_up_form.php?hash=' .
                $hash . '">Follow Up Info</a>';
    } elseif($_SERVER['SERVER_NAME']) {
        $message .= '<a href="https://prayer.rock.church/admin/follow_up_form.php?hash=' .
                $hash . '">Follow Up Info</a>';
    }
    
    return $message;
}
?>
