<?php
include_once "controllers/Controller.php";
include_once "include/util.php";
include_once "models/todo.php";

class TodoController extends Controller {
  
  function get_view($id) {
    ensureLoggedIn();
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

    renderTemplate(
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
    ensureLoggedIn();
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

    renderTemplate(
      "views/todo_edit.php",
      array(
        'title' => 'Editing To Do',
        'todo' => $todo
      )
    );
  }

  function post_done($id) {
    ensureLoggedIn();
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
    redirectRelative("index");
  }

  function post_add() {
    ensureLoggedIn();
    $description = safeParam($_POST, 'description', '');
    if (!$description) {
      die("no description given");
    }
    addToDo($description, $_SESSION['user']['id']);
    flash("Successfully added.");
    redirectRelative("index");
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
    ensureLoggedIn();
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
    redirectRelative("index");
  }

  function post_delete($id) {
    ensureLoggedIn();
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
    flash("Deleted.");
    redirectRelative("index");
  }

}

?>