<?php
require 'server_info.php';
include_once 'message_maker.php';
require_once 'swiftmailer/lib/swift_required.php';

// Get all variables from the prayer request form on main page
$user_first_name = sanitize($_POST['user-first']);
$user_last_name = sanitize($_POST['user-last']);
$attend = (isset($_POST['attend'])) ? 1 : 0;
$intercession = (isset($_POST['intercession'])) ? 1 : 0;
$for_first_name = ($intercession) ? sanitize($_POST['for-first']) : $user_first_name;
$for_last_name = ($intercession) ? sanitize($_POST['for-last']) : $user_last_name;
$request_contact = (isset($_POST['request-contact'])) ? 1 : 0;
$phone = sanitize($_POST['phone-num']);
$email_to = sanitize($_POST['email']);
$prayer_category = sanitize($_POST['category']);
$request = sanitize($_POST['prayer-request']);
$time = date("Y:m:d H:i:s");

//attempt to transfer variables to database
$q = "INSERT INTO web_form (user_first_name, user_last_name, attending, intercession,
        for_first_name, for_last_name, request_contact, phone, email, category,
        prayer_request, prayer_timestamp)
        VALUES ('$user_first_name', '$user_last_name', '$attend', '$intercession',
        '$for_first_name', '$for_last_name', '$request_contact', '$phone', '$email_to',
        '$prayer_category', '$request', '$time')";

$result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);

//create a confirmation message for user (found in message_maker.php)
echo getConfirmation($user_first_name, $attend, $intercession, $for_first_name);


// if user left an email address, create an email message and send it
if($email_to) {
    $email_subj = 'The Rock Church Prayer Request Received';
    $email_message = getEmailMessage($user_first_name, $attend, $intercession, $for_first_name);

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

?>

<?php
// Keep the database cleen
function cleanInput($input) {
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );

  $output = preg_replace($search, '', $input);
  return $output;
}

function escape($str) {
  $search=array("\\","\0","\n","\r","\x1a","'",'"');
  $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
  return str_replace($search,$replace,$str);
}

function sanitize($input) {
  if (is_array($input)) {
    foreach($input as $var=>$val) {
      $output[$var] = sanitize($val);
    }
  }
  else {
    if (get_magic_quotes_gpc()) {
      $input = stripslashes($input);
    }

    $input  = cleanInput($input);
    // Kept getting error: A link to the server could not be established
    // $output = mysql_real_escape_string($input);
    $output = escape($input);
  }

  return $output;
}

// Helpers
function currentUri() {
  return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}

function dd($var) {
  print '<pre>';
  var_dump($var);
  print '</pre>';
  //die();
}
//He deserves more
?>
