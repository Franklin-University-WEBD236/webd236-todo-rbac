<?php
class FormValidator {
  private $validator;
  private $rules;
  
  public function __construct() {
    $this->validator = new Validator();
    $this->rules = [];
  }
  
  // required, phone, email, integer, float, money, password, between[low,high], same[other]
  public function rule($field, $func, $message=null) {
    $rules[] = [
      'field' => $field,
      'func' => $func,
      'message' => $message;
    ];
  }
  
  public function get_errors($form) {
    foreach ($rules as $rule) {
      extract($rule);
      if (!method_exists($this->validator, $func)) {
        die("Unknown form validation rule $func");
      }
      $key = $rule[]
      $params = [$field, $form[$field], $message];
      call_user_func_array(array($this->validator, $func), $params);
    }
    return $this->validator
  }
}