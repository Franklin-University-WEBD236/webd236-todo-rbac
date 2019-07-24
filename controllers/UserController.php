<?php

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
    $form = safeParam($_POST, 'form');
    if (!$form) {
      die ("no form submitted.");
    }
    $validator = new Validator();
    $validator->required('firstName', safeParam($form, 'firstName'), "First name is required.");
    $validator->required('lastName', safeParam($form, 'lastName'), "Last name is required.");
    $validator->password('password1', safeParam($form, 'password1'), "Password must have at least 8 characters, a number, an uppercase, and a symbol.");
    $validator->same('password2', safeParam($form, 'password1'), safeParam($form, 'password2'), "Passwords must match.");
    $validator->email('email1', safeParam($form, 'email1'), "Invalid email address given.");
    $validator->same('email2', safeParam($form, 'email1'), safeParam($form, 'email2'), "Email addresses must match.");
    if (!$validator->hasErrors()) {
      $user = new UserModel(array(
        'email' => trim($form['email1']),
        'password' => password_hash($form['password1'], PASSWORD_DEFAULT),
        'firstName' => trim($form['firstName']),
        'lastName' => trim($form['lastName']),
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
          'form' => $form,
          'errors' => $validator->allErrors(),
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
        'title' => 'Change your password',
        'form' => array(
          'currentPassword' => '',
          'newPassword1' => '',
          'newPassword2' => '',
        ),
        'id' => $id,
      )
    );
  }

  public function post_password($id) {
    $this->ensureLoggedIn();
    $user = $_SESSION['user'];
    if ($id != $user->id) {
      die("Can't change someone elses password.");
    }
    $form = safeParam($_POST, 'form');
    if (!$form) {
      die("No data submitted.");
    }
    $validator = new Validator();
    $validator->required('currentPassword', safeParam($form, 'currentPassword'), "Current password is required.");
    $validator->passwordMatch('currentPassword', safeParam($form, 'currentPassword'), $user->password, "Incorrect current password.");
    $validator->password('newPassword1', safeParam($form, 'newPassword1'), "Password must have at least 8 characters, a number, an uppercase, and a symbol.");
    $validator->same('newPassword2', safeParam($form, 'newPassword1'), safeParam($form, 'newPassword2'), "Passwords must match.");
    if (!$validator->hasErrors()) {
      $user->password = password_hash(safeParam($form, 'newPassword1'), PASSWORD_DEFAULT);
      $user->update();
      $_SESSION['user'] = $user;
      $this->view->flash("Password updated");
      $this->view->redirectRelative("");
    } else {
      $this->view->renderTemplate(
        "views/user_change_password.php",
        array(
          'title' => 'Change your password',
          'form' => $form,
          'id' => $id,
          'errors' => $validator->allErrors(),
        )
      );
    }
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