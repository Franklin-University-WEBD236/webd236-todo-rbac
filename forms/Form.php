<?php
class Form implements ArrayAccess {

  private $data;
  private $validators;
  private $errors;
  
  public function __construct($fields = array()) {
    $this->errors = array();
    foreach ($fields as $field => $validators) {
      $this->addField($field, $validators);
    }
  }
  
  public function addField($key, $validators) {
    $this->data[$key] = '';
    $this->validators[$key] = $validators;
  }
  
  public function validate() {
    foreach ($this->validators as $key => $validators) {
      foreach ($validators as $validator) {
        if (is_array($validator)) {
          
        } else {
          $result = self::$validator($key, $this->data[$key]);
        }
        if ($result) {
          if (!isset($this->errors[$key])) {
            $this->errors[$key] = array();
          }
          $this->errors[$key][] = $result;
        }
      }
    }
    return count($this->errors) == 0;
  }
  
  public function load($fields) {
    foreach ($fields as $key => $value) {
      if (!isset($this->data[$key])) {
        throw new Exception("$key is not in the form");
      }
      $this->data[$key] = $value;
    }
  }
  
  public function getErrors() {
    $result = array();
    foreach ($this->errors as $key => $errors) {
      foreach ($errors as $error) {
        $result[] = $error;
      }
    }
    return $result;
  }

  private static function fieldToHuman($str) {
    $str = preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $str);
    $str = preg_replace('/[_]/', ' ', $str);
    return ucfirst(strtolower($str));
  }
  
  public static function optional($key, $value) {
    return false;
  }
  
  public static function required($key, $value) {
    $pattern = '/[[:graph:]]+/';
    if (!preg_match($pattern, $value)) {
      return self::fieldToHuman($key) . " is required";
    }
    return false;
  }
  
  public static function phone($key, $value) {
    $pattern = "/^\(?\d{3}\)?[. -]?\d{3}[. -]?\d{4}$/";
    if (!preg_match($pattern, $value)) {
      return "$value is not a valid phone number";
    }
    return false;
  }

  public static function integer($key, $value) {
    $pattern = '/^\d+$/';
    if (!preg_match($pattern, $value)) {
      return $value . " is not a valid " . self::fieldToHuman($key);
    }
    return false;
  }
  
  public static function float($key, $value) {
    $pattern = '/^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$/';
    if (!preg_match($pattern, $value)) {
      return $value . " is not a valid " . self::fieldToHuman($key);
    }
    return false;
  }
  
  public static function money($key, $value) {
    $pattern = '/^\$?\d+([.]?\d{2})?$/';
    if (!preg_match($pattern, $value)) {
      return $value . " is not a valid " . self::fieldToHuman($key);
    }
    return false;
  }
  
  public static function password($key, $value) {
    $patterns = array(
      '/^[[:graph:]]{8,}$/',  # all printable (no ws) and at least 8 in length
      '/[[:upper:]]/',        # at least 1 upper case character
      '/[[:digit:]]/',        # at least 1 digit
      '/[[:punct:]]/'         # at least 1 symbol
    );
    foreach ($patterns as $pattern) {
      if (!preg_match($pattern, $value)) {
        return self::fieldToHuman($key) . ' must be at least 8 characters, contain an upper case, digit, and symbol.'
      }
    }
    return false;
  }

  public static function email($key, $value, $message = false) {
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
    if (!preg_match($pattern, $value)) {
      return $value . " is not a valid email address";
    }
    return false;
  }
  
  public static function same($key, $value1, $value2) {
    
  }
  
  public function offsetExists($offset) {
    return isset($this->data[$offset]);
  }

  public function offsetGet($offset) {
    return $this->data[$offset];
  }

  public function offsetSet($offset , $value) {
    $this->data[$offset] = $value;
  }

  public function offsetUnset($offset) {
    unset($this->data[$offset]);
  }
}
?>