<?php
include_once "include/util.php";
include_once "models/todo.php";
include_once "controllers/todo.php";

class IndexController extends Controller {
  
  public function __construct() {
    parent::__construct();
  }
  
  function get_index() {
    $this->view->redirect('todo/list');
  }
  
}

?>