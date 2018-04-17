<?php
//displayConfirmation should display the confirmation to the user.
//The confirmation depends on the contact info given, whether or not the request is for someone else
// and what type of prayer the person is requesting.
function displayConfirmation($request_contact, $phone, $email_to, $intercession, $prayer_category) {
    if($request_contact && $phone && $email_to) {
        if($prayer_category == "physical"){
            if($intercession) {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for another, phone number, and email. Let him/her know we'll be emailing/calling.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for self, phone number, and email. Let him/her know we'll be emailing/calling.";
            }

        } elseif($prayer_category == "provision"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for another, phone number, and email. Let him/her know we'll be emailing/calling.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for self, phone number, and email. Let him/her know we'll be emailing/calling.";
            }
        } elseif($prayer_category == "salvation"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for another, phone number, and email. Let him/her know we'll be emailing/calling.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for self, phone number, and email. Let him/her know we'll be emailing/calling.";
            }
        }
    } elseif($request_contact && $phone && !$email_to) {
        if($prayer_category == "physical"){
            if($intercession) {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for another and phone number. Let him/her know we'll be calling.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for self and phone number. Let him/her know we'll be calling.";
            }

        } elseif($prayer_category == "provision"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for another, phone number. Let him/her know we'll be calling.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for self, phone number. Let him/her know we'll be calling.";
            }
        } elseif($prayer_category == "salvation"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for another, phone number. Let him/her know we'll be calling.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for self, phone number. Let him/her know we'll be calling.";
            }
        }
    } elseif($request_contact && !$phone && $email_to) {
        if($prayer_category == "physical"){
            if($intercession) {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for another and email. Let him/her know we'll be emailing.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for self and email. Let him/her know we'll be emailing.";
            }

        } elseif($prayer_category == "provision"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for another and email. Let him/her know we'll be emailing.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for self and email. Let him/her know we'll be emailing.";
            }
        } elseif($prayer_category == "salvation"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for another and email. Let him/her know we'll be emailing.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for self and email. Let him/her know we'll be emailing.";
            }
        }
    } elseif($request_contact && !$phone && !$email_to){
        $confirmation_message = "I'm confused, we aren't going to be  able to contact you.";
    } else {
        if($prayer_category == "physical"){
            if($intercession) {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for another. Let him/her know we'll be praying.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for healing for self. Let him/her know we'll be praying.";
            }

        } elseif($prayer_category == "provision"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for another. Let him/her know we'll be praying.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for provision for self. Let him/her know we'll be praying.";
            }
        } elseif($prayer_category == "salvation"){
            if($intercession){
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for another. Let him/her know we'll be praying.";
            } else {
                $confirmation_message = "Confirmation message acknowledging receipt of prayer request for salvation for self. Let him/her know we'll be praying.";
            }
        }

    }

    echo $confirmation_message;
}
?>
