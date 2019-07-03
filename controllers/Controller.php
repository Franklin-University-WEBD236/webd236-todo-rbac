<?php
include_once "views/View.php";

class Controller {
  protected $view;
  
  public function __construct() {
    $this->view = new View();
  }
}
?>