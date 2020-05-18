<?php

class Controller {
  protected $view;
  
  public function __construct() {
    $this->logger = Logger::instance();
    $this->auth = Authenticator::instance();
    $this->view = new View();
    $this->validator = new Validator();
  }
  
  public function ensureLoggedIn() {
    if (!isLoggedIn()) {
      $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
      $this->view->redirectRelative("user/login");
      exit();
    }
  }


}
?>