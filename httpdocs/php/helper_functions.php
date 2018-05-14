<?php
/******************************************************************************
* helper_functions.php holds all of the functions used to
******************************************************************************/
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
