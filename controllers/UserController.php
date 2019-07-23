<?php
include_once "controllers/Controller.php";
include_once "models/UserModel.php";
include_once "include/util.php";

class UserController extends Controller {

  protected $model;
  
  public function __construct() {
    parent::__construct();
    $this->model = 'UserModel';
  }

  public function get_login() {
    $this->view->renderTemplate(
      "views/user_login.php",
      array(
        'title' => 'Log in',
      )
    );
  }

  public function post_login() {
    $form = safeParam($_POST, 'form');
    $email = safeParam($form, 'email');
    $password = safeParam($form, 'password');

    $user = $this->model::findByEmail($email);
    if (!$user || !password_verify($password, $user->password)) {
      $errors = array("Bad username/password combination");
      $this->view->renderTemplate(
        "views/user_login.php",
        array(
          'title' => 'Log in',
          'form' => $form,
          'errors' => $errors,
        )
      );
    } else {
      $destination = isset($_SESSION['redirect']) ? $_SESSION['redirect'] : "/";
      restartSession();
      $_SESSION['user'] = $user;
      $this->view->flash("Login successful!");
      $this->view->redirect($destination);
    }
  }

  public function get_logout() {
    restartSession();
    $this->view->redirectRelative('');
  }

  public function get_register() {
    $this->view->renderTemplate(
      "views/user_register.php",
      array(
        'title' => 'Create an account',
        'form' => array(),
        'action' => $this->view->url('user/register'),
      )
    );
  }

  private function verify_account($form) {
    $errors = array();

    if (!$form) {
      $errors[] = "No data submitted";
      return $errors;
    }

    $email1 = safeParam($form, 'email1');
    if (!$email1) {
      $errors['email1'] = "An email address must be provided";
    }
    $email2 = safeParam($form, 'email2');
    if ($email1 != $email2) {
      $errors['email2'] = "Email addresses must match";
    }
    $password1 = safeParam($form, 'password1');
    if (!$password1 || strlen($password1) < 8) {
      $errors['password1'] = "Passwords must be at least 8 characters long";
    }
    $password2 = safeParam($form, 'password2');
    if ($password1 != $password2) {
      $errors['password1'] = "Passwords must match";
    }
    $firstName = safeParam($form, 'firstName');
    if (!$firstName) {
      $errors['firstName'] = "A first name must be provided";
    }
    $lastName = safeParam($form, 'lastName');
    if (!$lastName) {
      $errors['lastName'] = "A last name must be provided";
    }

    return $errors;
  }

  public function post_register() {
    $form = new Form(array(
      'firstName' => array('required'),
      'lastName' => array('required'),
      'password1' => array('password', array('same', 'password2')),
      'email1' => array('email', array('same', 'email2')),
    ));
    $form->load($_POST['form']);
    if ($form->validate()) {
      $user = new UserModel(array(
        'email' => $form['email1'],
        'password' => password_hash($form['password1'], PASSWORD_DEFAULT),
        'firstName' => $form['firstName'],
        'lastName' => $form['lastName'],
      ));
      $user->insert();
      restartSession();
      $_SESSION['user'] = $user;
      $this->view->flash("Welcome to To Do List, {$user['firstName']}.");
      $this->view->redirectRelative("");
    } else {
      $this->view->renderTemplate(
        "views/user_register.php",
        array(
          'title' => 'Create an account',
          'form' => $_POST['form'],
          'errors' => $form->getErrors(),
          'action' => $this->view->url('user/register'),
        )
      );
    }
  }

  public function get_edit() {
    $this->ensureLoggedIn();
    $user = $_SESSION['user'];

    $this->view->renderTemplate(
      "views/user_edit.php",
      array(
        'title' => 'Edit your profile',
        'action' => $this->view->url("user/edit/${user['id']}"),
        'form' => array(
          'firstName' => $user['firstName'],
          'lastName'  => $user['lastName'],
          'email1'    => $user['email'],
          'email2'    => $user['email'],
        )
      )
    );
  }

  public function get_password($id) {
    $this->ensureLoggedIn();
    $user = $_SESSION['user'];

    $this->view->renderTemplate(
      "views/user_change_password.php",
      array(
        'title' => 'Change your profile',
        'action' => $this->view->url("user/edit/${user['id']}"),
        'form' => array(
          'firstName' => $user['firstName'],
          'lastName'  => $user['lastName'],
          'email1'    => $user['email'],
          'email2'    => $user['email'],
        )
      )
    );
  }

  public function post_edit($id) {
    $this->ensureLoggedIn();
    $user=$_SESSION['user'];
    if ($id != $user['id']) {
      die("Can't edit somebody else.");
    }
    
    $form = new Form(array(
      'firstName' => array('required'),
      'lastName' => array('required'),
      'email1' => array('email', array('same', 'email2')),
    ));
    $form->load($_POST['form']);
    if ($form->validate()) {
      $user->email = $form['email1'];
      $user->firstName = $form['firstName'];
      $user->lastName = $form['lastName'];
      $user->update();
      $this->view->flash("Profile updated");
      $_SESSION['user'] = $user;
      $this->view->redirectRelative("");
    } else {
      $this->view->renderTemplate(
        "views/user_register.php",
        array(
          'title' => 'Edit your profile',
          'form' => $_POST['form'],
          'errors' => $form->getErrors(),
          'action' => $this->view->url("user/edit/${user['id']}"),
        )
      );
    }
  }
}
?>