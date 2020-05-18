<?php
class AdminController extends Controller {

  public function __construct() {
    parent::__construct();
    // this protects the entire controller
  }

  public function get_index() {
    $this -> auth -> ensure('admin_page');
    $users = UserModel::findAll();
    $this->view->renderTemplate(
      "views/AdminIndex.php",
      array(
        'title' => 'Administrative interface',
        'users' => $users,
      )
    );
  }

  public function get_edit_user($id) {
    $this -> auth -> ensure('edit_user');
    $user = UserModel::findById($id);
    $this->view->renderTemplate(
      "views/AdminEditUser.php",
      array(
        'title' => 'Edit user',
        'user' => $user,
        'form' => array(
          'firstName' => $user['firstName'],
          'lastName'  => $user['lastName'],
          'email1'    => $user['email'],
          'email2'    => $user['email'],
        )
      )
    );
  }

  public function post_edit_user() {
    // Put your code for post_edit_user here, something like
    // 1. Load and validate parameters or form contents
    // 2. Query or update the database
    // 3. Render a template or redirect
    $this->view->renderTemplate(
      "views/AdminEdit_user.php",
      array(
        'title' => 'AdminEdit_user',
      )
    );
  }
}