<?php
  
function safeParam($arr, $index, $default="") {
  if ($arr && isset($arr[$index])) {
    if (is_string($arr[$index])) {
      return trim($arr[$index]);
    } else {
      return $arr[$index];
    }
  }
  return $default;
}

function restartSession() {
  //remove PHPSESSID from browser
  if ( isset( $_COOKIE[session_name()] ) ) {
    setcookie( session_name(), "", time()-3600, "/" );
  }
  //clear session from globals
  $_SESSION = array();
  //clear session from disk
  session_destroy();
  session_start();
}

function login($user) {
  $_SESSION['user'] = $user;
}

function isLoggedIn() {
  return isset($_SESSION['user']);
}

function ensureLoggedIn() {
  if (!isLoggedIn()) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    redirectRelative("user/login");
    exit();
  }
}

function debug($something) {
  echo "<div class='debug'>\n";
  print_r($something);
  echo "\n</div>\n";
}


function checked(&$something, $compare) {
  if (isset($something) && (is_array($something) && in_array($compare, $something) || $something == $compare)) {
    return "checked";
  }
  return "";
}

function value(&$something, $default = "") {
  return isset($something) ? $something : $default;
}

?>