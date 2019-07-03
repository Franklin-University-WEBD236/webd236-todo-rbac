<?php
include_once "views/View.php";

class Controller {
  protected $view;
  
  public function __construct() {
    $this->view = new View();
  }
  
  function ensureLoggedIn() {
  if (!isLoggedIn()) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    redirectRelative("user/login");
    exit();
  }
}


}
?>