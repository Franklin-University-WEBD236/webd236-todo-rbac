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
    return strlen(trim($value)) ? false : self::fieldToHuman($key) . " is required";
  }
  
  public static function password($key, $value) {
    return strlen(trim($value)) >= 8 ? false : self::fieldToHuman($key) . " must be 8 or more characters";
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