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
    $this->rules[] = [
      'field' => $field,
      'func' => $func,
      'message' => $message,
    ];
  }
  
  public function get_errors($form) {
    foreach ($this->rules as $rule) {
      $extra_params = [];
      // get field, func, and message
      extract($rule);
      if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)\[(.*)\]$/m', $func, $matches)) {
        $func = $matches[1];
        $extra_params = explode(",", $matches[2]);
      }
      if (!method_exists($this->validator, $func)) {
        die("Unknown form validation rule $func.");
      }
      if (!isset($form[$field])) {
        die("No field $field in form.");
      }
      
      // build the array of parameters to the function
      $params = [$field, $form[$field]];
      foreach ($extra_params as $param) {
        if (isset($form[$param])) {
          $params[] = $form[$param];
        } else {
          $params[] = $param;
        }
      }
      $params[] = $message;

//      $str = print_r($params, 1);
//      debug("$str");
      call_user_func_array(array($this->validator, $func), $params);
    }
    return $this->validator->allErrors();
  }
}