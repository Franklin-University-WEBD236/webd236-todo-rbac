<?php
class Form {

  private $data;
  private $validators;
  
  public function __construct($fields = array()) {
    foreach ($fields as $field => $validators) {
      $this->addField($key, $validator);
    }
  }
  
  public function addField($key, $validators) {
    $this->data[$key] = null;
    $this->validators[$key] = $validators;
  }
  
  public function validate() {
    foreach ($this->validators as $key => $validators) {
      foreach ($validators as $validator) {
        $result = $validator($this->data[$key]);
        if ($result) {
          addError($key, $result);
        }
      }
    }
  }
  
  private function addError($message) {
    if ()
  }
}
?>