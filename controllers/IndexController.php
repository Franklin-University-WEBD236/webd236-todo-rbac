<?php
include_once "controllers/Controller.php";
include_once "include/util.php";

class IndexController extends Controller {

  public function __construct() {
    parent::__construct();
  }


  public function get_index() {
    $this->view->redirectRelative("");
  }
}
?>