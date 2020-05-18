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

  public function post_edit_user($id) {
    $this -> auth -> ensure('edit_user');
    $user = UserModel::findById($id);
    $this->view->flash("User updated");
    $this->view->redirectRelative("admin");
  }

  public function post_edit_password($id) {
    $this -> auth -> ensure('edit_user');
    $user = UserModel::findById($id);
    
    $form = safeParam($_POST, 'form');
    if (!$form) {
      die("No data submitted.");
    }
    $this->validator->password('newPassword1', safeParam($form, 'newPassword1'), "Password must have at least 8 characters, a number, an uppercase, and a symbol.");
    $this->validator->same('newPassword2', safeParam($form, 'newPassword1'), safeParam($form, 'newPassword2'), "Passwords must match.");
    if ($this->validator->hasErrors()) {
      $this->view->renderTemplate(
        "views/AdminEditUser.php",
        array(
          'title' => 'Edit user',
          'user' => $user,
          'errors' => $this->validator->allErrors(),
          'form' => array(
            'firstName' => $user['firstName'],
            'lastName'  => $user['lastName'],
            'email1'    => $user['email'],
            'email2'    => $user['email'],
            'newPassword1' => safeParam($form, 'newPassword1'),
            'newPassword2' => safeParam($form, 'newPassword2'),
          )
        )
      );
    }
    $user->password = password_hash(safeParam($form, 'newPassword1'), PASSWORD_DEFAULT);
    $user->update();
    $this->view->flash("User password updated");
    $this->view->redirectRelative("admin");
  }

  public function post_delete_user($id) {
    $this -> auth -> ensure('delete_user');
    $user = UserModel::findById($id);
    $user->delete();
    $this->view->flash("User deleted");
    $this->view->redirectRelative("admin");
  }
}