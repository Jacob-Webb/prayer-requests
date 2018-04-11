<?php
require 'server_info.php';
// Check Environment

$first_name = sanitize($_POST['first']);
$last_name = sanitize($_POST['last']);
$prayer_category = sanitize($_POST['category']);
$request = sanitize($_POST['prayer_request']);

$mysqli = new MySQLi($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error());

$q = "INSERT INTO web_form (first_name, last_name, category, prayer_request) VALUES ('$first_name', '$last_name', '$prayer_category', '$request')";

$result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);


?>

<?php
/*
require 'assets/utils/_setup.php';
require 'assets/lib/stripe/Stripe.php';
require 'assets/lib/swiftmailer/lib/swift_required.php';

$tickets = sanitize($_POST['tickets']);
$sponsored = sanitize($_POST['sponsor-number']);
$amount = sanitize($_POST['amount']);
$total = (sanitize($_POST['amount']) / 100);
$name = sanitize($_POST['name']);
$email = sanitize($_POST['email']);
$paymentType = sanitize($_POST['payment-type']);
$createdDate = date("Y-m-d \ h:i:s A");
$hash = md5($email);

if ($_POST) {
  Stripe::setApiKey($stripe_sk);
  $error = '';
  $success = '';
  try {
    if (!isset($_POST['stripeToken']))
      throw new Exception("The Stripe Token was not generated correctly");
      $response = Stripe_Charge::create(array("amount" => $_POST['amount'],
                                "currency" => "usd",
                                "receipt_email" => $email,
                                "description" => "Man Day 2015",
                                "card" => $_POST['stripeToken']));

      // Grab the Stripe charge ID
      $stripeId = $response['id'];
  }
  catch (Exception $e) {
    $message = json_encode($e->getMessage());
    $message = str_replace('"', "", $message);
    $message = str_replace("'", "", $message);
    header('Location: ' . $http . 'checkout.rockchurch.' . $tld . '/manday/register.php?name=' . urlencode($name) . '&tickets=' . $tickets . '&amount=' . $amount . '&email=' . $email . '&donated=' . $sponsored . '&message=' . $message);
    die();
  }
}


$q = "INSERT INTO manday15 (name, email, tickets, amount, paymentType, stripeId, createdDate, hash, sponsored) VALUES ('$name', '$email', '$tickets', '$amount', '$paymentType', '$stripeId', '$createdDate', '$hash', '$sponsored')";

$result = $mysqli->query($q) or die ("Query failed: " . $mysqli->error . " Actual query: " . $q);


// Send confirmation email
$name = urlencode($name);
$donated = ($sponsored != '') ? $sponsored . ' Donated<br>' : '';

$message_body = 'Congratulations <strong>'. urldecode($name) . '</strong>!<br><br>
You have been registered for Rock Men Man Day on Saturday, April 25 at The Rock Church and World Outreach Center. You can pick up your wristband for entrance into Man Day at the <strong>Will Call</strong> tables when you arrive the day of the event.
<br><br>
<strong>Order Details</strong>:<br>
' . $tickets . ' Ticket(s)<br>
' . $donated . '
===========<br>
$' . $total . '.00 Paid<br><br>
We’ll see you there!
<br><br>
Thank You,<br>
The Rock Registration Team<br>
http://rockchurch.com';

// Create the Transport
$transport = Swift_SmtpTransport::newInstance('mail.therockyouth.org', 25)
  ->setUsername($smtp_user)
  ->setPassword($smtp_pass);

$mailer = Swift_Mailer::newInstance($transport);

// Create a message
$message = Swift_Message::newInstance("Congratulations, You're Registered for Man Day!")
  ->setFrom(array('websupport@rockchurch.com' => 'The Rock Church'))
  ->setTo(array($email => urldecode($name)))
  ->setBody($message_body, 'text/html');

$mailer->send($message);

header('Location: ' . $http . 'checkout.rockchurch.' . $tld . '/manday/complete.php?registration=complete&name=' . $name . '&tickets=' . $tickets . '&donated=' . $sponsored . '&total=' . $total);
*/
?>

<?php
/*
// Check Environment
$tld = strrchr ( $_SERVER['SERVER_NAME'], "." );
$tld = substr ( $tld, 1 );
($tld == "dev") ? $env = "dev" : $env = "production";
$http = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';


// Set Default Timezone
date_default_timezone_set('America/Los_Angeles');

// Set SMTP auth
$smtp_user = 'username';
$smtp_pass = 'password';


// Setup Database Connection
if ($env == "production") {
  $db_name = "checkout";
  $db_user = "checkoutadmin";
  $db_pass = "password";
  $db_server = "localhost";

  $stripe_pk = "private-key";
  $stripe_sk = "secret-key";
}

if ($env == "dev") {
  $db_name = "checkout";
  $db_user = "root";
  $db_pass = "root";
  $db_server = "localhost";

  $stripe_pk = "private-key";
  $stripe_sk = "secret-key";
}

$mysqli = new MySQLi($db_server, $db_user, $db_pass, $db_name) or die(mysqli_error());

*/
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
