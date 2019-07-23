<?php

class ResetController extends Controller {

  public function __construct() {
    parent::__construct();
  }

  public function post_index() {
    $output = `sqlite3 ToDoList.db3 < ToDoList.sql`;
    $this->view->redirectRelative("");
  }
}
?>