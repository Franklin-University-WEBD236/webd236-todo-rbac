<?php

class Controller {
  protected $view;
  
  public function __construct() {
    $this->view = new View();
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