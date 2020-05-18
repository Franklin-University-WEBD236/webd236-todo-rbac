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
        'form' => array(
          'firstName' => $user['firstName'],
          'lastName'  => $user['lastName'],
          'email1'    => $user['email'],
          'email2'    => $user['email'],
        )
      )
    );
  }
}