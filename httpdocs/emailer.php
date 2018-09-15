<?php
require_once 'server_info.php';
require_once 'lib/swiftmailer/lib/swift_required.php';
require_once 'message_creator.php';

/******************************************************************************
Functions for  **receive_prayer_request.php**
******************************************************************************/
function sendConfirmationEmail($smtp_user, $smtp_pass, $user_first_name, $email_to, $attend, $intercession) {
	$email_subj = $user_first_name . "'s Prayer Request Receipt";
	// from message_creator.php
    $email_message = getConfirmationEmail($user_first_name, $attend, $intercession);

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

/******************************************************************************
 * Functions for **follow_up_email.php **
******************************************************************************/
function sendFollowUpEmail($smtp_user, $smtp_pass, $user_name, $hash, $email_to) {
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
}
?>