<?php
class ErrorController extends Controller {

  public function __construct($code, $message) {
    parent::__construct();
    $this->model = '';
    http_response_code($code);
    $this->view->renderTemplate(
      "views/error.php",
      array(
        'title' => "$code Error",
        'message' => $message,
      )
    );
    die();
  }
}