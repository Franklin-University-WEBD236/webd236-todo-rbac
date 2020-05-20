<?php
class FormvalidtestController extends Controller {

  public function __construct() {
    parent::__construct();
    // Need a model? Uncomment below:
    // $this->model = 'FormvalidtestModel';
  }

  public function get_index() {
    // Put your code for get_index here, something like
    // 1. Load and validate parameters or form contents
    // 2. Query or update the database
    // 3. Render a template or redirect
    $this->view->renderTemplate(
      "views/FormvalidtestIndex.php",
      array(
        'title' => 'Form Validation Test',
      )
    );
  }

  private static function get_validator() {
    $v = new FormValidator();
    $v->rule('required', 'required', 'Field is required');
    $v->rule('phone', 'phone', 'Not a valid phone number');
    $v->rule('email', 'email', 'Not a valid email address');
    $v->rule('integer', 'integer', 'Not a valid integer');
    $v->rule('float', 'float', 'Not a valid floating point number');
    $v->rule('money', 'money', 'Not a valid amount of money');
    $v->rule('password', 'password', 'Not strong enough');
    $v->rule('password2', 'same[password]', 'Does not match');
    $v->rule('between', 'between[25,555]', 'Not between 25 and 555');
    return $v;
  }
  
  public function post_index() {
    $form = safeParam($_POST, 'form');
    $errors = $this->get_validator()->get_errors($form);
    if (!$errors) {
      $this->view->flash("Success!");
    }
    $this->view->renderTemplate(
      "views/FormvalidtestIndex.php",
      array(
        'title' => 'Form Validation Test',
        'form' => $form,
        'errors' => $errors,
      )
    );
  }
}