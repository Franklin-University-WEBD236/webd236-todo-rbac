<?php
class AdminController extends Controller {

  public function __construct() {
    parent::__construct();
    // this protects the entire controller
    $this -> auth -> ensure('admin_page');
  }

  public function get_index() {
    
    // Put your code for get_index here, something like
    // 1. Load and validate parameters or form contents
    // 2. Query or update the database
    // 3. Render a template or redirect
    $users = UserModel::findAll();
    $this->view->renderTemplate(
      "views/AdminIndex.php",
      array(
        'title' => 'Administrative interface',
        'users' => $users,
      )
    );
  }
}