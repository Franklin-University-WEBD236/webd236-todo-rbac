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
        'title' => 'FormvalidtestIndex',
      )
    );
  }

  private function get_validator() {
    $v = new FormValidator();
    $v->rule('required', 'required');
    $v->rule('phone', 'phone');
    return $v;
  }
  
  public function post_index() {
    $form = safeParam($_POST, 'form');
    $errors = $this->get_validator()->get_errors($form);
    debug(print_r($errors),1);
    $this->view->renderTemplate(
      "views/FormvalidtestIndex.php",
      array(
        'title' => 'FormvalidtestIndex',
        'form' => $form,
        'errors' => $errors,
      )
    );
  }
}