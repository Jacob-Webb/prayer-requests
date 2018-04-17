<?php
require 'server_info.php';
include_once 'confirmation.php';
//require_once '/vendor/swiftmailer/lib/swift_required.php'

// Get all variables from the prayer request form on main page
$user_first_name = sanitize($_POST['user_first']);
$user_last_name = sanitize($_POST['user_last']);
$attend = (isset($_POST['attend'])) ? 1 : 0;
$intercession = (isset($_POST['intercession'])) ? 1 : 0;
$for_first_name = ($intercession) ? sanitize($_POST['for_first']) : $user_first_name;
$for_last_name = ($intercession) ? sanitize($_POST['for_last']) : $user_last_name;
$request_contact = (isset($_POST['request_contact'])) ? 1 : 0;
$phone = sanitize($_POST['phone_num']);
$email_to = sanitize($_POST['email']);
$prayer_category = sanitize($_POST['category']);
$request = sanitize($_POST['prayer_request']);

/* Database connection */
//set up server connection with variables from server_info.php
$mysqli = new MySQLi($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error());

//attempt to transfer variables to database
$q = "INSERT INTO web_form (user_first_name, user_last_name, attending, intercession,
        for_first_name, for_last_name, request_contact, phone, email, category, prayer_request)
        VALUES ('$user_first_name', '$user_last_name', '$attend', '$intercession',
        '$for_first_name', '$for_last_name', '$request_contact', '$phone', '$email_to', '$prayer_category', '$request')";

$result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);

//send confirmation to the user
displayConfirmation($request_contact, $phone, $email_to, $intercession, $prayer_category);



$email_subj = 'The Rock Church is praying for you!';
$email_header = "From: jacob.webb@rockchurch.com" . "\r\n";    //change when we get the right email
//Email confirmation to user if email was given
$email_message = "We just wanted to let you know that we received your prayer request. We will be praying for you and we'll reach out in a couple of days to see how its going.";
$email_message = "\n\nOriginal prayer request: " . $confirmation_message;

if($email_to) {
    //mail(to, subject, message, headers, parameters)
    //to: Required. Specifies the receiver/receivers of the email
    //subject: Required. Specifies the subject of the email.
    //message: Required. Defines the message to be sent. Each line should be separated with a LF (/n).
    //  Lines should not exceed 70 chars.
    //headers: optional. Specifies additional headers, like From, CC, and Bcc. The additional headers
    //  headers should be separated with a CRLF(\r\n)
    //parameters: optional. Specifies an additional parameter to the sendmail program (the one defined in the sendmail_path configuration setting.)
    if(mail($email_to, $email_subj, $confirmation_message)){
        echo "mailed";
    } else {
    echo "no email";
    }
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

?>
