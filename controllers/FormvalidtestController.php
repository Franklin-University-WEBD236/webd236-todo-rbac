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

  private function get_validator() {
    $v = new FormValidator();
    $v->rule('required', 'required');
    $v->rule('phone', 'phone');
    $v->rule('email', 'email');
    $v->rule('integer', 'integer');
    $v->rule('float', 'float');
    $v->rule('money', 'money');
    $v->rule('password', 'password');
    $v->rule('password2', 'same[password]');
    //email, integer, float, money, password
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