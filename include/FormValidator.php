<?php
class FormValidator {
  private $validator;
  private $rules;
  
  public function __construct() {
    $this->validator = new Validator();
    $this->rules = [];
  }
  
  // required, phone, email, integer, float, money, password, between[low,high], same[other]
  public function rule($field, $type, $message=null) {
    $rules[] = [
      'field' => $field,
      'type' => $type,
      'message' => $message;
    ];
  }
  
  public function is_valid($form) {
    foreach ($rules as $rule) {
      
    }
  }
}