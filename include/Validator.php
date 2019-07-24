<?php
class Validator {
  private $errors;

  public function __construct() {
    $this -> errors = array();
  }

  public function hasErrors() {
    return count($this -> errors) > 0;
  }

  public function allErrors() {
    return $this -> errors;
  }

  private function addError($key, $message) {
    $this -> errors[$key] = $message;
  }

  public function errorsFor($key) {
    if (isset($this -> errors[$key])) {
      return $this -> errors[$key];
    }
    return '';
  }

  // from https://stackoverflow.com/questions/6254093/how-to-parse-camel-case-to-human-readable-string

  static function fieldToHuman($str) {
    $str = preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $str);
    $str = preg_replace('/[_]/', ' ', $str);
    return ucfirst(strtolower($str));
  }

  private function check($pattern, $key, $value, $message) {
    if (!preg_match($pattern, $value)) {
      $this->addError($key, $message);
      return false;
    }
    return true;
  }

  public function required($key, $value, $message = false) {
    $pattern = '/[[:graph:]]+/';
    if (!$message) {
      $message = self::fieldToHuman($key) . " is required.";
    }
    return $this->check($pattern, $key, $value, $message);
  }

  public function phone($key, $value, $message = false) {
    $pattern = "/^\(?\d{3}\)?[. -]?\d{3}[. -]?\d{4}$/";
    if (!$message) {
      $message = $value . " is not a valid phone number.";
    }
    return $this->check($pattern, $key, $value, $message);
  }

  public function email($key, $value, $message = false) {
    // this regex copied from
    // http://fightingforalostcause.net/misc/2006/compare-email-regex.php
    $pattern = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|' . 
    '(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C' .
    '[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)' .
    '(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)' .
    '|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]' .
    '|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-' .
    '\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-' .
    '\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})' .
    '(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}' .
    '(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)' .
    '|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})' .
    '|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?' .
    '::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}' .
    '(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}' .
    '(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?' .
    '(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))' .
    '(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9])))' .
    '{3}))\]))$/iD';
    if (!$message) {
      $message = $value . " is not a valid email address.";
    }
    return $this->check($pattern, $key, $value, $message);
  }

  public function integer($key, $value, $message = false) {
    $pattern = '/^\d+$/';
    if (!$message) {
      $message = $value . " is not a valid " . strtolower(self::fieldToHuman($key)) . ".";
    }
    return $this->check($pattern, $key, $value, $message);
  }

  public function float($key, $value, $message = false) {
    $pattern = '/^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$/';
    if (!$message) {
      $message = $value . " is not a valid " . strtolower(self::fieldToHuman($key)) . ".";
    }
    return $this->check($pattern, $key, $value, $message);
  }

  public function money($key, $value, $message = false) {
    $pattern = '/^\$?\d+([.]?\d{2})?$/';
    if (!$message) {
      $message = $value . " is not a valid amount of money.";
    }
    return $this->check($pattern, $key, $value, $message);
  }

  public function between($key, $value, $low, $high, $message = false) {
    if (!$message) {
      $message = self::fieldToHuman($key) . " must be between $low and $high.";
    }
    if ($value < $low || $value > $high) {
      $this -> addError($key, $message);
      return false;
    }
    return true;
  }

  public function password($key, $value, $message = false) {
    if (!$message) {
      $message = self::fieldToHuman($key) . " must be at least 8 characters and contain an upper case character, digit, and symbol.";
    }

    // all these must be satisfied
    $patterns = array(
      '/^[[:graph:]]{8,}$/',  # all printable (no ws) and at least 8 in length
      '/[[:upper:]]/',        # at least 1 upper case character
      '/[[:digit:]]/',        # at least 1 digit
      '/[[:punct:]]/'         # at least 1 symbol
    );
    foreach ($patterns as $pattern) {
      if (!preg_match($pattern, $value)) {
        $this -> addError($key, $message);
        return false;
      }
    }
    return true;
  }
}
?>