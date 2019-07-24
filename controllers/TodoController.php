<?php
class TodoController extends Controller {

  protected $model;
  
  public function __construct() {
    parent::__construct();
    $this->model = 'TodoModel';
  }
  
  function get_view($id) {
    $this->ensureLoggedIn();
    if (!$id) {
      die("No todo id specified");
    }

    $todo = $this->model::findToDoById($id);
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
      $todos = $this->model::findAllCurrentToDos($_SESSION['user']['id']);
      $dones = $this->model::findAllDoneToDos($_SESSION['user']['id']);
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
    $todo = $this->model::findToDoById($id);
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
    $todo = $this->model::findToDoById($id);
    if (!$todo) {
      die("No todo with id {$id} found.");
    }

    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }
    $todo->toggleDone();
    $this->view->redirectRelative("index");
  }

  function post_add() {
    $this->ensureLoggedIn();
    $formData = safeParam($_POST, 'form');
    $validator = new Validator();
    $validator->required('description', safeParam($formData, 'description', "A description is required."));
    if (!$validator->hasErrors()) {
      $todo = new TodoModel(array('description' => $form['description'], 'user_id' => $_SESSION['user']['id']));
      $todo->insert();
      $this->view->flash("Successfully added.");
    } else {
      foreach ($form->getErrors() as $error) {
        $this->view->flash($error);
      }
    }
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
    $todo = $this->model::findToDoById($id);
    if (!$todo) {
      die("No todo with id {$id} found.");
    }
    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }
    $formData = safeParam($_POST, 'form');
    $validator = new Validator();
    $validator->required('description', safeParam($formData, 'description', "A description is required."));
    $validator->required('done', safeParam($formData, 'done', 'Done status is required.'));
    if (!$validator->hasErrors()) {
      $todo->description = safeParam($formData, 'description');
      $todo->done = safeParam($formData, 'done');
      $todo->update();
      $this->view->redirectRelative("index");
    } else {
      $this->view->renderTemplate(
        "views/todo_edit.php",
        array(
          'title' => 'Editing To Do',
          'todo' => $todo,
          'errors' => $validator->allErrors(),
        )
      );
    }
  }

  function post_delete($id) {
    $this->ensureLoggedIn();
    if (!$id) {
      die("No todo specified");
    }
    $todo = $this->model::findToDoById($id);
    if (!$todo) {
      die("No todo with id {$id} found.");
    }

    if ($todo['user_id'] != $_SESSION['user']['id']) {
      die("Not todo owner");
    }
    $todo->delete();
    //$this->model::deleteToDo($id);
    $this->view->flash("Deleted.");
    $this->view->redirectRelative("index");
  }

}

?>