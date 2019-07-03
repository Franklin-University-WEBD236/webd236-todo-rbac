<?php
include_once "controllers/Controller.php";
include_once "models/UserModel.php";
include_once "include/util.php";

class UserController extends Controller {

  protected $model;
  
  public function __construct() {
    parent::__construct();
    $this->model = new UserModel;
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

    $user = $this->model->findByEmailAndPassword($email, $password);
    if (!$user) {
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
      $destination = $_SESSION['redirect'] ? $_SESSION['redirect'] : "/index";
      restartSession();
      $_SESSION['user'] = $user;
      flash("Login successful!");
      $this->view->redirect($destination);
    }
  }

  public function get_logout() {
    restartSession();
    $this->view->redirectRelative('index');
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
    $form = safeParam($_POST, 'form');
    $errors = verify_account($form);
    if ($errors) {
      $this->view->renderTemplate(
        "views/user_register.php",
        array(
          'title' => 'Create an account',
          'form' => $form,
          'errors' => $errors,
          'action' => url('user/register'),
        )
      );
    } else {
      $id = $this->model->addUser($form['email1'], $form['password1'], $form['firstName'], $form['lastName']);
      restartSession();
      $user = $this->model->findUserById($id);
      $_SESSION['user'] = $user;
      $this->view->flash("Welcome to To Do List, {$user['firstName']}.");
      $this->view->redirectRelative("index");
    }
  }

  public function get_edit() {
    $this->view->$this->view->ensureLoggedIn();
    $user = $_SESSION['user'];

    $this->view->renderTemplate(
      "views/user_register.php",
      array(
        'title' => 'Edit your profile',
        'action' => url("user/edit/${user['id']}"),
        'form' => array(
          'firstName' => $user['firstName'],
          'lastName'  => $user['lastName'],
          'email1'    => $user['email'],
          'email2'    => $user['email'],
          'password1' => $user['password'],
          'password2' => $user['password'],
        )
      )
    );
  }

  public function post_edit($id) {
    $this->view->ensureLoggedIn();
    $user=$_SESSION['user'];
    if ($id != $user['id']) {
      die("Can't edit somebody else.");
    }
    $form = safeParam($_POST, 'form');
    $errors = verify_account($form);
    if ($errors) {
      $this->view->renderTemplate(
        "views/user_register.php",
        array(
          'title' => 'Edit your profile',
          'form' => $form,
          'errors' => $errors,
          'action' => url("user/edit/${user['id']}"),
        )
      );
    } else {
      $this->model->updateUser($user['id'], $form['email1'], $form['password1'], $form['firstName'], $form['lastName']);
      $_SESSION['user'] = findUserById($user['id']);
      $this->view->flash("Profile updated");
      $this->view->redirectRelative("index");
    }
  }
}
?>