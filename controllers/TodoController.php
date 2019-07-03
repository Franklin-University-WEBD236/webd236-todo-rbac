<?php
include_once "controllers/Controller.php";
include_once "models/TodoModel.php";
include_once "include/util.php";

class TodoController extends Controller {

  protected $model;
  
  public function __construct() {
    parent::__construct();
    $this->model = new TodoModel();
  }
  
  function get_view($id) {
    $this->ensureLoggedIn();
    if (!$id) {
      die("No todo id specified");
    }

    $todo = findToDoById($id);
    if (!$todo) {
      die("No todo with id $id found.");
    }

    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }

    $this->view->renderTemplate(
      "views/todo_view.php",
      array(
        'title' => 'Viewing To Do',
        'todo' => $todo
      )
    );
  }

  function get_list() {
    $todos = null;
    $dones = null;
    if (isLoggedIn()) {
      $todos = findAllCurrentToDos($_SESSION['user']['id']);
      $dones = findAllDoneToDos($_SESSION['user']['id']);
    }
    $this->view->renderTemplate(
      "views/index.php",
      array(
        'title' => 'To Do List',
        'todos' => $todos,
        'dones' => $dones
      )
    );
  }

  function get_edit($id) {
    $this->ensureLoggedIn();
    if (!$id) {
      die("No todo specified");
    }
    $todo = findToDoById($id);
    if (!$todo) {
      die("No todo with id {$id} found.");
    }

    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }

    $this->view->renderTemplate(
      "views/todo_edit.php",
      array(
        'title' => 'Editing To Do',
        'todo' => $todo
      )
    );
  }

  function post_done($id) {
    $this->ensureLoggedIn();
    if (!$id) {
      die("No todo specified");
    }
    $todo = findToDoById($id);
    if (!$todo) {
      die("No todo with id {$id} found.");
    }

    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }

    toggleDoneToDo($id);
    $this->view->redirectRelative("index");
  }

  function post_add() {
    $this->ensureLoggedIn();
    $description = safeParam($_POST, 'description', '');
    if (!$description) {
      die("no description given");
    }
    addToDo($description, $_SESSION['user']['id']);
    flash("Successfully added.");
    $this->view->redirectRelative("index");
  }

  function validate_present($elements) {
    $errors = '';
    foreach ($elements as $element) {
      if (!isset($_POST[$element])) {
        $errors .= "Missing $element\n";
      }
    }
    return $errors;
  }

  function post_edit($id) {
    $this->ensureLoggedIn();
    if (!$id) {
      die("No todo specified");
    }
    $todo = findToDoById($id);
    if (!$todo) {
      die("No todo with id {$id} found.");
    }

    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }

    $errors = validate_present(array('description', 'done'));
    if ($errors) {
      die($errors);
    }
    $description = safeParam($_POST, 'description');
    $done = safeParam($_POST, 'done');
    updateToDo($id, $description, $done);
    $this->view->redirectRelative("index");
  }

  function post_delete($id) {
    $this->ensureLoggedIn();
    if (!$id) {
      die("No todo specified");
    }
    $todo = findToDoById($id);
    if (!$todo) {
      die("No todo with id {$id} found.");
    }

    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }

    deleteToDo($id);
    $this->view->flash("Deleted.");
    $this->view->redirectRelative("index");
  }

}

?>