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
function getConfirmationMessage($user, $attend, $intercession) {

    // These are messages to add to the confirmation page depending on whether
    // or not the person is praying for someone else
    if($user) {
        $message = "$user, we are so thankful that you reached out to us! <br />";

    } else {
        $message = "We're so glad that you reached out to us! <br />";
    }

    $honored = "The Rock Church is honored to come along side you to pray and believe with you. <br />";

    // Concatenate one of these messages to the confirmation_message depending
    // on whether or not he/she attends the church
    $attendee_invite = "We would also like to extend and invitation to come to the altar after any service for additional prayer. <br />";
    $non_attendee_invite = "We would also like to extend an invitation to come to any of our services where prayer teams are available to pray with you in person. <br />";
    $non_attendee_invite .= "If you're not in the area, we would like to recommend that you find a church near you that will partner with you in prayer as well. <br />";

    $matthew = "Matthew 18:20 says, \"For where two or three are gathered in My name, I am there in the midst of them.\" <br />";
    // Build the message
    if($attend) {
        $confirmation_message = $message;
        $confirmation_message .= $honored;
        $confirmation_message .= $attendee_invite;
    } else {
        $confirmation_message = $message;
        $confirmation_message .= $honored;
        $confirmation_message .= $non_attendee_invite;
    }

    $confirmation_message .= $matthew;

    return $confirmation_message;
}

/******************************************************************************
* getConfirmationEmail creates a unique message to email based on the arguments.
*******************************************************************************/
function getConfirmationEmail($user, $attend, $intercession) {
    if($attend) {
        $invite = "If you need someone to pray with you in person, we'd
                love to pray with you at church!";
    } else {
        $invite = "If you live in the area we'd love to be able to pray
                with you in person. <br />Check us out at Rock.Church for our
                address, service times, and online messages.";
    }

    $message = "We have received your prayer request and will be praying and believing with you and for you. <br />
        We want to encourage you to not give up, come to church, stay in your Word and keep believing. <br />
        Numbers 6:24-26 says, <br />
        <blockquote>
            <p>\"The LORD bless you and keep you; <br />
            The LORD make His face shine upon you, and be gracious to you; <br />
            The LORD lift up His countenance upon you and give you peace.\"
            </p>
        </blockquote>";
    $message .= $invite;

    return $message;
}

/*******************************************************************************
* getFollowUpMessage creates a message for following up with requester after a
* number of days. Gets the users first name using the prayer's hash value
*******************************************************************************/
function getFollowUpEmail($user, $hash) {
    $message =  "Hey, $user, we've been praying about your request, and want to check in on your request. <br />
        We have included a link below that will let you update your request or give a praise report. <br />
        If you had a breakthrough we would love to hear about it and rejoice with you. <br />
        If you are still believing we would like to contact you and pray with you. <br /><br />";

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
