<?php
include_once 'models/Model.php';

class TodoModel extends Model {
  
  public function __construct() {
    parent::__construct();
  }
  
  public static function findToDoById($id) {
    $st = $this -> db -> prepare('SELECT * FROM todo WHERE id = ?');
    $st -> bindParam(1, $id);
    $st -> execute();
    return $st -> fetch(PDO::FETCH_ASSOC);
  }

  public static function findAllCurrentToDos($user_id) {
    $st = $this->db -> prepare('SELECT * FROM todo WHERE done = 0 AND user_id = :user_id ORDER BY id');
    $st -> bindParam(':user_id', $user_id);
    $st -> execute();
    return $st -> fetchAll(PDO::FETCH_ASSOC);
  }

  public static function findAllDoneToDos($user_id) {
    $st = $this->db -> prepare('SELECT * FROM todo WHERE done != 0  AND user_id = :user_id ORDER BY id');
    $st -> bindParam(':user_id', $user_id);
    $st -> execute();
    return $st -> fetchAll(PDO::FETCH_ASSOC);
  }

  public function addToDo($description, $user_id) {
    $st = $this->db -> prepare("INSERT INTO todo (description, done, user_id) values (:description, 0, :user_id)");
    $st -> bindParam(':description', $description);
    $st -> bindParam(':user_id', $user_id);
    $st -> execute();
    return $this->db->lastInsertId();
  }

  public static unction toggleDoneToDo($id) {
    $todo = $this->findToDoById($id);
    $this->updateToDo($id, $todo['description'], $todo['done'] ? 0 : 1);
  }

  public static function updateToDo($id, $description, $done) {
    $statement = $this->db -> prepare("UPDATE todo SET description = :description, done = :done WHERE id = :id");
    $statement -> bindParam(':description', $description);
    $statement -> bindParam(':done', $done);
    $statement -> bindParam(':id', $id);
    $statement -> execute();
  }

  public static function deleteToDo($id) {
    $statement = $this->db -> prepare("DELETE FROM todo WHERE id = :id");
    $statement -> bindParam(':id', $id);
    $statement -> execute();
  }
    
}

?>