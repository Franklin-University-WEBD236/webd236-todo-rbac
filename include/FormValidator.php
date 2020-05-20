<?php
class FormValidator {
  private $validator;
  private $rules;
  
  public function __construct() {
    $this->validator = new Validator();
    $this->rules = [];
  }
  
  // required, phone, email, integer, float, money, password, between[low,high], same[other]
  public function rule($field, $func, $message=false) {
    $rules[] = [
      'field' => $field,
      'func' => $func,
      'message' => $message;
    ];
  }
  
  public function get_errors($form) {
    foreach ($rules as $rule) {
      // get field, func, and message
      extract($rule);
      if (!method_exists($this->validator, $func)) {
        die("Unknown form validation rule $func.");
      }
      if (!isset($form[$field])) {
        die("No field $field in form.")
      }
      $params = [$field, $form[$field], $message];
      call_user_func_array(array($this->validator, $func), $params);
    }
    return $this->validator->allErrors();
  }
}